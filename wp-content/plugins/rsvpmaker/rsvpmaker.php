<?php

/*
Plugin Name: RSVPMaker
Plugin URI: http://www.rsvpmaker.com
Description: Schedule events and solicit RSVPs. Events are implemented as custom post types, so you get all your familiar post editing tools with extra options for setting dates and RSVP options. PayPal payments can be added with a little extra configuration. Recurring events can be tracked according to a schedule such as "First Monday" or "Every Friday" at a specified time, and the software will calculate future dates according to that schedule and let you track them together. <a href="options-general.php?page=rsvpmaker-admin.php">Options</a> / <a href="edit.php?post_type=rsvpmaker&page=rsvpmaker_doc">Shortcode documentation</a>. Note that if you delete RSVPMaker from the control panel, all associated data will be deleted automatically including contact info of RSVP respondents. To delete data more selectively, use the <a href="/wp-content/plugins/rsvpmaker/cleanup.php">cleanup utility</a> in the plugin directory.
Author: David F. Carr
Version: 3.0.7
Author URI: http://www.carrcommunications.com
*/

global $wp_version;

if (version_compare($wp_version,"3.0","<"))
	exit( __("RSVPmaker plugin requires WordPress 3.0 or greater",'rsvpmaker') );

$locale = get_locale();

$mofile = WP_PLUGIN_DIR . '/rsvpmaker/translations/rsvpmaker-' . $locale . '.mo';

load_textdomain('rsvpmaker',$mofile);

$rsvp_options = get_option('RSVPMAKER_Options');

//defaults
if(!isset($rsvp_options["menu_security"]))
	$rsvp_options["menu_security"] = 'manage_options'; // rsvp report
if(!isset($rsvp_options["rsvpmaker_template"]))
	$rsvp_options["rsvpmaker_template"] = 'publish_rsvpmakers';
if(!isset($rsvp_options["recurring_event"]))
	$rsvp_options["recurring_event"] = 'publish_rsvpmakers';
if(!isset($rsvp_options["multiple_events"]))
	$rsvp_options["multiple_events"] = 'publish_rsvpmakers';
if(!isset($rsvp_options["documentation"]))
	$rsvp_options["documentation"] = 'edit_rsvpmakers';

if(!isset($rsvp_options["rsvp_to"]))
	$rsvp_options["rsvp_to"] = get_bloginfo('admin_email');
if(!isset($rsvp_options["rsvp_confirm"]))
	$rsvp_options["rsvp_confirm"] = __('Thank you!','rsvpmaker');
if(!isset($rsvp_options["rsvp_count"]))
	$rsvp_options["rsvp_count"] = 1;
if(!isset($rsvp_options["rsvp_yesno"]))
	$rsvp_options["rsvp_yesno"] = 1;
if(!isset($rsvp_options['rsvplink']))
	$rsvp_options['rsvplink'] = '<p><a style="width: 8em; display: block; border: medium inset #FF0000; text-align: center; padding: 3px; background-color: #0000FF; color: #FFFFFF; font-weight: bolder; text-decoration: none;" class="rsvplink" href="%s?e=*|EMAIL|*#rsvpnow">'. __('RSVP Now!','rsvpmaker').'</a></p>';
if(!isset($rsvp_options['defaulthour']))
	{
	$rsvp_options['defaulthour'] = 19;
	$rsvp_options['defaultmin'] = 0;
	}
if(!isset($rsvp_options["long_date"]))
	$rsvp_options["long_date"] = 'l F jS, Y';
if(!isset($rsvp_options["short_date"]))
	$rsvp_options["short_date"] = 'F jS';
if(!isset($rsvp_options["time_format"]))
	$rsvp_options["time_format"] = 'g:i A';

if(!isset($rsvp_options["rsvp_form"]) || isset($_GET["reset_form"]))
	$rsvp_options["rsvp_form"] = '<table border="0" cellspacing="0" cellpadding="0" width="100%"> 
