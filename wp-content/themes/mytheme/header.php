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
            $accueil=get_site_url();
            if(is_front_page())
            {
                $args = array(
                    'post_type' => 'attachment',
                    'name' => sanitize_title('dpg-logo-home'),
                    'posts_per_page' => 1,
                    'post_status' => 'inherit',
                );
            }else{
                $args = array(
                    'post_type' => 'attachment',
                    'name' => sanitize_title('dpg-logo-home'),
                    'posts_per_page' => 1,
                    'post_status' => 'inherit',
                );
            }
            $img=get_posts($args);
            if($img) $src=wp_get_attachment_url($img[0]->ID);
        ?>
        <!-- #masthead -->
        <header id="masthead" class="row" role="banner">
            <div class="col-12 col-md-8">.col-12 .col-md-8</div>
            <div class="col-6 col-md-4">.col-6 .col-md-4</div>
        </header>
        <!-- fin #masthead -->
        <div id="main" class="site-main">
           
        </div>