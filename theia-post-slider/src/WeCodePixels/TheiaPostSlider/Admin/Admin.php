<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Admin;

use WeCodePixels\TheiaPostSlider\Options;

class Admin {
    protected $page;
    protected $tabs;
    protected $currentTab;

    public function __construct() {
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    public function admin_init() {
        register_setting( 'tps_options_dashboard', 'tps_dashboard', [ $this, 'validate' ] );
        register_setting( 'tps_options_general', 'tps_general', [ $this, 'validate' ] );
        register_setting( 'tps_options_nav', 'tps_nav', [ $this, 'validate' ] );
        register_setting( 'tps_options_carousel', 'tps_carousel', [ $this, 'validate' ] );
        register_setting( 'tps_options_advanced', 'tps_advanced', [ $this, 'validate' ] );
        register_setting( 'tps_options_advanced', 'tps_advanced_post_types', [ $this, 'validate' ] );
        register_setting( 'tps_options_troubleshooting', 'tps_troubleshooting', [ $this, 'validate' ] );

        $this->tabs = array(
            'dashboard'       => array(
                'title' => __( "Dashboard", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Dashboard'
            ),
            'general'         => array(
                'title' => __( "General", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\General'
            ),
            'navigationBar'   => array(
                'title' => __( "Navigation Bar", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\NavigationBar'
            ),
            'carousel'        => array(
                'title' => __( "Carousel", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Carousel'
            ),
            'advanced'        => array(
                'title' => __( "Advanced", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Advanced'
            ),
            'troubleshooting' => array(
                'title' => __( "Troubleshooting", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Troubleshooting'
            ),
            'account'         => array(
                'title' => __( "Account", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Account'
            ),
            'contact'         => array(
                'title' => __( "Contact Us", 'theia-post-slider' ),
                'class' => __NAMESPACE__ . '\\Contact'
            )
        );

        // Remove contact tab for theme licenses.
        global $theia_post_slider_fs;
        if ( $theia_post_slider_fs->is_plan( 'theme_bundle', true ) ) {
            unset( $this->tabs['contact'] );
        }

        // Allow add-ons to add other tabs.
        $this->tabs = apply_filters( 'tps_admin_tabs', $this->tabs );

        if ( array_key_exists( 'tab', $_GET ) && array_key_exists( $_GET['tab'], $this->tabs ) ) {
            $this->currentTab = $_GET['tab'];
        } else {
            $this->currentTab = 'dashboard';
        }

        $class      = $this->tabs[ $this->currentTab ]['class'];
        $this->page = new $class;
    }

    public function admin_menu() {
        add_options_page( 'Theia Post Slider Settings', 'Theia Post Slider', 'manage_options', 'theia-post-slider', [
            $this,
            'do_page'
        ] );
    }

    public function do_page() {
        ?>

        <div class="wrap">
            <h2 class="theiaPostSlider_adminTitle">
                <a href="https://wecodepixels.com/theia-post-slider-for-wordpress/?utm_source=theia-post-slider-for-wordpress"
                   target="_blank"><img src="<?php echo plugins_url( '/assets/images/theia-post-slider-thumbnail.png', THEIA_POST_SLIDER_MAIN ); ?>"></a>

                Theia Post Slider

                <a class="theiaPostSlider_adminLogo"
                   href="https://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress"
                   target="_blank"><img src="<?php echo plugins_url( '/assets/images/wecodepixels-logo.png', THEIA_POST_SLIDER_MAIN ); ?>"></a>
            </h2>

            <h2 class="nav-tab-wrapper">
                <?php
                foreach ( $this->tabs as $id => $tab ) {
                    $class = 'nav-tab';
                    if ( $id == $this->currentTab ) {
                        $class .= ' nav-tab-active';
                    }
                    ?>
                    <a href="?page=theia-post-slider&tab=<?php echo $id; ?>"
                       class="<?php echo $class; ?>"><?php echo $tab['title']; ?></a>
                    <?php
                }
                ?>
            </h2>

            <?php
            $showPreview = property_exists( $this->page, 'showPreview' ) && $this->page->showPreview;

            // Must enqueue this $(document).ready script first.
            if ( $showPreview ) {
                $sliderOptions = array(
                    'slideContainer'    => '#tps_slideContainer',
                    'nav'               => '#tps_nav_upper, #tps_nav_lower',
                    'navText'           => Options::get( 'navigation_text' ),
                    'helperText'        => Options::get( 'helper_text' ),
                    'transitionEffect'  => Options::get( 'transition_effect' ),
                    'transitionSpeed'   => (int) Options::get( 'transition_speed' ),
                    'keyboardShortcuts' => true,
                    'themeType'         => Options::get( 'theme_type' ),
                    'prevText'          => Options::get( 'prev_text' ),
                    'nextText'          => Options::get( 'next_text' ),
                    'prevFontIcon'      => Options::get_font_icon( is_rtl() ? 'right' : 'left' ),
                    'nextFontIcon'      => Options::get_font_icon( is_rtl() ? 'left' : 'right' ),
                    'buttonWidth'       => Options::get( 'button_width' ),
                    'numberOfSlides'    => 3,
                    'is_rtl'            => is_rtl()
                );

                ?>
                <div data-theia-post-slider-options="<?= htmlspecialchars( json_encode( $sliderOptions ), ENT_QUOTES ); ?>"></div>
                <?php
            }
            ?>

            <div class="theiaPostSlider_adminContainer <?php echo $showPreview ? 'hasPreview' : ''; ?>">
                <div class="theiaPostSlider_adminContainer_left">
                    <div class="theia-post-slider-admin-<?= $this->currentTab ?>">
                        <?php
                        $this->page->echoPage();
                        ?>
                    </div>
                </div>

                <div class="theiaPostSlider_adminContainer_right">
                    <?php
                    if ( $showPreview == true ) {
                        ?>
                        <h3><?php _e( "Live Preview", 'theia-post-slider' ); ?></h3>
                        <div class="theiaPostSlider_adminPreview">
                            <?php
                            echo \WeCodePixels\TheiaPostSlider\NavigationBar::get_navigation_bar( array(
                                'currentSlide' => 1,
                                'totalSlides'  => 3,
                                'id'           => 'tps_nav_upper',
                                'class'        => '_upper',
                                'style'        => in_array( Options::get( 'nav_vertical_position' ), array(
                                    'top_and_bottom',
                                    'top'
                                ) ) ? '' : 'display: none'
                            ) );
                            ?>
                            <div id="tps_slideContainer" class="theiaPostSlider_slides">
                                <?php
                                PreviewSlider::echoPreviewSlider();
                                ?>
                            </div>
                            <?php
                            echo \WeCodePixels\TheiaPostSlider\NavigationBar::get_navigation_bar( array(
                                'currentSlide' => 1,
                                'totalSlides'  => 3,
                                'id'           => 'tps_nav_lower',
                                'class'        => '_lower',
                                'style'        => in_array( Options::get( 'nav_vertical_position' ), array(
                                    'top_and_bottom',
                                    'bottom'
                                ) ) ? '' : 'display: none'
                            ) );
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function validate( $input ) {
        return $input;
    }
}
