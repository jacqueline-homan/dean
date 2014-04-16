<?php

function date_slug($data) {
	if($data["post_status"] != 'publish')
		return $data;

	if(isset($_POST["event_month"][1]) )
		{
		$y = (int) $_POST["event_year"][1];
		$m = (int) $_POST["event_month"][1];
		$d = (int) $_POST["event_day"][1];
		$date = $y.'-'.$m.'-'.$d;
	
		if (empty($data['post_name']) || !strpos($data['post_name'],$date) ) {
			$data['post_name'] = sanitize_title($data['post_title']);
			$data['post_name'] .= '-' .$date;
			}
		}
	
	return $data;
}

add_filter('wp_insert_post_data', 'date_slug', 10);

function unique_date_slug($slug, $post_ID = 0, $post_status = '', $post_type = '', $post_parent = 0, $original_slug='' )
	{
	global $post;
	global $wpdb;
	if($post->post_type != 'rsvpmaker')
		return $slug;
	if($post->post_status != 'publish')
		return $slug;
	
	if(!preg_match('/-[\d]{0,3}$/',$slug) )
		return $slug;
	
	$sql = "SELECT DATE_FORMAT(datetime,'%Y-%c-%e') from ". $wpdb->prefix."rsvp_dates WHERE postID=$post->ID ORDER BY datetime";
	$date = $wpdb->get_var($sql);
	
	$newslug = sanitize_title($post->post_title) .'-' .$date;
	$check_sql = $wpdb->prepare("SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND ID != %d LIMIT 1",$newslug, $post->post_type, $post->ID);
	$post_name_check = $wpdb->get_var(  $check_sql );
	if($post_name_check)
		return $slug;
	else
		return $newslug;
	}

add_filter('wp_unique_post_slug','unique_date_slug',10);

function save_calendar_data($postID) {

global $wpdb;

if($parent_id = wp_is_post_revision($postID))
	{
	$postID = $parent_id;
	}

if(isset($_POST["event_month"]) )
	{

	foreach($_POST["event_year"] as $index => $year)
		{
		if(isset($_POST["event_day"][$index]) && $_POST["event_day"][$index])
			{
			$cddate = $year . "-" . $_POST["event_month"][$index]  . "-" . $_POST["event_day"][$index] . " " . $_POST["event_hour"][$index] . ":" . $_POST["event_minutes"][$index] . ":00";
			if( $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."rsvp_dates WHERE postID=$postID AND datetime='$cddate' ") )
				continue;
			
			$dpart = explode(':',$_POST["event_duration"][$index]);
			if( is_numeric($dpart[0]) )
				{
				$hour = $_POST["event_hour"][$index] + $dpart[0];
				$minutes = (isset($dpart[1]) ) ? $_POST["event_minutes"][$index] + $dpart[1] : $_POST["event_minutes"][$index];
				$duration = mktime( $hour, $minutes,0,$_POST["event_month"][$index],$_POST["event_day"][$index],$year);
				}
			else
				$duration = $_POST["event_duration"][$index]; // empty or all day
				
			$sql = " SET datetime='$cddate',duration='$duration', postID=". $postID;
			
			if(isset($_POST["custom_post_dates_id"][$index]))
				$sql = "UPDATE ".$wpdb->prefix."rsvp_dates $sql WHERE id=". (int) $_POST["custom_post_dates_id"][$index]; 
			else
				$sql = "INSERT INTO ".$wpdb->prefix."rsvp_dates $sql"; 
			
			$wpdb->query($sql);
			}
		}

	
	if(isset($_POST["delete_date"]))
		{
		foreach($_POST["delete_date"] as $id)
			{
			$dsql = "DELETE FROM ".$wpdb->prefix."rsvp_dates WHERE id=$id";
			$wpdb->query($dsql);
			
			}
		}	
	
	}


if(isset($_POST["edit_month"]))
	{
//print_r($_POST);
	foreach($_POST["edit_year"] as $index => $year)
		{
			$cddate = $year . "-" . $_POST["edit_month"][$index]  . "-" . $_POST["edit_day"][$index] . " " . $_POST["edit_hour"][$index] . ":" . $_POST["edit_minutes"][$index] . ":00";
			
			if(strpos( $_POST["edit_duration"][$index],':' ))
				{
				$dpart = explode(':',$_POST["edit_duration"][$index]);
				if( is_numeric($dpart[0]) )
					{
					$hour = $_POST["edit_hour"][$index] + $dpart[0];
					$minutes = (isset($dpart[1]) ) ? $_POST["edit_minutes"][$index] + $dpart[1] : $_POST["edit_minutes"][$index];
					$duration = mktime( $hour, $minutes,0,$_POST["edit_month"][$index],$_POST["edit_day"][$index],$year);
					}
				}
			elseif( is_numeric($_POST["edit_duration"][$index]) )
				{					
				$minutes = $_POST["edit_minutes"][$index] + (60*$_POST["edit_duration"][$index]);
				$duration = mktime( $_POST["edit_hour"][$index], $minutes,0,$_POST["edit_month"][$index],$_POST["edit_day"][$index],$year);
				}
			else
				$duration = $_POST["edit_duration"][$index]; // empty or all day
			
			$sql = "UPDATE ".$wpdb->prefix."rsvp_dates  SET datetime='$cddate',duration='$duration'  WHERE id=$index"; 
			//echo $sql;
			$wpdb->query($sql);
			}
	} // end edit month
	
	if(isset($_POST["setrsvp"]["on"]))
		save_rsvp_meta($postID);
	else
		delete_post_meta($postID, '_rsvp_on', '1');

	if(isset($_POST["sked"]["week"]))
		save_rsvp_template_meta($postID);

}

function rsvpmaker_date_option($datevar = NULL, $index = NULL) {

global $rsvp_options;
$prefix = "event_";

if(is_array($datevar) )
{
	$datestring = $datevar["datetime"];
	$duration = $datevar["duration"];
	$prefix = "edit_";
	$index = $datevar["id"];
}
else
{
	$datestring = $datevar;
}

if(strpos($datestring,'-'))
	{
	$t = strtotime($datestring);
	$month =  (int) date('n',$t);
	$year =  (int) date('Y',$t);
	$day =  (int) date('j',$t);
	$hour =  (int) date('G',$t);
	$minutes =  (int) date('i',$t);
	}
elseif($datestring == 'today')
	{
	$month =  (int) date('n');
	$year =  (int) date('Y');
	$day =  (int) date('j');
	$hour = (isset($rsvp_options["defaulthour"])) ? ( (int) $rsvp_options["defaulthour"]) : 19;
	$minutes = (isset($rsvp_options["defaultmin"])) ? ( (int) $rsvp_options["defaultmin"]) : 0;
	}
else
	{
	$month = (int) date('n');
	$year =  (int) date('Y');
	$day = 0;
	$hour = (isset($rsvp_options["defaulthour"])) ? ( (int) $rsvp_options["defaulthour"]) : 19;
	$minutes = (isset($rsvp_options["defaultmin"])) ? ( (int) $rsvp_options["defaultmin"]) : 0;
	}

?>
<div id="<?php echo $prefix; ?>date<?php echo $index;?>" style="border-bottom: thin solid #888;">
<table width="100%">
<tr>
            <td width="*"><div id="date_block"><?php echo __('Month:','rsvpmaker');?> 
<select name="<?php echo $prefix; ?>month[<?php echo $index;?>]"> 
<?php
for($i = 1; $i <= 12; $i++)
{
echo "<option ";
	if($i == $month)
		echo ' selected="selected" ';
	echo 'value="'.$i.'">'.$i."</option>\n";
}
?>
</select> 
<?php echo __('Day:','rsvpmaker');?> 
<select name="<?php echo $prefix; ?>day[<?php echo $index;?>]"> 
<?php
if($day == 0)
	echo '<option value="0">Not Set</option>';
for($i = 1; $i <= 31; $i++)
{
echo "<option ";
	if($i == $day)
		echo ' selected="selected" ';
	echo 'value="'.$i.'">'.$i."</option>\n";
}
?>
</select> 
<?php echo __('Year','rsvpmaker');?>
<select name="<?php echo $prefix; ?>year[<?php echo $index ;?>]"> 
<?php
$y = (int) date('Y');
$limit = $y + 3;
for($i = $y; $i < $limit; $i++)
{
echo "<option ";
	if($i == $year)
		echo ' selected="selected" ';
	echo 'value="'.$i.'">'.$i."</option>\n";
}
?>
</select> 
</div> 
            </td> 
          </tr> 
<tr> 
<td><?php echo __('Hour:','rsvpmaker');?> <select name="<?php echo $prefix; ?>hour[<?php echo $index;?>]"> 
<?php
for($i=0; $i < 24; $i++)
	{
	$selected = ($i == $hour) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	if($i == 0)
		$twelvehour = "12 a.m.";
	elseif($i == 12)
		$twelvehour = "12 p.m.";
	elseif($i > 12)
		$twelvehour = ($i - 12) ." p.m.";
	else		
		$twelvehour = $i." a.m.";

	printf('<option  value="%s" %s>%s / %s:</option>',$padded,$selected,$twelvehour,$padded);
	}
?>
</select> 
 
<?php echo __('Minutes:','rsvpmaker');?> <select name="<?php echo $prefix; ?>minutes[<?php echo $index;?>]"> 
<?php
for($i=0; $i < 60; $i ++)
	{
	$selected = ($i == $minutes) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	printf('<option  value="%s" %s>%s</option>',$padded,$selected,$padded);
	}
?>
</select> -

<?php echo __('Duration','rsvpmaker');?> <select name="<?php echo $prefix; ?>duration[<?php echo $index;?>]">
<option value=""><?php echo __('Not set (optional)','rsvpmaker');?></option>
<option value="allday" <?php if(isset($duration) && ($duration == 'allday')) echo ' selected="selected" '; ?>><?php echo __("All day/don't show time in headline",'rsvpmaker');?></option>
<?php
if(isset($duration) && is_numeric($duration) )
	{
	$diff = (string) ( (((int) $duration) - $t) / 3600);
	$dparts = explode('.',$diff);
	$dh = (int) $dparts[0];
	$decimal = (isset($dparts[1]) ) ? (int) $dparts[1] : 0;
	}
else
	{
		$dh = $decimal = NULL;
	}
for($h = 1; $h < 24; $h++) {
	 ;?>
<option value="<?php echo $h;?>" <?php if(($h == $dh) && ($decimal == 0) ) echo ' selected="selected" '; ?> ><?php echo $h;?> hours</option>
<option value="<?php echo $h;?>:15" <?php if(($h == $dh) && ($decimal == 25) ) echo ' selected="selected" '; ?> ><?php echo $h;?>:15</option>
<option value="<?php echo $h;?>:30"  <?php if(($h == $dh) && ($decimal == 5) ) echo ' selected="selected" '; ?> ><?php echo $h;?>:30</option>

<option value="<?php echo $h;?>:45"  <?php if(($h == $dh) && ($decimal == 75) ) echo ' selected="selected" '; ?> ><?php echo $h;?>:45</option>
<?php } ;?>
</select>
<br /> 
</td> 
          </tr> 
</table>
</div>
<?php

}

