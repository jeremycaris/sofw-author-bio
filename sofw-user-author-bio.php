<?php
/*

  Plugin Name: Author Bio

  Description: Inserts the author's image and bio after blog posts. The image source is the user's gravatar image. The bio source is the user description. And, it adds the ability to use valid HTML tags in the user description.

  Author:      Jeremy Caris
  
  Author URI:   https://714web.com/

  Version:     1.0

*/

if (!defined('ABSPATH')) exit();


require 'checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jeremycaris/sofw-author-bio/',
	__FILE__,
	'sofw-author-bio'
);


wp_enqueue_style( 'style', plugins_url( '/assets/css/style.css', __FILE__ ) );



// Remove the default filerting of HTML in the user description (author bio).
remove_filter('pre_user_description', 'wp_filter_kses');
// Add filter to sanitize content for allowed HTML tags, like post content.
add_filter( 'pre_user_description', 'wp_filter_post_kses' );



function add_post_content($content) {
    $author = get_the_author_meta( 'ID' ); 
    $info = get_userdata( $author );
    $udsc = get_the_author_meta( 'description', $author );
    $avat = get_avatar($author, 96);
    
    if (is_singular('post')) {        
        $content .= '<div class="sofw-author-bio">' . $avat. '<h2>' . $info->display_name . '</h2>' . $udsc . '</div>';
    }

    return $content;
}
add_filter ('the_content', 'add_post_content', 0);

