<?php
/*
 * Plugin Name: Shortcodes In Use
 * Plugin URI: http://wordpress.org/plugins/shortcodes-in-use/
 * Description: List or search for all/any shortcodes that are currently in use
 * Version: 1.2.1
 * Author: Roger Barrett
 * Author URI: http://www.wizzud.com/
 * License: GPL2+
 * Text Domain: shortcodes-in-use
*/
defined( 'ABSPATH' ) or exit();
/*
 * v1.2.1 change log
 * - tweak : changed the plugin's h2 header to h1
 * - addition : a collapse/expand all button
 * - tweak : removed on-page javascript
 *
 * v1.2.0 change log
 * - internationalization
 *
 * v1.1.0 change log
 * - bugfix : handle the shortcode's callable function being specified as "someClass::someMethod"
 * - addition : shortcode, for when a plugin or theme doesn't declare their shortcode when in admin backend
 * - tweak : reformatted results to not use table, added a count, and made parts collapsible
 * - tweak : small optimisation changes
 *
 * v1.0.1 change log
 * - bugfix : ensure that checked by-location and posts-by-type filters also get hilighted when displaying results
 *
 * v1.0.0 change log
 * - initial release
 */

if( !class_exists( 'Shortcodes_In_Use_Plugin' ) ){

    //instantiate...
    add_action( 'plugins_loaded', array( 'Shortcodes_In_Use_Plugin', 'init' ), 1 );

    //deactivation/uninstall hooks...
    register_deactivation_hook( __FILE__, array( 'Shortcodes_In_Use_Plugin', 'on_deactivation' ) );
    register_uninstall_hook( __FILE__, array( 'Shortcodes_In_Use_Plugin', 'on_uninstall' ) );

    //declare the main plugin class...
    class Shortcodes_In_Use_Plugin {

        public static $version = '1.2.1';
        protected static $instance;

        public $menuPage = '';
        public $options = array();
        public $postTypes = array();
        public $providers = array();
        public $locations = array();
        public $alt_providers = array();
        public $alt_locations = array();

        public $cache = false;
        public $currentTheme = false;
        public $pageHook = '';
        public $pluginData = false;
        public $regexPattern = '*';
        public $tags = array();
        public $tagInfo = array();
        public $usingCache = false;
        public $cacheMinutes = 15;

        public $runningShortcode = false;
        public $wpIncludesPath = '';

        /**
         * constructor : sets up plugin data and adds actions
         */
        public function __construct(){

            load_plugin_textdomain( basename( dirname( __FILE__ ) ), false, basename( dirname( __FILE__ ) ) . '/languages' );

            $this->menuPage = basename( dirname( __FILE__ ) );

            $this->providers = array(
                'unknown'   => __( 'Unknown' , 'shortcodes-in-use'),
                'wordpress' => __( 'WordPress' , 'shortcodes-in-use'),
                'plugin'    => __( 'Plugin' , 'shortcodes-in-use'),
                'theme'     => __( 'Theme' , 'shortcodes-in-use')
                );
            $this->locations = array(
                'title'   => __( 'Post Title' , 'shortcodes-in-use'),
                'content' => __( 'Post Content' , 'shortcodes-in-use'),
                'excerpt' => __( 'Post Excerpt' , 'shortcodes-in-use'),
                'meta'    => __( 'Post Meta' , 'shortcodes-in-use'),
                'widget'  => __( 'Widget' , 'shortcodes-in-use')
                );
            //alternatives to above, for results display...
            $this->alt_providers = array(
                'unknown'   => '<span class="s-i-u_red">' . __( 'Unknown Provider' , 'shortcodes-in-use') . '</span>',
                );
            //alternatives to above, for various post types...
            $this->alt_locations = array(
                'attachment' => array(
                    'content' => __( 'Description' , 'shortcodes-in-use'),
                    'excerpt' => __( 'Caption' , 'shortcodes-in-use')
                    ),
                'nav_menu_item' => array(
                    'title'   => __( 'Item Label' , 'shortcodes-in-use'),
                    'content' => __( 'Item Description' , 'shortcodes-in-use')
                    )
                );

            $this->wpIncludesPath = wp_normalize_path( ABSPATH . 'wp-includes/' );

            //this action adds the tools page, and some other actions...
            add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
            //add the shortcode...
            add_action( 'widgets_init', array( &$this, 'shortcode_init' ) );

        }

        /**
         * hooked into plugins_loaded action : creates the plugin instance
         */
        public static function init(){

            is_null( self::$instance ) && self::$instance = new self;
            return self::$instance;

        }

        /**
         * tidy up transient on deactivation
         */
        public static function on_deactivation(){

            if( current_user_can( 'activate_plugins' ) && function_exists( 'delete_transient' ) ){
                delete_transient( basename( dirname( __FILE__ ) ) );
            }

        }

        /**
         * tidy up transient on uninstall
         */
        public static function on_uninstall(){

            if( __FILE__ == WP_UNINSTALL_PLUGIN && current_user_can( 'activate_plugins' ) && function_exists( 'delete_transient' ) ){
                delete_transient( basename( dirname( __FILE__ ) ) );
            }

        }

        /**
         * hooked into admin_menu action : add tools page and action for when an update to this plugin is available
         */
        public function admin_menu(){

            //add tools page...
            $this->pageHook = add_management_page(
                __( 'Shortcodes In Use' , 'shortcodes-in-use'),
                __( 'Shortcodes In Use' , 'shortcodes-in-use'),
                'manage_options',
                $this->menuPage,
                array( &$this, 'admin_settings' )
                );

            //this action simply puts out a styled prompt/link to read the changelog whenever there is a plugin update available...
            add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ), array( &$this, 'update_message' ), 10, 2 );
            //add script for the admin page...
            add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_script' ) );
            //add styles for the admin page...
            add_action( 'admin_print_styles-' . $this->pageHook, array( &$this, 'enqueue_stylesheet' ) );

        }

        /**
         * callback from add_management_page() : displays the form and results (when form is submitted)
         */
        public function admin_settings(){

            $this->runningShortcode = false;
            $this->_set_taginfo();

            $byProvider = array();
            foreach( $this->providers as $k => $v ){
                $byProvider[ $k ] = array();
            }
            foreach( $this->tagInfo as $k => $v ){
                $n = empty( $v['name'] ) ? '' : $v['name'];
                if( !isset( $byProvider[ $v['provider'] ][ $n ] ) ){
                    $byProvider[ $v['provider'] ][ $n ] = array( $k );
                }else{
                    $byProvider[ $v['provider'] ][ $n ][] = $k;
                }
            }

            $this->_set_options( empty( $_POST ) ? array() : $_POST );

            ?>

<div class='wrap'>
    <div class="icon32" id="icon-options-general"><br></div>
    <h1><?php _e( 'Shortcodes In Use' , 'shortcodes-in-use'); ?></h1>
    <p>
        <?php _e( "The plugin can search post title/content/excerpt/metadata fields, and widgets, for text strings that resemble a shortcode." , 'shortcodes-in-use'); ?>
        <br />
        <?php _e( "Revisions and trashed items are ignored. Filters are by inclusion, but only when set (ie. no Providers set == all Providers set)." , 'shortcodes-in-use'); ?>
    </p>
    <p>
        <?php _e( sprintf( "In the report's Provider column, a shortcode may be denoted as '%s' if :" , $this->alt_providers['unknown'] ) , 'shortcodes-in-use'); ?>
        <span class="s-i-u_text-indent"><?php _e( "(a) it is known to WordPress but its provider cannot be determined" , 'shortcodes-in-use'); ?></span>
        <span class="s-i-u_text-indent"><?php _e( "(b) it was provided by a now inactive, or deleted, plugin or theme" , 'shortcodes-in-use'); ?></span>
        <span class="s-i-u_text-indent"><?php _e( "(c) its provider does not declare it for use in the admin backend (eg. WP Photo Album Plus's [wppa] shortcode)." , 'shortcodes-in-use'); ?></span>
    </p>
    <form method='post' action='tools.php?page=<?php echo $this->menuPage; ?>'>
        <?php wp_nonce_field( 'shortcodes-in-use_list' ); ?>
        <table id="shortcodes_in_use_parameters" class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="shortcodes_in_use_settings_s"><?php _e( 'Search Shortcode Tag' , 'shortcodes-in-use'); ?></label></th>
                    <td>
                        <input type="search" value="<?php echo implode( ' ', $this->options[ 'search' ] ); ?>" name="s" id="shortcodes_in_use_settings_s" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Filter by Provider/Tag', 'shortcodes-in-use'); ?></th>
                    <td class="s-i-u_provider_list">
                        <?php foreach( $this->providers as $k => $v ) : ?>
                        <div>
                            <label class="for_s-i-u_checkbox button"><input type="checkbox" name="p[]" value="<?php echo $k; ?>"
                                <?php checked( in_array( $k, $this->options[ 'provider' ] ), true ); ?>
                                class="s-i-u_providers_provider" /><?php echo $v; ?></label>
                            <?php if( !empty( $byProvider[ $k ] ) ) : ?>
                            <a class="dashicons-before dashicons-arrow-up dashicons-arrow-down s-i-u_list_reveal button"></a>
                            <div class="s-i-u_list_inset">
                                <?php ksort( $byProvider[ $k ] ); ?>
                                <?php foreach( $byProvider[ $k ] as $n => $a ) : ?>
                                    <?php if( !empty( $n ) ) : ?>
                                <div>
                                    <label class="for_s-i-u_checkbox button"><input type="checkbox" name="n[]" value="<?php echo "$k/$n"; ?>"
                                        <?php checked( in_array( strtolower("$k/$n"), $this->options[ 'name' ] ), true ); ?>
                                        class="s-i-u_providers_name" /><?php echo $n; ?></label>
                                    <a class="dashicons-before dashicons-arrow-up dashicons-arrow-down s-i-u_list_reveal button"></a>
                                    <div class="s-i-u_list_inset">
                                    <?php endif; ?>

                                        <div class="s-i-u_list_tags">
                                    <?php foreach( $a as $tag ) : ?>
                                            <label class="for_s-i-u_checkbox button"><input type="checkbox" name="t[]" value="<?php echo $tag; ?>"
                                                <?php checked( in_array( $tag, $this->options[ 'tag' ] ), true ); ?>
                                                class="s-i-u_providers_tag" />[<?php echo $tag; ?>]</label>
                                    <?php endforeach; ?>
                                        </div>

                                    <?php if( !empty( $n ) ) : ?>
                                    </div>
                                </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Filter by Location', 'shortcodes-in-use'); ?></th>
                    <td>
                        <?php foreach( $this->locations as $k => $v ) : ?>
                        <label class="for_s-i-u_checkbox button"><input type="checkbox" name="l[]" value="<?php echo $k; ?>"
                            <?php checked( in_array( $k, $this->options[ 'location' ] ), true ); ?> /><?php echo $v; ?></label>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e( 'Filter Posts by Type', 'shortcodes-in-use'); ?></th>
                    <td>
                        <?php foreach( $this->_list_post_types() as $v ) : ?>
                        <label class="for_s-i-u_checkbox button"><input type="checkbox" name="pt[]" value="<?php echo esc_attr( $v ); ?>"
                            <?php checked( in_array( $v, $this->options[ 'post_type' ] ), true ); ?> /><?php echo esc_html( $v ); ?></label>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label class="button"><?php _e( 'Clear Cache', 'shortcodes-in-use'); ?>
                            <input type="checkbox" name="cc" value="1" /></label>
                    </th>
                    <td>
                        <input type="submit" value="<?php _e( "Find Shortcodes..." , 'shortcodes-in-use'); ?>" class="button button-primary" name="_ignore" />
                    </td>
                </tr>
            </tbody>
        </table>

        <?php if( isset( $_POST[ 's' ] ) && check_admin_referer( 'shortcodes-in-use_list' ) ){
            echo $this->find_shortcodes();
            echo $this->show_shortcode();
        } ?>

    </form>
</div>
<?php

        }

        /**
         * hooked into admin_enqueue_scripts action : queues script used by the plugin
         */
        public function enqueue_script( $hook ){

            if( $hook == $this->pageHook ){
                wp_enqueue_script( $this->menuPage . '-script', plugins_url( '/' . $this->menuPage . '.js', __FILE__ ), array('jquery'), self::$version, true );
            }

        }

        /**
         * hooked into admin_print_styles-tools.php action : queues stylesheet used by the plugin
         */
        public function enqueue_stylesheet(){

            wp_enqueue_style( $this->menuPage . '-styles', plugins_url( '/' . $this->menuPage . '.css', __FILE__ ), array('dashicons'), self::$version );

        }

        /**
         * list any post that contains a shortcode
         * @return string HTML
         */

        public function find_shortcodes(){
            global $wpdb, $wp_registered_sidebars, $wp_registered_widgets, $post;

            $html = '';

            //uses WP's function to help construct the regexp pattern...
            $this->_get_shortcode_regex();

            $set_cache = false;
            $can_use_cache = $this->cache !== false;
            $postids = array(
                'posts' => array(),
                'postmeta' => array()
                );

            foreach( $this->locations as $k => $v ){
                $j = "look_$k";
                $$j = empty( $this->options[ 'location' ] ) || in_array( $k, $this->options[ 'location' ] );
                if( $k != 'widget' ){
                    //if looking at any database table (posts or postmeta) then we may want to save to cache...
                    $set_cache = $set_cache || $$j;
                }
            }

            //decide whether the cache can be used...
            if( $can_use_cache ){
                if( //...looking for postmeta and haven't got it in cache...
                        ( $look_meta && empty( $this->cache[ 'look_meta' ] ) ) ||
                        //...OR...
                        (
                            //...looking for *something* in post...
                            ( $look_content || $look_excerpt || $look_title ) &&
                            //...AND whatever I'm now looking for is exactly the same thing(s) that cache looked for...
                            ( $look_content === empty( $this->cache[ 'look_content' ] ) ||
                                $look_excerpt === empty( $this->cache[ 'look_excerpt' ] ) ||
                                $look_title === empty( $this->cache[ 'look_title' ] )
                            )
                        )
                    ){
                    //disable use of cache...
                    $can_use_cache = false;
                }
            }

            if( $can_use_cache ){
                if( $look_meta ){
                    $postids[ 'postmeta' ] = $this->cache[ 'postmeta' ];
                }
                if( $look_content || $look_excerpt || $look_title ){
                    $postids[ 'posts' ] = $this->cache[ 'posts' ];
                }
                $this->usingCache = $set_cache;
                $set_cache = false;
            }

            //search in post content...
            if( !$this->usingCache && ( $look_content || $look_excerpt || $look_title ) ){
                $sql = array();
                if( $look_content ){
                    $sql[] = "post_content LIKE '%1\$s'";
                }
                if( $look_excerpt ){
                    $sql[] = "post_excerpt LIKE '%1\$s'";
                }
                if( $look_title ){
                    $sql[] = "post_title LIKE '%1\$s'";
                }
                //filter out trash & revisions here...
                $sql = "SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_status!='trash' AND post_type!='revision' AND (" . implode( ' OR ', $sql ) . ')';
                $postids[ 'posts' ] = $wpdb->get_col( $wpdb->prepare( $sql, '%[%]%' ) );
            }
            //search in post meta...
            if( !$this->usingCache && $look_meta ){
                $sql = "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s'";
                $postids[ 'postmeta' ] = $wpdb->get_col( $wpdb->prepare( $sql, '%[%]%' ) );
            }

            //having got ids from the 2 searches above, fetch the posts for each unique id found...
            if( !empty( $postids[ 'posts' ] ) || !empty( $postids[ 'postmeta' ] ) ){
                if( $set_cache && !$this->runningShortcode ){
                    //set cache...
                    set_transient(
                        $this->menuPage,
                        array_merge(
                            array(
                                'look_meta' => $look_meta,
                                'look_content' => $look_content,
                                'look_excerpt' => $look_excerpt,
                                'look_title' => $look_title
                                ),
                            $postids
                        ),
                        $this->cacheMinutes * MINUTE_IN_SECONDS
                        );
                }
                $args = array(
                    'ignore_sticky_posts' => true,
                    'nopaging' => true,
                    'orderby' => 'date',
                    'post_type' => empty( $this->options[ 'post_type' ] ) ? $this->_list_post_types() : $this->options[ 'post_type' ],
                    'post_status' => array( 'publish', 'draft', 'future', 'pending', 'private', 'inherit' ),
                    'post__in' => array_keys( array_flip( array_merge( $postids[ 'posts' ], $postids[ 'postmeta' ] ) ) )
                    );

                $the_query = new WP_Query( $args );
                if( $the_query->have_posts() ){
                    while( $the_query->have_posts() ){
                        $the_query->the_post();
                        $id = get_the_ID();
                        $isMenuItem = is_nav_menu_item( $id );

                        $typeOfPost = get_post_type( $id );
                        if( empty( $typeOfPost ) ){
                            $typeOfPost = __( 'unknown' , 'shortcodes-in-use');
                        }else{
                            $typeOfPost = (string)$typeOfPost;
                        }

                        //if a tag was found in the post...
                        if( in_array( $id, $postids[ 'posts' ] ) ){
                            $scanAll = $look_title && $look_content && $look_excerpt;
                            if( $scanAll || $look_title ){
                                $this->_scan_for_tags( $post->post_title, $this->_found_in( 'title', $typeOfPost ) );
                            }
                            if( $scanAll || $look_content ){
                                $this->_scan_for_tags( $post->post_content, $this->_found_in( 'content', $typeOfPost ) );
                            }
                            if( $scanAll || $look_excerpt ){
                                $this->_scan_for_tags( $post->post_excerpt, $this->_found_in( 'excerpt', $typeOfPost ) );
                            }
                        }
                        //if a tag was found the postmeta...
                        if( in_array( $id, $postids[ 'postmeta' ] ) ){
                            $meta_cache = wp_cache_get( $id, 'post_meta' );
                            if( !$meta_cache ){
                                $meta_cache = update_meta_cache( 'post', array( $id ) );
                                $meta_cache = $meta_cache[ $id ];
                            }
                            //key pointing to an array of values...
                            foreach( $meta_cache as $k => $a ){
                                //foreach each value...
                                foreach( $a as $v ){
                                    //do quick check for opening square bracket, then run full shortcode check...
                                    //NB: don't need to worry about maybe_unserialize because I'm only testing for a string match somewhere
                                    //    within this particular value ($v) of this particular key ($k); whether $v is a string or another
                                    //    (serialized) array is getting too pedantic!
                                    //for meta data, also report the name of the custom field...
                                    $this->_scan_for_tags( $v, $this->locations[ 'meta' ] . ' <span title="' . __( 'Name of Custom Field' , 'shortcodes-in-use') . '">(&nbsp;' . $k . '&nbsp;)</span>' );
                                }
                            }
                        }

                        if( !empty( $this->tags ) ){
                            if( $isMenuItem ){

                                $terms = wp_get_post_terms( $id, 'nav_menu' );
                                if( !empty( $terms ) ){
                                    $menuName = $terms[ 0 ]->name;
                                }
                                if( empty( $menuName ) ){
                                    $menuName = __( 'unknown' , 'shortcodes-in-use');
                                }

                                $html .= $this->_format_result(
                                    array(
                                        'header' => '<span class="dashicons-before dashicons-list-view"> ' . get_the_title() . '</span>'
                                            . ' <em title="' . esc_attr__( 'Type of Post' , 'shortcodes-in-use') . '">( ' . $typeOfPost . ', ' . __( 'menu' , 'shortcodes-in-use') . ': <strong title="' . esc_attr__( 'Name of Menu' , 'shortcodes-in-use') . '">' . $menuName . '</strong> )</em>'
                                        )
                                    );

                            }else{

                                $link = get_edit_post_link( $id );
                                if( empty( $link ) ){
                                    $link = get_permalink();
                                    $icon = 'visibility';
                                }else{
                                    $icon = 'edit';
                                }

                                $html .= $this->_format_result(
                                    array(
                                        'header' => '<a href="' . $link . '" title="' . ($icon == 'edit' ? esc_attr__( 'Edit ' , 'shortcodes-in-use') : esc_attr__( 'View ' , 'shortcodes-in-use')) . $typeOfPost . '" class="s-i-u_no-underline dashicons-before dashicons-' . $icon . '">' . get_the_title() . '</a>'
                                            . ' <em title="' . esc_attr__( 'Type of Post' , 'shortcodes-in-use') . '">(&nbsp;' . $typeOfPost . '&nbsp;)</em>'
                                        )
                                    );
                            }
                        }
                    } //end while posts
                }

                wp_reset_postdata();
                unset( $the_query );

            } //end posts

            unset( $postids );

            //search in widgets...
            if( $look_widget ){
                $sidebars_widgets = get_option( 'sidebars_widgets' );
                //each sidebar (including wp_inactive_widgets)...
                foreach( (array) $sidebars_widgets as $i => $sidebar ){
                    if( is_array( $sidebar ) ){
                        $sidebar_name = empty( $wp_registered_sidebars[ $i ] ) ? '' : $wp_registered_sidebars[ $i ][ 'name' ];
                        if( empty( $sidebar_name ) ){
                            $sidebar_name = $i == 'wp_inactive_widgets' ? __( 'Inactive Widgets' , 'shortcodes-in-use') : __( 'unknown' , 'shortcodes-in-use');
                        }
                        //...each widget in the sidebar...
                        for( $ct = count( $sidebar ), $j = 0; $j < $ct; $j++ ){

                            $widget_name = isset( $wp_registered_widgets[ $sidebar[ $j ] ] )
                                ? $wp_registered_widgets[ $sidebar[ $j ] ][ 'name' ]
                                : __( 'unknown widget' , 'shortcodes-in-use');
                            $widget_slug = explode( '-', $sidebar[ $j ] );
                            $widget_inst = array_pop( $widget_slug );
                            $widget_slug = implode( '-', $widget_slug );
                            if( ( $widget_options = get_option( 'widget_' . $widget_slug ) ) !== false &&
                                    !empty( $widget_options[ $widget_inst ] ) &&
                                    is_array( $widget_options[ $widget_inst ] ) ){
                                //widget instance has options...
                                $v = implode( ' ', $widget_options[ $widget_inst ] );
                                if( strpos( $v, '[' ) !== false ){
                                    $this->_scan_for_tags( $v, $this->locations[ 'widget' ] );
                                }
                            }

                            if( !empty( $this->tags ) ){
                                $html .= $this->_format_result(
                                    array(
                                        'header' => '<span class="dashicons-before dashicons-welcome-widgets-menus"> <strong>' . $widget_name . '</strong> <em>' . __( 'widget' , 'shortcodes-in-use') . '</em></span>'
                                            . ' <em title="' . esc_attr__( 'Name of Widget Area' , 'shortcodes-in-use') . '">(&nbsp;' . $sidebar_name . '&nbsp;)</em>'
                                        )
                                    );
                            }
                        } //end each widget
                    }
                } //end each sidebar
            } //end widgets

            unset( $sidebars_widgets );

            if( empty( $html ) ){
                if( $this->usingCache ){
                    $html = '<p class="s-i-u_using-cache"><strong>' . __( 'Using Cache...' , 'shortcodes-in-use') . '</strong></p>';
                }
                $html .= '<div class="s-i-u_result-block"><p class="s-i-u_none-found">';
                $html .= __( 'None found.' , 'shortcodes-in-use') . '</p></div>';
            }else{
                $html =
                      '<p class="s-i-u_using-cache">'
                    .   '<button class="s-i-u_results-toggle-all">'
                    .     '<span class="dashicons dashicons-arrow-up"></span>'
                    .     '<span class="dashicons dashicons-arrow-down"></span>'
                    .   '</button>'
                    .   ( $this->usingCache
                            ? '<strong>' . __( 'Using Cache...' , 'shortcodes-in-use') . '</strong>'
                            : '&nbsp;'
                        )
                    . '</p>'
                    . '<div class="s-i-u_results-table">'
                    .   $html
                    . '</div>';
            }
            //wrap in div...
            $html = '<div id="shortcodes_in_use_results">' . $html . '</div>';

            return $html;

        } //end find_shortcodes()

        /**
         * hooked into widgets_init action : adds [shortcodes-in_use/] shortcode
         */
        public function shortcode_init(){

            add_shortcode( 'shortcodes_in_use', array( &$this, 'shortcode_run' ) );

        }

        /**
         * shortcode processing (as of v1.1.0)
         * @param array $atts options supplied to the shortcode
         * @param string $content Within start-end shortcode tags
         * @param string $tag Shortcode tag
         * @return string HTML that comes from running the_widget()
         */
        public function shortcode_run( $atts, $content, $tag ){

            //must be a logged in user with edit_posts capability...
            if( !current_user_can('edit_posts') ){
                return '';
            }

            $this->runningShortcode = true;
            $instance = shortcode_atts(
                array(
                    'location' => '',
                    'name' => '',
                    'post_type' => '',
                    'provider' => '',
                    'search' => '',
                    'tag' => ''
                ),
                (array)$atts,
                $tag
            );

            $opts = array();
            //valid values eg. title, content, excerpt, meta, widget
            if( !empty( $instance['location'] ) ){
                //lowercase and remove 'post '...
                $instance['location'] = str_replace( 'post ', '', strtolower( $instance['location'] ) );
                //can be comma- or space-separated...
                $instance['location'] = preg_split( '/[,\s]+', trim( $instance['location'] ), -1, PREG_SPLIT_NO_EMPTY );
                if( !empty( $instance['location'] ) ){
                    $opts['l'] = $instance['location'];
                }
            }
            if( !empty( $instance['name'] ) ){
                //names are provided as comma- or space-separated instances of Provider/Name, and
                //are only applicable to plugins and themes...
                $instance['name'] = preg_split( '/(plugin|theme)\s*\/\s*/i', $instance['name'], -1, PREG_SPLIT_DELIM_CAPTURE );
                if( count( $instance['name'] ) > 1 ){
                    //discard the first one...
                    array_shift( $instance['name'] );
                    //should now be an even number of elements, evens being Provider, odds being Name...
                    $opts['n'] = array();
                    for( $i = 0; $i < count( $instance['name'] ); $i += 2 ){
                        if( !empty( $instance['name'][ $i + 1 ] ) ){
                            $opts['n'][] = $instance['name'][ $i ] . '/' . trim( $instance['name'][ $i + 1 ], ', ' );
                        }
                    }
                }
            }
            if( !empty( $instance['posttype'] ) ){
                //can be comma- or space-separated...
                $instance['posttype'] = preg_split( '/[,\s]+/', trim( $instance['posttype'] ), -1, PREG_SPLIT_NO_EMPTY );
                if( !empty( $instance['posttype'] ) ){
                    $opts['pt'] = $instance['posttype'];
                }
            }
            if( !empty( $instance['provider'] ) ){
                //can be comma- or space-separated...
                $instance['provider'] = preg_split( '/[,\s]+/', strtolower( trim( $instance['provider'] ) ), -1, PREG_SPLIT_NO_EMPTY );
                if( !empty( $instance['provider'] ) ){
                    $opts['p'] = $instance['provider'];
                }
            }
            if( !empty( $instance['search'] ) ){
                $opts['s'] = $instance['search'];
            }
            if( !empty( $instance['tag'] ) ){
                //can be comma- or space-separated...
                $instance['tag'] = preg_split( '/[,\s]+/', trim( $instance['tag'] ), -1, PREG_SPLIT_NO_EMPTY );
                if( !empty( $instance['tag'] ) ){
                    $opts['t'] = $instance['tag'];
                }
            }

            $this->_set_taginfo();
            $this->_set_options( $opts );
            $this->enqueue_stylesheet();
            return $this->show_shortcode() . $this->find_shortcodes();

        }

        /**
         * produces the shortcode equivalent of the submitted options
         */
        public function show_shortcode(){

            $params = array();
            foreach( array(
                    'search',
                    'provider',
                    'name',
                    'tag',
                    'location',
                    'post_type'
                    ) as $v ){
                if( !empty( $this->options[ $v ] ) ){
                    $spaceOrComma = $v == 'name' ? ', ' : ' ';
                    $params[] = ' ' . $v . '="' . implode( $spaceOrComma, $this->options[ $v ] ) . '"';
                }
            }
            $params = esc_html( implode( '', $params ) );
            $title = $this->runningShortcode ? ' title="' . esc_attr__('This is the sanitized shortcode, which may differ slightly from the actual shortcode entered!', 'shortcodes-in-use') . '"' : '';
            return '<div class="s-i-u_shortcode"' . $title . '><code>[shortcodes_in_use' . $params . '/]</code></div>';

        }

        /**
         * hooked into in_plugin_update_message-shortcodes-in-use action : request read changelog before updating
         * @param array $plugin_data Plugin metadata
         * @param array $r Metadata about the available plugin update
         */
        public function update_message( $plugin_data, $r ){

            $url = 'http://wordpress.org/plugins/' . $r->slug. '/changelog/';
            $style = implode( ';', array(
                '-webkit-box-sizing:border-box',
                '-moz-box-sizing:border-box',
                'box-sizing:border-box',
                'background-color:#D54E21',
                'border-radius:2px',
                'color:#FFFFFF',
                'display:inline-block',
                'margin:0',
                'max-width:100%',
                'overflow:hidden',
                'padding:0 0.5em',
                'text-overflow:ellipsis',
                'text-shadow:0 1px 0 rgba(0, 0, 0, 0.5)',
                'vertical-align:text-bottom',
                'white-space:nowrap',
                ''
                ) );
            /* translators: 1: anchor starttag, 2: anchor endtag */
            $msg = sprintf( __('Please %1$sread the Changelog%2$s before updating!', 'shortcodes-in-use'),
                '<a href="' . $url . '" style="color:#FFFFFF;text-decoration:underline;" target="_blank">',
                '</a>'
                );

?>
 <p style="<?php echo $style; ?>"><em><?php echo $msg; ?></em></p>
<?php

        }

        /**
         * returns a result set of rows with information about the item and the contained shortcode(s)
         * @param array $params Item info
         * @return string HTML
         */
        private function _format_result( $params ){

            if( empty( $this->tags ) ){
                return '';
            }

            $html = '';
            $rowcount = count( $this->tags );
            $html .= "\n" . '<div class="s-i-u_result-block">';

            foreach( $this->tags as $instance ){
                $tag = $instance[ 'tag' ];
                if( $rowcount > 0 ){
                    //information about the item containing these shortcode(s)...
                    $html .= "\n" . '<div class="s-i-u_result-header">';
                    $html .= $params['header'];
                    $html .= '<button class="s-i-u_result-count">' . $rowcount . ' <span class="dashicons dashicons-arrow-up"></span><span class="dashicons dashicons-arrow-down"></span></button>';
                    $html .= '</div>';
                    $html .= "\n" . '<div class="s-i-u_result-rows">';
                    $rowcount = 0;
                }
                $html .= "\n" . '<div class="s-i-u_result-row">';

                //the shortcode tag and full shortcode...
                $html .= '<div class="s-i-u_result-inset" style="padding-left:'.$instance['level'].'em;">';
                $html .= '<div class="s-i-u_result-code" style="text-indent:-' . $instance['level'] . 'em;">';
                if( $instance[ 'level' ] > 0 ){
                    for( $i = 0; $i < $instance[ 'level' ]; $i++ ){
                        $html .= '<span class="dashicons dashicons-minus"></span>';
                    }
                }
                $code = '[<strong class="s-i-u_result-tagcode">' . esc_html( $tag ) . '</strong>';
                if( !empty( $instance[ 'atts' ] ) ){
                    $code .= ' ' . $instance[ 'atts' ];
                }
                if( $instance[ 'self_term' ] ){
                    $code .= '/]';
                }else{
                    $code .= ']';
                    if( !empty( $instance[ 'content' ] ) ){
                        $code .= '<span title="' . esc_attr( $instance[ 'content' ] ) . '"> &hellip;&nbsp;</span>';
                        $code .= '[/' . esc_html( $tag ) . ']';
                    }
                }
                $html .= $code . '</div></div>';

                //where the shortcode was found...
                $html .= '<div class="s-i-u_result-location"><span style="padding-left:'.$instance['level'].'em;"></span><em>' . $instance[ 'found_in' ] . '</em></div>';

                //the shortcode provider, and a plugin/theme name if possible...
                $html .= '<div class="s-i-u_result-provider">';
                if( isset( $this->alt_providers[ $this->tagInfo[ $tag ][ 'provider' ] ] ) ){
                    $html .= $this->alt_providers[ $this->tagInfo[ $tag ][ 'provider' ] ];
                }else{
                    $html .= $this->providers[ $this->tagInfo[ $tag ][ 'provider' ] ];
                }
                if( !empty( $this->tagInfo[ $tag ][ 'name' ] ) ){
                    $html .= ' : ' . esc_html( $this->tagInfo[ $tag ][ 'name' ] );
                }
                $html .= '</div>';

                //end the row...
                $html .= '</div>';
            }
            //close the result rows & block...
            $html .= "\n" . '</div></div>';

            //clear down tags...
            $this->tags = array();

            return $html;

        }

        /**
         * get "found in" depending on post type
         */
        private function _found_in( $location, $post_type ){

            return empty( $this->alt_locations[ $post_type ][ $location ] ) ? $this->locations[ $location ] : $this->alt_locations[ $post_type ][ $location ];

        }

        /**
         * get the current/parent theme(s)
         */
        private function _get_current_theme(){

            //if we haven't already got it, get it...
            if( $this->currentTheme === false ){
                $theme = wp_get_theme();
                $this->currentTheme = array(
                    'name' => $theme->display('Name'),
                    'path' => wp_normalize_path( $theme->stylesheet_dir ),
                    'parent' => false
                );
                if( ( $parent = $theme->parent() ) !== false ){
                    $this->currentTheme['parent'] = array(
                        'name' => $parent->display('Name'),
                        'path' => wp_normalize_path( $parent->stylesheet_dir )
                    );
                }
            }

        }

        /**
         * get the data for installed plugins
         */
        private function _get_plugin_data(){

            //if we haven't already got all the necessary plugin data to test against, get it now...
            if( $this->pluginData === false ){
                if( !function_exists( 'get_plugins' ) ){
                    require_once ABSPATH . 'wp-admin/includes/plugin.php';
                }
                $this->pluginData = array();
                //the key is usually 'folder/file.php', eg. 'shortcodes-in-use/shortcodes-in-use.php'
                //but could be just 'file.php' when the plugin is a single file residing in the plugins
                //folder itself, eg. 'hello.php' for the "Hello Dolly" plugin
                foreach( get_plugins() as $k => $v ){
                    $this->pluginData[ $k ] = $v['Name'];
                    $pluginFolder = dirname( $k );
                    if( !empty( $pluginFolder ) && $pluginFolder != '.' ){
                        $this->pluginData[ $pluginFolder ] = $v['Name'];
                    }
                }
            }

        }

        /**
         * try to find out where, or to what, the shortcode might belong
         * NB : if the shortcode is inactive, there's little we can do except report 'unknown'
         * @param string $tag The shortcode tag
         * @param string|array $globalTagItem Optional, the value from the global $shortcode_tags array
         */
        private function _get_shortcode_info( $tag, $globalTagItem = false ){

            if( isset( $this->tagInfo[ $tag ] ) ){
                return;
            }elseif( empty( $globalTagItem ) ){
                //it's not a known tag...
                //could belong to an inactive plugin or theme, or be left over having removed a plugin or theme, or not actually a shortcode?...
                $this->tagInfo[ $tag ] = array(
                    'provider' => 'unknown',
                    'name' => ''
                    );
                return;
            }

            //if the (apparent) shortcode is not known to WordPress then there's no callback to track down...
            if( class_exists( 'ReflectionFunction' ) ){

                //handle the callable function being specified as "someClass::someMethod"...
                if( is_string( $globalTagItem ) && strpos( $globalTagItem, '::' ) ){
                    $globalTagItem = explode( '::', trim( $globalTagItem ) );
                }

                if( is_string( $globalTagItem ) ){
                    try{
                        $reflect = new ReflectionFunction( $globalTagItem );
                    } catch( ReflectionException $Exception ){}
                }elseif( is_array( $globalTagItem ) ){
                    try{
                        $reflect = new ReflectionMethod( $globalTagItem[ 0 ], $globalTagItem[ 1 ] );
                    } catch( ReflectionException $Exception ){}
                }

                if( !empty( $reflect ) ){

                    //use normalized path...
                    $filename = wp_normalize_path( $reflect->getFileName() );

                    //check for internal to WordPress...
                    if( strpos( $filename, $this->wpIncludesPath ) === 0 ){
                        $this->tagInfo[ $tag ] = array(
                            'provider' => 'wordpress',
                            'name' => ''
                        );
                    }

                    //...check theme...
                    //NB: can only be current theme (or possibly parent of current child theme)
                    //    because otherwise it wouldn't be in $shortcode_tags
                    if( !isset( $this->tagInfo[ $tag ] ) ){
                        $this->_get_current_theme();
                        //child theme?...
                        if( strpos( $filename, $this->currentTheme['path'] ) === 0 ){
                            $this->tagInfo[ $tag ] = array(
                                'provider' => 'theme',
                                'name' => $this->currentTheme['name'],
                                'theme' => $this->currentTheme['path']
                            );
                        //parent theme?...
                        }elseif( $this->currentTheme['parent'] !== false &&
                                strpos( $filename, $this->currentTheme['parent']['path'] ) === 0 ){
                            $this->tagInfo[ $tag ] = array(
                                'provider' => 'theme',
                                'name' => $this->currentTheme['parent']['name'],
                                'theme' => $this->currentTheme['parent']['path']
                            );
                        }
                    }

                    //...check plugins...
                    if( !isset( $this->tagInfo[ $tag ] ) ){
                        //try to get plugin basename :
                        //- if it's a plugin the path will be relative to the plugins dir, eg. "file.php", or "folder/file.php", or "folder/folder/file.php"
                        //- if it's not a plugin then it will be the full system path (normalized), eg. "D:/xampp/htdocs/wordpress/wp-content/themes/mine/functions.php" or whatever
                        //NB: last thing plugin_basename() does is trim( $file, '/'), returning $file
                        $plugin = plugin_basename( $filename );
                        //if the filename returns unchanged (bar trimmed slashes) then it's not a plugin...
                        if( !empty( $plugin ) && $plugin != trim( $filename, '/' ) ){
                            $this->_get_plugin_data();
                            $pluginFolder = explode( '/', $plugin );
                            $pluginFolder = $pluginFolder[ 0 ];
                            if( array_key_exists( $pluginFolder, $this->pluginData ) ){
                                $this->tagInfo[ $tag ] = array(
                                    'provider' => 'plugin',
                                    'name' => $this->pluginData[ $pluginFolder ],
                                    'plugin' => $pluginFolder
                                );
                            }
                        }
                    }
                }
            }

            if( !isset( $this->tagInfo[ $tag ] ) ){
                //could :
                // - belong to an inactive plugin or theme, or
                // - only get added/registered when *not* in admin, or
                // - be left over having removed a plugin or theme, or
                // - not actually be a shortcode?...
                $this->tagInfo[ $tag ] = array(
                    'provider' => 'unknown',
                    'name' => ''
                );
            }

        }

        /**
         * get a regex pattern from WP, but instead of matching shortcode|shortcode|shortcode, match anything that looks like a shortcode
         * ref: get_shortcode_regex() in wp-includes/shortcodes.php
         * @return string Pattern
         */
        private function _get_shortcode_regex(){
            global $shortcode_tags;

            //store the global temporarily...
            $temp = $shortcode_tags;
            //get a unique string...
            $uniqStr = uniqid();
            //...and set the global to one element with just the unique string...
            $shortcode_tags = array( "$uniqStr" => '' );
            //now get the regex pattern from WordPress...
            $pattern = get_shortcode_regex();
            //...and reinstate the global...
            $shortcode_tags = $temp;
            unset( $temp );
            //replace the unique string with a generic "match all"...
            $this->regexPattern = '/' . str_replace( $uniqStr, '[\\w-]+', $pattern ) . '/s';

        }

        /**
         * get a list of post types, with certain exclusions (eg. builtin revisions)
         */
        private function _list_post_types(){
            global $wp_post_types;

            if( empty( $this->postTypes ) ){
                $this->postTypes = get_post_types();
                if( ( $i = array_search( 'revision', $this->postTypes ) ) !== false ){
                    unset( $this->postTypes[ $i ] );
                }
                sort( $this->postTypes );
            }
            return $this->postTypes;

        }

        /**
         * scan text for (outer) shortcodes
         * @param string $toScan The text to scan
         * @param string $foundIn Text indicating where this shortcode was found (eg. content or meta data)
         * @param integer $level Nested shortcodes level
         * @return integer Number found
         */
        private function _scan_for_tags( $toScan, $foundIn, $level = 0 ){

            if( preg_match_all( $this->regexPattern, $toScan, $m ) > 0 ){
                return $this->_scan_possible_tags( $m, $foundIn, $level );
            }
            return 0;

        }

        /**
         * scan the regexp matches for eligible shortcodes
         * @param array $m The results of the pattern match, using WP's regexp (very slightly modified, but same capturing brackets)
         * @param string $foundIn Text indicating where this shortcode was found (eg. content or meta data)
         * @param integer $level Nested shortcodes level
         * @return integer Number found
         */
        private function _scan_possible_tags( $m, $foundIn, $level ){

            $rtn = 0;
            //tags are in $m[2], attributes are in $m[3], and any content will be in $m[5]...
            foreach( $m[ 2 ] as $i => $tag ){
                //if shortcode is escaped with double square brackets (eg. [[foo]]), ignore it...
                if( $m[ 1 ][ $i ] == '[' && $m[ 6 ][ $i ] == ']' ){
                    continue;
                }
                $levelIncrease = 0;
                //this returns TRUE if there's no search specified, or the tag matches the search...
                if( $this->_tag_matches_search( $tag ) ){
                    $this->_get_shortcode_info( $tag );
                    if( $this->options[ 'anyTag' ]
                            || in_array( $tag, $this->options[ 'tag' ] )
                            || in_array( $this->tagInfo[ $tag ][ 'provider' ], $this->options[ 'provider' ] )
                            || in_array( $this->tagInfo[ $tag ][ 'provider' ] . '/' . strtolower( $this->tagInfo[ $tag ][ 'name' ] ), $this->options[ 'name' ] )
                            //when running from a shortcode, this allows searching for name="plugin/custom-menu-wizard",
                            //where the 'name' part is actually the name of the plugin's folder rather than the plugin's
                            //actual name (which would be name="plugin/Custom Menu Wizard", and is what the admin form
                            //uses). it only applies to the plugin & theme providers ...
                            || ( $this->runningShortcode
                                && !empty( $this->tagInfo[ $tag ][ $this->tagInfo[ $tag ][ 'provider' ] ] )
                                && in_array( $this->tagInfo[ $tag ][ 'provider' ] . '/' . strtolower( $this->tagInfo[ $tag ][ $this->tagInfo[ $tag ][ 'provider' ] ] ), $this->options[ 'name' ] )
                                )
                            ){
                        $content = empty( $m[ 5 ][ $i ] ) ? '' : str_replace( array( "\r", "\n" ), '', $m[ 5 ][ $i ] );
                        if( strlen( $content ) > 300 ){
                            $content = substr( $content, 0, 300 ) . '......';
                        }
                        $this->tags[] = array(
                            'tag' => $tag,
                            'found_in' => $foundIn,
                            'atts' => trim( $m[ 3 ][ $i ] ),
                            'self_term' => !empty( $m[ 4 ][ $i ] ),
                            'content' => $content,
                            'level' => $level
                            );
                        $levelIncrease = 1;
                        $rtn++;
                    }
                }
                if( !empty( $m[ 5 ][ $i ] ) ){
                    $rtn += $this->_scan_for_tags( $m[ 5 ][ $i ], $foundIn, $level + $levelIncrease );
                }
            }
            return $rtn;

        }

        /**
         * sanitizes request options
         * @param array $opts Array of submitted options
         */
        private function _set_options( $opts ){

            $this->options = array(
                'search' => array(), //'s'
                'location' => array(), //l[]
                'post_type' => array(), //pt[]
                'provider' => array(), //p[]
                'name' => array(), //n[]
                'tag' => array() //t[]
                );

            //sanitize the search string, which can be space- and/or comma-separated, and with no min length of string...
            if( !empty( $opts[ 's' ] ) ){
                $this->options[ 'search' ] = preg_split(
                    '/[\s,]+/',
                    str_replace( array( '[/', '[', '/]', ']' ), '', strtolower( trim( sanitize_text_field( $opts[ 's' ] ) ) ) ),
                    -1,
                    PREG_SPLIT_NO_EMPTY
                    );
            }

            //check against my list of locations...
            //NB : I specify the locations so I'll allow case-insensitive
            if( isset( $opts[ 'l' ] ) && is_array( $opts[ 'l' ] ) ){
                foreach( $opts[ 'l' ] as $v ){
                    $v = strtolower( $v );
                    if( isset( $this->locations[ $v ] ) ){
                        $this->options[ 'location' ][] = $v;
                    }
                }
            }
            //all locations set == none set...
            if( !empty( $this->options['location'] ) ){
                $diff = array_diff( array_keys( $this->locations ), $this->options['location'] );
                if( empty( $diff ) ){
                    $this->options['location'] = array();
                }
            }

            //check against retrieved list of post types...
            //NB : post types are out my control and part of the db query so they have to be case-sensitive
            if( isset( $opts[ 'pt' ] ) && is_array( $opts[ 'pt' ] ) ){
                $this->_list_post_types();
                foreach( $opts[ 'pt' ] as $v ){
                    if( in_array( $v, $this->postTypes ) ){
                        $this->options[ 'post_type' ][] = $v;
                    }
                }
            }
            //all post types set == none set...
            if( !empty( $this->options['post_type'] ) ){
                $diff = array_diff( $this->postTypes, $this->options['post_type'] );
                if( empty( $diff ) ){
                    $this->options['post_type'] = array();
                }
            }

            //check against my list of providers...
            //NB : I specify the providers so I'll allow case-insensitive
            if( isset( $opts[ 'p' ] ) && is_array( $opts[ 'p' ] ) ){
                foreach( $opts[ 'p' ] as $v ){
                    $v = strtolower( $v );
                    if( isset( $this->providers[ $v ] ) ){
                        $this->options[ 'provider' ][] = $v;
                    }
                }
            }

            //name comes in as provider/name so check provider and store as provider/name...
            //NB : provider - allow case-insensitive (as above)
            //     name - I'm going to allow case-insensitive
            if( isset( $opts[ 'n' ] ) && is_array( $opts[ 'n' ] ) ){
                foreach( $opts[ 'n' ] as $v ){
                    $n = explode( '/', $v );
                    $p = strtolower( array_shift( $n ) );
                    $n = empty( $n ) ? '' : strtolower( implode( '/', $n ) );
                    if( !empty( $n ) && !empty( $p ) && isset( $this->providers[ $p ] ) ){
                        $this->options[ 'name' ][] = $p . '/' . $n;
                        //a name automatically knocks out it's provider, so just in case someone's buggering about...
                        if( ( $i = array_search( $p, $this->options['provider'] ) ) !== false ){
                            unset( $this->options['provider'][ $i ] );
                        }
                    }
                }
                $this->options[ 'provider' ] = array_values( $this->options[ 'provider' ] );
            }

            //specific tags...
            //NB : case-sensitive
            if( isset( $opts[ 't' ] ) && is_array( $opts[ 't' ] ) ){
                foreach( $opts[ 't' ] as $v ){
                    if( isset( $this->tagInfo[ $v ] ) ){
                        $this->options[ 'tag' ][] = $v;
                        $p = $this->tagInfo[ $v ]['provider'];
                        //a tag automatically knocks out that tag's provider/name, so just in case someone's buggering about...
                        if( !empty( $this->tagInfo[ $v ]['name'] ) ){
                            //the fact that the name is set means that the provider has already been
                            //checked and removed if set!
                            $pn = $p . '/' . strtolower( $this->tagInfo[ $v ]['name'] );
                            if( ( $i = array_search( $pn, $this->options['name'] ) ) !== false ){
                                unset( $this->options['name'][ $i ] );
                            }
                        //also need to check provider because not all tags have a provider/name between
                        //them and the provider, and a tag automatically knocks out that tag's provider...
                        }elseif( !empty( $p ) && ( $i = array_search( $p, $this->options['provider'] ) ) !== false ){
                            unset( $this->options['provider'][ $i ] );
                        }
                    }
                }
                $this->options[ 'provider' ] = array_values( $this->options[ 'provider' ] );
                $this->options[ 'name' ] = array_values( $this->options[ 'name' ] );
            }
            //all providers set == none set...
            if( !empty( $this->options['provider'] ) ){
                $diff = array_diff( array_keys( $this->providers ), $this->options['provider'] );
                if( empty( $diff ) ){
                    $this->options['provider'] = array();
                }
            }

            //delete or fetch transient?...
            $this->usingCache = false;
            //...leave cache alone if running shortcode...
            if( $this->runningShortcode ){
                $this->cache = false;
            }else{
                if( !empty( $opts[ 'cc' ] ) ){
                    delete_transient( $this->menuPage );
                }else{
                    $this->cache = get_transient( $this->menuPage );
                }
            }

            $this->options[ 'anyTag' ] = empty( $this->options[ 'provider' ] ) &&
                    empty( $this->options[ 'name' ] ) && empty( $this->options[ 'tag' ] );

        }

        /**
         * sets tagInfo by running through global $shortcode_tags
         */
        private function _set_taginfo(){
            global $shortcode_tags;

            foreach( array_keys( $shortcode_tags ) as $tag){
                $this->_get_shortcode_info( $tag, $shortcode_tags[ $tag ] );
            }
            ksort( $this->tagInfo );

        }

        /**
         * caseless search for strings within tag
         * @param string $tag Tag to check
         * @return boolean
         */
        private function _tag_matches_search( $tag ){

            if( empty( $this->options[ 'search' ] ) ){
                return true;
            }
            $tag = strtolower( $tag );
            foreach( $this->options[ 'search' ] as $v ){
                if( strpos( $tag, $v ) !== false ){
                    return true;
                }
            }
            return false;

        }

    } //end class Shortcodes_In_Use_Plugin

}
