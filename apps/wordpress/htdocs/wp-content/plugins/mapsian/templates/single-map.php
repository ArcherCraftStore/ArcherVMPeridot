<?php

get_header();

      if ( have_posts() ) :

      	$post_id = $post->ID;

       while ( have_posts() ) : the_post();

		echo 	do_shortcode('[map-view id='.$post_id.']');
		
		endwhile;
      else :
        // If no content, include the "No posts found" template.
      endif;
get_footer();
?>