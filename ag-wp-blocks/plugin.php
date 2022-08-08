<?php 
/**
 * Plugin Name: Ag WP Blocks
 * Description: Custom Gutenberg blocks 
 * 
 */


 if( ! defined( 'ABSPATH')) {
     exit;
 }



function jci_blocks_categories( $categories, $post) {
    return array_merge(
        $categories,
        array( 
            array( 
                'slug' => 'jci-category',
                'title' => __('Livability', 'jci_blocks'),
            )
        )
            );
}
 add_filter('block_categories_all', 'jci_blocks_categories', 10, 2);

 



 function jci_blocks_register_block_type($block, $options = array()){
    register_block_type(
        'jci-blocks/' . $block,
        array_merge(
            array(
                'editor_script' => 'jci-blocks-editor-script',
                'editor_style' => 'jci-blocks-editor-style',
                'script' => 'jci-blocks-script',
                'style' => 'jci-blocks-style',
            ), 
            $options
        )
        );   
 }

 function jci_block_register() {

    // jci_blocks_register_block_type('firstblock');

    // jci_blocks_register_block_type('secondblock');

    jci_blocks_register_block_type('ad-area-one', array( 
        'render_callback'   => 'jci_blocks_render_ad_one'
    ));

    jci_blocks_register_block_type('ad-area-two', array( 
        'render_callback'   => 'jci_blocks_render_ad_two'
    ));

    jci_blocks_register_block_type('ad-area-three', array(
        'render_callback'   => 'jci_blocks_render_ad_three'
    ));

    // jci_blocks_register_block_type('best-place-masonry', array( 
    //     'render_callback'   => 'jci_blocks_render_best_place_masonry'
    // ));

    

    // jci_blocks_register_block_type('magazine-link', array( 
    //     'render_callback'   => 'jci_blocks_render_magazine_link'
    // ));

    jci_blocks_register_block_type('magazine-articles', array( 
        'render_callback'   => 'jci_blocks_render_magazine_articles'
    ));

    //  jci_blocks_register_block_type('blockquote-section');

  

    // jci_blocks_register_block_type('plac-list', array( 
    //     'render_callback' => 'jci_blocks_render_term_list'
    // ));

   

    jci_blocks_register_block_type('best-place-data', array( 
        'render_callback'   => 'jci_blocks_render_bp_data'
    ));

    jci_blocks_register_block_type('post-title', array( 
        'render_callback'   => 'jci_blocks_render_post_title',
        'attributes'        => array(
            'content'       => array( 
                'type'      => 'string',
                'default'   => ''
            )
        )
    ));

    jci_blocks_register_block_type('madlib', array( 
        'render_callback'   => 'jci_blocks_render_madlib'
    ));

    jci_blocks_register_block_type('onehundred-list', array( 
        'render_callback'   => 'jci_blocks_render_onehundred_list'
    ));

    jci_blocks_register_block_type('breadcrumbs', array( 
        'render_callback'   => 'jci_blocks_render_breadcrumbs'
    ));

    // jci_blocks_register_block_type('-posts');

    jci_blocks_register_block_type('featured-image', array( 
        'render_callback'   => 'jci_blocks_render_featured_image'
    ));

    jci_blocks_register_block_type('editable-post-title');

    jci_blocks_register_block_type('section-header');

    jci_blocks_register_block_type('sponsored-by', array( 
        'render_callback'   => 'jci_blocks_render_sponsored_by'
    ));

    jci_blocks_register_block_type('excerpt-and-post-author', array( 
        'render_callback'   => 'jci_blocks_render_excerpt_and_post_author'
    ));

    // jci_blocks_register_block_type('author-block', array( 
    //     'render_callback'   => 'jci_blocks_render_author_block'
    // ));

    jci_blocks_register_block_type('magazine', array( 
        'render_callback'   => 'jci_blocks_render_magazine',
    ));

    // jci_blocks_register_block_type('contentcard', array( 
    //     'render_callback' => 'jci_blocks_render_content_card_block',
    // ));

    // jci_blocks_register_block_type('onehundredslider');

    jci_blocks_register_block_type('onehundredslider', array(
        'render_callback' => 'jci_blocks_render_posts_block'
    ));

    // jci_blocks_register_block_type('best-place-title-section', array( 
    //     'render_callback' => 'jci_blocks_render_bp_title'
    // ));

    jci_blocks_register_block_type('info-box');

    jci_blocks_register_block_type('info-box-with-button');

    jci_blocks_register_block_type('suggested-posts-404', array(
        'render_callback' => 'jci_blocks_suggested_posts'
    ));

    jci_blocks_register_block_type('bp-sponsor', array(
        'render_callback'   => 'jci_blocks_bp_sponsor'
    ));

    jci_blocks_register_block_type('city-list', array(
        'render_callback'   => 'jci_blocks_city_list'
    ));

    jci_blocks_register_block_type('city-301', array(
        'render_callback'   => 'jci_blocks_city_301'
    ));

    jci_blocks_register_block_type('city-map', array(
        'render_callback'   => 'jci_blocks_city_map'
    ));

    jci_blocks_register_block_type('mag-sponsor', array(
        'render_callback'   => 'jci_blocks_mag_sponsor'
    ));

    jci_blocks_register_block_type('link-place-to-top-100', array(
        'render_callback'   => 'jci_blocks_link_place_to_top_100'
    ));

    // jci_blocks_register_block_type('onehundred-paginated', array(
    //     'render_callback'   => 'jci_blocks_onehundred_paginated'
    // ));

    wp_register_script( 'jci-blocks-editor-script', 
    plugins_url('dist/editor.js', __FILE__), array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components', 'wp-block-editor', 'wp-data', 'wp-api-fetch', 'wp-compose') );

    wp_register_script( 'jci-blocks-script', 
    plugins_url('dist/script.js', __FILE__), array() );

    wp_register_style( 'jci-blocks-editor-style', plugins_url('dist/editor.css', __FILE__), array('wp-edit-blocks') );

    wp_register_style( 'jci-blocks-style', plugins_url('dist/script.css', __FILE__) );


    // jci_blocks_register_block_type('quote', array( 
    //     'render_callback'   => 'jci_blocks_render_quote'
    // ));

    // jci_blocks_register_block_type('scroll-load-posts', array(  
    //     'render_callback'   => 'jci_blocks_render_scroll_load_posts'
    // ));

    }

 add_action( 'init', 'jci_block_register');

//  function jci_blocks_enqueue_scripts() {
    // wp_enqueue_script( 'jci-blocks-custom', plugin_dir_url(__FILE__) . 'dist/jcicustom.js', array('jquery'), null, true );
    // wp_enqueue_script( 'slick', plugin_dir_url( __FILE__ ) . 'js/slick.min.js', array('jquery'), null, true);
//  }

//  add_action( 'wp_enqueue_scripts', 'jci_blocks_enqueue_scripts'  );

 function jci_blocks_render_posts_block(  ) {
    $currentID = get_the_ID();
    $parent = get_post_parent($currentID);
    $parentLink = get_the_permalink( $parent->ID);
    $args = array(  
        'posts_per_page' => -1,
        'post_type'     => 'best_places',
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        'post_parent'   => $parent->ID,
    );
    $query = new WP_Query($args);
    $current_rank = get_field('bp_rank', $currentID);
    // SPLIT THE QUERY ARRAY SO CURRENT BP IS FIRST 
    $queryArray = $query->posts;
    $current_rank = $current_rank - 1;
    $row2 = array_splice($queryArray, $current_rank);
    $row1 = array_splice($queryArray, 0, $current_rank );
    $merged = array_merge($row2, $row1);
    $query->posts = $merged;
    $posts = '';
    if ($query->have_posts()) {
        $posts .= '<div class="list-carousel-container custom-block"><ul class="wp-block-jci_blocks-blocks list-carousel">';
        while ( $query->have_posts()):  
            $query->the_post();
            $ID = get_the_ID(  );
            $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url($ID, 'rel_article') : '';
            $title = __(get_the_title(), 'jci_blocks');
            $rank = get_field('bp_rank');
            // $posts .= '<li><a href="' . esc_url( get_the_permalink()) . '" >' . get_the_title() . '</a></li>';
            $posts .= '<li class="lc-slide">';
            $posts .= '<a href="'.get_the_permalink().'">';
            $posts .= '<div class="lc-slide-inner"><div class="lc-slide-content">';
            $posts .= '<div class="lc-slide-img" style="background-image: url(' . $thumb_url . ')">';
            $posts .= '<p class="slide-count">' . $rank . '</p>';
            $posts .= '</div>';
            $posts .= '<div><h4 class="city-state">' . $title . '</h4></div>';
            $posts .= '</div></div>';
            $posts .= '</a></li>';
        endwhile;
        $posts .= '</ul>';
        $posts .= '<button class="list-carousel-button"><a href="'.$parentLink.'">Go Back To List</a></button>';
        $posts .= '</div>';
        return $posts;
        die();
        // wp_reset_postdata();
    } else {
        return '<div>' . __("No Posts Found", "jci_blocks") . '</div>';
    }
    // echo '<button>Here is a button</button>';
 }


 function jci_blocks_render_content_card_block(  $attributes ) {
    // var_dump($attributes);
    // return 'Id is ' . $attributes['postId'];
    
    if ($attributes && $attributes['postId']) {
        $id = $attributes['postId'];
        $title = get_the_title($id); 

        $html = '<a href="'.get_the_permalink( $id ).'">';
        $html .= '<div class="jci-content-card-block" style="background-image: url('.get_the_post_thumbnail_url($id, 'rel_article').');">';
        $html .= '<h2>'.$title.'</h2>';
        $html .= '</div></a>';

        return $html;
       
     } else {
        return 'No ID is set';
    }
 }

 function jci_blocks_render_bp_data () {
     $livscore = get_field('ls_livscore');
     $civic = get_field('ls_civic');
     $demographics = get_field('ls_demographics');
     $economy = get_field('ls_economy');
     $education = get_field('ls_education');
     $health = get_field('ls_health');
     $housing = get_field('ls_housing');
     $infrastructure = get_field('ls_infrastructure');
    $amenities = get_field('amenities');
    $remote_ready = get_field('remote_ready');
     $the_ID = get_the_ID();
     $thumb = get_the_post_thumbnail_url($the_ID, 'medium_large');
     $how_we_calculate_link = get_the_permalink( '92097' ); 
    //  $how_we_calculate_link = get_the_permalink( '88506' );
     ?>
     <style>
     .bp-data-image {
         background-image: url("<?php echo $thumb; ?>");
     }
     </style>
     <?php $html = <<< EOF
     <div class="bp-data-container">
     <div class="bp-data-image"></div>
     <table class="livscore-table">
        <thead>
            <tr>
                <th colspan=2><span class="livscore">liv score</span><br /><span class="livscore-number">$livscore</span><br /><a href="'.$how_we_calculate_link.'" class="livscore-link">How We Calculate Our Data</a></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Civics</td>
                <td>$civic</td>
            </tr>
            <tr>
                <td>Demographics</td>
                <td>$demographics</td>
            </tr>
            <tr>
                <td>Economy</td>
                <td>$economy</td>
            </tr>
            <tr>
                <td>Education</td>
                <td>$education</td>
            </tr>
            <tr>
                <td>Health</td>
                <td>$health</td>
            </tr>
            <tr>
                <td>Housing</td>
                <td>$housing</td>
            </tr>
            <tr>
                <td>Infrastructure</td>
                <td>$infrastructure</td>
            </tr>    
            <tr>
                <td>Amenities</td>
                <td>$amenities</td>
            </tr>   
            <tr>
                <td>Remote Ready </td>
                <td>$remote_ready</td>
            </tr>                                                         
        </tbody>
    </table>
    </div>
EOF;
     return $html;
     die();
    // return 'hello world';
    
 }

 function jci_blocks_render_post_title($attributes) {
     $ID = get_the_ID();
    //  print_r($attributes);
    //  print_r($secondarg);
    if (strlen($attributes["content"]) > 2) {
        $text = $attributes["content"];
    } else {
        $text = get_the_title( $ID);
    }
    //  $text = $attributes["content"];
    //  echo 'string length '.strlen($text);
    //  if (  strlen($text) < 2) {
    //      return '<h1 class="h2">'.get_the_title( $ID).'</h1>';
    //  } else {
         return '<h1 class="h1">'.$text.'</h1>';
    //  }
     die();
 }

 function jci_blocks_render_madlib() {
    $ID = get_the_ID();
    $title = get_the_title( $ID );
    // $county?
    $population = get_field('city_population', $ID);
    $income = get_field('city_household_income', $ID);
    $homeValue = get_field('city_home_value', $ID);
    $startOverLink = get_permalink( 18680);
    $gtkLink = get_permalink(19038);
    $mymLink = get_permalink( 70453 );

    
    $html = '<p>Looking to move to '.$title.'? You’ve come to the right place. Livability helps people find their perfect places to live, and we’ve got everything you need to know to decide if moving to '.$title.' is right for you.</p>';

    $html .= '<p>Let’s start with the basics: '.$title.' has a population of '.$population.'. What about cost of living in '.$title.'? The median income in '.$title.' is $'.$income.' and the median home value is $'.$homeValue.'.</p>';
    
    $html .= '<p>Read on to learn more about '.$title.', and if you’d like some tips and advice for making your big move, check out our <a href="'.$mymLink.'">Make Your Move</a> page, where you’ll find all kinds of stories and insights including <a href="'.$startOverLink.'">How to Start Over in a New City</a>, <a href="'.$gtkLink.'">Tips for Getting to Know a New City Before You Move</a> and so much more.</p>';

return $html;
die();
    
 }

 function jci_blocks_render_onehundred_list() {
    $currentID = get_the_ID();
    // $child_array = array();
    // $children = get_posts(array(
    //     'post_type'     => 'best_places',
    //     'post_parent'   => $currentID,
    //     'post_status'   => 'publish',
    //     'posts_per_page' => 100
    // ));
    // This was just done to accomodate ALM plugin limitation
    // foreach($children as $child) {
    //     $child_array[] = $child->ID;
    // }
    // print_r($child_array);
    // $list = implode(', ', $child_array);
    $args = array( 
        'post_type'     => 'best_places',
        'posts_per_page'=> 10,
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        // 'post__in'  => $child_array,
        'post_parent'   => $currentID,
        'paged'     => 1
    ); ?>
    <script>

    window.ohlObj = {};
    Object.assign(window.ohlObj, {current_page: '2'});
    Object.assign(window.ohlObj, {parent: <?php echo $currentID; ?>});
    // Object.assign(window.ohlObj, {children: <?php //echo json_encode($child_array); ?>});
    </script>


    <?php $ohl_query = new WP_Query($args);

    $html = '';
    if ($ohl_query->have_posts()): 
        $html .= '<ul class="onehundred-container">';
        while ($ohl_query->have_posts()): $ohl_query->the_post();
        $ID = get_the_ID();
        $place = get_field('place_relationship');
        $population = '';
        if ($place) {
            $population = get_field('city_population', $place[0]);
        }
        $html .= '<li class="one-hundred-list-item"><div class="one-hundred-list-container">';
        // $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" ><div style="background-image: url('.get_the_post_thumbnail_url($ID, 'three_hundred_wide').');">';
        $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" >'.get_the_post_thumbnail($ID, 'rel_article');
        $html .= '<p class="green-circle with-border">'.get_field('bp_rank').'</p></a>';
        $html .= '<div class="ohl-text">';
        $html .= '<a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2>';
        $html .= '<h3 class="uppercase">Livscore: '.get_field('ls_livscore');
        if ($population) {
            $html .= ' | Population: '.$population;
        }
        $html .= '</h3>';
        $html .= '<p>'.get_the_excerpt().'</p></a>';
        $html .= '</div></div></li>';
        endwhile;
        wp_reset_postdata();
        // inner block here

        $html .= '</ul>';
        
    endif;
    



    // $html = '<div class="onhundred-container">';
    // if(function_exists('alm_render')){
        // $html .= alm_render($args);
    // }
    // $html .= do_shortcode( '[ajax_load_more container_type="div" posts_per_page="10"  css_classes="onehundred-container" loading_style="infinite fading-circles" post_type="best_places" meta_key="bp_rank"  meta_compare="IN" post__in="'.$list.'" order="ASC" orderby="meta_value_num" scroll_container=".onehundred-container"]');
    // $html .= '</div>';

    
    return $html;
    die();
 } 

function jci_blocks_render_breadcrumbs() {
    return return_breadcrumbs();
}

function jci_blocks_render_bp_title() {
    $rank = get_field('bp_rank');

    $html = '<h3>#'.$rank.'. '.get_the_title().'</h3>';
    $html .= do_shortcode( '[addtoany]' );
    return $html;
    die();
} 

function jci_blocks_render_quote() {
    $html = '';
    if (have_rows('bp_quote')): 
        while(have_rows('bp_quote')): the_row();
        $text = get_sub_field('bp_quote_text');
        $source = get_sub_field('bp_quote_source');
        $html .= '<div class="quote-block">';
        $html .= $text ? '<p>'.$text.'</p>' : '';
        $html .= $source ? '<p class="bold">'.$source.'</p>' : '';
        $html .= '</div>';
        endwhile;
    endif;
    return $html;
    die();
}

function jci_blocks_render_magazine() {
    $iframe = get_field('calameo_id');
    $html = '';
    if ($iframe) {
        $html = '<div class="magazine-container">';
        $html .= '<iframe src=" //v.calameo.com/?bkcode='.$iframe.'&mode=viewer" width="100%" frameborder="0" scrolling="no" allowtransparency allowfullscreen></iframe>';
        $html .= '</div>';
    }
    
    return $html;
    die();
}

function jci_blocks_render_magazine_link() {
    $html = '';
    $ID = get_the_ID();
    $place_type = get_field('place_type', $ID);
    if ($place_type == 'metro') {
        $place_type = 'Region';
    }
    $args = array(
        'post_type'     => 'liv_magazine',
        'posts_per_page'=> 1,
        'post_status'   => 'publish',
        'meta_query'    => array(  
            'relation'  => 'AND',
            array( 
                'key'   => 'place_relationship',
                'value' => '"'.$ID.'"',
                'compare'=> 'LIKE'
            ),
            array( 
                'key'   => 'mag_place_type',
                'value' => $place_type,
                'compare'=> 'LIKE'
            )
        )
    );
    $mag_query = new WP_Query($args);
    if (count($mag_query->posts) == 1):
        $magID = $mag_query->posts[0]->ID;
        $src = get_the_post_thumbnail_url( $magID, 'rel_article');
        $sponsor = get_field('mag_sponsored_by_title', $magID);
        $sponsor_link = get_field('mag_sponsored_by_title', $magID);
        $html .= '<div class="magazine-link">';
        $html .= '<img src="'.$src.'" />';
        $html .= '<h4>'.get_the_title($magID).'</h4>';
        // $html .= '<p>Place type is '.$place_type.'</p>';
        if ($sponsor) {
            $html .= '<p>This digital edition of the <span class="italic">'.get_the_title($magID).'</span> is sponsored by the <a href="'.$sponsor_link.'">'.$sponsor.'</a>.</p>';
        }
        $html .= '<a style="color: #fff;" href="'.get_the_permalink($magID).'"><button>Read the Magazine</button></a>';
        $html .= '</div>';
    endif;
    return $html;
}

function jci_blocks_render_magazine_articles() {
    $html = '';
    // $ID = get_the_ID();
    if (have_rows('articles')):
        $html .= '<h2 class="green-line">In This Issue</h2>';
        $html .= '<div class="mag-article-list">';
        while (have_rows('articles')): the_row();
        $article = get_sub_field('article');
        if ($article):
        $art_id = $article->ID;
        $img = get_the_post_thumbnail_url( $art_id, 'rel_article' );
        $cat = get_the_category($art_id);
        $html .= '<a  class="mag-article" href="'.get_the_permalink( $art_id ).'" >';
        $html .= '<div style="background-image:linear-gradient(
            180deg,
            rgba(0, 0, 0, 0) 50%,
            rgba(0, 0, 0, 1) 100%
          ), url('.$img.'); " >';
        $html .= get_field('sponsored', $art_id) ? '<p class="sponsored-label">Sponsored</p>' : "";
        
        if ($cat) {$html .= '<h5 class="green-text uppercase">'.$cat[0]->name.'</h5>';}
        $html .= '<h3>'.get_the_title($art_id).'</h3>';
        $html .= '</div></a>';
        endif; 
        endwhile;
        $html .= '</div>';
    endif;
    return $html;
    die();
}

