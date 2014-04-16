=== RSVPmaker ===
Contributors: davidfcarr
Donate: http://www.rsvpmaker.com
Tags: event, calendar, rsvp, custom post type, paypal
Donate link: http://rsvpmaker.com/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 3.0.7

Event scheduling and RSVP tracking.

== Description ==

RSVPMaker is an event scheduling and RSVP tracking plugin for WordPress, using the custom post types feature introduced in WP3.0 to track events as an alternate post type with associated dates.

Site editors and administrators have the option to request RSVPs for any given event and specify an email address for notifications when someone responds. RSVP Reports can also be run from the administrator's dashboard.

For events that follow a recurring schedule, such as First Monday or Every Friday, you can set up a template that allows you to add multiple events along the projected schedule using the boilerplate details from the template. You still have the flexibility to customize individual events. For example, you might book a series of monthly events for the year and add the names of speakers or agenda details as you go along.

If a fee is to be charged for the event, RSVPMaker can collect payments online using PayPal (requires manual setup of a PayPal account and creation of a configuration file with API credentials). RSVP reports can be viewed through the admin user interface.

When used with an additional plugin, [RSVPMaker Excel](http://wordpress.org/extend/plugins/rsvpmaker-excel), RSVP reports can easily be downloaded to a spreadsheet, using the Excel functions from the [PHPExcel](http://www.phpexcel.net/) library.

[__RSVPMaker.com__](http://www.rsvpmaker.com/)

Related plugin: [__ChimpBlast__](http://wordpress.org/extend/plugins/chimpblast/) for sending event invites and other email broadcasts through the MailChimp broadcast email service.

Translations:

Spanish: Andrew Kurtis, [__WebHostingHub__](http://www.webhostinghub.com/)

Polish: Jarosław Żeliński

Norwegian: Thomas Nybø

Thank you!

== Installation ==

1. Upload the entire `rsvpmaker` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Visit the RSVPMaker options page to configure default values for RSVP email notifications, etc.
1. Ensure you have enabled directory-style permalinks so the address to events is displayed in the format /rsvpmaker/my-event/ rather than ?rsvpmaker=my-event -- see [http://codex.wordpress.org/Using_Permalinks](http://codex.wordpress.org/Using_Permalinks)
1. See the documentation for shortcodes you can use to create an events listing page, or a list of event headlines for the home page. Use the RSVPMaker widget if you would like to add an events listing to your WordPress sidebar.
1. OPTIONAL: Depending on your theme, you may want to create a single-rsvpmaker.php template to prevent confusion between the post date and the event date (move the post date display code to the bottom or just remove it). A sample for the Twentyten theme is included with this distribution.
1. OPTIONAL: To enable online payments for events, obtain a PayPal API signature and password, edit the included paypal-constants.php file, and upload it (ideally to a location outside of web root). Record the file location on the settings screen.
1. OPTIONAL: Install [RSVPMaker Excel](http://wordpress.org/extend/plugins/rsvpmaker-excel) if you want the ability to export RSVP reports to a spreadsheet.
1. OPTIONAL: You can override any of the functions in rsvpmaker-pluggable.php by creating your own rsvpmaker-custom.php file and adding it to the plugins directory (the directory above the rsvpmaker folder). You can, for example, override the function that displays the RSVP form to include more, fewer, or different fields.

For basic usage, you can also have a look at the [plugin homepage](http://www.rsvpmaker.com/).

== Frequently Asked Questions ==

= Why am I getting a "page not found" error? =

A minority of users report that the RSVPMaker permalinks don't function properly in the default configuration. Go to the RSVPMaker options settings screen and check the box for "Tweak Permalinks." This should clear up the problem by making WordPress reset the permalinks.

Also ensure you have enabled directory-style permalinks so the address to events is displayed in the format /rsvpmaker/my-event/ rather than ?rsvpmaker=my-event -- see [http://codex.wordpress.org/Using_Permalinks](http://codex.wordpress.org/Using_Permalinks).

= Where can I get more information about using RSVPMaker? =

For basic usage, you can also have a look at the [plugin homepage](http://www.rsvpmaker.com/).

== Screenshots ==

1. Edit events like WordPress posts, setting date, time, and RSVP options.
2. Example of an event listing with an RSVP Now! button (click to display a customizable form with info you want to collect).
3. Event templates let you schedule multiple events that occur on a regular schedule, projecting future dates and adding them as a batch. You can also track events associated with the template. Individual events can still be customized as needed.

== Credits ==

    RSVPMaker
    Copyright (C) 2010 David F. Carr

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    See the GNU General Public License at <http://www.gnu.org/licenses/gpl-2.0.html>.
	
	RSVPMaker also includes code derived from the PayPal NVP API software
	development kit for PHP.

== Changelog ==

= 3.0.7 =

Fix to calendar display

= 3.0.6 =

Bug fix: correct handing of "More Events" link. The "events page" field on the RSVPMaker settings screen should be set to a full url like http://rsvpmaker.com/events/

= 3.0.5 = 

Additional rsvpmaker_upcoming attribute of one="next" or one="slug-here" or one="123" (post id) to [highlight a single event in a page or blog post](http://rsvpmaker.com/2014/01/embedding-a-single-event-in-a-page-or-post/).

= 3.0.4 =

Fix to handle password protected posts properly (previously was showing RSVP form even if content was supposed to be protected).

= 3.0.3 =

* Updated Spanish translation
* Fix to dashboard widget

= 3.0.2 =

* Tweaked code to avoid overwriting event post slugs that have been set manually.
* Updated translation for Norwegian

= 3.0.1 =

* Optional dashboard widget
* Updated admin screen for better control of custom menus (display for only authors, only editors, or only admins)
* Updated Norwegian translation (thank you Thomas Nybø)

= 3.0 =

Bug fixes for additional editors function (very tricky)

= 2.8.9 =

Bug fixes, primarily in the event template functions.

= 2.8.8 =

Bug fixes. Checkbox settings on editing screen weren't being recorded properly.

= 2.8.7 =

Bug fix for incorrect rounding of ticket prices.

= 2.8.6 =

More complete Spanish translation

= 2.8.5 =

* Spanish language translation
* Option to allow event authors to designate other users who can edit an event or, more importantly, an event template -- and all events derived from that template. This allows users who do not have full editing rights to be granted rights to edit specific events or series of events. Useful on community websites where several representatives of a group or club may wish to share editing rights, without the site owner having to make them editors of the entire site or of all events.

= 2.8.4 =

Additional form customization shortcodes for checkbox and radio buttons. See [form customization](http://rsvpmaker.com/2012/07/rsvpmaker2-5/)

= 2.8.3 =

Bug fix - trying to address issue some users report with permalinks. Switched to get_post_permalink() instead of get_permalink() (according to Codex, may be better at handling custom post types)

= 2.8.1 and 2.8.2 =

Improvements to template function.

= 2.8 =

* Event template function - more flexible way of handling recurring events
* Update of translation files, more admin functions included

= 2.7.8 =

* Bug fix: recurring events utility was broken, now it's not
* Bug fix: calendar navigation from month to month fixed for sites without pretty permalinks (?page_id=123 format)

= 2.7.7 =

Removed a spam check that created more problems than it solved.

= 2.7.6 =

* Fixes to paypal code
* Better handling of query string post addresses (question mark format rather than pretty permalinks)
* Sort by chronological option for RSVPMaker posts in admin screen
* RSVP Report option to show members who have not responded (for membership sites where users log in to a WordPress account before responding, tracks user IDs). Must be activated on settings screen.

= 2.7.5 =

* Fixed a glitch with display of CAPTCHA image
* Added option to hide yes/no radio boxes (assume the answer is yes)

= 2.7.4.1 and 2.7.4.2 =

Bug fixes

= 2.7.4 =

You can now specify an SMTP account to be used for more reliable delivery of notification emails (less likely to be flagged as spam if they're coming from a real account).

= 2.7.3 =

Another bug fix related to JavaScript output.

= 2.7.2 =

Bug fix. RSVPMaker-specific JavaScript was being output on other post types. Oops.

= 2.7.1 =

* Improved functionality for attendees updating their RSVPs. Previous data loaded from email address coded in link (from confirmation message) or from profile of a logged in user.
* Fixed JavaScript error that was interfering with display attendees function.
* Fixed error in More Events link for an event listing.

= 2.7 =

* Added the option to require a login prior to RSVP for membership-oriented sites where event attendees have a user name and password in WordPress. Name and email can automatically be filled in on the form. It's possible to read in other profile data by customizing the rsvpmaker_profile_lookup function (see the documentation on RSVPMaker customization at rsvpmaker.com).

= 2.6 =

Incremental update to translation files.

= 2.5.9 =

* Norwegian translation (thank you: Thomas Nybø) and update of translation source file.

* Added checkbox to let you specify whether the count of people who have RSVPed should always be shown (or only when a maximum number of participants is specified).

= 2.5.8 =

Bugfix

= 2.5.7 =

* Form customization now includes the ability to set fields as required, with both client and server-side validation. This works with the new shortcode-style method of specifying form fields and form layout. Example: `[rsvpfield textfield="phone" required="1"]`. By default, the required fields are first, last, and email.

* The filter used to add RSVP form fields has also been updated with a lower priority index to make it execute before other filters on the_content. This is in response to a user complaint about interaction with a related posts plugin that also operates on the_content, where the related posts widget was appearing above rather than below the form. New call is `add_filter('the_content','event_content',5)`

= 2.5.6 =

Fixes bug fix with some checkbox options not being set / cleared correctly in the event editor.

= 2.5.5 =

* The date editing section of the event editor now uses drop-downs controls for both adding dates and editing dates.

* rsvpmaker_upcoming shortcode now accepts limit="x" (show x number of events) as an attribute. Example `[rsvpmaker_upcoming limit="3"]` would retrieve a maximum of 3 posts. You can also use add_to_query="myquerystring" to modify the query using query_posts() syntax. Example: `[rsvpmaker_upcoming add_to_query="p=99"]` would retrieve a single rsvpmaker post with the ID of 99. 

* Code changes to prevent a potential security risk with user submitted data in RSVP Reports, use of esc_attr() on variables to prevent script injection.

= 2.5.4 =

* Moved functions for downloading RSVP results to Excel to a separate plugin, RSVPMaker Excel.

* Several bugfixes were released following version 2.5, and a few more are included in this release.

= 2.5 =

Introduced a new method for customizing the RSVP form, either on the settings screen or on a per-event basis. NOTE THAT PREVIOUS CUSTOMIZATIONS WILL NOT BE AUTOMATICALLY BE PRESERVED. The new method provides greater design freedom, allowing you to change the form layout, the order in which fields appear, and whether you want to include the guest section or a note field. A series of shortcodes are provide to generate the fields in the correct format for RSVPMaker.

This release also includes some code cleanup and a fix to the JavaScript function for adding guest fields (thanks to soaringthor for the code shared on the support forum).

= 2.4.2 =

Fix to PayPal code for handling currency other than USD.

= 2.4.1 =

Fix to calendar grid display, navigation between months.

= 2.4 =

Number format options on settings screen for non-U.S. currencies. For example, PLN 1.000,00 (Polish currency, European separation for thousands and decimal) instead of $1,000.00

= 2.3.9 =

* Updates to Polish translation by Jaroslaw Zelinski
* Fix for multi-currency support (display of currency code rather than $ for currencies other than USD)

= 2.3.6 =

* Introducing Polish translation by Jaroslaw Zelinski
* Corrections to translation file setup

= 2.3.5 =

* Improvements to automated reminders. Ability to set timing for reminders cron job
* Even more tweaks for UTF-8 email (coding for From and Subject headers)

= 2.3.4 =

* Automated event reminders to people on RSVP list for an event (experimental)
* Email and confirmation messages set to UTF-8

= 2.3.3 =

Bug fix - rsvp report

= 2.3.2 =

* Fixing character encoding issue with database table for RSVP responses (setting to utf-8 for better multi-language compatibility).
* Fixed typographical error on calendar display (comma between month and year)

= 2.3.1 =

More changes for use with ChimpBlast

= 2.3 =

* Currency for use with PayPal payments can now be customized on Settings screen
* Minor changes for use with ChimpBlast

= 2.2 =

Added option to require people to decode the secret message in a CAPTCHA image when completing the RSVP form. Useful if you're getting spam bot submissions.

= 2.1 =

* Fields for RSVP form can now be edited from the settings panel. Previously modifying the form required some PHP hacking.
* You can now get a listing of past events with some attributes for the event_listings shortcode. Suggesting past="1" format="headline" date_format="F jS, Y"

= 2.0 =

Fixed code for downloading reports to Excel (again), this time based on the [PHPExcel](http://www.phpexcel.net/) library

= 1.9.3 =

* Fix to code for downloading reports to Excel (bundling of PEAR libraries)
* Changed loading of translation domain.

= 1.9.1 =

* Tweak to handing of the loop within rsvpmaker_upcoming shortcode
* Update to plugin url references using plugins_url() instead of constant

= 1.9 =

* Integrated ability to download reports to Excel (still based on PEAR Spreadsheet Writer, but you no longer have to download it separately).
* Bug fixes and code cleanup.

= 1.8 =

Fixing translation files that were missing from svn

= 1.7 =

Bug fixes: display glitch, form spam filtering

= 1.6 =

Added by request: support for custom-fields and post_tag in the rsvpmaker content type. I understand this helps with WooThemes integratiton?

= 1.3, 1.4, 1.5 =

Bug fixes. Sorry

= 1.2 =

* Update to pluggable function rsvpmaker_profile_lookup - will now look up profile details of users who are logged in. Override to retrieve profile details from a member database or any other source.
* Customizable security settings for RSVP Report.

= 1.1 =

* Bug fix for uninstall.php file.
* Fixed display of events with no RSVP set.

= 1.0 =

* Added a `basic_form` function that you can override to change the basic fields of the RSVP form. For example, you can change it to omit the section that asks for the names of guests. This is in addition to the `rsvp_profile` function, which is used to collect additional contact details such as phone # or mailing address. See the instructions for [__adding custom functions__](http://www.rsvpmaker.com/2010/12/changing-the-rsvp-form-other-customizations/).
* You have the option of allowing the names of attendees and the contents of the notes field to be displayed publicly. To avoid encouraging spam entries, this content is loaded via AJAX and only when the user clicks the Show Attendees button
* Moved most of the default formatting into a CSS file that is queued up on pages that show event content. There is in option on the settings page for specifying your own css file to use instead.  Most inline styles have been replaced by class selectors. However, the styling of the RSVP Now button is still set on the RSVPMaker settings screen. Works better for email distribution of events.
* RSVP Report now lists timestamp on reply and lets you sort by either alphabetical order or most recent.
* If you're signing up employees or workers for specific timeslots, you can now set that to half-hour increments
* Tweaked redirection code to handle confirmation and error messages on sites that don't have permalinks enabled
* Changed label for RSVPMaker widget as it shows up on the administrator's screen under Appearance.
* Added an uninstall script for removing custom tables and database entries.

= 0.9.2 =

Bug fix

= 0.9.1 =

Added debug checkbox in options. When this is turned on, it creates an additional admin screen for checking that RSVPs are recorded properly, displaying system variables.

= 0.9 =

* Made it easier to edit dates for events previously entered in system.
* Widget and headlines listing shortcode output now include a link to your event listing page.
* Cleanup on options handling.

= 0.8 =

* Added type parameter for shortcode so you can display only events tagged with "featured" or another event type using `[rsvpmaker_upcoming type="featured"]`
* Added ability to set RSVP start date as well as deadline for RSVPs
* If signing up workers or volunteers for specific timeslots, you can now specify the duration of the timeslots in one-hour increments
* Cleaned up Event Dates, RSVP Options box in editor, moving less commonly used parameters to the bottom.
* Added a Tweak Permalinks setting (a hack for a few users who have reported "page not found" errors, possibly because some other plugin is overwriting the RSVPMaker rewrite rules).
* Tested with WP 3.1 release candidate

= 0.7.6 =

Fixed issue with setting default options.

= 0.7.5 =

Improved ability to add a series of recuring events, including ability for software to calculate the dates based on a schedule like "Second Tuesday of the month"

= 0.7.4 =

Bug fix to prevent customizations from being overwritten. Custom functions should be placed in rsvpmaker-custom.php and the file must be installed in the plugins directory above the rsvpmaker folder: wp-content/plugins/ instead of wp-content/plugins/rsvpmaker/

= 0.7.3 =

* Updated code for displaying RSVP Reports. Added functionality for deleting entries.
* Beginning to introduce translation support. See translations directory for rsvp.pot file to be used by translators.

= 0.7.2 =

Bug fix, RSVP Reports

= 0.7.1 =

Bug fix, tweak to register post type configuration

= 0.7 =

* Custom post type slug changed from 'event' to 'rsvpmaker' in an attempt to avoid name conflicts, permalink issues.
* Widget now lets you set the # of posts to display and date format string

= 0.6.2 =

* Updated to WP 3.03
* Addition of event type taxonomy

= 0.6.1 =

* Fixed errors in database code for recording guests and payments
* Added option to switch between 12-hour and 24-hour time formats
* Added ability to set maximum participants per event.

= 0.6 =

* First public release November 2010.

== Upgrade Notice ==

= 3.0 =

Important fixes if you are using the event templates or additional editors functions

= 2.5.4 =

Export to Excel function moved to a separate plugin.

= 2.5 =

New method for customizing the RSVP form introduced.