function save_rsvp_meta($postID)
{
$setrsvp = $_POST["setrsvp"];

if(!isset($setrsvp["show_attendees"])) $setrsvp["show_attendees"] = 0;
if(!isset($setrsvp["count"])) $setrsvp["count"] = 0;
if(!isset($setrsvp["captcha"])) $setrsvp["captcha"] = 0;
if(!isset($setrsvp["login_required"])) $setrsvp["login_required"] = 0;
$setrsvp["yesno"] = (isset($setrsvp["yesno"]) && $setrsvp["yesno"]) ? 1 : 0;

if(isset($_POST["deadyear"]) && isset($_POST["deadmonth"]) && isset($_POST["deadday"]) && $_POST["deadday"])
	$setrsvp["deadline"] = strtotime($_POST["deadyear"].'-'.$_POST["deadmonth"].'-'.$_POST["deadday"].' 23:59:59');

if(isset($_POST["startyear"]) && isset($_POST["startmonth"]) && isset($_POST["startday"]) && $_POST["startday"])
	$setrsvp["start"] = strtotime($_POST["startyear"].'-'.$_POST["startmonth"].'-'.$_POST["startday"].' 00:00:00');

if(isset($_POST["remindyear"]) && isset($_POST["remindmonth"]) && isset($_POST["remindday"]) && $_POST["remindday"])
	$setrsvp["reminder"] = date('Y-m-d',strtotime($_POST["remindyear"].'-'.$_POST["remindmonth"].'-'.$_POST["remindday"].' 00:00:00') );

foreach($setrsvp as $name => $value)
	{
	$field = '_rsvp_'.$name;
	$single = true;
	$current = get_post_meta($postID, $field, $single);
	
	if( (($current == "") || ($current == NULL)) )
		{
		add_post_meta($postID, $field, $value, true);
		}
	else
		{
		update_post_meta($postID, $field, $value);
		}
	}

if(isset($_POST["unit"]))
	{
	foreach($_POST["unit"] as $index => $value)
		{
		if($value && isset($_POST["price"][$index]))
			{
			$per["unit"][$index] = $value;
			$per["price"][$index] = $_POST["price"][$index];
			}
		}	
	
	$value = $per;
	$field = "_per";
	
	$current = get_post_meta($postID, $field, $single); 
	
	if($value && ($current == "") )
		add_post_meta($postID, $field, $value, true);
	
	elseif($value != $current)
		update_post_meta($postID, $field, $value);
	
	elseif($value == "")
		delete_post_meta($postID, $field, $current);
	
	}
}

add_action('admin_menu', 'my_events_menu');

add_action('save_post','save_calendar_data');