function jci_blocks_render_scroll_load_posts() {
    // $postdate = get_the_date($post->ID);
    // $ID = $post->ID;

    $html = '<h2 class="green-line">More Articles</h2>';
    $html .= '<div class="alm-container">';
    $ID = get_the_ID();
    // $current_date = get_the_date();
    // $args = array( 
    //     'post_type'         => 'post',
    //     'repeater'          => 'template_1',
    //     'posts_per_page'    => 5,
    // );
    // if(function_exists('alm_render')){
        // return alm_render($args);
        // die();
        $html .= do_shortcode( '[ajax_load_more id="my_id" repeater="template_1"]');
    // }
    $html .= '</div>';
    return $html;
    die();
}

function jci_blocks_render_sponsored_by() {
    // sponsored, sponsor_name, sponsor_text, sponsor_url, sponsor_logo
    $html = '';
    if (get_field('sponsored')): 
        $sponsor_text = get_field('sponsor_text') ? get_field('sponsor_text') : 'Sponsored by';
        $name = get_field('sponsor_name');
        $url = get_field('sponsor_url');
        $html .= '<div class="sponsored-by"><p>'.$sponsor_text.' <a href="'.$url.'">'.$name.'</a></p></div>';
    endif;
    return $html;
    die();
}

function jci_blocks_render_excerpt_and_post_author() {
    $html = '';
    if (has_excerpt( )) {
        $html .= '<p class="article-excerpt">'.get_the_excerpt().'</p>';
    }
    $html .= '<p class="author">By '.esc_html__( get_the_author(), 'livibility' ) .' on '.esc_html( get_the_date() ).'</p>';
    return $html;
}

