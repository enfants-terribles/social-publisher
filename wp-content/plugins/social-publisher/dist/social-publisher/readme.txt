=== Social Publisher ===
Contributors: maller
Tags: linkedin, auto-publish, social media, ACF, B2B
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.4.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: https://www.enfants.de/social-publisher-wordpress-plugin-fuer-linkedin-social-media-auto-posting/
Auto-publish your WordPress posts on LinkedIn – with custom text and images. For B2B, agency workflow, or personal usage.

== Description ==

Social Publisher is a GDPR-compliant WordPress plugin for automatic posting on LinkedIn – directly when publishing a post. It is ideal for agencies, editorial teams, and B2B marketing teams who want to save time and appear more professional.

To ensure GDPR compliance, the LinkedIn OAuth connection is routed through a neutral, tracking-free proxy domain. This avoids direct requests to LinkedIn from the user's browser and prevents personal data leakage.

**Why Social Publisher?**

* Publish your posts directly to LinkedIn upon saving – no copy & paste, no separate tool
* Use custom texts and images for each post – perfect for tailored LinkedIn posts
* Choose between personal profile or company page
* Works seamlessly with Advanced Custom Fields (ACF) – even in the free version
* GDPR-compliant – OAuth connection via a central, tracking-free proxy domain
* Easy to use – no technical knowledge required


The Pro version is in preparation – with support for Facebook and Instagram as well as additional professional features.

== External Services ==

This plugin connects to the official LinkedIn API to authenticate users and publish content. Below is a list of all external services used, including what data is transmitted and under which conditions.

=== LinkedIn (https://www.linkedin.com and https://api.linkedin.com) ===

The plugin integrates with LinkedIn to allow users to publish posts from WordPress to their LinkedIn profile or company page.

**Purpose:**
- Authenticate a LinkedIn account via OAuth 2.0
- Fetch basic profile information
- Publish posts (text and image) on behalf of the user

**Data transmitted:**
- During OAuth: The plugin sends the `client_id` and `redirect_uri` to LinkedIn.
- After authorization: A temporary authorization code is exchanged for an access token.
- When publishing: The plugin sends the post content (title, text, optional image URL) and the target profile or organization ID.

**When is data sent?**
- Only after the user explicitly connects their LinkedIn account and enables auto-posting.
- Publishing occurs only when the "Share on LinkedIn" option is checked in the post edit screen.

**Endpoints used:**
 - https://www.linkedin.com/oauth/v2/authorization
 - https://www.linkedin.com/oauth/v2/accessToken
 - https://api.linkedin.com/v2/userinfo
 - https://api.linkedin.com/v2/me
 - https://api.linkedin.com/v2/ugcPosts
 - https://api.linkedin.com/v2/assets?action=registerUpload
 - https://api.linkedin.com/media/upload
 - https://api.linkedin.com/v2/assets?action=registerUpload
 - https://api.linkedin.com/v2/userinfo
 - https://api.linkedin.com/v2/me
 - https://social-publisher.enfants.de/oauth/start
 - https://social-publisher.enfants.de/oauth/start?state=linkedin_auth_
 - https://social-publisher.enfants.de/wp-json/social-publisher/v1/linkedin/callback

**Privacy Policy:** https://www.linkedin.com/legal/privacy-policy  
**Terms of Use:** https://www.linkedin.com/legal/user-agreement

No tracking, analytics, or unsolicited data is sent to LinkedIn. All calls are made directly or via an optional GDPR-compliant proxy provided by the plugin author.

== Proxy and GDPR ==

To ensure compliance with the General Data Protection Regulation (GDPR), Social Publisher does not communicate directly with LinkedIn from the WordPress admin interface.

Instead, all OAuth-related requests are routed through a separate proxy server (https://connect.enfants.de), which acts as a neutral relay between WordPress and LinkedIn. This prevents any tracking or third-party cookies from being injected into the WordPress backend.

The proxy server does not store any personal data, credentials, or tokens beyond the runtime of the connection. The connection is end-to-end encrypted and uses short-lived access tokens granted by LinkedIn.

This architecture ensures maximum privacy and a GDPR-compliant integration of LinkedIn into your WordPress website.

== Installation ==

1. Upload and activate the plugin
2. Go to "Settings > Social Publisher"
3. Connect your LinkedIn profile via the button
4. Select the desired target profile
5. Enable "Share on LinkedIn" for your posts

== Frequently Asked Questions ==

= Will my post be updated if I edit it later? =
No – changes to the post do not affect the already published LinkedIn post. You must make changes directly on LinkedIn.

= What do I need for this? =
You need a free LinkedIn profile, a company page (optional), and ACF (Advanced Custom Fields - free).

= Is the plugin free? =
Yes. Social Publisher is currently completely free to use. A Pro version with additional features is in preparation.

= Can I choose between multiple LinkedIn pages or profiles? =
Yes. After connecting your account, you can select either a personal profile or one of your available company pages.

= Will the plugin schedule posts in the future? =
No. Currently, Social Publisher triggers the LinkedIn post immediately when a WordPress post is published. A scheduling feature may be added in the Pro version.

= Can I disconnect or switch LinkedIn accounts? =
Yes. You can disconnect at any time from the settings page and reconnect with a different account.

== Changelog ==

= 1.4.2 =
* Enhancement: Moved inline JavaScript to external files for better performance and GDPR compliance
* Enhancement: Fixed delayed dropdown rendering by adding delayed reload after authentication
* Cleanup: Removed legacy inline <script> output in PHP
* Refactor: Improved plugin feedback logic and JS initialization timing

= 1.4.1 =
* Feature: Added check to hide metabox and ACF fields when not connected to LinkedIn (Pro)
* Feature: Optional backlink toggle (Pro)
* Feature: Loading indicator while posting
* Enhancement: Changelog now aligned with internal CHANGELOG.md

= 1.3.1 =
* Bugfix: Preview script was not loaded on external servers if early header output occurred

= 1.3.0 =
* New OAuth connection with automatic profile selection
* Improved ACF integration

== Upgrade Notice ==

= 1.3.1 =
Important bugfix for the preview – update recommended.

== Screenshots ==

1. Setting "Connect with LinkedIn"
2. Selection of the target profile (company page or personal profile)
3. Checkbox & preview in the post