function rsvpmaker_menu_security($label, $slug,$options) {

echo $label;
?>
 <select name="option[<?php echo $slug; ?>]" id="<?php echo $slug; ?>">
  <option value="manage_options" <?php if(isset($options[$slug]) && ($options[$slug] == 'manage_options')) echo ' selected="selected" ';?> >Administrator (manage_options)</option>
  <option value="edit_others_rsvpmakers" <?php if(isset($options[$slug]) && ($options[$slug] == 'edit_others_rsvpmakers')) echo ' selected="selected" ';?> >Editor (edit_others_rsvpmakers)</option>
  <option value="publish_rsvpmakers" <?php if(isset($options[$slug]) && ($options[$slug] == 'publish_rsvpmakers')) echo ' selected="selected" ';?> >Author (publish_rsvpmakers)</option>
  <option value="edit_rsvpmakers" <?php if(isset($options[$slug]) && ($options[$slug] == 'edit_rsvpmakers')) echo ' selected="selected" ';?> >Contributor (edit_rsvpmakers)</option>
  </select><br />
<?php
}

  
  // Avoid name collisions.
  if (!class_exists('RSVPMAKER_Options'))
      : class RSVPMAKER_Options
      {
          // this variable will hold url to the plugin  
          var $plugin_url;
          
          // name for our options in the DB
          var $db_option = 'RSVPMAKER_Options';
          
          // Initialize the plugin
          function RSVPMAKER_Options()
          {
              $this->plugin_url = plugins_url('',__FILE__).'/';

              // add options Page
              add_action('admin_menu', array(&$this, 'admin_menu'));
              
          }
          
          // hook the options page
          function admin_menu()
          {
              add_options_page('RSVPMaker', 'RSVPMaker', 'manage_options', basename(__FILE__), array(&$this, 'handle_options'));
          }
          
          
          // handle plugin options
          function get_options()
          {
              global $rsvp_options;
              return $rsvp_options;
          }
          
          // Set up everything
          function install()
          {
              // set default options
              $this->get_options();
          }
          
          // handle the options page
          function handle_options()
          {
              $options = $this->get_options();
              
              if (isset($_POST['submitted'])) {
              		
              		//check security
              		check_admin_referer('calendar-nonce');
              		
                  $newoptions = stripslashes_deep($_POST["option"]);
                  $newoptions["rsvp_on"] = (isset($_POST["option"]["rsvp_on"]) && $_POST["option"]["rsvp_on"]) ? 1 : 0;
                  $newoptions["login_required"] = (isset($_POST["option"]["login_required"]) && $_POST["option"]["login_required"]) ? 1 : 0;
                  $newoptions["rsvp_captcha"] = (isset($_POST["option"]["rsvp_captcha"]) && $_POST["option"]["rsvp_captcha"]) ? 1 : 0;
                  $newoptions["rsvp_yesno"] = (isset($_POST["option"]["rsvp_yesno"]) && $_POST["option"]["rsvp_yesno"]) ? 1 : 0;
                  $newoptions["rsvp_count"] = (isset($_POST["option"]["rsvp_count"]) && $_POST["option"]["rsvp_count"]) ? 1 : 0;
                  $newoptions["show_attendees"] = (isset($_POST["option"]["show_attendees"]) && $_POST["option"]["show_attendees"]) ? 1 : 0;
                  $newoptions["missing_members"] = (isset($_POST["option"]["missing_members"]) && $_POST["option"]["missing_members"]) ? 1 : 0;
                  $newoptions["additional_editors"] = (isset($_POST["option"]["additional_editors"]) && $_POST["option"]["additional_editors"]) ? 1 : 0;
				  $newoptions["dbversion"] = $options["dbversion"]; // gets set by db upgrade routine
				  $newoptions["posttypecheck"] = $options["posttypecheck"];
				if(isset($options["noeventpageok"]) ) $newoptions["noeventpageok"] = $options["noeventpageok"];
				$nfparts = explode('|',$_POST["currency_format"]);
				$newoptions["currency_decimal"] = $nfparts[0];
				$newoptions["currency_thousands"] = $nfparts[1];
				
				  $options = $newoptions;
				  
                  update_option($this->db_option, $options);
                  
                  echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
              }
              
              // URL for form submit, equals our current page
              $action_url = $_SERVER['REQUEST_URI'];


$defaulthour = (isset($options["defaulthour"])) ? ( (int) $options["defaulthour"]) : 19;
$defaultmin = (isset($options["defaultmin"])) ? ( (int) $options["defaultmin"]) : 0;
$houropt = $minopt ="";

for($i=0; $i < 24; $i++)
	{
	$selected = ($i == $defaulthour) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	if($i == 0)
		$twelvehour = "12 a.m.";
	elseif($i == 12)
		$twelvehour = "12 p.m.";
	elseif($i > 12)
		$twelvehour = ($i - 12) ." p.m.";
	else		
		$twelvehour = $i." a.m.";

	$houropt .= sprintf('<option  value="%s" %s>%s / %s:</option>',$padded,$selected,$twelvehour,$padded);
	}

for($i=0; $i < 60; $i += 5)
	{
	$selected = ($i == $defaultmin) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	$minopt .= sprintf('<option  value="%s" %s>%s</option>',$padded,$selected,$padded);
	}

if(isset($_GET["test"]))
	print_r($options);

if(isset($_GET["reminder_reset"]))
	rsvp_reminder_reset($_GET["reminder_reset"]);

?>

<div class="wrap" style="max-width:950px !important;">

<div style="float: right;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="N6ZRF6V6H39Q8">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>

	<h2>Calendar Options</h2>
    
    <?php
if(file_exists(WP_PLUGIN_DIR."/rsvpmaker-custom.php") )
	echo "<p><em>".__('Note: This site also implements custom code in','rsvpmaker').' '.WP_PLUGIN_DIR."/rsvpmaker-custom.php.</em></p>";
	?>
    
	<div id="poststuff" style="margin-top:10px;">

	 <div id="mainblock" style="width:710px">
	 
		<div class="dbx-content">
		 	<form name="caldendar_options" action="<?php echo $action_url ;?>" method="post">
					
                    <input type="hidden" name="submitted" value="1" /> 
					<?php wp_nonce_field('calendar-nonce');?>

					<h3><?php _e('Default Content for Events (such as standard meeting location)','rsvpmaker'); ?>:</h3>
  <textarea name="option[default_content]"  rows="5" cols="80" id="default_content"><?php if(isset($options["default_content"])) echo $options["default_content"];?></textarea>
	<br />
<?php _e('Hour','rsvpmaker'); ?>: <select name="option[defaulthour]"> 
<?php echo $houropt;?>
</select> 
 
<?php _e('Minutes','rsvpmaker'); ?>: <select name="option[defaultmin]"> 
<?php echo $minopt;?>
</select>
<br />
<strong><?php _e('RSVP TO','rsvpmaker'); ?>:</strong><br />
<textarea rows="2" cols="80" name="option[rsvp_to]" id="rsvp_to"><?php if(isset($options["rsvp_to"])) echo $options["rsvp_to"];?></textarea>
<br />
<input type="checkbox" name="option[rsvp_on]" value="1" <?php if(isset($options["rsvp_on"]) && $options["rsvp_on"]) echo ' checked="checked" ';?> /> <strong><?php _e('RSVP On','rsvpmaker'); ?></strong>
<?php _e('check to turn on by default','rsvpmaker'); ?>	<br />    

<input type="checkbox" name="option[rsvp_captcha]" value="1" <?php if(isset($options["rsvp_captcha"]) && $options["rsvp_captcha"]) echo ' checked="checked" ';?> /> <strong><?php _e('RSVP CAPTCHA On','rsvpmaker'); ?></strong> <?php _e('check to turn on by default','rsvpmaker'); ?><br />

<input type="checkbox" name="option[login_required]" value="1" <?php if(isset($options["login_required"]) && $options["login_required"]) echo ' checked="checked" ';?> /> <strong><?php _e('Login Required to RSVP','rsvpmaker'); ?></strong> <?php _e('check to turn on by default','rsvpmaker'); ?>
<br />

  <input type="checkbox" name="option[show_attendees]" value="1" <?php if(isset($options["show_attendees"]) && $options["show_attendees"]) echo ' checked="checked" ';?> /> <strong><?php _e('RSVPs Attendees List Public','rsvpmaker'); ?></strong> <?php _e('check to turn on by default','rsvpmaker'); ?>
	<br />

  <input type="checkbox" name="option[rsvp_count]" value="1" <?php if(isset($options["rsvp_count"]) && $options["rsvp_count"]) echo ' checked="checked" ';?> /> <strong><?php _e('Show RSVP Count','rsvpmaker'); ?></strong> <?php _e('check to turn on by default','rsvpmaker'); ?>
	<br />

  <input type="checkbox" name="option[rsvp_yesno]" value="1" <?php if(isset($options["rsvp_yesno"]) && $options["rsvp_yesno"]) echo ' checked="checked" ';?> /> <strong><?php _e('Show RSVP Yes/No Radio Buttons','rsvpmaker'); ?></strong> <?php _e('check to turn on by default','rsvpmaker'); ?>
	<br />

  <input type="checkbox" name="option[missing_members]" value="1" <?php if(isset($options["missing_members"]) && $options["missing_members"]) echo ' checked="checked" ';?> /> <strong><?php _e('RSVP Form Shows Members Not Responding','rsvpmaker'); ?></strong><br /><em><?php _e('if members log in to RSVP, this shows user accounts NOT associated with an RSVP (tracking WordPress user IDs)','rsvpmaker'); ?>.</em>
	<br />

					<h3><?php _e('Instructions for Form','rsvpmaker'); ?>:</h3>
  <textarea name="option[rsvp_instructions]"  rows="5" cols="80" id="rsvp_instructions"><?php if(isset($options["rsvp_instructions"]) ) echo $options["rsvp_instructions"];?></textarea>
	<br />
					<h3><?php _e('Confirmation Message','rsvpmaker'); ?>:</h3>
  <textarea name="option[rsvp_confirm]"  rows="5" cols="80" id="rsvp_confirm"><?php if( isset($options["rsvp_confirm"]) ) echo $options["rsvp_confirm"];?></textarea>
	<br />
					<h3><?php _e('RSVP Form','rsvpmaker'); ?>RSVP Form (<a href="#" id="enlarge"><?php _e('Enlarge','rsvpmaker'); ?></a>):</h3>
  <textarea name="option[rsvp_form]"  rows="5" cols="80" id="rsvpform"><?php if( isset($options["rsvp_form"]) ) echo htmlentities($options["rsvp_form"]);?></textarea>
<br /><?php _e("This is a customizable template for the RSVP form, introduced as part of the Aug. 2012 update. With the exception of the yes/no radio buttons and the notes textarea, fields are represented by the shortcodes [rsvpfield textfield=&quot;fieldname&quot;] or [rsvpfield selectfield=&quot;fieldname&quot; options=&quot;option1,option2&quot;]. There is also a [rsvpprofiletable show_if_empty=&quot;phone&quot;] shortcode which is an optional block that will not be displayed if the required details (such as a phone number) are already &quot;on file&quot; from a prior RSVP. For this to work, there must also be a [/rsvpprofiletable] closing tag. The guest section of the form is represented by [rsvpguests] (no parameters). If you don't want the guest blanks to show up, you can remove this. The form code you supply will be wrapped in a form tag with the CSS ID of",'rsvpmaker'); ?> &quot;rsvpform&quot;.
<script>
jQuery('#enlarge').click(function() {
  jQuery('#rsvpform').attr('rows','40');
  return false;
});
</script>
	<br />
					<h3><?php _e('RSVP Link','rsvpmaker'); ?>:</h3>
  <textarea name="option[rsvplink]"  rows="5" cols="80" id="rsvplink"><?php if(isset($options["rsvplink"]) ) echo $options["rsvplink"];?></textarea>
	<br />
					<h3><?php _e('Date Format (long)','rsvpmaker'); ?>:</h3>
  <input type="text" name="option[long_date]"  id="long_date" value="<?php if(isset($options["long_date"]) ) echo $options["long_date"];?>" /> (used in event display, PHP <a target="_blank" href="http://us2.php.net/manual/en/function.date.php">date format string</a>)
	<br />
					<h3><?php _e('Date Format (short)','rsvpmaker'); ?>:</h3>
  <input type="text" name="option[short_date]"  id="short_date" value="<?php if(isset($options["short_date"]) ) echo $options["short_date"];?>" /> (used in headlines for event_listing shortcode)
	<br />
<h3><?php _e('Time Format','rsvpmaker'); ?>:</h3>
<p>
<input type="radio" name="option[time_format]" value="g:i A" <?php if( isset($options["time_format"]) && ($options["time_format"] == "g:i A")) echo ' checked="checked"';?> /> 12 hour AM/PM 
<input type="radio" name="option[time_format]" value="H:i" <?php if( isset($options["time_format"]) && ($options["time_format"] == "H:i")) echo ' checked="checked"';?> /> 24 hour 

<br />
					<h3><?php _e('Event Page','rsvpmaker'); ?>:</h3>
  <input type="text" name="option[eventpage]" value="<?php if(isset($options["eventpage"]))  echo $options["eventpage"];?>" size="80" />

<br /><h3><?php _e('Custom CSS','rsvpmaker'); ?>:</h3>
  <input type="text" name="option[custom_css]" value="<?php if(isset($options["custom_css"]) ) echo $options["custom_css"];?>" size="80" />
<?php
if(isset($options["custom_css"]) && $options["custom_css"])
	{

		$file_headers = @get_headers($options["custom_css"]);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			echo ' <span style="color: red;">'.__('Error: CSS not found','rsvpmaker').'</span>';
		}
		else {
			echo ' <span style="color: green;">'.__('OK','rsvpmaker').'</span>';
		}

	}
$dstyle = plugins_url('/style.css',__FILE__);
?>

    <br /><em><?php _e('Allows you to override the standard styles from','rsvpmaker'); ?> <br /><a href="<?php echo $dstyle;?>"><?php echo $dstyle;?></a></em>


<br />					<h3><?php _e('PayPal Configuration File','rsvpmaker'); ?>:</h3>
  <input type="text" name="option[paypal_config]" value="<?php if(isset($options["paypal_config"]) ) echo $options["paypal_config"];?>" size="80" />
<?php
if( isset($options["paypal_config"]) ) $config = $options["paypal_config"];
if(isset($config) && file_exists($config) )
	echo ' <span style="color: green;">'.__('OK','rsvpmaker').'</span>';
else
	echo ' <span style="color: red;">'.__('error: file not found','rsvpmaker').'</span>';

?>	
    <br /><em><?php _e('To enable PayPal payments, you must manually create a configuration file. Sample config file included with distribution. Must be manually configured. For security reasons, we recommend storing the file outside of web root. For example, /home/account/paypal_config.php where web content is stored in /home/account/public_html/','rsvpmaker'); ?>
<?php
echo "<br /><br />".__('On your system, the base web directory is','rsvpmaker').": <strong>".$_SERVER['DOCUMENT_ROOT'].'</strong>';
?>
    </em>

<br /><h3><?php _e('PayPal Currency','rsvpmaker'); ?>:</h3>
<input type="text" name="option[paypal_currency]" value="<?php if(isset($options["paypal_currency"])) echo $options["paypal_currency"];?>" size="5" /> <a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes">(list of codes)</a>

<select name="currency_format">
<option value="<?php if(isset($options["currency_decimal"]) ) echo $options["currency_decimal"];?>|<?php if(isset($options["currency_thousands"])) echo $options["currency_thousands"];?>"><?php echo number_format(1000.00, 2, $options["currency_decimal"],  $options["currency_thousands"]); ?></option>
<option value=".|,"><?php echo number_format(1000.00, 2, '.',  ','); ?></option>
<option value=",|."><?php echo number_format(1000.00, 2, ',',  '.'); ?></option>
<option value=",| "><?php echo number_format(1000.00, 2, ',',  ' '); ?></option>
</select>    
<br />

<h3><?php _e('Menu Security','rsvpmaker'); ?>:</h3>
<?php
rsvpmaker_menu_security( __("RSVP Report",'rsvpmaker'),  "menu_security", $options );
rsvpmaker_menu_security(__("Event Templates",'rsvpmaker'),"rsvpmaker_template",$options );
rsvpmaker_menu_security( __("Recurring Event",'rsvpmaker'), "recurring_event", $options );
rsvpmaker_menu_security( __("Multiple Events",'rsvpmaker'), "multiple_events",$options );
rsvpmaker_menu_security( __("Documentation",'rsvpmaker'), "documentation",$options );
?>
<p><em><?php _e('Security level required to access custom menus (RSVP Report, Documentation)','rsvpmaker'); ?></em></p>

<h3>Dashboard</h3>
<select name="option[dashboard]">
<option value="">No Widget</option>
<option value="show" <?php if($options["dashboard"] == 'show') echo ' selected="selected" '; ?> >Show Widget</option>
<option value="top" <?php if($options["dashboard"] == 'top') echo ' selected="selected" '; ?> >Show Widget on Top</option>
</select>
<br /><?php _e('Note','rsvpmaker'); ?>
<br />
<textarea name="option[dashboard_message]" style="width:90%;"><?php echo $options["dashboard_message"]; ?></textarea>

<h3><?php _e('SMTP for Notifications','rsvpmaker'); ?></h3>
<p><?php _e('For more reliable delivery of email notifications, enable delivery through the SMTP email protocol. Standard server parameters will be used for Gmail and the SendGrid service, or specify the server port number and security protocol','rsvpmaker'); ?>.</p>
  <select name="option[smtp]" id="smtp">
  <option value="" <?php if($options["smtp"] == '' ) {echo ' selected="selected" ';}?> ><?php _e('None - use PHP mail()','rsvpmaker'); ?></option>
  <option value="gmail" <?php if($options["smtp"] == 'gmail') {echo ' selected="selected" ';}?> >Gmail</option>
  <option value="sendgrid" <?php if($options["smtp"] == 'sendgrid') {echo ' selected="selected" ';}?> >SendGrid</option>
  <option value="other" <?php if($options["smtp"] == 'other') {echo ' selected="selected" ';}?> ><?php _e('Other SMTP (specified below)','rsvpmaker'); ?></option>
  </select> <?php echo $options["smtp"]; ?>
<br />
<?php _e('Email Account for Notifications','rsvpmaker'); ?>
<br />
<input type="text" name="option[smtp_useremail]" value="<?php if(isset($options["smtp_useremail"])) echo $options["smtp_useremail"];?>" size="15" />
<br />
<?php _e('Email Username','rsvpmaker'); ?>
<br />
<input type="text" name="option[smtp_username]" value="<?php if(isset($options["smtp_username"])) echo $options["smtp_username"];?>" size="15" />
<br />
<?php _e('Email Password','rsvpmaker'); ?>
<br />
<input type="text" name="option[smtp_password]" value="<?php if(isset($options["smtp_password"])) echo $options["smtp_password"];?>" size="15" />
<br />
<?php _e('Server (parameters below not necessary if you specified Gmail or SendGrid)','rsvpmaker'); ?><br />
<input type="text" name="option[smtp_server]" value="<?php if(isset($options["smtp_server"])) echo $options["smtp_server"];?>" size="15" />
<br />
<?php _e('SMTP Security Prefix (ssl or tls, leave blank for non-encrypted connections)','rsvpmaker'); ?> 
<br />
<input type="text" name="option[smtp_prefix]" value="<?php if(isset($options["smtp_prefix"])) echo $options["smtp_prefix"];?>" size="15" />
<br />
<?php _e('SMTP Port','rsvpmaker'); ?>
<br />
<input type="text" name="option[smtp_port]" value="<?php if(isset($options["smtp_port"])) echo $options["smtp_port"];?>" size="15" />
<br />
<?php 
if($options["smtp"])
	{
?>
<a href="<?php echo admin_url('?smtptest=1'); ?>"><?php _e('Send SMTP Test to RSVP To address','rsvpmaker'); ?></a>
<?php
	}

?>
<h3><?php _e('Event Templates','rsvpmaker'); ?></h3>
  <input type="checkbox" name="option[additional_editors]" value="1" <?php if(isset($options["additional_editors"]) && $options["additional_editors"]) echo ' checked="checked" ';?> /> <strong><?php _e('Additional Editors','rsvpmaker'); ?></strong> <em><?php _e('Allow users to share editing rights for event templates and related events.','rsvpmaker'); ?></em> 
	<br />

<h3><?php _e('Troubleshooting','rsvpmaker'); ?></h3>
  <input type="checkbox" name="option[flush]" value="1" <?php if(isset($options["flush"]) && $options["flush"]) echo ' checked="checked" ';?> /> <strong><?php _e('Tweak Permalinks','rsvpmaker'); ?></strong> <?php _e('Check here if you are getting &quot;page not found&quot; errors for event content (should not be necessary for most users).','rsvpmaker'); ?> 
	<br />
  <input type="checkbox" name="option[debug]" value="1" <?php if(isset($options["debug"]) && $options["debug"]) echo ' checked="checked" ';?> /> <strong><?php _e('Debug','rsvpmaker'); ?>:</strong>
	<br />

					<div class="submit"><input type="submit" name="Submit" value="<?php _e('Update','rsvpmaker'); ?>" /></div>
			</form>

<form action="<?php echo admin_url('options-general.php'); ?>" method="get"><input type="hidden" name="page" value="rsvpmaker-admin.php" /><?php _e('RSVP Reminders scheduled for','rsvpmaker'); ?>: <?php echo date('F jS, g:i A / H:i',wp_next_scheduled( 'rsvp_daily_reminder_event' )).' GMT offset '.get_option('gmt_offset').' hours'; // ?><br />
<?php _e('Set new time','rsvpmaker'); ?>: <select name="reminder_reset">
<?php echo $houropt;?>
</select><input type="submit" name="submit" value="<?php _e('Set','rsvpmaker'); ?>" /></form>
	    </div>
		
	 </div>

	</div>
	
</div>


<?php              

          }
      }
  
  else
      : exit("Class already declared!");
  endif;
  

  // create new instance of the class
  $RSVPMAKER_Options = new RSVPMAKER_Options();
  //print_r($RSVPMAKER_Options);
  if (isset($RSVPMAKER_Options)) {
      // register the activation function by passing the reference to our instance
      register_activation_hook(__FILE__, array(&$RSVPMAKER_Options, 'install'));
  }