function jci_blocks_render_author_block() {
    $ID = get_the_ID();
    $html = '<div class=author-bio>';
    $html .= get_avatar( get_the_author_meta( 'ID' ), '130' );
    $html .= '<div class="author-bio-content">';
    $html .= '<h2 class="author-title">About the Author</h2>';
    $html .= '<p class="author-description">'.get_the_author_meta( 'description' ).'</p>';
    $html .= '<p><a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'"><button>More</button></a></p>';
    $html .= '</div></div>';
    return $html;
}

function jci_blocks_render_featured_image() {
    $html = '';
    if (has_post_thumbnail( ) ) {
        $html .= '<div>'.get_the_post_thumbnail('medium_large', array('style'=> 'height: auto; max-width: none;')).'</div>';
    }
    return $html;
}

function jci_blocks_render_ad_one() {
    $ID = get_the_ID();
    $all_ads = get_field( 'all_ads', $ID);
    $html = '';
    // $html .= '<script>cmWrapper.que.push(function() {cmWrapper.ads.define("ROS_ATF_970x250","'.$ID.'-1")});</script>';
    // $html .= '<div class="wp-block-jci-blocks-ad-area-one" id="'.$ID.'-1"></div>';
    $html .= '<div class="wp-block-jci-blocks-ad-area-one" id="div-gpt-ad-1568929479747-0">';
    // $html .= ' <script>
    // if (window.googletag) {
    //     googletag.cmd.push(function () {
    //         googletag.display("div-gpt-ad-1568929479747-0");
    //     });
    //     }
    // </script>';
$html .= '</div>';
    // if ($all_ads) {
        return $html;
    // }
}

