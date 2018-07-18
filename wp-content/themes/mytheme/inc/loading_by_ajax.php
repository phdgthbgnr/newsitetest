<?php

function initialiser_scripts() {
    // global $wp_query;
    wp_register_script('withajax', get_template_directory_uri().'/js/ajaxcall.js', array('jquery'), '1.0', true);
    wp_enqueue_script('withajax');
    wp_localize_script( 'withajax', 'with_ajax', array(
        'url' => admin_url( 'admin-ajax.php' )
        // 'queryvars' => json_encode( $wp_query->query )
    ));
}

add_action('wp_enqueue_scripts', 'initialiser_scripts');



// load ONE FULL POST from AJAX ----------------------------------------------------------------
add_action( 'wp_ajax_loadcontent', 'fload_contentpost' );
add_action( 'wp_ajax_nopriv_loadcontent', 'fload_contentpost' );

function fload_contentpost(){
    $queryvars = json_decode( stripslashes( $_POST['queryvars'] ), true );
    $queryvars['paged'] = $_POST['page'];
    // echo json_encode($queryvars);
    $type = wp_strip_all_tags($_POST['type']);
    $slug = wp_strip_all_tags($_POST['slug']);
    $queryvars['paged'] = $_POST['page'];
    // $posts = new WP_Query( $queryvars );
    //$queryvars = new WP_Query(o.type)
    $posts = new WP_Query( array($type => $slug) );
    // print_r($posts);
    // $GLOBALS['wp_query'] = $posts;
    if( ! $posts->have_posts() ) { 
        echo 'nothing';
        // get_template_part( 'content', 'none' );
    }
    else {
        while ( $posts->have_posts() ) { 
            $posts->the_post();
            // echo get_the_title();
            get_template_part( 'content', get_post_format() );
        }
        wp_reset_postdata();
    }
}
// -----------------------------------------------------------------------------------------




// load ALL POSTS by ONE CATEGORY from AJAX ------------------------------------------------
add_action( 'wp_ajax_loadcontent-posts', 'fload_contentposts' );
add_action( 'wp_ajax_nopriv_loadcontent-posts', 'fload_contentposts' );

function fload_contentposts(){
    $queryvars = json_decode( stripslashes( $_POST['queryvars'] ), true );
    $queryvars['paged'] = $_POST['page'];
    // echo json_encode($queryvars);
    $type = wp_strip_all_tags($_POST['type']);
    $slug = wp_strip_all_tags($_POST['slug']);
    $queryvars['paged'] = $_POST['page'];
    // $posts = new WP_Query( $queryvars );
    //$queryvars = new WP_Query(o.type)
    $posts = new WP_Query( array($type => $slug) );
    // print_r($posts);
    // $GLOBALS['wp_query'] = $posts;
    ?>
    <div class="row">
        <div class="card-group">
    <?php
    if( ! $posts->have_posts() ) {
        echo 'nothing';
        // get_template_part( 'content', 'none' );
    }else{
        while ( $posts->have_posts() ) { 
            $posts->the_post();
            // echo get_the_title();
            get_template_part( 'content-category', get_post_format() );
        }
        wp_reset_postdata();
        ?>
        </div>
            </div>
        <?php
    }
    die();
}
// -----------------------------------------------------------------------------------------

?>