add_action('init','save_rsvp');


function admin_event_listing() {
global $wpdb;

$sql = "SELECT *, $wpdb->posts.ID as postID
FROM `".$wpdb->prefix."rsvp_dates`
JOIN $wpdb->posts ON ".$wpdb->prefix."rsvp_dates.postID = $wpdb->posts.ID
WHERE datetime > CURDATE( ) AND $wpdb->posts.post_status = 'publish'
ORDER BY datetime";

if(!($_GET["events"] == 'all') )
	$sql .= " LIMIT 0, 20";

$results = $wpdb->get_results($sql,ARRAY_A);

foreach($results as $row)
	{
	$t = strtotime($row["datetime"]);
	$dateline[$row["postID"]] .= date('F jS',$t)." ";
	if(!$eventlist[$row["postID"]])
		$eventlist[$row["postID"]] = $row;
	}

if($eventlist)
foreach($eventlist as $event)
	{
		$listings .= sprintf('<li><a href="'.admin_url().'post.php?post=%d&action=edit">%s</a> %s</li>'."\n",$event["postID"],$event["post_title"],$dateline[$event["postID"]]);
	}	

	$listings = "<p><strong>".__('Events (click to edit)','rsvpmaker')."</strong></p>\n<ul id=\"eventheadlines\">\n$listings</ul>\n".'<p><a href="?events=all">'.__('Show All','rsvpmaker').'</a></p>';
	return $listings;
}

