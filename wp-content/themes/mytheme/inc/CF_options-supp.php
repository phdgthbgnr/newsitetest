<?php

// META BOX options supplémentaires ---------------------------------------------------------------------------------------------------

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
    global $wpdb;
    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'posts_options_meta_box', 'posts_options_meta_box_nonce' );
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $liendemo = get_post_meta( $post->ID, 'liendemo', true );
    $typeope = get_post_meta( $post->ID, 'typeope', true );
    $client = get_post_meta( $post->ID, 'client', true );
    $tech = get_post_meta( $post->ID, 'tech', true );
    
    // lien demo
    echo '<div class="optsupp">';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Lien demo', '').'</label> ';
    // liens demos
    echo '<input type="text" name="liendemo" id="liendemo" placeholder="lien vers la démo" value="'.(isset($liendemo)?$liendemo:'').'"/></p>';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Type d\'opération', '').'</label> ';
    echo '<input type="text" name="typeope" id="typeope" placeholder="Type d\'opération" value="'.(isset($typeope)?$typeope:'').'"/></p>';
    // client final
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Client final', '').'</label> ';
    echo '<input type="text" name="client" id="client" placeholder="Client final" value="'.(isset($client)?$client:'').'"/></p>';
    // technique
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Technique', '').'</label> ';
    echo '<input type="text" name="tech" id="tech" placeholder="Technique" value="'.(isset($tech)?$tech:'').'"/></p>';

    // recup toutes les tech dispo (CHECKBOX) get_all_techs in functions.php
    do_action('get_all_techs');

    echo '</div>';
 
}
add_action( 'add_meta_boxes', 'posts_options_add_meta_box' );
//do_action('get_all_techs');

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
    if( isset( $_POST[ 'client' ] ) ) {
    update_post_meta( $post_id, 'client', sanitize_text_field( $_POST[ 'client' ] ) );
    }
    if( isset( $_POST[ 'tech' ] ) ) {
    update_post_meta( $post_id, 'tech', sanitize_text_field( $_POST[ 'tech' ] ) );
    }

    if(isset($_POST['techs']) && is_array($_POST['techs'])){
        $tbp=$_POST['techs'];
        $techs=implode(" ", $tbp);
        update_post_meta( $post_id, 'gtechs', $techs );
    }else{
        update_post_meta( $post_id, 'gtechs', '' );
    }
}
add_action( 'save_post', 'posts_options_save_meta_box_data' );

// -----------------------------------------------------------------------------------------------------------------------------------





// META BOX libellé voir la gallerie ---------------------------------------------------------------------------------------------------

function posts_divers_add_meta_box() {
    // callback : posts_options_meta_box_callback
    $screens = array('post');
    foreach ( $screens as $screen ) {
        add_meta_box(
          'posts_divers_meta_sectionid',
          __( 'Divers', '' ),
          'posts_divers_meta_box_callback',
          $screen,
              'normal',
              'high'
         );
    }
}

function posts_divers_meta_box_callback( $post ) {
    global $wpdb;
    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'posts_divers_meta_box', 'posts_divers_meta_box_nonce' );
    
    /*
    * Use get_post_meta() to retrieve an existing value
    * from the database and use the value for the form.
    */
    $libpost = get_post_meta( $post->ID, 'libpost', true ); //libellé lien vers le post
    $libgal = get_post_meta( $post->ID, 'libgal', true ); //libellé lien vers la galerie

    $libpost = empty($libpost)?'Plus d\'infos':$libpost;
    $libgal = empty($libgal)?'Voir la galerie':$libgal;

    // form elements go here
    //Photo Source
    echo '<div class="optsupp">';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Libellé lien article', '').'</label> ';
    // liens demos
    echo '<input type="text" name="libpost" id="libpost" placeholder="Libellé du lien vers article" value="'.(isset($libpost)?$libpost:'Plus d\'infos').'"/></p>';
    echo '<p class="metaboxoptions">';
    echo '<label>'.__('Libellé lien galerie', '').'</label> ';
    echo '<input type="text" name="libgal" id="libgal" placeholder="Libellé du lien vers galerie" value="'.(isset($libgal)?$libgal:'Voir la galerie').'"/></p>';
    echo '</div>';
 
}

add_action( 'add_meta_boxes', 'posts_divers_add_meta_box' );


function posts_divers_save_meta_box_data( $post_id ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'posts_divers_meta_box_nonce' ] ) && wp_verify_nonce( $_POST[ 'posts_divers_meta_box' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'libpost' ] ) ) {
    update_post_meta( $post_id, 'libpost', sanitize_text_field( $_POST[ 'libpost' ] ) );
    }
    if( isset( $_POST[ 'libgal' ] ) ) {
    update_post_meta( $post_id, 'libgal', sanitize_text_field( $_POST[ 'libgal' ] ) );
    }

}
add_action( 'save_post', 'posts_divers_save_meta_box_data' );

?>