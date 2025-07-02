<?php
defined( 'ABSPATH' ) || exit;

// Function to get LinkedIn Person ID
function socipu_get_linkedin_person_id($access_token) {
    $response = wp_remote_get('https://api.linkedin.com/v2/userinfo', [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['sub'])) {
        return $body['sub'];
    }

    return false;
}

// Function to safely check an image URL
function socipu_is_image_url_accessible($url) {
    $response = wp_remote_head($url, ['timeout' => 5]);

    if (is_wp_error($response)) {
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    
    if ($status_code === 200) {
        return true; // Image exists and is reachable
    }

    return false;
}

// Function to post to LinkedIn with optional image
function socipu_social_publisher_post_to_linkedin($access_token, $person_id, $post_id, $content, $acf_image_url = null) {
    
    $endpoint = 'https://api.linkedin.com/v2/ugcPosts';

    // Start time for debugging
    $start_time = microtime(true);

    // 📌 Retrieve title
    $title = get_the_title($post_id);
    if (empty($title)) {
        return false;
    }


    // Check if backlink is enabled for this post
    $include_backlink = get_post_meta($post_id, '_linkedin_enable_backlink', true);
    if ($include_backlink) {
        $permalink = get_permalink($post_id);
        $content .= "\n\n" . esc_url($permalink);
    }

    // LinkedIn requires 'urn:li:company:' instead of 'urn:li:organization:' for organizations
    if (strpos($person_id, 'urn:li:organization:') === 0) {
        // Convert organization to company URN
        $person_id = str_replace('urn:li:organization:', 'urn:li:company:', $person_id);
    } elseif (strpos($person_id, 'urn:li:person:') === 0) {
        // Personal profile is fine as-is
    } else {
        return false;
    }

    // Default body for text posts
    $body = [
        "author" => $person_id,
        "lifecycleState" => "PUBLISHED",
        "specificContent" => [
            "com.linkedin.ugc.ShareContent" => [
                "shareCommentary" => [
                    "text" => $content,
                ],
                "shareMediaCategory" => "NONE",
            ],
        ],
        "visibility" => [
            "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC",
        ],
    ];

    // 🔹 **Use only ACF image, ignore featured image**
    if (!empty($acf_image_url)) {

        // New, improved image URL check
        if (socipu_is_image_url_accessible($acf_image_url)) {
        } else {
            $acf_image_url = null;
        }
    }

    // 🔹 If an ACF image exists, upload it to LinkedIn
    if (!empty($acf_image_url)) {
        $asset_id = socipu_upload_image_to_linkedin($access_token, $acf_image_url, $person_id);

        if ($asset_id) {
            
            // Set LinkedIn post to "IMAGE"
            $body['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = "IMAGE";
            $body['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [
                [
                    "status" => "READY",
                    "media" => $asset_id,
                    "title" => ["text" => $title],
                    "description" => ["text" => $title],
                ],
            ];
        } else {
        }
    } else {
    }

    // 📌 API logging before sending

    // 🚀 LinkedIn API POST with timeout
    $response = wp_remote_post($endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ],
        'body' => json_encode($body),
        'timeout' => 8, // Wait max 8 seconds
    ]);

    if (!is_wp_error($response)) {
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($response_body['id'])) {
            return $response_body;
        } else {
        }
    } else {
    }

    // Debugging: duration of the process
    $total_time = round(microtime(true) - $start_time, 2);

    return false;
}

if (!function_exists('socipu_resolve_linkedin_image_url')) {
    function socipu_resolve_linkedin_image_url($post_id) {
        // Retrieve the ACF field value
        $acf_value = get_field('linkedin_image', $post_id);

        // If ACF field has a value, use it
        if (!empty($acf_value)) {
            return $acf_value;
        }

        // Fallback to featured image if ACF field is not set
        $featured_image_url = get_the_post_thumbnail_url($post_id, 'full');
        if (!empty($featured_image_url)) {
            return $featured_image_url;
        }

        // No valid image found
        return null; // Return null if no image is available
    }
}

// Function to upload an image to LinkedIn
function socipu_upload_image_to_linkedin($access_token, $image_url, $person_id) {
    $register_endpoint = 'https://api.linkedin.com/v2/assets?action=registerUpload';
    $register_body = [
        "registerUploadRequest" => [
            "recipes" => ["urn:li:digitalmediaRecipe:feedshare-image"],
            "owner" => $person_id,
            "serviceRelationships" => [
                [
                    "relationshipType" => "OWNER",
                    "identifier" => "urn:li:userGeneratedContent"
                ]
            ]
        ]
    ];

    $register_response = wp_remote_post($register_endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($register_body),
    ]);

    if (is_wp_error($register_response)) {
        return false;
    }

    $register_body_response = json_decode(wp_remote_retrieve_body($register_response), true);

    if (isset($register_body_response['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'])) {
        $upload_url = $register_body_response['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
        $asset_id = $register_body_response['value']['asset'];

        // Upload the image
        $image_data = wp_remote_get($image_url);
        if (is_wp_error($image_data)) {
            return false;
        }

        $upload_response = wp_remote_post($upload_url, [
            'headers' => [
                'Content-Type' => 'application/octet-stream',
            ],
            'body' => wp_remote_retrieve_body($image_data),
        ]);

        if (is_wp_error($upload_response)) {
            return false;
        }

        return $asset_id;
    }

    return false;
}
?>