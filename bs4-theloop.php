<?php

$post_count = 0;
while ( have_posts() ) {
	the_post();  // Load Post
	$post_count++;

	/* Include the Post-Format-specific template for the content.
	 * If you want to overload this in a child theme then include a file
	 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	 */
	get_template_part( 'content', get_post_format() );

	bs4_link_pages();
}

if ($post_count == 0)
	get_template_part( 'content-not-found' );
else {
	bs4_content_pager();
	if (comments_open()) comments_template('', true);
}
