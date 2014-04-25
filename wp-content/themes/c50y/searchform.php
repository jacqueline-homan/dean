<?php get_search_form();
  $echo = true;
?>
<form action="/" method="get">
  <fieldset>
    <label for="search">Search in<?php echo home_url('/'); ?></label>
    <input type="text" name="s" id="search" value="<php the_search_query(); ?>"
  </fieldset>
</form>