<?php


/*-------------------------------------------------------------------------------------------*/
/* this is a hook to add scripts and styles */
function add_spotlight_scripts_and_styles() {

	global $wp_query;

    // main style sheet
    wp_enqueue_style('style', get_stylesheet_uri());

    // slick, the carousel on top
    wp_enqueue_style('slick', get_template_directory_uri() . '/css/slick.css');
    wp_enqueue_script('slick-js', get_template_directory_uri() . '/js/slick.min.js', array ( 'jquery' ), 1.9, true);

    wp_enqueue_script('slideshow-js', get_template_directory_uri() . '/js/slideshow.js', array('jquery'), 1.0, true);

    // this is the script for loading more posts
    wp_register_script('loadmore-js', get_template_directory_uri() . '/js/loadmore.js', array('jquery'), 1.0, true);

    // to pass parameters to above script:
    wp_localize_script('loadmore-js', 'params', array(
    		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',  # call wordpress ajax handler
    	    'posts' => json_encode($wp_query->query_vars),
    		'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
    		'max_page' => $wp_query->max_num_pages));

    wp_enqueue_script('loadmore-js');

    // styles and script for the instagram widget
    wp_enqueue_style('igwidget', get_template_directory_uri() . '/css/igwidget.css');
    wp_register_script('igwidget-js', get_template_directory_uri() . '/js/igwidget.js', array('jquery'), 1.0, true);

    // pass params
    wp_localize_script('igwidget-js', 'igparams', array(
    		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'));

    wp_enqueue_script('igwidget-js');

}

add_action('wp_enqueue_scripts', 'add_spotlight_scripts_and_styles');

/*-------------------------------------------------------------------------------------------*/
/* AJAX hooks */

function loadmore_handler() {
	// prepare params for query
	$args = json_decode(stripslashes($_POST['query']), true);
	$args['paged'] = $_POST['page'] + 1;  // load next page
	$args['post_status'] = 'publish';

	// perform query
	query_posts($args);

	if (have_posts()) {
		while (have_posts()) {
			the_post();
			// render post snippet
			get_template_part('template-parts/post-snippet', get_post_format());
		}
	}
	die;
}

add_action('wp_ajax_loadmore', 'loadmore_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_handler');


function igdata_handler() {
	// first load igtoken from options or use defaults
	$options_defaults = array(
		'igtoken' => '25118164.e64fcd6.2d0b7cb5e3da4a1492957b30534078c2',
		'igpostcount' => 16,
		'igpostsperrow' => 8
	);
	$splt_options = wp_parse_args(get_option('splt_options', $options_defaults), $options_defaults);

	
	// do the API request on instagram
	$req = wp_remote_get('https://api.instagram.com/v1/users/self/media/recent/?count=' . $splt_options['igpostcount'] . '&access_token=' . $splt_options['igtoken']);

	if (is_wp_error($req)) {
		echo 'Cannot query Instagram at the moment.';
		die;
	}

	$data = json_decode(wp_remote_retrieve_body($req));


	// build the html from the retrieved images
	foreach ($data->data as $picdata) {
		echo '<li style="width:' . (100.0 / $splt_options['igpostsperrow']) . '%">';
			echo '<a href="' . $picdata->link . '" target="_blank">';
				echo '<div class="ig-stat">';
					echo '<p>';
						echo '<span class="ig-likes">' . $picdata->likes->count . '</span>';
						echo '<span class="ig-comments">' . $picdata->comments->count . '</span>';
					echo '</p>';
				echo '</div>';
				echo '<img src="' . $picdata->images->low_resolution->url . '" />';
			echo '</a>';
		echo '</li>';
	}
	die;
}

add_action('wp_ajax_igdata', 'igdata_handler');
add_action('wp_ajax_nopriv_igdata', 'igdata_handler');

/*-------------------------------------------------------------------------------------------*/
/* this hook replaces the ellipsis [...] in the excerpt with a "button" link to the full article. */
function spotlight_excerpt_more($link) {
    
    $link = sprintf('<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
                    esc_url(get_permalink(get_the_ID())),
                    __('Full article', 'slwp_theme'));
                    
    return $link;   
}

add_filter('excerpt_more', 'spotlight_excerpt_more');


/*-------------------------------------------------------------------------------------------*/
/* The options page */
add_action('admin_menu', 'add_spotlight_menu_option');

function add_spotlight_menu_option() {
	add_menu_page('Spotlight Theme Options', 'Spotlight Theme Options',
		'manage_options', 'spotlight-options', 'spotlight_options_page');
	add_action('admin_init', 'spotlight_theme_register_options');
}

function spotlight_theme_register_options() {
	register_setting('spotlight-theme-settings-group', 'splt_options', 
		'spotlight_sanitize_options');
}

function spotlight_sanitize_options($input) {
	$input['igtoken'] = sanitize_text_field($input['igtoken']);
	return $input;
}

function spotlight_options_page() {
?>
	<div class="wrap">
		<h2>Spotlight Theme Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields('spotlight-theme-settings-group'); ?>
			<?php 
				# this implements defaults for the options
				$options_defaults = array(
					'igtoken' => '25118164.e64fcd6.2d0b7cb5e3da4a1492957b30534078c2',
					'igpostcount' => 16,
					'igpostsperrow' => 8
				);
				$splt_options = wp_parse_args(get_option('splt_options', $options_defaults), $options_defaults);
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Instagram API access token</th>
					<td><input type="text" name="splt_options[igtoken]"
						value="<?=esc_attr($splt_options['igtoken'])?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Number of most recent Instagram posts to pull</th>
					<td><input type="number" name="splt_options[igpostcount]" min="0" step="1"
						value="<?=esc_attr($splt_options['igpostcount'])?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Number of Instagram posts per row</th>
					<td><input type="number" name="splt_options[igpostsperrow]" min="1" step="1"
						value="<?=esc_attr($splt_options['igpostsperrow'])?>"></td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="Save Changes" />
			</p>
		</form>
	</div>
<?php
}



/*-------------------------------------------------------------------------------------------*/
/* features post images */
function spotlight_theme_setup() {
	add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'spotlight_theme_setup');