function jci_blocks_render_ad_two() {
    $ID = get_the_ID();
    $all_ads = get_field( 'all_ads', $ID);
    
    // $html .= '<div class="wp-block-jci-blocks-ad-area-two" id="'.$ID.'-3"></div>';
    $html = '<div class="wp-block-jci-ad-area-two" id="div-gpt-ad-1568929535248-0">';
    // $html .= '<script>
    // googletag.cmd.push(function() { googletag.display("div-gpt-ad-1568929535248-0"); });
    // </script>';
    $html .= '</div>';
    // if ($all_ads) {
        return $html;
    // }
}

function jci_blocks_render_ad_three() {
    $ID = get_the_ID();
    $all_ads = get_field( 'all_ads', $ID);
    $html = '';
    $in_content = get_field( 'in_content_ads', $ID);
    // $html .= '<div class="wp-block-jci-blocks-ad-area-three" id="'.$ID.'-2"></div>';
    $html .= '<div class="wp-block-jci-ad-area-three" id="div-gpt-ad-1568929556599-0">';
    // $html .= '<script>
    // googletag.cmd.push(function() { googletag.display("div-gpt-ad-1568929556599-0"); });
    // </script>';
    $html .= '</div>';
    // if ($all_ads || $in_content) {
        return $html;
    // }
}

