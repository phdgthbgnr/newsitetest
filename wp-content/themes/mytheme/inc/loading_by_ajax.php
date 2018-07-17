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

// script JS loading with akax
add_action( 'wp_ajax_loadcontent', 'fload_content' );
add_action( 'wp_ajax_nopriv_loadcontent', 'fload_content' );

function fload_content(){
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
    // remove_filter( 'editor_max_image_size', 'my_image_size_override' );

    // the_posts_pagination( array(
    //     'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
    //     'next_text'          => __( 'Next page', 'twentyfifteen' ),
    //     'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
    // ) );
}

?>