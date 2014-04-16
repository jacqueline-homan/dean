=== Allow HTML in Category Descriptions ===
Contributors: arno.esterhuizen, arno_esterhuizen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SGS5KSM9N4D3Y
Tags: categories, category descriptions, html, filter
Requires at least: 2.5
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to use unfiltered HTML in your category descriptions by disabling selected WordPress filters.

== Description ==

When you add text to the category description textarea and save the category, WordPress runs content filters that 
strips out all but the most basic formatting tags.

This plugin disables those filters. Any html code you add to the category description will not be stripped out.

This plugin does not do anything other than disable the filters. It does not protect you from entering invalid HTML, 
nor does it help you create WYSIWYG HTML. You can use the post or page composing screen to help you create the text 
and formatting. Switch to the 'code' tab and copy the HTML code into the category description field.

== Installation ==

1. Upload `html-in-category-descriptions.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Paste or type HTML code in the category description and save.
1. Enjoy HTML in your category descriptions

== Frequently Asked Questions ==

= How do I contact you? =

1. **Email Address:** arno.esterhuizen+wordpress-plugins@gmail.com
1. **Subject Line:** Question: WordPress Plugin: Allow HTML in Category Descriptions
1. **Donations:** https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SGS5KSM9N4D3Y

== Changelog ==

= 1.2.1 =
* Minor formatting of the plugin code, syntax, etc.
* Added a banner image for the plugin page

= 1.2 =
* A version bump to indicate to WordPress that the plugin was reviewed and tested in the latest version of WordPress
* Made sure that the pre_filters array had corresponding items in the filters array
* Added a donation link

= 1.1 =
* Added a filter array for the textareas admin displays

= 1.0 =
* First release into the wild after helping someone on a forum post

== Upgrade Notice ==

= 1.2.1 =
Upgraded plugin for the latest versions of WordPress.

= 1.2 =
Upgraded plugin for the latest versions of WordPress.

= 1.1 =
Added code to prevent HTML from being stripped in textareas in the admin display.