<?php

// enlever l'affichage du n° de version de WP
remove_action('wp_head', 'wp_generator');

// enleve l'admin bar
function my_admin_bar(){
    $cc_user = wp_get_current_user();
    if (empty($cc_user->roles) || in_array('appren', $cc_user->roles) || in_array('prof', $cc_user->roles)) {
      return false;
    }
    return true;
  }
  add_filter( 'show_admin_bar' , 'my_admin_bar') ;


// renomme l'expediteur des mails
function new_mail_from() { return 'no-reply@lemail.fr'; }
function new_mail_from_name() { return '[lemail.fr]'; }
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');


// add customm CSS admin
function my_custom_css() {
    echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/admin_style.css" type="text/css" media="all" />';
}
add_action('admin_head', 'my_custom_css');



// add featured images -----------------------------------------------------------------------------------------------------
add_theme_support( 'post-thumbnails' );
// set custom size
// set_post_thumbnail_size( 50, 50);
// add image size to use with the_post_thumbnail() 
// add_image_size( 'single-post-thumbnail', 590, 180 );

// FRONT : 
// the_post_thumbnail( 'single-post-thumbnail' );

//FALLBACK :
/*
if ( has_post_thumbnail() ) {
    the_post_thumbnail();
    } else { ?>
    <img src="<?php bloginfo('template_directory'); ?>/images/default-image.jpg" alt="<?php the_title(); ?>" />
    <?php } ?>
*/
// OR:
/*
//function to call first uploaded image in functions file
function main_image() {
    $files = get_children('post_parent='.get_the_ID().'&post_type=attachment
    &post_mime_type=image&order=desc');
      if($files) :
        $keys = array_reverse(array_keys($files));
        $j=0;
        $num = $keys[$j];
        $image=wp_get_attachment_image($num, 'large', true);
        $imagepieces = explode('"', $image);
        $imagepath = $imagepieces[1];
        $main=wp_get_attachment_url($num);
            $template=get_template_directory();
            $the_title=get_the_title();
        print "<img src='$main' alt='$the_title' class='frame' />";
      endif;
    }

    //front :
    if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
    echo get_the_post_thumbnail($post->ID);
    } else {
    echo main_image();
    }
*/
// add multiple featured images : https://www.wpbeginner.com/plugins/how-to-add-multiple-post-thumbnails-featured-images-in-wordpress/
// -----------------------------------------------------------------------------------------------------------------------


/*
// customise login logo
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/login-logo.png);
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
*/



/*
// full customize login page
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/style-login.css' );
    wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/style-login.js' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );
*/


/*
// remove menu items
function remove_menus(){
     if ( !current_user_can( 'manage_options' ) ) {
          remove_menu_page( 'plugins.php' );
     }
}
add_action( 'admin_menu', 'remove_menus' );
*/



// add excerpts to page
add_action( 'init', 'my_add_excerpts_to_pages' );

function my_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}


// Hide Administrator From User List 
function isa_pre_user_query($user_search) {
    $user = wp_get_current_user();
    if (!current_user_can('administrator')) { // Is Not Administrator - Remove Administrator
      global $wpdb;
  
      $user_search->query_where = 
          str_replace('WHERE 1=1', 
              "WHERE 1=1 AND {$wpdb->users}.ID IN (
                   SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                      WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
                      AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
              $user_search->query_where
          );
    }
}
add_action('pre_user_query','isa_pre_user_query');




// empeche non admin d'aller sur wp-admin
function restrict_admin()
{
    if (!current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX) && count($_POST) == 0) 
    {
            //wp_die( __('You are not allowed to access this part of the site') );
            wp_redirect(home_url()); 
            exit;
     }

}
add_action( 'admin_init', 'restrict_admin',1);



// empeche l'utilisateur non loggé d'aller sur wp-admin
function restrict_adminnopriv()
{
   // wp_die($_SERVER['REQUEST_URI']);
    //wp_die( __('You are not allowed to access this part of the site') );
    //$user = wp_get_current_user();
        if( !is_user_logged_in() && strpos($_SERVER['REQUEST_URI'],'wp-admin') && !(defined('DOING_AJAX') && DOING_AJAX) && count($_POST) == 0)
        {
           wp_redirect(home_url()); 
            exit;
        }
}
add_action( 'init', 'restrict_adminnopriv',1);