<tr> 
<td>'. __('First Name','rsvpmaker').':</td><td>[rsvpfield textfield="first" required="1"]</td> 
</tr> 
<tr> 
<td>'. __('Last Name','rsvpmaker').':</td><td>[rsvpfield textfield="last" required="1"]</td> 
</tr> 
<tr> 
<td width="100">'.__('Email','rsvpmaker').':</td><td>[rsvpfield textfield="email" required="1"]</td> 
</tr>
</table>
[rsvpprofiletable show_if_empty="phone"]
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr> 
<td width="100">'.__('Phone','rsvpmaker').':</td> 
<td>[rsvpfield textfield="phone" size="20"]</td> 
</tr> 
<tr> 
<td>'.__('Phone Type','rsvpmaker').':</td>
<td>[rsvpfield selectfield="phonetype" options="'.__('Work Phone','rsvpmaker').','.__('Mobile Phone','rsvpmaker').','.__('Home Phone','rsvpmaker').'"]</td> 
</tr>
</table>
[/rsvpprofiletable]
[rsvpguests]      
<p>'. __('Note','rsvpmaker').':<br /> 
<textarea name="note" cols="60" rows="2" id="note">[rsvpnote]</textarea> 
</p>
';
if(!isset($rsvp_options["smtp"]) )
	$rsvp_options["smtp"] = '';

if(isset($_GET["reset_form"]))
	update_option('RSVPMAKER_Options',$rsvp_options);

if(!isset($rsvp_options["paypal_currency"]))
	$rsvp_options["paypal_currency"] = 'USD';
if(!isset($rsvp_options["currency_decimal"]))
	$rsvp_options["currency_decimal"] = '.';
if(!isset($rsvp_options["currency_thousands"]))
	$rsvp_options["currency_thousands"] = ',';

if(file_exists(WP_PLUGIN_DIR."/rsvpmaker-custom.php") )
	include_once WP_PLUGIN_DIR."/rsvpmaker-custom.php";

include WP_PLUGIN_DIR."/rsvpmaker/rsvpmaker-admin.php";
include WP_PLUGIN_DIR."/rsvpmaker/rsvpmaker-display.php";
include WP_PLUGIN_DIR."/rsvpmaker/rsvpmaker-plugabble.php";

add_action( 'init', 'rsvpmaker_create_post_type' );

function rsvpmaker_create_post_type() {
global $rsvp_options;
$menu_label = (isset($rsvp_options["menu_label"])) ? $rsvp_options["menu_label"] : __("RSVP Events",'rsvpmaker');
$supports = ( isset($rsvp_options["rsvpmaker_supports"]) ) ? $rsvp_options["rsvpmaker_supports"] : array('title','editor','author','excerpt','custom-fields');

  register_post_type( 'rsvpmaker',
    array(
      'labels' => array(
        'name' => $menu_label,
        'add_new_item' => __( 'Add New Event','rsvpmaker' ),
        'edit_item' => __( 'Edit Event','rsvpmaker' ),
        'new_item' => __( 'Events','rsvpmaker' ),
        'singular_name' => __( 'Event','rsvpmaker' )
      ),
    'menu_icon' => plugins_url('/calendar.png',__FILE__),
	'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'rsvpmaker','with_front' => FALSE), 
    'capability_type' => 'rsvpmaker',
    'map_meta_cap' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => $supports,
	'taxonomies' => array('rsvpmaker-type','post_tag')
    )
  );

  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Event Types', 'taxonomy general name', 'rsvpmaker' ),
    'singular_name' => _x( 'Event Type', 'taxonomy singular name', 'rsvpmaker' ),
    'search_items' =>  __( 'Search Event Types','rsvpmaker' ),
    'all_items' => __( 'All Event Types','rsvpmaker' ),
    'parent_item' => __( 'Parent Event Type','rsvpmaker' ),
    'parent_item_colon' => __( 'Parent Event Type:','rsvpmaker' ),
    'edit_item' => __( 'Edit Event Type','rsvpmaker' ), 
    'update_item' => __( 'Update Event Type','rsvpmaker' ),
    'add_new_item' => __( 'Add New Event Type','rsvpmaker' ),
    'new_item_name' => __( 'New Event Type','rsvpmaker' ),
    'menu_name' => __( 'Event Type','rsvpmaker' ),
  ); 	

  register_taxonomy('rsvpmaker-type',array('rsvpmaker'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'rsvpmaker-type' ),
  ));

