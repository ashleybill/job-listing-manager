=== Job Listing Manager ===
Contributors: AJB
Tags: jobs, employment, careers, job postings
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage job postings with custom post types and blocks.

== Description ==

Job Listing Manager provides functionality to configure job postings easily with content loaded from a custom post type.

Features:
* Custom Job Listing post type
* Job Categories taxonomy
* Custom fields for job details (location, salary, closing date, etc.)
* Automatic job expiration based on closing date
* Application method configuration (mailto or Forminator form)
* Forminator form integration with custom styling
* Template system for easy job listing duplication
* Block editor support with custom blocks
* Settings page for application method configuration

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/job-listing-manager/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Install and activate Secure Custom Fields (SCF) plugin
4. (Optional) Install Forminator plugin if using form-based applications
5. Configure application method in Settings > Job Listings
6. Create job postings under Jobs menu

== Changelog ==

= 0.3.1 =
* Added location column to admin job listings view
* Templates now appear first in admin list
* Location column is sortable

= 0.3.0 =
* Improved template system using meta field instead of custom status
* Template flag no longer copied when duplicating
* Custom slugs now include location (e.g., title-location)
* Added template checkbox in post editor sidebar

= 0.2.0 =
* Added template system for job listings
* Templates can be duplicated to create new job postings
* Templates excluded from frontend display

= 0.1.0 =
* Initial release