function jci_blocks_suggested_posts() {
    $html = '';
    // get last segment of url
    global $wp;
    $request = $wp->request;
    $pos = strrpos($request, '/');
    $basename = $pos === false ? $request : substr($request, $pos + 1);
    $basename = str_replace('-', ' ',$basename);
    $args = array(
        's'                 => '"'.$basename.'"',
        'posts_per_page'    => 20,
        'post_status'       => 'published'
    );
    $posts = get_posts($args);
    if ($posts) {
        $html .= '<p>Sorry, we couldn\'t find the page your were looking for. Here are some other results for '.ucwords($basename).'.  </p>';
        $html .= '<ul style="padding-left: 0;">';
        // foreach($posts as $p) {
        //     $pid = $p->ID;
        //     $html .= '<li class="one-hundred-list-container">';
        //     $html .= '<a href="'.get_the_permalink( $pid).'"  class="ohl-thumb" style="background-image: url('.get_the_post_thumbnail_url( $pid ).');"></a>';
        //     $html .= '<div class="ohl-text">';
        //     $html .= '<a href="'.get_the_permalink( $pid).'"><h3>'.get_the_title($pid).'</h3>'.get_the_excerpt($pid).'</a>';
        //     $html .= '</li>';
        // }
        $html .= do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_3" post_type="any" posts_per_page="20" search="'.$basename.'" scroll_distance="-200"]');
        $html .= '</ul>';
    } else {
        $html .= '<p>Sorry, we couldn\'t find the page your were looking for. Here are our latest posts.</p>';
        $html .= do_shortcode('[ajax_load_more loading_style="infinite classic" repeater="template_3" post_type="post" posts_per_page="20" scroll_distance="-200"]');
    }
    return $html;
}

