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

function my_custom_fonts() {
    echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/admin_style.css" type="text/css" media="all" />';
}
add_action('admin_head', 'my_custom_fonts');

/*
// initialisation les scripts
function initialiser_scripts() {
    if(!is_admin())
    {
        wp_deregister_script('jquery');
 		wp_register_script('jquery','http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', '',false, true);
        
        //wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui.custom.min.js', false, '');
        
        // charger jQuery
		wp_enqueue_script('jquery');
        
        $logged='no';
            
        if ( is_user_logged_in())
        {
        
            $current_user = wp_get_current_user();
            if($current_user->roles[0]=='appren')
            {
                $logged='appren';
            }
            
             if($current_user->roles[0]=='prof')
            {
                $logged='prof';
            }
        }
        
        if( is_page_template('template-agendaprof.php'))
        {
            wp_register_style('jqueryuicustmincss',get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.css','',false,'screen');
            wp_register_style('fullcalendarcss',get_template_directory_uri().'/js/fullcalendar/fullcalendar.css','',false,'screen');
            wp_register_style('fullcalendarprintcss',get_template_directory_uri().'/js/fullcalendar/fullcalendar.print.css','',false,'print');
            wp_enqueue_style( 'jqueryuicustmincss' );
            wp_enqueue_style( 'fullcalendarcss' );
            wp_enqueue_style( 'fullcalendarprintcss' );
            
            
            wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js', '', false, true);
            wp_register_script('fullcalendarmin', get_template_directory_uri().'/js/fullcalendar/fullcalendar.min.js','', false, true);
            wp_enqueue_script('jqueryuicustmin');
            wp_enqueue_script('fullcalendarmin');
            
            // chargement JS prof
            if($logged=='prof')
            {
                wp_register_script('agendaprof', get_template_directory_uri().'/js/agenda-prof.js','', false, true);
                wp_enqueue_script('agendaprof');
                
                // refresh
                wp_localize_script(
                    'agendaprof',
                    '_ajax_refreshprof',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_calprof')
                    )
                );
                
                // eventresize
                wp_localize_script(
                    'agendaprof',
                    '_ajax_eventResize',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('resize_nonce_event')
                    )
                );
                
                 // eventdrop
                wp_localize_script(
                    'agendaprof',
                    '_ajax_eventDrop',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('drop_nonce_event')
                    )
                );
                
                if(is_page('planning-professeur'))
                {
                    wp_localize_script(
                        'agendaprof',
                        '_ajax_resumecoursprof',
                        array(
                            'url' => admin_url( 'admin-ajax.php' ),
                            'nonce' => wp_create_nonce('resumecours_nonce_prof')
                        )
                    );
                    }
                
            }
            
            // chargement JS common (non logué)
            if($logged=='no')
            {
                wp_register_script('agendacommon', get_template_directory_uri().'/js/agenda-common.js','', false, true);
                wp_enqueue_script('agendacommon');
                
                $id=0;
                if($_GET && isset($_GET['id'])) $id=wp_strip_all_tags($_GET['id']);
                
                 wp_localize_script(
                    'agendacommon',
                    '_ajax_refreshprof',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_calprof')
                    )
                );
                
            }
            
            // chargement JS apprenant (connexion au planning du professeur
            if($logged=='appren' && !is_page('planning-eleve'))
            {
                wp_register_script('agendappren', get_template_directory_uri().'/js/agenda-appren.js','', false, true);
                wp_enqueue_script('agendappren');
                
                $id=0;
                if($_GET && isset($_GET['id'])) $id=wp_strip_all_tags($_GET['id']);
                
                 wp_localize_script(
                    'agendappren',
                    '_ajax_refreshprofappren',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_reserve',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('reserve_nonce_event')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_deprogramm',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('deprogramm_nonce_event')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_testconso',
                    array(
                        'id'=>$current_user->ID,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('testconso_nonce')
                    )
                );
                
                
            }
            
            if($logged=='appren' && is_page('planning-eleve'))
            {
                
                wp_register_script('appren-priv', get_template_directory_uri().'/js/agenda-appren-priv.js', '', false, true);
                wp_enqueue_script('appren-priv');
                
                wp_register_style('fancyboxcss',get_template_directory_uri().'/js/fancybox/jquery.fancybox.css','',false,'screen');
                wp_enqueue_style( 'fancyboxcss' );
                
                wp_register_script('fancybox', get_template_directory_uri().'/js/fancybox/jquery.fancybox.pack.js', '', false, true);
                wp_enqueue_script('fancybox');
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_refreshapprenpriv',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refreshpriv_nonce_appren')
                    )
                );
                
                 wp_localize_script(
                    'appren-priv',
                    '_ajax_resumecours',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('resumecours_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_evaluercours',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('evaluercours_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_affeval',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('affeval_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_valider',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('valider_nonce_appren')
                    )
                );
				
				wp_localize_script(
                    'appren-priv',
                    '_ajax_validerplann',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('validerplann_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_deprogramm',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('deprogramm_nonce_event')
                    )
                );
                
            }
                
        }
        
        if( is_page_template('template-inscription.php'))
        {
            wp_register_script('inscription', get_template_directory_uri().'/js/inscription.js', '', false, true);
            wp_enqueue_script('inscription');
        }
        
         if( is_page_template('template-profilprof.php') && $logged=='prof')
        {
            wp_register_script('profprofil', get_template_directory_uri().'/js/profilprof.js', '', false, true);
            wp_enqueue_script('profprofil');
        }
        
            
        if( is_page_template('template-profileleve.php') && $logged=='appren')
        {
            wp_register_script('inscription', get_template_directory_uri().'/js/inscription.js', '', false, true);
            wp_enqueue_script('inscription');
        }
        
        if( is_page_template('template-contact.php'))
        {
            wp_register_style('jqueryuicustmincss',get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.css','',false,'screen');
            wp_enqueue_style( 'jqueryuicustmincss' );
            wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js', '', false, true);
            wp_register_script('contact', get_template_directory_uri().'/js/contact.js', '', false, true);
            wp_enqueue_script('jqueryuicustmin');
            wp_enqueue_script('contact');
        }
        
        if( is_category() )
        {
            wp_register_script('infoprof', get_template_directory_uri().'/js/info-prof.js', '', false, true);
            wp_enqueue_script('infoprof');
        }
        
        if(is_page_template('template-recherchercours.php'))
        {
            wp_register_script('rechercours', get_template_directory_uri().'/js/rechercours.js', '', false, true);
            wp_enqueue_script('rechercours');
        }
    }
}
add_action('wp_enqueue_scripts', 'initialiser_scripts');
*/



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






