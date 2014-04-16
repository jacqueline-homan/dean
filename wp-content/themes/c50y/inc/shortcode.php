<?php

function change_shortcodes($text){
	$text = str_replace('[template-url]', get_template_directory_uri(), $text);
	$text = str_replace('[site-url]', get_option('siteurl'), $text);
	return $text;
}

add_filter('the_content', 'change_shortcodes');
add_filter('get_the_content', 'change_shortcodes');
add_filter('widget_text', 'change_shortcodes');
add_filter('the_excerpt', 'change_shortcodes');
add_filter('get_the_excerpt', 'change_shortcodes');


// [constant-contact]
/*function cc_sub_func( $atts ) {
	return '
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffcc" style="border:2px solid #000000;">
<tr>
<td align="center" style="font-weight: bold; font-family:Arial; font-size:12px; color:#000000;">Join Our Mailing List</td>
</tr>
<tr>
<td align="center" style="border-top:2px solid #000000">
<form name="ccoptin" action="http://visitor.r20.constantcontact.com/d.jsp" target="_blank" method="post" style="margin-bottom:2;">
<input type="hidden" name="llr" value="cke9fycab">
<input type="hidden" name="m" value="1102470517058">
<input type="hidden" name="p" value="oi">
<font style="font-weight: normal; font-family:Arial; font-size:12px; color:#000000;">Email:</font> <input type="text" name="ea" size="20" value="" style="font-size:10pt; border:1px solid #999999;">
<input type="submit" name="go" value="Go" class="submit" style="font-family:Verdana,Geneva,Arial,Helvetica,sans-serif; font-size:10pt;">
</form>
</td>
</tr>
</table>
';    
}
add_shortcode( 'constantcontact_subscribe', 'cc_sub_func' );
*/
