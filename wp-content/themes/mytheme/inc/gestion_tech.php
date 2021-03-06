<?php



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



add_action('admin_menu','tech_panel');

function tech_panel(){
    // add_menu_page('gestion des techniques','gestion des techniques','activate_plugins','tp','render_tp',null,81);
    add_menu_page('gestion des techniques','gestion des techniques','manage_options','tp_settings','render_tp',null,81);
    
    global $wpdb;
    
    $table = $wpdb->prefix.'tech';
    if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            `id` smallint(3) NOT NULL AUTO_INCREMENT,
            `tp` varchar(250) NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}





// creation custom taxonomy (post,page) ---------------------------------------------------------------------------
// add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it topics for your posts
 
function create_topics_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Topics', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topics' ),
    'all_items' => __( 'All Topics' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'menu_name' => __( 'Topics' ),
  );    
 
// Now register the taxonomy
 
  register_taxonomy('topics',array('post','page'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'topic' ),
  ));
 
}

// -------------------------------------------------------------------------------------------------------------------




// callback add_menu_page tech_panel
function render_tp()
{
    global $wpdb;
    $error=false;
    global $post;
    echo 'postID'.$post->ID;
    // ajout cp
    if(isset($_POST['action']))
    {
        if($_POST['action']=='ajouter')
        {
            $tp= wp_strip_all_tags($_POST['tech']);
            // if(!preg_match ("(^[0-9]*$)", $cp) || strlen($cp)!=5) $error=true;
            $table = $wpdb->prefix.'tech';
            if(!empty($tp))
            {
                $wpdb->insert( $table,array('tp'=>$tp));
            }
        }
    }
    
    
    // affichage techniques

    $table=$wpdb->prefix.'tech';
    $sql=$wpdb->prepare("SELECT * from $table ORDER BY 'tp' DESC",1);
    $rows = $wpdb->get_results($sql,ARRAY_A);
    ?>
    <div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
        <form action="" method="post">
            <div id="icon-options-general" class="icon32"><br/></div>
            <h2>Gestion des techniques</h2>
    <?php
    if(count($rows)>0)
    {
        ?>
        <br/><br/>
        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0" style="width:80%">
        <thead>
        <tr>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Id</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 80%;text-align:center">Technique</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 20%;text-align:center">action</th>
        </tr>
        </thead>
        <tbody id="the-list">
        <?php
        foreach ($rows as $val)
        {
            echo '<tr>';
            foreach($val as $k=>$v)
            {
                if($k=='id') $id=$v;
                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px">'.$v.'</th>';
            }
            echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px"><a href="#" data-id='.$id.' id="tp'.$id.'" class="effcp">Supprimer</a></th>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        
    }else{
        ?>
        <br/><br/>
        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 5%;text-align:center">aucune technique disponible</th>
        </tr>
        </thead>
        </table>
    <?php
    }

?>
            <br/>
        <div class="tablenav bottom">
            <div class="alignleft actions">
                <input type="text" name="tech" id="cpst" />
                <input type="submit" name="" id="doaction2" class="button-secondary action" value="ajouter"/>
                <input type="hidden" name="action" id="ajouter" class="button-secondary action" value="ajouter"/>
            </div>
        </div>    
        </form>
        </div>
        </div>
        </div>
        

        <?php
        
        wp_register_script('gestiontech', get_template_directory_uri().'/js/suppr_tech.js', '', false, true);
        wp_enqueue_script('gestiontech');
        wp_localize_script( 'gestiontech', 'gestion_tech', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
        

}
?>