function jci_blocks_bp_sponsor() {
    $html = '';
    $sponsor = get_field('sponsor_name');
    if ($sponsor) {
        $url = get_field('sponsor_url');
        $html .= '<div class="bp-sponsor-container">';
        $html .= $url ? '<p>Sponsored by <a href="'.$url.'" target="_blank">'.$sponsor.'</a></p>' : '<p>Sponsored by '.$sponsor.'</p>';
        $html .= '</div>';
    }
    return $html;
}

function jci_blocks_city_list() {
    $ID = get_the_ID( );
    $args = array(
        'post_type' => 'liv_place',
        'child_of' => $ID,
        'echo' => false,
        'title_li' => ''
    );

    $html = '<div class="city-list-block" id="city-list">';
    $html .= '<h3>Cities in '.get_the_title($ID).' on Livability.com</h3>';
    $html .= '<ul>';
    $html .= wp_list_pages($args);
    $html .= '</ul>';
    $html .= '<div class="city-list-bkgrnd">';
    $html .= '<button class="show-city-list">See Full List</button>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function jci_blocks_city_301() {
    if (isset($_GET['city'])) {
        $city = $_GET['city'];
        $ID = get_the_ID( );
        $title = get_the_title($ID);
        $html = '';
        $html .= '<div class="wp-block-jci-blocks-info-box city">';
        $html .= '<p>We\'re sorry, '.ucfirst(htmlspecialchars($city)).', '.$title.' isn\'t on our site.</p>';
        $html .= '<a href="#city-list" class="components-button info-box-button">See Other '.$title.' Cities</a>';
        $html .= '</div>';
        return $html;
    } else {
        return;
    }
}