// CUSTOM FIELDS OPTIONS SUPPLEMENTAIRES in POSTS ---------------------------------------------------------------

function posts_options_add_meta_box() {
    // callback : posts_options_meta_box_callback
    $screens = array('post');
    foreach ( $screens as $screen ) {
        add_meta_box(
          'posts_options_meta_sectionid',
          __( 'Options supplémentaires', '' ),
          'posts_options_meta_box_callback',
          $screen,
              'normal',
              'high'
         );
        }
  }


function posts_options_meta_box_callback( $post ) {
    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'posts_options_meta_box', 'posts_options_meta_box_nonce' );
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $liendemo = get_post_meta( $post->ID, 'liendemo', true );
    $typeope = get_post_meta( $post->ID, 'typeope', true );
    $typeope = get_post_meta( $post->ID, 'tech', true );
    
    // form elements go here
    //Photo Source
    echo '<div class="optsupp">';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Lien demo', '').'</label> ';
    // liens demos
    echo '<input type="text" name="liendemo" id="liendemo" placeholder="lien vers la démo" value="'.(isset($liendemo)?$liendemo:'').'"/></p>';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Type d\'opération', '').'</label> ';
    echo '<input type="text" name="typeope" id="typeope" placeholder="Type d\'opération" value="'.(isset($typeope)?$typeope:'').'"/></p>';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Technique', '').'</label> ';
    echo '<input type="text" name="tech" id="tech" placeholder="Technique" value="'.(isset($tech)?$tech:'').'"/></p>';
    
    echo '</div>';
 
}
add_action( 'add_meta_boxes', 'posts_options_add_meta_box' );


function posts_options_save_meta_box_data( $post_id ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'posts_options_meta_box_nonce' ] ) && wp_verify_nonce( $_POST[ 'posts_options_meta_box' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
   
        return;
    }
    
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'liendemo' ] ) ) {
    update_post_meta( $post_id, 'liendemo', sanitize_text_field( $_POST[ 'liendemo' ] ) );
    }
    if( isset( $_POST[ 'typeope' ] ) ) {
    update_post_meta( $post_id, 'typeope', sanitize_text_field( $_POST[ 'typeope' ] ) );
    }
    if( isset( $_POST[ 'tech' ] ) ) {
    update_post_meta( $post_id, 'tech', sanitize_text_field( $_POST[ 'tech' ] ) );
    }
}
add_action( 'save_post', 'posts_options_save_meta_box_data' );





   
    // script JS gestion des techniques
    add_action( 'wp_ajax_acgestion_tech', 'f_gestion_tech' );

    function f_gestion_tech(){
        $ret=wp_strip_all_tags($_POST['cid']);
        $ret=intval($ret);
        if(is_numeric($ret))
        {
            global $wpdb;
            $table=$wpdb->prefix.'tech';
            $res=$wpdb->delete($table,array('id'=>$ret));
            echo json_encode($res);
        }
    }

    // add custom post tech elements
    include('inc/gestion_tech.php');

?>