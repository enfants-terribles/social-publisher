<?php
// Function to get LinkedIn Person ID
function get_linkedin_person_id($access_token) {
    $response = wp_remote_get('https://api.linkedin.com/v2/userinfo', [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('LinkedIn API Error: ' . $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['sub'])) {
        error_log('LinkedIn Person ID: ' . $body['sub']);
        return $body['sub'];
    }

    error_log('No LinkedIn Person ID found: ' . print_r($body, true));
    return false;
}

// Funktion zum sicheren Überprüfen einer Bild-URL
function is_image_url_accessible($url) {
    $response = wp_remote_head($url, ['timeout' => 5]);

    if (is_wp_error($response)) {
        error_log("❌ [ERROR] Fehler beim Überprüfen der Bild-URL: " . $url . " - " . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    
    if ($status_code === 200) {
        return true; // Bild existiert und ist erreichbar
    }

    error_log("❌ [ERROR] Bild nicht erreichbar. HTTP Status: " . $status_code . " für URL: " . $url);
    return false;
}

// Function to post to LinkedIn with optional image
function social_publisher_post_to_linkedin($access_token, $person_id, $post_id, $content, $acf_image_url = null) {
    
    $endpoint = 'https://api.linkedin.com/v2/ugcPosts';

    // Startzeit für Debugging
    $start_time = microtime(true);
    error_log("⏳ [DEBUG] LinkedIn-Post gestartet für Post ID: $post_id um " . date("H:i:s"));

    // 📌 Titel abrufen
    $title = get_the_title($post_id);
    if (empty($title)) {
        error_log("⚠️ [ERROR] Kein gültiger Titel für Post ID $post_id gefunden!");
        return false;
    }

    error_log("🔹 [DEBUG] LinkedIn Post Title: " . $title);

    // Standard-Body für Text-Posts
    $body = [
        "author" => $person_id,
        "lifecycleState" => "PUBLISHED",
        "specificContent" => [
            "com.linkedin.ugc.ShareContent" => [
                "shareCommentary" => [
                    "text" => $title . "\n\n" . $content,
                ],
                "shareMediaCategory" => "NONE",
            ],
        ],
        "visibility" => [
            "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC",
        ],
    ];

    // 🔹 **Nur ACF-Bild verwenden, Featured Image ignorieren**
    if (!empty($acf_image_url)) {
        error_log("✅ [INFO] ACF Image gefunden: " . $acf_image_url);

        // Neue, verbesserte Prüfung der Bild-URL
        if (is_image_url_accessible($acf_image_url)) {
            error_log("✅ [INFO] ACF Image ist gültig und erreichbar: " . $acf_image_url);
        } else {
            error_log("⚠️ [ERROR] ACF Image URL ist nicht erreichbar oder ungültig: " . $acf_image_url);
            $acf_image_url = null;
        }
    }

    // 🔹 Falls ein ACF-Bild existiert, es zu LinkedIn hochladen
    if (!empty($acf_image_url)) {
        error_log("📌 [INFO] Hochladen des Bildes zu LinkedIn: " . $acf_image_url);
        $asset_id = upload_image_to_linkedin($access_token, $acf_image_url, $person_id);

        if ($asset_id) {
            error_log("✅ [SUCCESS] LinkedIn Asset ID: " . $asset_id);
            
            // LinkedIn-Post auf "IMAGE" setzen
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
            error_log("❌ [ERROR] Bild konnte nicht zu LinkedIn hochgeladen werden.");
        }
    } else {
        error_log("📌 [INFO] Kein ACF-Bild gefunden, sende reinen Text-Post.");
    }

    // 📌 API-Logging vor dem Absenden
    error_log("🔹 [API TEST] JSON der Anfrage:");
    error_log(json_encode($body, JSON_PRETTY_PRINT));

    // 🚀 LinkedIn API POST mit Timeout
    $response = wp_remote_post($endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ],
        'body' => json_encode($body),
        'timeout' => 8, // Maximal 8 Sekunden warten
    ]);

    if (!is_wp_error($response)) {
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($response_body['id'])) {
            error_log("✅ [SUCCESS] LinkedIn Post erfolgreich veröffentlicht! ID: " . $response_body['id']);
            return $response_body;
        } else {
            error_log("❌ [ERROR] LinkedIn API Fehler: " . print_r($response_body, true));
        }
    } else {
        error_log("❌ [ERROR] LinkedIn API Request Fehler: " . $response->get_error_message());
    }

    // Debugging: Dauer des Prozesses
    $total_time = round(microtime(true) - $start_time, 2);
    error_log("✅ [DEBUG] LinkedIn-Post beendet. Dauer: {$total_time} Sekunden.");

    error_log("❌ [FINAL ERROR] LinkedIn Post fehlgeschlagen.");
    return false;
}

if (!function_exists('resolve_linkedin_image_url')) {
    function resolve_linkedin_image_url($post_id) {
        // Retrieve the ACF field value
        $acf_value = get_field('linkedin_image', $post_id);
        //error_log('ACF LinkedIn Image Value (Raw): ' . print_r($acf_value, true));

        // If ACF field has a value, use it
        if (!empty($acf_value)) {
            error_log('Using ACF Image URL: ' . $acf_value);
            return $acf_value;
        }

        // Fallback to featured image if ACF field is not set
        $featured_image_url = get_the_post_thumbnail_url($post_id, 'full');
        if (!empty($featured_image_url)) {
            error_log('Using Featured Image URL: ' . $featured_image_url);
            return $featured_image_url;
        }

        // No valid image found
        //error_log('No valid image found for LinkedIn post.');
        return null; // Return null if no image is available
    }
}

// Function to upload an image to LinkedIn
function upload_image_to_linkedin($access_token, $image_url, $person_id) {
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
        error_log('Error registering LinkedIn upload: ' . $register_response->get_error_message());
        return false;
    }

    $register_body_response = json_decode(wp_remote_retrieve_body($register_response), true);

    if (isset($register_body_response['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'])) {
        $upload_url = $register_body_response['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
        $asset_id = $register_body_response['value']['asset'];

        // Upload the image
        $image_data = wp_remote_get($image_url);
        if (is_wp_error($image_data)) {
            error_log('Error fetching image: ' . $image_data->get_error_message());
            return false;
        }

        $upload_response = wp_remote_post($upload_url, [
            'headers' => [
                'Content-Type' => 'application/octet-stream',
            ],
            'body' => wp_remote_retrieve_body($image_data),
        ]);

        if (is_wp_error($upload_response)) {
            error_log('Error uploading image to LinkedIn: ' . $upload_response->get_error_message());
            return false;
        }

        return $asset_id;
    }

    error_log('Error in image upload response: ' . print_r($register_body_response, true));
    return false;
}
?>