function jci_blocks_city_map() {
    $place_query = str_replace(',','',get_the_title());
    $place_query = str_replace(' ','+', $place_query);
    $html = '<div class="city-map-block">';
    $html .= '<iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-VaZnsUyefqTA8Nu4LZOB6rKJcQoTRgQ&amp;q='.$place_query.'" allowfullscreen=""></iframe>';
    $html .= '</div>';
    return $html;

}

function jci_blocks_mag_sponsor() {
    $sponsorname = get_field('mag_sponsored_by_title');
    $sponsorlink = get_field('mag_sponsored_by_link');
    $sponsorimg = get_field('mag_sponsored_by_logo');
    $html = '';
    if ($sponsorname ) {
        $html .= '<div class="magazine-sponsor-block"><p><em>This digital edition of the '.esc_html__(get_the_title(), 'livability').' is sponsored by ';
        $html .= $sponsorlink ? '<a href="'.esc_attr__( $sponsorlink, 'livability' ).'">': '';
        $html .= $sponsorname;
        $html .= $sponsorlink ? '</a>' : '';
        $html .= '.</em></p>';
        $html .= $sponsorimg ? wp_get_attachment_image($sponsorimg['ID'], 'full', false, array('height' => 'auto')) : '';
        $html .= '</div>';
    }
    
    return $html;
}

