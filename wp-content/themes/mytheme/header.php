<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
$vers=$_SERVER['HTTP_USER_AGENT'];
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php
        if (strpos($vers,'iPad') && strpos($vers,'Mobile') && strpos($vers,'Version/5.1')){
            echo '<meta name="viewport" content="width=977, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />';
        }else{
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >';
        }
    ?>
	<title><?php  wp_title( '-', true, 'left' ); ?></title>
    
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!--<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />-->
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); echo '?ver=' . filemtime(get_stylesheet_directory() . '/style.css'); ?>" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
    
<body>
    <div id="page" class="container">
        <?php
            //if(is_front_page())
            // $accueil=get_site_url();
            // if(is_front_page())
            // {
            //     $args = array(
            //         'post_type' => 'attachment',
            //         'name' => sanitize_title('dpg-logo-home'),
            //         'posts_per_page' => 1,
            //         'post_status' => 'inherit',
            //     );
            // }else{
            //     $args = array(
            //         'post_type' => 'attachment',
            //         'name' => sanitize_title('dpg-logo-home'),
            //         'posts_per_page' => 1,
            //         'post_status' => 'inherit',
            //     );
            // }
            // $img=get_posts($args);
            // if($img) $src=wp_get_attachment_url($img[0]->ID);
            $curcat='0';
            $parentcat='0';
            $cat=get_the_category($post->ID);
            if(count($cat)>0) {
                    $curcat=$cat[0]->term_id;
                    $parentcat=$cat[0]->parent;
            }
            $root = get_category_by_slug( 'realisations' );
            $args = array(
                'type'                     => 'post',
                'child_of'                 => $root->term_id,
                'parent'                   => '',
                'orderby'                  => 'custom_sort',
                'order'                    => 'ASC',
                'hide_empty'               => 0,
                'hierarchical'             => 1,
                'exclude'                  => '',
                'include'                  => '',
                'number'                   => '',
                'taxonomy'                 => 'category',
                'pad_counts'               => true 
            
            );
            $categories = get_terms('category', $args );
        ?>
        <!-- nav -->
        <div class="row" style="padding:1% 0">
        <!-- <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light"> -->
        <nav id="navbar" class="nav nav-pills nav-fill flex-column flex-sm-row">
            <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> -->
                <!-- <ul class="nav nav-pills nav-fill"> -->
                <?php foreach ( $categories as $category ) { ?>
                    <!-- <li class="nav-item"> -->
                        <a href="<?php echo get_category_link( $category->term_id ) ?>" class="nav-item nav-link <?php  echo $category->term_id==$curcat?'active"':''; echo $category->count==0?' disabled"':''?>"><?php echo $category->name ?></a>
                    <!-- </li> -->
                <?php } ?>
                <!-- </ul> -->
            </nav>
        <!-- </nav> -->
        </div>
        <!-- fin #masthead -->
        <!-- <div id="main" class="site-main">
           
        </div> -->