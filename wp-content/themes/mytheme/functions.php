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
require(get_template_directory().'/inc/ajax.php');

?>