function jci_blocks_onehundred_paginated() {
    $currentID = get_the_ID();
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array( 
        'post_type'     => 'best_places',
        'posts_per_page'=> 10,
        'post_status'   => 'publish',
        'orderby'    => 'meta_value_num',
        'meta_key'  => 'bp_rank',
        'order'     => 'ASC',
        // 'post__in'  => $child_array,
        'post_parent'   => $currentID,
        'paged'     => $paged
    ); 
    $ohl_query = new WP_Query($args);

        $html = '';
    if ($ohl_query->have_posts()): 
        $html .= '<ul class="onehundred-container">';
        while ($ohl_query->have_posts()): $ohl_query->the_post();
        $ID = get_the_ID();
        $place = get_field('place_relationship');
        $population = '';
        if ($place) {
            $population = get_field('city_population', $place[0]);
        }
        $html .= '<li class="one-hundred-list-item"><div class="one-hundred-list-container">';
        $html .= '<a  class="ohl-thumb" href="'.get_the_permalink().'" ><div style="background-image: url('.get_the_post_thumbnail_url($ID, 'three_hundred_wide').');">';
        $html .= '<p class="green-circle with-border">'.get_field('bp_rank').'</p></div></a>';
        $html .= '<div class="ohl-text">';
        $html .= '<a href="'.get_the_permalink().'"><h2>'.get_the_title().'</h2>';
        $html .= '<h3 class="uppercase">Livscore: '.get_field('ls_livscore');
        if ($population) {
            $html .= ' | Population: '.$population;
        }
        $html .= '</h3>';
        $html .= '<p>'.get_the_excerpt().'</p></a>';
        $html .= '</div></div></li>';
        endwhile;
        wp_reset_postdata();

        $html .= '</ul>';
        
    endif;
    
    return $html;
}

function jci_blocks_link_place_to_top_100() {
    $html = "";
    $args = array(
        'post_type'     => 'best_places',
        'post_status'   => 'publish',
        'posts_per_page' => 10,
        'meta_query'        => array(
            'relation'      => 'AND',
            array( 
                'key'       => 'place_relationship',
                'value'     => '"' . get_the_ID() . '"',
                'compare'   => 'LIKE'
            ),
            array(
                'key'       => 'is_top_one_hundred_page',
                'value'     => 1
            ),
        ),
        'tax_query'         => array(
            array(
                'taxonomy'  => 'best_places_years',
                'field'     => 'slug',
                'terms'     => range('2020','2030'),
            ),
        ),
    );
    $bp_query = new WP_Query($args);
    if ( $bp_query->have_posts() ):
        $recent_year = $recent_id = 1; // save id of post from most recent year
        $title = get_the_title();
        while ( $bp_query->have_posts() ): $bp_query->the_post();
        $years = get_the_terms( get_the_ID(), 'best_places_years' );
        $year = $years[0]->name;
        if ($year > $recent_year) {
            $recent_year = $year;
            $recent_id = get_the_ID();
        }
        endwhile;
        $rank = get_post_meta($recent_id, 'bp_rank', true);
        $parent = wp_get_post_parent_id( $recent_id );
        $badge = get_post_meta( $parent, 'badge', true );
        $html .= '<div class="link-place-to-top-100">';
        $html .= '<a href="'.get_the_permalink($parent).'" title="'.get_the_title($parent).'" >';
        $html .= wp_get_attachment_image($badge, 'three_hundred_wide').'</a>';
        $html .= '<p><a href="'.get_the_permalink($recent_id ).'" title="'.$title.' Best Place to Live" >';
        $html .= $title.' Best Places to Live in the U.S. '.$recent_year.' - ranked #'.$rank.'</a></p>';
        $html .= '</div>';
        wp_reset_postdata();
    endif;
    return $html;
}