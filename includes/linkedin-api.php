<?php



/**
 * Returns the LinkedIn Person URN for the current user using /v2/userinfo.
 *
 * @param string $access_token The LinkedIn access token.
 * @return string|null The Person URN (urn:li:person:xxxx) or null on error.
 */
if ( !function_exists('get_linkedin_person_id') ) {
    function get_linkedin_person_id( $access_token ) {
        $user_response = wp_remote_get(
            'https://api.linkedin.com/v2/userinfo',
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $access_token,
                ),
            )
        );

        if ( is_wp_error( $user_response ) ) {
            error_log( '[ERROR] LinkedIn /userinfo request failed: ' . $user_response->get_error_message() );
            return null;
        }

        $user_body = json_decode( wp_remote_retrieve_body( $user_response ), true );

        if ( empty( $user_body['sub'] ) ) {
            error_log( '[ERROR] LinkedIn /userinfo response missing sub (user id): ' . print_r( $user_body, true ) );
            return null;
        }

        return $user_body['sub'];
    }
}

/**
 * Send a post to LinkedIn using only custom text and an optional image.
 *
 * @param string $access_token
 * @param string $person_id
 * @param int    $post_id
 * @param string $content
 * @param string $image_url
 * @return mixed
 */
if ( !function_exists('social_publisher_post_to_linkedin') ) {
    function social_publisher_post_to_linkedin($access_token, $person_id, $post_id, $content, $image_url) {
        // This function should implement the same logic as social_publisher_publish_to_linkedin,
        // but with the provided $content and $image_url, and without using the post title.
        // For reusability, let's call the main logic:
        // We replicate the key logic from social_publisher_publish_to_linkedin, but use $content and $image_url as arguments.

        $target_urn = get_option( 'social_publisher_linkedin_target_profile' );
        if ( ! $access_token || empty( $target_urn ) ) {
            error_log( '[ERROR] Kein LinkedIn-Zielprofil oder Access Token verfügbar.' );
            return false;
        }

        $author_urn = $target_urn; // Use the target URN from settings, not person_id

        $post_text = $content;
        $uploaded_media = null;

        if ( $image_url ) {
            // 1. Asset registrieren
            $register_upload_response = wp_remote_post( 'https://api.linkedin.com/v2/assets?action=registerUpload', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                    'Content-Type'  => 'application/json',
                    'X-Restli-Protocol-Version' => '2.0.0',
                ],
                'body' => json_encode([
                    "registerUploadRequest" => [
                        "recipes" => [
                            "urn:li:digitalmediaRecipe:feedshare-image"
                        ],
                        "owner" => $target_urn,
                        "serviceRelationships" => [
                            [
                                "relationshipType" => "OWNER",
                                "identifier" => "urn:li:userGeneratedContent"
                            ]
                        ]
                    ]
                ]),
            ]);

            if ( !is_wp_error( $register_upload_response ) ) {
                $register_body = json_decode( wp_remote_retrieve_body( $register_upload_response ), true );
                if ( isset( $register_body['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ) && isset( $register_body['value']['asset'] ) ) {
                    $upload_url = $register_body['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
                    $asset = $register_body['value']['asset'];

                    // 2. Bild hochladen
                    $image_data = wp_remote_get( $image_url );
                    if ( !is_wp_error( $image_data ) ) {
                        $upload_response = wp_remote_request( $upload_url, [
                            'method' => 'PUT',
                            'headers' => [
                                'Authorization' => 'Bearer ' . $access_token,
                                'Content-Type' => 'application/octet-stream',
                            ],
                            'body' => wp_remote_retrieve_body( $image_data ),
                        ]);

                        if ( !is_wp_error( $upload_response ) ) {
                            $uploaded_media = $asset;
                            error_log('[SUCCESS] Bild erfolgreich zu LinkedIn hochgeladen: ' . $uploaded_media);
                        } else {
                            error_log('[ERROR] Fehler beim Bild-Upload zu LinkedIn: ' . $upload_response->get_error_message());
                        }
                    }
                } else {
                    error_log('[ERROR] Fehler beim Registrieren des Uploads bei LinkedIn.');
                }
            } else {
                error_log('[ERROR] Fehler beim Registrieren Upload: ' . $register_upload_response->get_error_message());
            }
        }

        $share_media_category = $uploaded_media ? 'IMAGE' : 'NONE';

        // Debug-Ausgabe für den tatsächlichen Author-URN
        error_log('[DEBUG] Autor für LinkedIn-Post: ' . $author_urn);

        $linkedin_post = [
            'author' => $author_urn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $post_text,
                    ],
                    'shareMediaCategory' => $share_media_category,
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        if ( $uploaded_media ) {
            $linkedin_post['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [
                [
                    'status' => 'READY',
                    'media' => $uploaded_media,
                    'title' => [
                        'text' => $post_text,
                    ],
                    'description' => [
                        'text' => $post_text,
                    ],
                ],
            ];
        }

        $response = wp_remote_post( 'https://api.linkedin.com/v2/ugcPosts', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode( $linkedin_post ),
        ]);

        if ( is_wp_error( $response ) ) {
            error_log( '[ERROR] LinkedIn post failed: ' . $response->get_error_message() );
            return false;
        } else {
            $body = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( isset( $body['id'] ) ) {
                update_post_meta( $post_id, '_linkedin_posted', '1' );
                error_log( '[SUCCESS] LinkedIn post successfully published. ID: ' . $body['id'] );
                return $body;
            } else {
                $raw_body = wp_remote_retrieve_body( $response );
                if ( strpos( $raw_body, 'Duplicate post' ) !== false ) {
                    update_post_meta( $post_id, '_linkedin_posted', '1' );
                    error_log( '[INFO] Duplicate Post erkannt und trotzdem als erfolgreich markiert.' );
                    return true;
                } else {
                    error_log( '[ERROR] LinkedIn API returned no ID: ' . $raw_body );
                    return false;
                }
            }
        }
    }
}