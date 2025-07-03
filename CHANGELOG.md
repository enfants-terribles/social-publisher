
## [1.4.2] – 2025-06-16

- 🔧 Removed inline JavaScript reload logic from settings page and moved to external JS file
- 🐞 Fixed loading indicator not disappearing after reconnect
- 🔁 Improved reliability of profile dropdown rendering without requiring full page reload
- 🧼 Cleaned up leftover `echo <script>` tags from earlier versions
- 🔒 Verified all settings updates use nonce checks and capability checks

## [1.4.1] – 2025-06-10

- 🧪 Metabox and ACF fields only appear if a valid LinkedIn connection exists (`sp_is_linkedin_connected()`) (Pro).
- 💡 Improved user guidance: Loading indicator shown during the publishing process in the metabox (Pro).
- 🔗 Optional backlink (Pro): Users can enable a checkbox in the metabox to include a blog post link in the LinkedIn post.
- 🔁 Reposting feature (Pro): Posts can now be re-shared manually via the metabox

## [1.4.0] – 2025-06-07

- 🔐 Security update: Added nonce verification and `current_user_can()` for AJAX and post handling
- 🚫 Protection against direct access to plugin files with `defined('ABSPATH')`
- 🧼 Removed inline JavaScript, now using `wp_enqueue_script()` with versioning
- 🧠 Consolidated namespace: All functions, options, and classes now prefixed with `sp_`
- 📄 Updated `readme.txt`: GDPR notice, proxy explanation, FAQ, correct Stable Tag version
- ✅ Passed Plugin Check with no warnings/errors

## [1.3.1] – 2025-04-30x

- 🐞 Bugfix: Preview script was not loaded on external servers if early header output occurred
- 🔧 Early header detection now only reports but no longer prevents script loading

## [1.3.0] – 2025-04-30

- ✅ Revised LinkedIn integration
- 🔐 Auth flow for personal and company profiles
- 🧠 Automatic profile selection on first login
- 🪄 Profile selection dropdown with persistent saving
- 🧼 Workaround for header/output issues during callback handling
- 🩹 Prevents continuous reload during callback redirect
- 🧪 Improved debug output in callback log
- ⚠️ Early header output detected and handled
- 🔧 Consolidated configuration: only one LinkedIn App required

## [1.2.0] – 2025-04-29
- ✨ Full LinkedIn integration: central configuration, ACF fields, backend settings
- 🔄 Automated publishing on `save_post` / `acf/save_post`
- 🔍 ACF preview integration in the backend

## [1.1.0] – 2025-04-22
- 🆕 First usable version with ACF fields and manual LinkedIn posting
- ⚙️ Uses `social_publisher_post_to_linkedin()`
- 🖼 Image upload via LinkedIn Media API implemented

## [1.0.0] – 2025-04-18
- 🚀 Initial plugin setup
- ⚙️ ACF dependency check, admin page, option storage
