<?php

if(!function_exists('basic_form') ) {
function basic_form($profile, $guestedit = '')
{
//be cautious if you override this function. RSVPMaker expects tog get at least name and email address as identifiers for people who respond, and the first and last name fields are also used for alphabetical sorting and searching of RSVP records
;?>
        <table border="0" cellspacing="0" cellpadding="0"> 
          <tr> 
            <td><?php echo __('First Name','rsvpmaker');?>:</td> 
            <td> 
              <input name="profile[first]" type="text" id="first" size="60"  value="<?php echo $profile["first"];?>"  /> 
            </td> 
          </tr> 
          <tr> 
            <td><?php echo __('Last Name','rsvpmaker');?>:</td> 
            <td> 
              <input name="profile[last]" type="text" id="last" size="60"  value="<?php echo $profile["last"];?>"  /> 
            </td> 
          </tr> 
          <tr> 
            <td width="100"><?php echo __('Email','rsvpmaker');?>:</td>
            <td><input name="profile[email]" type="text" id="rsvp[email]" size="60" value="<?php echo $profile["email"];?>" /></td> 
          </tr> 
  </table> 

<?php
//by default, this displays the phone # field or a message saying this is already on file
//can also be customized to request more extensive contact info
rsvp_profile($profile);
?>

<!-- end of profile section-->      

<!-- guest section -->
        <p id="guest_section"><strong><?php echo __('Guests','rsvpmaker');?>:</strong> <?php echo __('If you are bringing guests, please enter their names here','rsvpmaker');?></p>
        <?php echo $guestedit;?>
        <div class="guest_blank"><?php echo __('First Name','rsvpmaker');?>: <input type="text" name="guestfirst[]" /> <?php echo __('Last Name','rsvpmaker');?>: <input type="text" name="guestlast[]" /><input type="hidden" name="guestid[]" value="0" /></div>
        <a href="#guest_section" id="add_guests" name="add_guests">(+) <?php echo __('Add more guests','rsvpmaker');?></a></p>
<script>
jQuery(document).ready(function($) {

$('#add_guests').click(function(){
	$('.guest_blank').append('<div class="guest_blank">First Name: <input type="text" name="guestfirst[]" /> Last Name: <input type="text" name="guestlast[]" /><input type="hidden" name="guestid[]" value="0" /></div>');
	});
});
</script>
<!-- end of guest section-->              
        
<p><?php echo __('Note','rsvpmaker');?>:<br /> 
<textarea name="note" cols="60" rows="2" id="note"></textarea> 
</p> 

<?php

} }

// customize the fields to be included with the RSVP form (in addition to name and email)
if(!function_exists('rsvp_profile') ) {
function rsvp_profile($profile) {

if($profile["phone"])
	{
;?>
<p id="profiledetails"><?php printf( __('Phone # on file.<br />To update profile, 
or RSVP for someone else <a href="%s">fetch a blank 
form</a>','rsvpmaker'),get_permalink() );?></p>
<input type="hidden" name="onfile" value="1" />
<?php
	}
else
	{

global $rsvp_options;
echo $rsvp_options["profile_table"];
	}

} } // end rsvp_profile

?>