//tweak for users who report "page not found" errors - flush rules on every init
global $rsvp_options;
if(isset($rsvp_options["flush"]) && $rsvp_options["flush"])
	flush_rewrite_rules();

// if there is a logged in user, set editing roles
global $current_user;
if( isset($current_user) )
	rsvpmaker_roles();

}

//make sure new rules will be generated for custom post type - flush for admin but not for regular site visitors
if(!isset($rsvp_options["flush"]))
	add_action('admin_init','flush_rewrite_rules');

function cpevent_activate() {
global $wpdb;
global $rsvp_options;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$sql = "CREATE TABLE `".$wpdb->prefix."rsvp_dates` (
  `id` int(11) NOT NULL auto_increment,
  `postID` int(11) default NULL,
  `datetime` datetime default NULL,
  `duration` varchar(255) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
dbDelta($sql);

$sql = "CREATE TABLE `".$wpdb->prefix."rsvpmaker` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255)   CHARACTER SET utf8 COLLATE utf8_general_ci  default NULL,
  `yesno` tinyint(4) NOT NULL default '0',
  `first` varchar(255)  CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL default '',
  `last` varchar(255)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `details` text  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `event` int(11) NOT NULL default '0',
  `owed` float(6,2) NOT NULL default '0.00',
  `amountpaid` float(6,2) NOT NULL default '0.00',
  `master_rsvp` int(11) NOT NULL default '0',
  `guestof` varchar(255)   CHARACTER SET utf8 COLLATE utf8_general_ci  default NULL,
  `note` text   CHARACTER SET  utf8 COLLATE utf8_general_ci NOT NULL,
  `participants` INT NOT NULL DEFAULT '0',
  `user_id` INT NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
dbDelta($sql);

$sql = "CREATE TABLE `".$wpdb->prefix."rsvp_volunteer_time` (
  `id` int(11) NOT NULL auto_increment,
  `event` int(11) NOT NULL default '0',
  `rsvp` int(11) NOT NULL default '0',
  `time` int(11) default '0',
  `participants` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
dbDelta($sql);

$rsvp_options["dbversion"] = 5;
update_option('RSVPMAKER_Options',$rsvp_options);

global $wpdb;
$sql = "SELECT slug FROM ".$wpdb->prefix."terms JOIN `".$wpdb->prefix."term_taxonomy` on ".$wpdb->prefix."term_taxonomy.term_id= ".$wpdb->prefix."terms.term_id WHERE taxonomy='rsvpmaker-type' AND slug='featured'";

if(! $wpdb->get_var($sql) )
	{
	wp_insert_term(
  'Featured', // the term 
  'rsvpmaker-type', // the taxonomy
  array(
    'description'=> 'Featured event. Can be used to put selected events in a listing, for example on the home page',
    'slug' => 'featured'
  )
);
	}

}

register_activation_hook( __FILE__, 'cpevent_activate' );

//upgrade database if necessary
if($rsvp_options["dbversion"] < 4)
	{
	cpevent_activate();
	//correct character encoding error in early releases
	global $wpdb;
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `first` `first` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `last` `last` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");	
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `guestof` `guestof` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");	
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `details` `details` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ");	
	$wpdb->query("ALTER TABLE `wp_rsvpmaker` CHANGE `note` `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ");
	}
if($rsvp_options["dbversion"] < 5)
	cpevent_activate();


function rsvpmaker_template_order( $templates='' )
{
global $post;
if($post->post_type != 'rsvpmaker')
    return $templates;

if(!is_array($templates) && strpos($templates, 'single-rsvpmaker' ) )
   	 return $templates;
	 
   	$template = locate_template(array("single-rsvpmaker.php","page.php","single.php","index.php"),false);

return $templates;
}
add_filter( 'single_template', 'rsvpmaker_template_order' );

?>