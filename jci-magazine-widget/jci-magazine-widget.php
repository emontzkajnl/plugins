<?php
/**
* Plugin Name: JCI Sidebar Magazine Widget
* Plugin URI: https://www.journalcommunications.com
* Description: Creates a widget for displaying the most recent magazine in the sidebar
* Version: 1.0
* Author: Richard Stevens
* Author URI: https://www.journalcommunications.com
**/

// Register and load the widget
function jci_load_mag_widget() {
    register_widget( 'jci_mag_widget' );
}
add_action( 'widgets_init', 'jci_load_mag_widget' );
 
// Creating the widget 
class jci_mag_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'jci_mag_widget', 
 
// Widget name will appear in UI
__('JCI Magazine Widget', 'jci_mag_widget_domain'), 
 
// Widget description
array( 'description' => __( 'Display the most recent magazine', 'jci_mag_widget_domain' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// This is where you run the code and display the output
$args = array( 
	'post_type' => 'magazine', 
	'posts_per_page' => 1
	);
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
$magoutput = '';
$magthumb = get_the_post_thumbnail( $post_id, array(175,auto) );
$maglink = get_permalink();
$magtitle = str_replace(array('Fall','Winter','Spring','Summer'), array('<br>Fall','<br>Winter','<br>Spring','<br>Summer'), get_the_title());
$magtitle = str_replace(array('And','and','AND'),'&amp;', $magtitle);
$magoutput = '<div class="sidebar-widget"><div style="width:175px; margin:0px auto 11px; text-align:center;">';
$magoutput .= '<a href="' . $maglink . '" alt="Read the Magazine">';
$magoutput .= $magthumb;
$magoutput .= '</a></div>';
$magoutput .= '<p style="text-align:center; text-transform:uppercase;"><a href="' . $maglink . '">';
$magoutput .= $magtitle . '</a></p></div>';
endwhile;
wp_reset_query();
echo __( $magoutput, 'wpb_widget_domain' );
echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'jci_mag_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here