function default_event_content($content) {
global $post;
global $rsvp_options;
global $rsvp_template;
if(($post->post_type == 'rsvpmaker') && ($content == ''))
{
if(isset($rsvp_template->post_content))
	return $rsvp_template->post_content;
return $rsvp_options['default_content'];
}
else
return $content;
}

function title_from_template($title) {
//if(!empty($title))
//	return $title;
global $rsvp_template;
global $post;
global $wpdb;
if(isset($_GET["from_template"]) ) // && empty($post->post_title) ) 
	{
	$t = (int) $_GET["from_template"];
	$sql = "SELECT post_title, post_content FROM $wpdb->posts WHERE ID=$t";
	//return $sql;
	$rsvp_template = $wpdb->get_row($sql);
	return $rsvp_template->post_title;
	}
return $title;
}

add_filter('the_editor_content','default_event_content');
add_filter('default_title','title_from_template');


function multiple() {

global $wpdb;
global $current_user;

if($_POST)
{

	$my_post['post_status'] = current_user_can('publish_rsvpmakers') ? 'publish' : 'draft';
	$my_post['post_author'] = $current_user->ID;
	$my_post['post_type'] = 'rsvpmaker';

	foreach($_POST["recur_year"] as $index => $year)
		{
		if($_POST["recur_day"][$index] )
			{
			$my_post['post_title'] = $_POST["title"][$index];
			$my_post['post_content'] = $_POST["body"][$index];
			$cddate = $year . "-" . $_POST["recur_month"][$index]  . "-" . $_POST["recur_day"][$index] . " " . $_POST["recur_hour"][$index] . ":" . $_POST["recur_minutes"][$index] . ":00";
// Insert the post into the database
  			if($postID = wp_insert_post( $my_post ) )
				{
				$sql = "INSERT INTO ".$wpdb->prefix."rsvp_dates SET datetime='$cddate', postID=". $postID;
				$wpdb->show_errors();
				$return = $wpdb->query($sql);
				if($return == false)
					echo '<div class="updated">'."Error: $sql.</div>\n";
				else
					echo '<div class="updated">'."Added post # $postID for $cddate.</div>\n";	
				}
			}		
		}

}

global $rsvp_options;

;?>
<div class="wrap"> 
	<div id="icon-edit" class="icon32"><br /></div> 
<h2><?php _e('Multiple Events','rsvpmaker'); ?></h2> 

<p><?php _e('Use this form to enter multiple events quickly with basic formatting','rsvpmaker'); ?>.</p>

<form id="form1" name="form1" method="post" action="<?php echo admin_url('edit.php?post_type=rsvpmaker&page=multiple');?>">
<?php
$today = '<option value="0">None</option>';
for($i=0; $i < 10; $i++)
{

$m = date('n');
$y = date('Y');
$y2 = $y+1;

wp_nonce_field(-1,'add_date'.$i);
?>
<p><?php _e('Title','rsvpmaker'); ?>: <input type="text" name="title[<?php echo $i;?>]" /></p>
<p><textarea name="body[<?php echo $i;?>]" rows="5" cols="80"><?php echo $rsvp_options["default_content"];?></textarea></p>

<div id="recur_date<?php echo $i;?>" style="border-bottom: thin solid #888;">

<?php _e('Month','rsvpmaker'); ?>: 
              <select name="recur_month[<?php echo $i;?>]"> 
              <option value="<?php echo $m;?>"><?php echo $m;?></option> 
              <option value="1">1</option> 
              <option value="2">2</option> 
              <option value="3">3</option> 
              <option value="4">4</option> 
              <option value="5">5</option> 
              <option value="6">6</option> 
              <option value="7">7</option> 
              <option value="8">8</option> 
              <option value="9">9</option> 
              <option value="10">10</option> 
              <option value="11">11</option> 
              <option value="12">12</option> 
              </select> 
            <?php _e('Day','rsvpmaker'); ?> 
            <select name="recur_day[<?php echo $i;?>]"> 
              <?php echo $today;?> 
              <option value="1">1</option> 
              <option value="2">2</option> 
              <option value="3">3</option> 
              <option value="4">4</option> 
              <option value="5">5</option> 
              <option value="6">6</option> 
              <option value="7">7</option> 
              <option value="8">8</option> 
              <option value="9">9</option> 
              <option value="10">10</option> 
              <option value="11">11</option> 
              <option value="12">12</option> 
              <option value="13">13</option> 
              <option value="14">14</option> 
              <option value="15">15</option> 
              <option value="16">16</option> 
              <option value="17">17</option> 
              <option value="18">18</option> 
              <option value="19">19</option> 
              <option value="20">20</option> 
              <option value="21">21</option> 
              <option value="22">22</option> 
              <option value="23">23</option> 
              <option value="24">24</option> 
              <option value="25">25</option> 
              <option value="26">26</option> 
              <option value="27">27</option> 
              <option value="28">28</option> 
              <option value="29">29</option> 
              <option value="30">30</option> 
              <option value="31">31</option> 
            </select> 
            <?php _e('Year','rsvpmaker'); ?>
            <select name="recur_year[<?php echo $i;?>]"> 
              <option value="<?php echo $y;?>"><?php echo $y;?></option> 
              <option value="<?php echo $y2;?>"><?php echo $y2;?></option> 
            </select> 

<?php _e('Hour','rsvpmaker'); ?>: <select name="recur_hour[<?php echo $i;?>]"> 
 
<option  value="00">12 a.m.</option> 
<option  value="1">1 a.m.</option> 
<option  value="2">2 a.m.</option> 
<option  value="3">3 a.m.</option> 
<option  value="4">4 a.m.</option> 
<option  value="5">5 a.m.</option> 
<option  value="6">6 a.m.</option> 
<option  value="7">7 a.m.</option> 
<option  value="8">8 a.m.</option> 
<option  value="9">9 a.m.</option> 
<option  value="10">10 a.m.</option> 
<option  value="11">11 a.m.</option> 
<option  value="12">12 p.m.</option> 
<option  value="13">1 p.m.</option> 
<option  value="14">2 p.m.</option> 
<option  value="15">3 p.m.</option> 
<option  value="16">4 p.m.</option> 
<option  value="17">5 p.m.</option> 
<option  value="18">6 p.m.</option> 
<option  selected = "selected"  value="19">7 p.m.</option> 
<option  value="20">8 p.m.</option> 
<option  value="21">9 p.m.</option> 
<option  value="22">10 p.m.</option> 
<option  value="23">11 p.m.</option></select> 
 
<?php _e('Minutes','rsvpmaker'); ?>: <select name="recur_minutes[<?php echo $i;?>]"> 
<option value="00">00</option> 
<option value="00">00</option> 
<option value="15">15</option> 
<option value="30">30</option> 
<option value="45">45</option> 
</select>

</div>
<?php
} // end for loop
;?>

<input type="submit" name="button" id="button" value="<?php _e('Submit','rsvpmaker'); ?>" />
</form>
</div>
<?php
}



