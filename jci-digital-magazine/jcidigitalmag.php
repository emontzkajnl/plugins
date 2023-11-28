<?php
/*
Plugin Name: JCI Digital Magazine Grid
Plugin URI: http://jnlcom.com
Description: GRID VERSION: Adds a content type and display controls for Digital Magazines
Version: 2.0
Author: JCI Developers
Author URI: http://jnlcom.com
License: GPL2
*/

// Create the Magazine post type
function create_post_type() {
	register_post_type( 'magazine',
		array(
			'labels' => array(
				'name' => __( 'Magazines' ),
				'singular_name' => __( 'Magazine' )
			),
		'description' => 'Digital Magzines help integrate third-party provider magazine content.',
		'public' => true,
		'has_archive' => true,
		'menu_position' => 5, 
		'supports' => array('title','editor', 'thumbnail'),
		'register_meta_box_cb' => 'add_digital_magazine_fields',
		'taxonomies' => array('category'),
		)
	);
}

add_action( 'init', 'create_post_type' );

// Add meta boxes for Magazine options
function add_digital_magazine_fields(){
	// Controls display textarea for copy under magazine embed
	add_meta_box('Digital Magazine', 'Digital Magazine', 'jcidm_magazine', 'magazine', 'normal');
}

/*****
MAGAZINE POST TYPE
******/

function jcidm_magazine(){
	global $post;
	
// callback to display Magazine Copy text field
	echo '<label>Magazine Copy</label>
	  <input type="hidden" name="calameometa_noncename" id="calameometa_noncename" value=' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the location data if its already been entered
    $magazine_copy = get_post_meta($post->ID, 'magazine_copy', true);
    // Echo out the textarea with editor
    echo  wp_editor( $magazine_copy, 'magazine', $settings = array('textarea_name' => $magazine_copy, 'cols' => 60) ); 

// callback to display Calameo ID text field
	echo '<div class="calameo-id-field"><label>Calameo ID</label>
	<input type="hidden" name="calameometa_noncename" id="calameometa_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the location data if its already been entered
    $calameo_id = get_post_meta($post->ID, 'calameo_id', true);
    // Echo out the field
    echo '<input type="text" name="calameo_id" value="' . $calameo_id  . '" class="widefat" /></div>';

// callback to display Apple ID text field
    // callback to display option field
    // checks to see if this is a new post 
    if ($post->post_status == 'auto-draft'){
        // if it is new, use the default setting
        $apple_badge = get_option( 'dm_apple_badge' );
    }
    else {
        $apple_badge = get_post_meta($post->ID, 'apple_link', true);
    }
    $chkchk = checked(1, $apple_badge, false);
    echo '<div><input type="checkbox" name="apple_link" value="1" '. $chkchk .' /><label> Display Apple Badge</label>
    <p><small>Check if this magazine should display the Apple Badge</small></p></div>';


// callback to display Android link text field
    // callback to display option field
    // checks to see if this is a new post 
    if ($post->post_status == 'auto-draft'){
        // if it is new, use the default setting
        $android_badge = get_option( 'dm_android_badge' );
    }
    else {
        $android_badge = get_post_meta($post->ID, 'android_link', true);
    }
    $chkchk = checked(1, $android_badge, false);
    echo '<div><input type="checkbox" name="android_link" value="1" '. $chkchk .' /><label> Display Android Badge</label>
    <p><small>Check if this magazine should display the Android Badge</small></p></div>';


// callback to display Amazon link text field
    // callback to display option field
    // checks to see if this is a new post 
    if ($post->post_status == 'auto-draft'){
        // if it is new, use the default setting
        $amazon_badge = get_option( 'dm_amazon_badge' );
    }
    else {
        $amazon_badge = get_post_meta($post->ID, 'amazon_link', true);
    }
    $chkchk = checked(1, $amazon_badge, false);
    echo '<div><input type="checkbox" name="amazon_link" value="1" '. $chkchk .' /><label> Display Amazon Badge</label>
    <p><small>Check if this magazine should display the Amazon Badge</small></p></div>';
    
// callback to display Bookshelf field
// callback to display option field
    // checks to see if this is a new post 
    if ($post->post_status == 'auto-draft'){
        // if it is new, use the default setting
        $in_bookshelf = get_option( 'dm_in_bookshelf' );
    }
    else {
        $in_bookshelf = get_post_meta($post->ID, 'in_bookshelf', true);
    }
    $chkchk = checked(1, $in_bookshelf, false);
    echo '<div><input type="checkbox" name="in_bookshelf" value="1" '. $chkchk .' /><label>No Display Bookshelf</label>
    <p><small>Check if this magazine should NOT display the Calameo Bookshelf</small></p></div>';

// callback to display option field
    global $post;
    $featured_dm = get_post_meta($post->ID, 'featured_dm', true);
    $chkchk = checked(1, $featured_dm, false);
    echo '<div> <input type="checkbox" name="featured_dm" value="1" '. $chkchk .' /> <label> Featured Magazine</label>
    <p><small>Check if this magazine should be Featured</small></p></div>';
    
    $device_detection_post = query_posts('category_name=Device Detection Post');
    $device_detection_post = $device_detection_post[0]->guid;
    echo '<div class="enhanced_mobility_link"><a href="' . $device_detection_post . '">Go to Device Detection Post</a></div>';
}


