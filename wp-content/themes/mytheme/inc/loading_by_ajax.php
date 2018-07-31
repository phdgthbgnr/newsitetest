<?php

function initialiser_scripts() {

   
    wp_register_script('router', get_template_directory_uri().'/js/routes.js', array('crossroads'), '1.0', true);
    wp_enqueue_script('router');
    wp_localize_script( 'router', 'router_ajax', array(
        'url' => admin_url( 'admin-ajax.php' )
        // 'queryvars' => json_encode( $wp_query->query )
    ));

    wp_register_script('events', get_template_directory_uri().'/js/events.js', array('router'), '1.0', true);
    wp_enqueue_script('events');

}

add_action('wp_enqueue_scripts', 'initialiser_scripts');



// load ONE FULL POST from AJAX ----------------------------------------------------------------
add_action( 'wp_ajax_loadcontent-post', 'fload_contentpost' );
add_action( 'wp_ajax_nopriv_loadcontent-post', 'fload_contentpost' );

function fload_contentpost(){
    // $queryvars = json_decode( stripslashes( $_POST['queryvars'] ), true );
    // $queryvars['paged'] = $_POST['page'];
    // echo json_encode($queryvars);
    $type = wp_strip_all_tags($_POST['post']);
    $slug = wp_strip_all_tags($_POST['slug']);
    // $queryvars['paged'] = $_POST['page'];
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
    // $queryvars = json_decode( stripslashes( $_POST['queryvars'] ), true );
    // $queryvars['paged'] = $_POST['page'];
    // echo json_encode($queryvars);
    $type = wp_strip_all_tags($_POST['type']);
    $slug = wp_strip_all_tags($_POST['slug']);
    echo($slug);
    // $queryvars['paged'] = $_POST['page'];
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
        ?>
        </div>
            </div>
        <?php
    }
    wp_reset_postdata();

    // die();
}
// -----------------------------------------------------------------------------------------

?>