function add_dates() {

global $wpdb;
global $current_user;

if($_POST)
{

if(!wp_verify_nonce($_POST['add_recur'],'recur'))
	die("Security error");

if($_POST["recur-title"])
	{
	$my_post['post_title'] = $_POST["recur-title"];
	$my_post['post_content'] = $_POST["recur-body"];
	$my_post['post_status'] = current_user_can('publish_rsvpmakers') ? 'publish' : 'draft';
	$my_post['post_author'] = $current_user->ID;
	$my_post['post_type'] = 'rsvpmaker';

	foreach($_POST["recur_checked"] as $index => $on)
		{
		$year = $_POST["recur_year"][$index];
		if($_POST["recur_day"][$index] )
			{
			$cddate = $year . "-" . $_POST["recur_month"][$index]  . "-" . $_POST["recur_day"][$index] . " " . $_POST["event_hour"] . ":" . $_POST["event_minutes"] . ":00";

			$dpart = explode(':',$_POST["event_duration"]);			
			
			if( is_numeric($dpart[0]) )
				{
				$hour = $_POST["event_hour"] + $dpart[0];
				$minutes = $_POST["event_minutes"] + $dpart[1];
				$duration = mktime( $hour, $minutes,0,$_POST["recur_month"][$index],$_POST["recur_day"][$index],$year);
				}
			else
				$duration = $_POST["event_duration"]; // empty or all day

// Insert the post into the database
  			if($postID = wp_insert_post( $my_post ) )
				{
				$sql = "INSERT INTO ".$wpdb->prefix."rsvp_dates SET datetime='$cddate', duration='$duration', postID=". $postID;
				
				$wpdb->show_errors();
				$return = $wpdb->query($sql);
				if($return == false)
					echo '<div class="updated">'."Error: $sql.</div>\n";
				else
					echo '<div class="updated">Posted: event for '.$cddate.' <a href="post.php?action=edit&post='.$postID.'">Edit</a> / <a href="'.site_url().'/?p='.$postID.'">View</a></div>';	

				if($_POST["setrsvp"]["on"])
					save_rsvp_meta($postID);

				}
			}		
		}

	}

}

global $rsvp_options;

;?>
<div class="wrap"> 
	<div id="icon-edit" class="icon32"><br /></div> 
<h2>Recurring Event</h2> 

<?php

$defaulthour = (isset($_GET["hour"])) ? ( (int) $_GET["hour"]) : 19;
$defaultmin = (isset($_GET["minutes"])) ? ( (int) $_GET["minutes"]) : 0;
$houropt = $minopt = '';
for($i=0; $i < 24; $i++)
	{
	$selected = ($i == $defaulthour) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	if($i == 0)
		$twelvehour = "12 a.m.";
	elseif($i == 12)
		$twelvehour = "12 p.m.";
	elseif($i > 12)
		$twelvehour = ($i - 12) ." p.m.";
	else		
		$twelvehour = $i." a.m.";

	$houropt .= sprintf('<option  value="%s" %s>%s / %s:</option>',$padded,$selected,$twelvehour,$padded);
	}

for($i=0; $i < 60; $i += 5)
	{
	$selected = ($i == $defaultmin) ? ' selected="selected" ' : '';
	$padded = ($i < 10) ? '0'.$i : $i;
	$minopt .= sprintf('<option  value="%s" %s>%s</option>',$padded,$selected,$padded);
	}

$cm = date('n');
$y = date('Y');
$y2 = $y+1;

if(!isset($_GET["week"]))
{
;?>

<p>Use this form to create multiple events with the same headline, description, and RSVP paramaters. You can have the program automatically calculate dates for a regular montly schedule.</p>

<p><em>Optional: Calculate dates for a recurring schedule ...</em></p>

<form method="get" action="<?php echo admin_url("edit.php");?>" id="recursked">

<p>Regular schedule: 

<select name="week" id="week">

<option value="+0 week">First</option> 
<option value="+1 week">Second</option> 
<option value="+2 week">Third</option> 
<option value="+3 week">Fourth</option> 
<option value="Last">Last</option> 
</select>

<select name="dayofweek" id="dayofweek">

<option value="Sunday">Sunday</option> 
<option value="Monday">Monday</option> 
<option value="Tuesday">Tuesday</option> 
<option value="Wednesday">Wednesday</option> 
<option value="Thursday">Thursday</option> 
<option value="Friday">Friday</option> 
<option value="Saturday">Saturday</option> 
</select>

</p>
        <table border="0">

<tr><td> Time:</td>

<td>Hour: <select name="hour" id="hour">
<?php echo $houropt;?>
</select>

Minutes: <select id="minutes" name="minutes">
<?php echo $minopt;?>
</select> 

<em>For an event starting at 12:30 p.m., you would select 12 p.m. and 30 minutes.</em>

</td>

          </tr>
</table>

<input type="hidden" name="post_type" value="rsvpmaker" />
<input type="hidden" name="page" value="add_dates" />
<input type="submit" value="Get Dates" />
</form>

<p><em>... or enter dates individually.</em></p>

<?php
$futuremonths = 12;
for($i =0; $i < $futuremonths; $i++)
	$projected[$i] = mktime(0,0,0,$cm+$i,1); // first day of month
}
else
{
	$week = $_GET["week"];
	$dow = $_GET["dayofweek"];
	$futuremonths = 12;
	for($i =0; $i < $futuremonths; $i++)
		{
		$thisdate = mktime(0,0,0,$cm+$i,1); // first day of month
		$datetext =  "$week $dow ".date("F Y",$thisdate);
		$projected[$i] = strtotime($datetext);
		$datetexts[$i] = $datetext;
		}//end for loop

echo "<p>Loading recurring series of dates for $week $dow. To omit a date in the series, change the day field to &quot;Not Set&quot;</p>\n";
}

;?>

<h3><?php _e('Enter Recurring Events','rsvpmaker'); ?></h3>

<form id="form1" name="form1" method="post" action="<?php echo admin_url("edit.php?post_type=rsvpmaker&page=add_dates");?>">
<p>Headline: <input type="text" name="recur-title" size="60" value="<?php if(isset($_POST["recur-title"])) echo stripslashes($_POST["recur-title"]);?>" /></p>
<p><textarea name="recur-body" rows="5" cols="80"><?php echo (isset($_POST["recur-body"]) && $_POST["recur-body"]) ? stripslashes($_POST["recur-body"]) : $rsvp_options["default_content"];?></textarea></p>
<?php
wp_nonce_field('recur','add_recur');

foreach($projected as $i => $ts)
{

$today = date('d',$ts);
$cm = date('n',$ts);
$y = date('Y',$ts);

$y2 = $y+1;

;?>
<div id="recur_date<?php echo $i;?>" style="margin-bottom: 5px;">

<input type="checkbox" name="recur_checked[<?php echo $i;?>]" value="<?php echo $i;?>" />

<?php _e('Month','rsvpmaker'); ?>: 
              <select name="recur_month[<?php echo $i;?>]"> 
              <option value="<?php echo $cm;?>"><?php echo $cm;?></option> 
              <option value="1">1</option> 
              <option value="2">2</option> 
              <option value="3">3</option> 
              <option value="4">4</option> 
              <option value="5">5</option> 
              <option value="6">6</option> 
              <option value="7">7</option> 
              <option value="8">8</option> 
              <option value="9">9</option> 
              <option value="10">10</option> 
              <option value="11">11</option> 
              <option value="12">12</option> 
              </select> 
            <?php _e('Day','rsvpmaker'); ?> 
            <select name="recur_day[<?php echo $i;?>]"> 
<?php
if($week)
	echo sprintf('<option value="%s">%s</option>',$today,$today);
?>
              <option value="1">1</option> 
              <option value="2">2</option> 
              <option value="3">3</option> 
              <option value="4">4</option> 
              <option value="5">5</option> 
              <option value="6">6</option> 
              <option value="7">7</option> 
              <option value="8">8</option> 
              <option value="9">9</option> 
              <option value="10">10</option> 
              <option value="11">11</option> 
              <option value="12">12</option> 
              <option value="13">13</option> 
              <option value="14">14</option> 
              <option value="15">15</option> 
              <option value="16">16</option> 
              <option value="17">17</option> 
              <option value="18">18</option> 
              <option value="19">19</option> 
              <option value="20">20</option> 
              <option value="21">21</option> 
              <option value="22">22</option> 
              <option value="23">23</option> 
              <option value="24">24</option> 
              <option value="25">25</option> 
              <option value="26">26</option> 
              <option value="27">27</option> 
              <option value="28">28</option> 
              <option value="29">29</option> 
              <option value="30">30</option> 
              <option value="31">31</option> 
            </select> 
            <?php _e('Year','rsvpmaker'); ?>
            <select name="recur_year[<?php echo $i;?>]"> 
              <option value="<?php echo $y;?>"><?php echo $y;?></option> 
              <option value="<?php echo $y2;?>"><?php echo $y2;?></option> 
            </select> 

</div>

<?php
} // end for loop

?>
<p><?php echo __('Hour:','rsvpmaker');?> <select name="event_hour"> 
<?php echo $houropt;?>
</select> 
 
<?php echo __('Minutes:','rsvpmaker');?> <select name="event_minutes"> 
<?php echo $minopt;?>
</select> -

<?php echo __('Duration','rsvpmaker');?> <select name="event_duration">
<option value=""><?php echo __('Not set (optional)','rsvpmaker');?></option>
<option value="allday"><?php echo __("All day/don't show time in headline",'rsvpmaker');?></option>
<?php for($h = 1; $h < 24; $h++) { ;?>
<option value="<?php echo $h;?>"><?php echo $h;?> hours</option>
<option value="<?php echo $h;?>:15"><?php echo $h;?>:15</option>
<option value="<?php echo $h;?>:30"><?php echo $h;?>:30</option>
<option value="<?php echo $h;?>:45"><?php echo $h;?>:45</option>
<?php 
}
;?>
</select>
</p>
<?php

echo GetRSVPAdminForm(0);

;?>

<input type="submit" name="button" id="button" value="Submit" />
</form>

</div><!-- wrap -->

<?php
}


