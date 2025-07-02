<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Returns the LinkedIn Person URN for the current user using /v2/userinfo.
 *
 * @param string $access_token The LinkedIn access token.
 * @return string|null The Person URN (urn:li:person:xxxx) or null on error.
 */
if ( !function_exists('socipu_get_linkedin_person_id') ) {
    function socipu_get_linkedin_person_id( $access_token ) {
        $user_response = wp_remote_get(
            'https://api.linkedin.com/v2/userinfo',
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $access_token,
                ),
            )
        );

        if ( is_wp_error( $user_response ) ) {
            return null;
        }

        $user_body = json_decode( wp_remote_retrieve_body( $user_response ), true );

        if ( empty( $user_body['sub'] ) ) {
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
if ( !function_exists('socipu_social_publisher_post_to_linkedin') ) {
    function socipu_social_publisher_post_to_linkedin($access_token, $person_id, $post_id, $content, $image_url) {
        // Diese Funktion übernimmt die Kernlogik aus social_publisher_publish_to_linkedin,
        // verwendet jedoch die übergebenen $content- und $image_url-Argumente, ohne den Beitragstitel zu nutzen.
        // Wir übernehmen die Kernlogik aus social_publisher_publish_to_linkedin, verwenden jedoch $content und $image_url als Argumente.

        $target_urn = get_option( 'social_publisher_linkedin_target_profile' );
        if ( ! $access_token || empty( $target_urn ) ) {
            return false;
        }

        $author_urn = $target_urn; // Use the target URN from settings, not person_id

        // LinkedIn UGC API requires 'company' instead of 'organization'
        if (strpos($author_urn, 'urn:li:organization:') === 0) {
            $author_urn = str_replace('urn:li:organization:', 'urn:li:company:', $author_urn);
        }

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
                        } else {
                        }
                    }
                } else {
                }
            } else {
            }
        }

        $share_media_category = $uploaded_media ? 'IMAGE' : 'NONE';

        // Debug-Ausgabe für den tatsächlichen Author-URN

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
            return false;
        } else {
            $body = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( isset( $body['id'] ) ) {
                update_post_meta( $post_id, '_linkedin_posted', '1' );
                return $body;
            } else {
                $raw_body = wp_remote_retrieve_body( $response );
                if ( strpos( $raw_body, 'Duplicate post' ) !== false ) {
                    update_post_meta( $post_id, '_linkedin_posted', '1' );
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}