// Save the Metabox Data
function digital_magazine_save_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['calameometa_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $digital_magazine_meta['magazine_copy'] = $_POST['magazine_copy'];
    $digital_magazine_meta['calameo_id'] = $_POST['calameo_id'];
    $digital_magazine_meta['enhancedmob'] = $_POST['enhancedmob'];
    $digital_magazine_meta['apple_link'] = $_POST['apple_link'];
    $digital_magazine_meta['android_link'] = $_POST['android_link'];
    $digital_magazine_meta['amazon_link'] = $_POST['amazon_link'];
    $digital_magazine_meta['in_bookshelf'] = $_POST['in_bookshelf'];
    $digital_magazine_meta['featured_dm'] = $_POST['featured_dm'];
    // Add values of $events_meta as custom fields
    foreach ($digital_magazine_meta as $key => $value) { // Cycle through the $digital_magazine_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'digital_magazine_save_meta', 1, 2); // save the custom fields

/*****
MAGAZINE TEMPLATE
******/

// Magazine template selection
function magazine_template(){
	global $wp;
	if ($wp->query_vars["post_type"] == "magazine"){
	    // If the theme provides a template, use it
		if(file_exists(get_stylesheet_directory_uri(  ) . "/single-digital_magazine.php")){
			include(get_stylesheet_directory_uri(  ) . "/single-digital_magazine.php");
			die();
		}
		// otherwise use the default template
		else { include(ABSPATH . 'wp-content/plugins/jci-digital-magazine/single-digital_magazine.php');
			die();
		}
	}
}

// add_action('template_redirect', 'magazine_template');


/*****
MAGAZINE AD PROCESSING OF PAGES
******/

function calameo_url_pager_query_vars($aVars) {
	$aVars[] = "dmpage"; // represents the name of the product category as shown in the URL
	return $aVars;
}
 
add_filter('query_vars', 'calameo_url_pager_query_vars');

function calameo_add_rewrite_rules($aRules) {
    $aNewRules = array('^magazine/([^/]*)/([^/]*)/?','index.php?post_type=$matches[1]&magazine=$matches[2]&calameo_url_pager=$matches[3]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}

add_filter('rewrite_rules_array', 'calameo_add_rewrite_rules');


/*****
MAGAZINE MENU BACKEND
******/

function magazine_menu() {
	add_submenu_page( 'edit.php?post_type=magazine','Display Settings', 'Display Settings', 'manage_options', 'dm-settings', 'digital_magazine_settings_cb');
}

add_action('admin_menu', 'magazine_menu');

// You have options...
function digital_magazine_settings_cb(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	// variables for the field and option names 
    $width_option = 'dm_width';
    $width_field_name = 'dm_width';
  	$height_option = 'dm_height';
    $height_field_name = 'dm_height';
    $hidden_field_name = 'mt_submit_hidden';
    $default_apple_option = 'dm_apple_badge';
    $default_apple_field_name = 'dm_apple_badge';
    $default_android_option = 'dm_android_badge';
    $default_android_field_name = 'dm_android_badge';
    $default_amazon_option = 'dm_amazon_badge';
    $default_amazon_field_name = 'dm_amazon_badge';
    $default_in_b_option = 'dm_in_bookshelf';
    $default_in_b_field_name = 'dm_in_bookshelf';
    $library_url_option = 'dm_library_url';
    $library_url_field_name = 'dm_library_url';

    // Read in existing option value from database
    $default_in_b = get_option( $default_in_b_option );
    $library_url = get_option ( $library_url_option );
    $width_val = get_option( $width_option );
    $height_val = get_option( $height_option );
    if($library_url == ''){$library_url = 'online-library';}

	   // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $default_in_b = $_POST[ $default_in_b_field_name ];
        $library_url = $_POST[ $library_url_field_name ];
        if($library_url == ''){$library_url = 'online-library';}
        $width_val = $_POST[ $width_field_name ];
        $height_val = $_POST[ $height_field_name ];
        // Save the posted value in the database
        update_option( $default_in_b_option, $default_in_b );
        update_option( $library_url_option, $library_url );
        
        if(is_numeric($width_val)){
            // Awesome, a number, add px
            $width_val = $width_val.'px';
        }
        elseif((strpos($width_val, 'px') !== false) && (strpos($width_val, 'px')+2 == strlen($width_val))){
            // if it has px at the end, strip it.
            $width_val = preg_replace('/px/', '', $width_val);
            // and check again to be sure it's not still 'wrong'
            if(!is_numeric($width_val)){
            wp_die( __( 'Width must be a valid pixel count' ) );
            }
            else {$width_val = $width_val.'px';}
        }
        elseif(strpos($width_val, '%')+1 == strlen($width_val)){
            // groovy, we have % at end
            $checkwidth = preg_replace('/%/', '', $width_val);
            if(!is_numeric($checkwidth)){
                wp_die( __( 'Width must be a valid percentage (%)' ) );
            }
        }
        else {
            wp_die( __( 'Width must be a percentage (%) or pixel count' ) );
        }
        update_option( $width_option, $width_val );
        
        if(is_numeric($height_val)){
        // Awesome, a number
        }
        elseif((strpos($height_val, 'px') !== false) && (strpos($height_val, 'px')+2 == strlen($height_val))){
            // if it has px at the end, strip it.
            $height_val = preg_replace('/px/', '', $height_val);
            // and check again to be sure it's not still 'wrong'
            if(!is_numeic($height_val)){
            wp_die( __( 'Height must be a valid pixel count' ) );
            }
        }
        elseif(strpos($height_val, '%')+1 == strlen($height_val)){
            // groovy, we have % at end
            $checkheight = preg_replace('/%/', '', $width_val);
            if(!is_numeric($checkheight)){
                wp_die( __( 'Height must be a valid percentage (%)') );
            }
        }
        else {
            wp_die( __( 'Height must be a percentage (%) or pixel count' ) );
        }
        update_option( $height_option, $height_val ); ?>

        // Put an settings updated message on the screen
      <div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

/*****
MAGAZINE MENU FRONTEND
******/
<?php
    }
    // Now display the settings editing screen
    echo '<div class="wrap">';
    // header
    echo "<h2>Digital Magazine Settings</h2>";
    // settings form
    ?>
<h3>Display Options</h3>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><b>Display in Bookshelf by Default?</b>
<?php $chkchk = checked(1, $default_in_b, false); ?>
<input type="checkbox" name="<?php echo $default_in_b_field_name; ?>" value="1" <?php echo $chkchk; ?> /></p>

<?php /*<p><b>Library URL</b> 
<?php print site_url();?>/<input type="text" name="<?php echo $library_url_field_name; ?>" value="<?php echo $library_url; ?>" size="20"> */
?>
<p><b>Display Width</b> 
<input type="text" name="<?php echo $width_field_name; ?>" value="<?php echo $width_val; ?>" size="6">
<br/>You may use either a percentage (100% - include '%') or pixel length (800 - do not include 'px').</p>

<p><b>Display Height</b> 
<input type="text" name="<?php echo $height_field_name; ?>" value="<?php echo $height_val; ?>" size="6">
<br/>You may use either a percentage (100% - include '%') or pixel length (800 - do not include 'px').</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
<h4>Shortcodes</h4>
<pre>[jci-featured-magazine width= height= post_id=]</pre>
<p>Displays the most recent magazine selected as 'featured' by default.  Width and height maybe pixels or percentages, while the post_id must be a number.</p>
<pre>[jci-magazine-widget width= height= post_id= arrows=]</pre>
<p>Displays a "mini-flipper" widget (links to the Magazine post).  The arrows argument should be 0 or 1, and controls the display of navigational arrows.</p>
<pre>[jci-magazine-shelf width= height= count= size=]</pre>
<p>Displays a "bookshelf" of all magazines selected to display in bookshelf by default.  Set count to limit the magazines.  Size should be the name of pre-defined image size.</p>
</div>
<?php 
}

// And now some shortcodes

/*****
FEATURED MAG SHORTCODE
******/

// register shortcode [jci-featured-magazine width= height= post_id=]
add_shortcode("jci-featured-magazine", "featured_magazine_handler");

function featured_magazine_handler($atts) {
    $dm_width = get_option('dm_width', '100%');
    $dm_height = get_option('dm_height', '800px');
    // we want the default to be the latest 'featured' magazine (will grab the latest if none are 'featured')
    $dm_query = new WP_Query(array('post_type' => 'magazine', 'meta_key' => 'featured_dm', 'meta_value' => 1, 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));
    if ($dm_query->post_count == 0){
    	    $dm_query = new WP_Query(array('post_type' => 'magazine', 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));
    }
    $dm_post = $dm_query->posts[0]->ID;
    // need sanity checks here for user input

    $atts = shortcode_atts( array (
         'width' => $dm_width,
         'height' => $dm_height,
         'post_id' => $dm_post,
    ), $atts );
    //run function that actually does the work of the plugin
    $jcifeatdm_output = jcifeatureddm_function($atts);
    //send back text to replace shortcode in post
    return $jcifeatdm_output;
}

// now build the display of full post in the entry DM page
function jcifeatureddm_function($atts) {

    // we want the default to be the latest 'featured' magazine (will grab the latest if none are 'featured')
    // $dm_query = new WP_Query(array('post_type' => 'magazine', 'meta_key' => 'featured_dm', 'meta_value' => 1, 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));
    // if ($dm_query->post_count == 0){ $dm_query = new WP_Query(array('post_type' => 'magazine', 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));}
    $dm_query = new WP_Query(array('post_type' => 'magazine', 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC'));
    $dm_post = $dm_query->posts[0];
        
	$custom_fields = get_post_custom($dm_post->ID); 
    $title = $dm_post->post_title;
    $content = $dm_post->post_content;
    $theme_path = get_template_directory_uri();
    
    // $args = array( 'numberposts' => 1, 'category' => 114, 'post_status' => 'publish' );
    $args = array( 'numberposts' => 1, 'post_type' => 'magazine', 'post_status' => 'publish' );
    $dd = get_posts( $args );
		$device_detection = get_post_custom($dd[0]->ID);
   
    if (isset($_GET['page'])) {
				$page = $_GET['page'];
			}
			else {$page = 1;}

    $magazine_display = '<div id="magazine-content">';
    $magazine_display .= '<h1>' . $title . '</h1>';
    // $magazine_display .= '<div class="intro test">';
    $magazine_display .= $content;
    // $magazine_display .= '</div>';
    $magazine_display .= '<div class="clear"></div>';
    $magazine_display .= '<hr size="1px">';
    $magazine_display .= '<div id="magazine">';
    $magazine_display .= '<iframe style="margin: 0 auto;" src="//v.calameo.com/?bkcode=' . $custom_fields['calameo_id'][0] . '&page=' . $page . '" width="100%" height="700" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
    $magazine_display .= '</div>';
    $magazine_display .= '<hr size="1px">';
    error_log(print_r($magazine_display, true));
 
  wp_reset_postdata();
	return $magazine_display;
}


/*****
WIDGET SHORTCODE
******/

// register shortcode [jci-magazine-widget width= height= post_id= arrows=]
add_shortcode("jci-magazine-widget", "magazine_widget_handler");

function magazine_widget_handler($atts) {
    $dm_width = '270px';
    $dm_height = '216px';
    // we want the default to be the latest 'featured' magazine (will grab the latest if none are 'featured')
    $dm_query = new WP_Query(array('post_type' => 'magazine', 'meta_key' => 'featured_dm', 'meta_value' => 1, 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));
    if ($dm_query->post_count == 0){
    	    $dm_query = new WP_Query(array('post_type' => 'magazine', 'post_status' => 'publish', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'meta_value date'));
    }
    $dm_post = $dm_query->posts[0]->ID;
    // need sanity checks here for user input
    
    $atts = shortcode_atts( array (
         'width' => $dm_width,
         'height' => $dm_height,
         'post_id' => $dm_post,
    ), $atts );    

    //run function that actually does the work of the plugin
    $jcidmwidget_output = jcidmwidget_function($atts);
    //send back text to replace shortcode in post
    return $jcidmwidget_output;
}

// now build the display
function jcidmwidget_function($atts) {
    // better load the magazine post, eh?
    $featuredmag = new WP_Query(array('post_type' => 'magazine','p' => $atts['post_id']));
    $magazine = $featuredmag->posts[0];
    $calameo_id = get_post_custom_values('calameo_id', $magazine->ID);
    $magazine_link = get_permalink($magazine->ID);
    $encodedlink = urlencode($magazine_link);
    $magazine_display = '<div id="magazine-widget">';
    $magazine_display .= '	<h3><a href="'. $magazine_link .'" title="'. $magazine->post_title .'">'.  $magazine->post_title .'</a></h3>';
	$magazine_display .= '      <iframe style="margin: 0 auto;" src="//v.calameo.com/?bkcode=' . $calameo_id[0] .'" width="100%" height="700" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
	$magazine_display .= '</div>';
	return $magazine_display;
}


/*****
SHELF SHORTCODE
******/

// register shortcode [jci-magazine-shelf width= height= count= size=]
add_shortcode("jci-magazine-shelf", "magazine_shelf_handler");

// for the slider, we'll need to add our slider script and styles
add_action('init', 'register_jcidm_reqs', 5);

function register_jcidm_reqs() {

//TO DO change wp_enqueue to wp_register & load only on magazine pages

	wp_enqueue_style('elastislide-custom-css', plugins_url('css/custom.css',__FILE__));
	wp_enqueue_style('elastislide-css', plugins_url('css/elastislide.css',__FILE__));
	wp_enqueue_style('elastislide-demo', plugins_url('css/demo.css',__FILE__));
	
	//wp_enqueue_script('modernizr-custom', plugins_url('js/modernizr.custom.17475.js',__FILE__));
	wp_enqueue_script('jquery-elastislide', plugins_url('js/jquery.elastislide.js',__FILE__), array('jquery'), false, true );
	wp_enqueue_script('jquery-custom', plugins_url('js/jquerypp.custom.js',__FILE__), array('jquery'), false, true);

}

function magazine_shelf_handler($atts) {
    $dm_width = '100%';
    $dm_height = '210px';
    $count = 0; //unlimited, all maybe -1
        // need sanity checks here for user input
    $imagesize = 'thumbnail';

    $atts = shortcode_atts( array (
         'width' => $dm_width,
         'height' => $dm_height,
         'count' => $count,
         'size' => $imagesize
    ), $atts );
    
    //run function that actually does the work of the plugin
    $jcidmshelf_output = jcidmshelf_function($atts);
    //send back text to replace shortcode in post
    return $jcidmshelf_output;
}

function jcidmshelf_function($atts) {
    global $post;
    $args = array( 'post_type' => 'magazine', 'order' => 'DESC', 'orderby' => 'meta_value date', 'post_status' => 'publish', 'posts_per_page' =>-1);
    $themagazines = get_posts( $args );

if ( $themagazines ):
	global $jcidm_script;
	$jcidm_script = true;
	$i = 0;
        $magsize = $atts['size'];     
        $mymagwidth = get_option($magsize.'_size_w');
        global $_wp_additional_image_sizes;
        if($mymagwidth == ''){
            $mymagwidth = $_wp_additional_image_sizes[$magsize]['width'];
        }
        
        $slider_display = '<div class="container demo-1">
            <div class="main"><ul id="carousel" class="elastislide-list">';
            
          foreach( $themagazines as $post ) :  setup_postdata($post); 
        $dm_cover = get_the_post_thumbnail($themagazines->posts[$i]->ID, $atts['size']);
        
        $slider_display .='<li><a href="' . get_permalink() .'">' . $dm_cover . '</a><br />
                               <a class="title" href="' . get_permalink() .'">'. get_the_title() .'</a>
                           </li>';
		    $i++;
      	endforeach;
        $slider_display .=' </ul></div></div>
        		<script type="text/javascript">
                jQuery(document).ready(function($) {
                $( \'#carousel\' ).elastislide();
                });
		        </script>
        ';        
	endif;
  wp_reset_postdata();
	return $slider_display;

}
?>