function rsvpmaker_doc () {
global $rsvp_options;
?>
<h2>Documentation</h2>
<p>More detailed documentation at <a href="http://www.rsvpmaker.com/documentation/">http://www.rsvpmaker.com/documentation/</a></p>
		    <h3>Shortcodes and Event Listing / Calendar Views</strong></h3>
		    <p>RSVPMaker provides the following shortcodes for listing events, listing event headlines, and displaying a calendar with links to events.</p>
		    <p>[event_listing format=&quot;headlines&quot;] displays a list of headlines</p>
		    <p>[event_listing format=&quot;calendar&quot;] OR [event_listing calendar=&quot;1&quot;] displays the calendar</p>
		    <p>[rsvpmaker_upcoming] displays the index of upcoming events. If an RSVP is requested, the event includes the RSVP button link to the single post view, which will include your RSVP form.</p>
		    <p>[rsvpmaker_upcoming calendar=&quot;1&quot;] displays the calendar, followed by the index of upcoming events.</p>
		    <p>[rsvpmaker_upcoming type=&quot;featured&quot;] Displays only the events of the specified type (&quot;featured&quot; type available by default).</p>
            <p>[rsvpmaker_upcoming no_event="We're working on it. Check back soon"] specifies a custom message to display if there are no upcoming events in the database.</p>
            <div style="background-color: #FFFFFF; padding: 15px; text-align: center;">
            <img src="<?php echo plugins_url('/shortcode.png',__FILE__);?>" width="535" height="412" />
<br /><em>Contents for an events page.</em>
            </div>
<h3>RSVP Form</h3>
<p>The RSVP from is also now formatted using shortcodes, which you can edit in the RSVP Form section of the Settings screen. You can also vary the form on a per-event basis, which can be handy for capturing an extra field. This is your current default form:</p>
<pre>
<?php echo(htmlentities($rsvp_options["rsvp_form"])); ?>
</pre>
<p>Explanation:</p>
<p>[rsvpfield textfield=&quot;myfield&quot;] outputs a text field coded to capture data for &quot;myfield&quot;</p>
<p>[rsvpfield textfield=&quot;myfield&quot; required=&quot;1&quot;] treats &quot;myfield&quot; as a required field.</p>
<p>[rsvpfield selectfield=&quot;phonetype&quot; options=&quot;Work Phone,Mobile Phone,Home Phone&quot;] HTML select field with specified options</p>
<p>[rsvpprofiletable show_if_empty=&quot;phone&quot;]CONDITIONAL CONTENT GOES HERE[/rsvpprofiletable] This section only shown if the required field is empty; otherwise displays a message that the info is &quot;on file&quot;. Because RSVPMaker is capable of looking up profile data based on an email address, you may want some data to be hidden for privacy reasons.</p>
<p>[rsvpguests] Outputs the guest blanks.</p>

<p>If you're having trouble with the form fields not being formatted correctly, <a href="<?php echo admin_url('options-general.php?page=rsvpmaker-admin.php&amp;reset_form=1');?>">Reset default RSVP Form</a></p>
<?php

}

function rsvpmaker_debug () {
global $wpdb;
global $rsvp_options;

ob_start();
if($_GET["rsvp"])
	{
	
$sql = "SELECT ".$wpdb->prefix."rsvpmaker.*, ".$wpdb->prefix."posts.post_title FROM ".$wpdb->prefix."rsvpmaker JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."rsvpmaker.event = ".$wpdb->prefix."posts.ID ORDER BY ".$wpdb->prefix."rsvpmaker.id DESC LIMIT 0, 10";

$wpdb->show_errors();
$results = $wpdb->get_results($sql);
echo "RSVP RECORDS\n";
echo $sql . "\n";
print_r($results);

	}
if($_GET["options"])
	{
echo "\n\nOPTIONS\n";
print_r($rsvp_options);	
	}
if($_GET["rewrite"])
	{
	global $wp_rewrite;
	echo "\n\nREWRITE\n";
	print_r($wp_rewrite);
	}
if($_GET["globals"])
	{
	echo "\n\nGLOBALS\n";
	print_r($GLOBALS);
	}
$output = ob_get_clean();

$output = "Version: ".get_bloginfo('version')."\n".$output;

if(MULTISITE)
	$output .= "Multisite: YES\n";
else
	$output .= "Multisite: NO\n";

if($_GET["author"])
	{
	$url = get_bloginfo('url');
	$email = get_bloginfo('admin_email');
	mail("david@carrcommunications.com","RSVPMAKER DEBUG: $url", $output);
	}

;?>
<h2>Debug</h2>
<p>Use this screen to verify that RSVPMaker is recording data correctly or to share debugging information with the plugin author. If you send debugging info, follow up with a note to <a href="mailto:david@carrcommunications.com">david@carrcommunications.com</a> and explain what you need help with.</p>
<form action="<?php echo admin_url("edit.php");?>" method="get">
<input type="hidden" name="post_type" value="rsvpmaker" />
<input name="page" type="hidden" value="rsvpmaker_debug" />
  <label>
  <input type="checkbox" name="rsvp" id="rsvp"  value="1" />
  RSVP Records</label>
 <label>
 <input type="checkbox" name="options" id="options"  value="1" />
 Options</label>
    <label>
    <input type="checkbox" name="rewrite" id="rewrite"  value="1" />
    Rewrite Rules
</label>
<label>
<input type="checkbox" name="globals" id="globals" value="1" />
Globals</label>
<label>
    <input type="checkbox" name="author" id="author"  value="1"  />
   Send to Plugin Author</label>
   <input type="submit" value="Show" />
</form>
<pre>
<?php echo $output;?>
</pre>
<?php
}

//my_events_rsvp function in rsvpmaker-pluggable.php
add_action('admin_menu', 'my_rsvp_menu');

add_filter('manage_posts_columns', 'rsvpmaker_columns');
function rsvpmaker_columns($defaults) {
	if($_GET["post_type"] != 'rsvpmaker')
		return $defaults;
    $defaults['event_dates'] = __('Event Dates');
    return $defaults;
}

add_action('manage_posts_custom_column', 'rsvpmaker_custom_column', 10, 2);