// require(get_template_directory().'/inc/ajax.php');

// if(is_admin()){
//     wp_localize_script(
//         'agendacommon',
//         '_ajax_refreshprof',
//         array(
//             'id'=>$id,
//             'url' => admin_url( 'admin-ajax.php' ),
//             'nonce' => wp_create_nonce('refresh_nonce_calprof')
//         )
//     );
// }

/*
// custom posts types

function movie_reviews_init() {
    $args = array(
      'label' => 'Movie Reviews',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'movie-reviews'),
        'query_var' => true,
        'menu_icon' => 'dashicons-video-alt',
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );
    register_post_type( 'movie-reviews', $args );
}
add_action( 'init', 'movie_reviews_init' );

*/

// chargement JQUERY / BOOSTRAP

if(!is_admin()){
    wp_register_style('bootstrapcss',get_template_directory_uri().'/bootstrap412/css/bootstrap.min.css','',false,'screen');
    wp_enqueue_style( 'bootstrapcss' );

    wp_deregister_script('jquery');
    wp_register_script('jquery',get_template_directory_uri().'/js/jquery-3.1.1.min.js', '',false, true);
    wp_enqueue_script('jquery');

    wp_register_script('bootstrapjs',get_template_directory_uri().'/bootstrap412/js/bootstrap.min.js', '',false, true);
    wp_enqueue_script('bootstrapjs');

    wp_register_script('signals',get_template_directory_uri().'/js/signals.js', '',false, true);
    wp_enqueue_script('signals');

    wp_register_script('crossroads',get_template_directory_uri().'/js/crossroads.js', 'signals',false, true);
    wp_enqueue_script('crossroads');

    // wp_register_script('routes',get_template_directory_uri().'/js/routes.js', 'crossroads',false, true);
    // wp_enqueue_script('routes');

    wp_register_style('styles',get_template_directory_uri().'/style.css','',false,'screen');
    wp_enqueue_style( 'styles' );
                    
    // wp_register_script('load_with_ajax', get_template_directory_uri().'/js/ajaxcall.js', array('jquery'), false, true);
    // wp_enqueue_script('load_with_ajax');
    // wp_localize_script( 'load_with_ajax', 'loadwithajax', array(
    //     'ajaxurl' => admin_url( 'admin-ajax.php' )
    // ));
}



// ADD CUSTOM POST GESTION TECHNIQUE ------------------------------------------------------------
if(is_admin()) include('inc/gestion_tech.php');



// enregistrement hook -- affichage de la liste des technique
add_action('get_all_techs','return_all_techs');

function return_all_techs(){
    global $wpdb, $post;
    $table=$wpdb->prefix.'tech';
    $sql=$wpdb->prepare("SELECT * from $table ORDER BY 'tp' DESC",1);
    $rows = $wpdb->get_results($sql,ARRAY_A);
    $res= '<p class="metaboxoptions">';
    foreach ($rows as $k=>$v){
        $res.='<label style="text-align:right;padding-right:2%">'.$v['tp'].'</label><input type="checkbox" name="techs[]" value="'.$v['id'].'" '.getcheck_tech($post->ID, $v['id'] ).'/>';
    }
    $res.='</p>';
    echo  $res;
}

// verifie checkbox value
function getcheck_tech($postid, $val)
{
    $arr=get_post_meta( $postid, 'gtechs', true );
    $tbarr=explode(' ',$arr);
    if(is_array($tbarr))
    {
        if(in_array($val,$tbarr))
        {
            return ' CHECKED="CHECKED"';
        }else{
            return '';
        }
    }else{
        return '';
    }
}



// CUSTOM FIELDS OPTIONS SUPPLEMENTAIRES in POSTS ---------------------------------------------------------------
if(is_admin()) include('inc/CF_options-supp.php');

// ADD AJAX LOADING
include('inc/loading_by_ajax.php');


// fonctions utiles
include ('inc/utils.php');

?>