function rsvpmaker_custom_column($column_name, $post_id) {
    global $wpdb;
    if( $column_name == 'event_dates' ) {
$sql = "SELECT *, $wpdb->posts.ID as postID
FROM `".$wpdb->prefix."rsvp_dates`
JOIN $wpdb->posts ON ".$wpdb->prefix."rsvp_dates.postID = $wpdb->posts.ID
WHERE $wpdb->posts.ID = $post_id ORDER BY datetime";

$results = $wpdb->get_results($sql,ARRAY_A);

$dateline = '';

if($results)
{
foreach($results as $row)
		{
		$t = strtotime($row["datetime"]);
		if($dateline)
			$dateline .= ", ";
		$dateline .= date('F jS, Y',$t);
		}
if(isset($dateline)) echo $dateline;

}
else
	{
$template = get_post_meta($post_id,'_sked',true);
if(!$template)
	return;
echo __("Template",'rsvpmaker').": ";
$week = (int) $template["week"];
$dow = (int) $template["dayofweek"];
$weekarray = Array(__("Varies",'rsvpmaker'),__("First",'rsvpmaker'),__("Second",'rsvpmaker'),__("Third",'rsvpmaker'),__("Fourth",'rsvpmaker'),__("Last",'rsvpmaker'),__("Every",'rsvpmaker'));
$dayarray = Array(__("Sunday",'rsvpmaker'),__("Monday",'rsvpmaker'),__("Tuesday",'rsvpmaker'),__("Wednesday",'rsvpmaker'),__("Thursday",'rsvpmaker'),__("Friday",'rsvpmaker'),__("Saturday",'rsvpmaker'));
echo ($week == 0) ? __('Schedule varies','rsvpmaker') : $weekarray[$week].' '.$dayarray[$dow];		
	}

	}
}

function rsvpmaker_admin_notice() {
global $wpdb;
global $rsvp_options;


if(isset($_GET["update"]) && ($_GET["update"] == "eventslug"))
	{
	$wpdb->query("UPDATE $wpdb->posts SET post_type='rsvpmaker' WHERE post_type='event' OR post_type='rsvp-event' ");
	}
if(isset($_GET["noeventpageok"]) && $_GET["noeventpageok"])
	{
	$rsvp_options["noeventpageok"] = 1;
	update_option('RSVPMAKER_Options',$options);
	}
elseif( (!isset($rsvp_options["eventpage"]) || empty($rsvp_options["eventpage"]) ) && !isset($rsvp_options["noeventpageok"]))
	{
	$sql = "SELECT ID from $wpdb->posts WHERE post_status='publish' AND post_content LIKE '%[rsvpmaker_upcoming%' ";
	$front = get_option('page_on_front');
	if($front)
		$sql .= " AND ID != $front ";
	if($id =$wpdb->get_var($sql))
		{
		$rsvp_options["eventpage"] = get_permalink($id);
		update_option('RSVPMAKER_Options',$rsvp_options);
		}
	else
		echo '<div class="updated" style="background-color:#fee;"><p>RSVPMaker needs you to create a page with the [rsvpmaker_upcoming] shortcode to display event listings. (<a href="options-general.php?page=rsvpmaker-admin.php&noeventpageok=1">Turn off this warning</a>)</p></div>';
	}
	
if(!isset($rsvp_options["posttypecheck"]) || !$rsvp_options["posttypecheck"])
	{	
	$sql = "SELECT count(*) from $wpdb->posts WHERE post_type='event' OR post_type='rsvp-event' ";
	if($count =$wpdb->get_var($sql))
		echo '<div class="updated" style="background-color:#fee;"><p>RSVPMaker has detected '.$count.' posts that appear to have been created with an earlier release. You need to update them to reflect the new permalink naming. Update now? <a href="./index.php?post_type=rsvpmaker&update=eventslug" style="font-weight: bold;">Yes</a> (The post_type field will be changed from &quot;event&quot; to &quot;rsvpmaker&quot; also changing the permalink structure).</p></div>';
	$rsvp_options["posttypecheck"] = 1;
	update_option('RSVPMAKER_Options',$rsvp_options);	
	}

if(isset($rsvp_options["profile_table"]) && !empty($rsvp_options["profile_table"]))
		{
		echo '<div class="updated" style="background-color:#fee;"><p>Notice: RSVPMaker 2.5 introduced a new method for customizing the RSVP form. If you had customized the form to include additional or alternate fields, you will have to make an update on the RSVPMaker settings screen to restore those changes.</p></div>';
		$rsvp_options["profile_table"] = NULL;
		update_option('RSVPMAKER_Options',$rsvp_options);	
		}

	if(isset($_GET["smtptest"]))
		{
		$mail["to"] = $rsvp_options["rsvp_to"];
	$mail["from"] = "david@carrcommunications.com";
	$mail["fromname"] = "RSVPMaker";
	$mail["subject"] = "Testing SMTP email notification";
	$mail["html"] = ' <h1>SMTP Test</h1>
<p>I hope you will find this is a more reliable way to send email notifications related to RSVP Events.</p>

<p>In normal operation, RSVPMaker sends the event organizer a notification as people RSVP. It also sends attendees a confirmation message with the rsvp_to email address as the From email address.';
	$result = rsvpmailer($mail);
	echo '<div class="updated" style="background-color:#fee;">'."<strong>Sending test email $result </strong></div>";
		}
}

add_action('admin_notices', 'rsvpmaker_admin_notice');

function rsvpmailer($mail) {
	
	global $rsvp_options;	
	
	require_once ABSPATH . WPINC . '/class-phpmailer.php';
	require_once ABSPATH . WPINC . '/class-smtp.php';
	$rsvpmail = new PHPMailer();
	
	$rsvpmail->IsSMTP(); // telling the class to use SMTP

	if($rsvp_options["smtp"] == "gmail") {
		$rsvpmail->SMTPAuth   = true;                  // enable SMTP authentication
		$rsvpmail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$rsvpmail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$rsvpmail->Port       = 587;                   // set the SMTP port for the GMAIL server
	}
	elseif($rsvp_options["smtp"] == "sendgrid") {
	$rsvpmail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host = 'smtp.sendgrid.net';
	$mail->Port = 587; 
	}
	else {
	$rsvpmail->Host = $rsvp_options["smtp_server"]; // SMTP server
	$rsvpmail->SMTPAuth=true;
	if(isset($rsvp_options["smtp_prefix"]) && $rsvp_options["smtp_prefix"] )
		$rsvpmail->SMTPSecure = $rsvp_options["smtp_prefix"];                 // sets the prefix to the servier
	$rsvpmail->Port=$rsvp_options["smtp_port"];
	}
 
 $rsvpmail->Username=$rsvp_options["smtp_username"];
 $rsvpmail->Password=$rsvp_options["smtp_password"];
 $rsvpmail->AddAddress($mail["to"]);
 if(isset($mail["cc"]) )
 	$rsvpmail->AddCC($mail["cc"]);
if(is_admin() && isset($_GET["debug"]))
	$rsvpmail->SMTPDebug = 2;
 $rsvpmail->SetFrom($rsvp_options["smtp_useremail"], $mail["fromname"]. ' (via '.$_SERVER['SERVER_NAME'].')');
 $rsvpmail->ClearReplyTos();
 $rsvpmail->AddReplyTo($mail["from"], $mail["fromname"]);
 $rsvpmail->Subject = $mail["subject"];
if($mail["html"])
	{
	if($mail["text"])
		$rsvpmail->AltBody = $mail["text"];
	else
		$rsvpmail->AltBody = trim(strip_tags($mail["html"]) );
	$rsvpmail->MsgHTML($mail["html"]);
	}
	else
		{
			$rsvpmail->Body = $mail["text"];
			$rsvpmail->WordWrap = 50;
		}
	
	try {
		$rsvpmail->Send();
	} catch (phpmailerException $e) {
		echo $e->errorMessage();
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}
	return $rsvpmail->ErrorInfo;
}

function set_rsvpmaker_order_in_admin( $wp_query ) {
  if ( is_admin() && $_GET["rsvpsort"]=="chronological") {
//    $wp_query->set( 'orderby', 'title' );
//    $wp_query->set( 'order', 'ASC' );
add_filter('posts_join', 'rsvpmaker_join' );
add_filter('posts_where', 'rsvpmaker_where' );
add_filter('posts_groupby', 'rsvpmaker_groupby' );
add_filter('posts_orderby', 'rsvpmaker_orderby' );
add_filter('posts_distinct', 'rsvpmaker_distinct' );
  }
}
add_filter('pre_get_posts', 'set_rsvpmaker_order_in_admin' );

function rsvpmaker_sort_message() {
	if((basename($_SERVER['SCRIPT_NAME']) == 'edit.php') && ($_GET["post_type"]=="rsvpmaker") && !isset($_GET["page"]))
	{
		echo '<div style="padding: 5px; margin: 2px; ">';
		if($_GET["rsvpsort"] == 'chronological')
			echo '<a href="'.admin_url('edit.php?post_type=rsvpmaker&rsvpsort=newest').'">'.__('Sort By Newest','rsvpmaker').'</a>';
		else
			echo '<a href="'.admin_url('edit.php?post_type=rsvpmaker&rsvpsort=chronological').'">'.__('Sort By Chronological','rsvpmaker').'</a>';
		echo '</div>';
	}
}
add_action('admin_notices','rsvpmaker_sort_message');
?>