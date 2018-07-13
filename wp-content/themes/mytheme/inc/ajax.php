<?php
add_action('wp_ajax_inscript','_ajax_inscript');            // inscription utilisateur connecte
add_action('wp_ajax_nopriv_inscript','_ajax_inscript');     // inscription utilisateur non connecte 


add_action('wp_ajax_contact','_ajax_contact');            // contact utilisateur connecte
add_action('wp_ajax_nopriv_contact','_ajax_contact');     // contact utilisateur non connecte 

add_action('wp_ajax_rechcours','_ajax_rechcours');  

add_action('wp_ajax_modifprofil','_ajax_modifprofil');  // utilisateur connecte modification du profil

add_action('wp_ajax_addcours','_ajax_addcours');  // ajout d'un cours professeur

add_action('wp_ajax_refreshprof','_ajax_refreshprof'); // refresh agenda prof connecte
add_action('wp_ajax_nopriv_refreshprof','_ajax_refreshprofnopriv'); // refresh agenda prof non connecte

add_action('wp_ajax_refreshprofappren','_ajax_refreshprofappren'); // refresh agenda prof eleve connecte

add_action('wp_ajax_eventResize','_ajax_eventResize'); // resize event

add_action('wp_ajax_eventDrop','_ajax_eventDrop'); // resize event

add_action('wp_ajax_modifcours','_ajax_modifcours');  // modif d'un cours professeur

add_action('wp_ajax_reserve','_ajax_reserve');  // réserve un cours

add_action('wp_ajax_testconso','_ajax_testconso'); // test restant conso eleve

add_action('wp_ajax_deprogramm','_ajax_deprogramm');  // réserve un cours

add_action('wp_ajax_refreshapprenpriv','_ajax_refreshapprenpriv');  // réserve un cours

add_action('wp_ajax_resumecours','_ajax_resumecours');  // resume cours apprenant

add_action('wp_ajax_evaluercours','_ajax_evaluercours');  // evaluation cours par l'apprenant

add_action('wp_ajax_resumecoursprof','_ajax_resumecoursprof');  // resume cours prof

add_action('wp_ajax_modifprofilprof','_ajax_modifprofilprof');  // modification profil prof

add_action('wp_ajax_evaluation','_ajax_evaluation'); // formulaire evaluation

add_action('wp_ajax_affeval','_ajax_affeval'); // affichage evaluation

add_action('wp_ajax_valider','_ajax_valider'); // valide le cours (resume)

add_action('wp_ajax_validerplann','_ajax_validerplann'); // valide le cours (sur le planning)

// preinscription

function _ajax_inscript()
{
    
    global $wpdb;
    
    // securite

    check_ajax_referer('ajax_inscript_nonce','security');
    
    // tableau erreur
    $errors = array();
    
    //protect
    
    $nomeleve = wp_strip_all_tags($_POST['nomeleve']);
    $prenomeleve = wp_strip_all_tags($_POST['prenomeleve']);
    $nomparent = wp_strip_all_tags($_POST['nomparent']);
    $prenomparent = wp_strip_all_tags($_POST['prenomparent']);
    $naissparent = wp_strip_all_tags($_POST['parentnaiss']);
    $naisseleve = wp_strip_all_tags($_POST['naisseleve']);
    $scolniv = wp_strip_all_tags($_POST['nivscol']);
    $adress = wp_strip_all_tags($_POST['adress']);
    $cp = wp_strip_all_tags($_POST['cp']);
    $ville = wp_strip_all_tags($_POST['ville']);
    $cmail = wp_strip_all_tags($_POST['cmail']);
    $cmail2 = wp_strip_all_tags($_POST['cmailcnfrm']);
    $tel = wp_strip_all_tags($_POST['tel']);
    $mobile = wp_strip_all_tags($_POST['mobile']);
    
    $tel= str_replace(CHR(32),'',$tel); 
    $mobile=str_replace(CHR(32),'',$mobile); 
    $cp=str_replace(CHR(32),'',$cp);
    $naisseleve=str_replace(CHR(32),'',$naisseleve);
    
    // test email
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(!preg_match($Syntaxe,$cmail)) array_push($errors,'cemail');
    
    if(!preg_match($Syntaxe,$cmail2)) array_push($errors,'cmailcnfrm');
    
    if(!in_array('cemail',$errors) && $cmail!=$cmail2){
        //array_push($errors,'cemail');
        array_push($errors,'cmailcnfrm');
    }
    
    // test tel
    if(!preg_match ("(^[0-9]*$)", $tel) || strlen($tel)!=10) array_push($errors,'ctel');
    //if(!is_numeric($tel) || ceil(log10($tel))!=10)  array_push($errors,'ctel');
    
    // test mobile
    if(!preg_match ("(^[0-9]*$)", $mobile) || strlen($mobile)!=10) array_push($errors,'cmob');
    //if(!is_numeric($mobile) || ceil(log10($mobile))!=10)  array_push($errors,'cmob');
    
    //test cp
    if(!preg_match ("(^[0-9]*$)", $cp) || strlen($cp)!=5) array_push($errors,'cpost');
    //if(!is_numeric($cp) || ceil(log10($cp))!=5)  array_push($errors,'cpost');
    
    // test date naissance
    if(!preg_match( '`^\d{1,2}/\d{1,2}/\d{4}$`' , $naisseleve ))
    {
        array_push($errors,'bitrhday');
    }else{
        $dates = explode("/", $naisseleve); 
        if(intval($dates[0])>31 || intval($dates[1])>12 || intval($dates[0])==0 || intval($dates[1])==0 ) array_push($errors,'bitrhday');
    }
    
    $rappel=0;
    if(isset($_POST['rappel'])) $rappel=1;
    
    // test si cp présent dans la base

    if(!in_array('cpost',$errors))
    {
        $table = $wpdb->prefix.'cpost';
        $cps = $wpdb->get_results("SELECT id FROM $table WHERE cp=$cp");
        if(count($cps)==0 && !in_array('cpost', $errors) && $rappel==0) array_push($errors,'cps');
    }
    
    
    
    //
    
    if(count($errors)==0)
    {    
        // enregistrement
        
        $date=date("Y-m-d H:i:s");
        
        $table = $wpdb->prefix.'inscription';
        $wpdb->insert(
            $table,
            array(
            'nomeleve'=>$nomeleve,
            'prenomeleve'=>$prenomeleve,
            'prenomparent'=>$prenomparent,
            'nomparent'=>$nomparent,
            'lieunaissparent'=>$naissparent,
            'naisseleve'=>$naisseleve,
            'scolniv'=>$scolniv,
            'adresse'=>$adress,
            'cp'=>$cp,
            'ville'=>$ville,
            'cmail'=>$cmail,
            'tel'=>$tel,
            'mobile'=>$mobile,
            'idate'=>$date,
            'rappel'=>$rappel
            )
            );
        
         add_filter( 'wp_mail_content_type', 'set_html_content_type' );
        
       // envoil mail au client
        $subject='DPG-Education : confirmation pré-inscription';
        $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
        $message.='<tr><td>&nbsp;</td></tr>';
        $message.='<tr>';
        $message.='<td align="center">';
        $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
        $message.='<tr><td>&nbsp;</td></tr>';
        $message.='<tr><td>Votre demande de pré-inscription a bien été enregistrée</td></tr>';
        $message.='<tr><td>Nous vous contacterons par téléphone très prochainement</td></tr>';
        $message.='<tr><td>&nbsp;</td></tr>';
        $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
        $message.='</table>';
        $message.='</td></tr></table>';
        
        $headers = 'From: DPG-Education <no-reply@dpg-education.fr>'."\r\n";
        $boo=wp_mail($cmail,$subject,$message,$headers);
        if($boo){
            //wp_send_json('success');
        }else{
            wp_send_json('errormail');
        }
        
        // envoi mail à l'admin inscription
        $subject='DPG-Formation.fr : nouvelle pré-inscription';
        $message='Une nouvelle demande de pré-inscription a été enregistrée'."\n\n".'Détails de la demande : '."\n\n";
        $message.='Nom de l\'élève : '.$nomeleve."\n";
        $message.='Prénom de l\'élève : '.$prenomeleve."\n";
        $message.='Nom des parents (titulaire) : '.$nomparent."\n";
        $message.='Niveau scolaire : '.$scolniv."\n";
        $message.='Code Postal : '.$cp."\n";
        $message.='Ville : '.$ville."\n";
        $message.='Mail : '.$cmail."\n";
        $message.='Téléphone : '.$tel."\n";
        $message.='Mobile : '.$mobile."\n\n";
        if($rappel==1)
        {
            $message.='Hors zones d\'intervention'."\n";
            $message.='Demande à être rappelé'."\n";
        }
        $boo=wp_mail('inscription@dpg-education.fr',$subject,$message,$headers);
        
        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
        
        if($boo){
            wp_send_json('confirminscrp');
        }else{
            wp_send_json('errormail');
        }
    }else{
        
        wp_send_json($errors);
    }
}


function _ajax_contact()
{
    global $wpdb;
    
    // securite

    check_ajax_referer('ajax_contact_nonce','security');
    
    // tableau erreur
    $errors = array();
    
    $nom=wp_strip_all_tags($_POST['nom']);
    $prenom=wp_strip_all_tags($_POST['prenom']);
    $mail=wp_strip_all_tags($_POST['mail']);
    $tel=wp_strip_all_tags($_POST['tel']);
    $message=wp_strip_all_tags($_POST['message']);
    
    $iduser=0;
    $current_user = wp_get_current_user();
    if($current_user) $iduser=$current_user->ID;
    
    $nom=trim($nom);
    $nom=trim($prenom);
    $message=trim($message);
    
    if(empty($nom)) array_push($errors,'nom');
    if(empty($prenom)) array_push($errors,'prenom');
    if(empty($message)) array_push($errors,'message');
    
    // test email
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(!preg_match($Syntaxe,$mail)) array_push($errors,'mail');
    
    // test tel
    if(!preg_match ("(^[0-9]*$)", $tel) || strlen($tel)!=10) array_push($errors,'tel');
    
    if(count($errors)==0)
    {
         $date=date("Y-m-d H:i:s");
        
        $table = $wpdb->prefix.'contact';
        $wpdb->insert(
            $table,
            array(
            'nom'=>$nom,
            'prenom'=>$prenom,
            'mail'=>$mail,
            'tel'=>$tel,
            'message'=>$message,
            'iduser'=>$iduser
            )
            );
        
        // envoi mail à l'admin contact
        $headers = 'From: DPG-Education <no-reply@dpg-education.fr>'."\r\n";
        $subject='DPG-Education.fr : nouveau contact';
        $msg='Une nouvelle demande de contact a été enregistrée'."\n\n".'Détails de la demande : '."\n\n";
        $msg.='Nom : '.$nom."\n";
        $msg.='Prénom : '.$prenom."\n";
        $msg.='mail : '.$mail."\n";
        $msg.='tel : '.$tel."\n";
        $msg.='Code message : '."\n";
        $msg.=$message."\n";

        $boo=wp_mail('contact@dpg-education.fr',$subject,$msg,$headers);
        if($boo){
            wp_send_json('confirmcontact');
        }else{
            wp_send_json('errormail');
        }
        
    }else{
        wp_send_json($errors);
    }
}



// rechercher un cours

function _ajax_rechcours()
{
    check_ajax_referer('ajax_rechcours_nonce','security');
    
    $matiere=wp_strip_all_tags($_POST['matiere']);
    $niveau=wp_strip_all_tags($_POST['niveau']);
    $cp=wp_strip_all_tags($_POST['cp']);
    
    $ret=0;
    
    global $wpdb;
    
    // matiere
    if($matiere>0 && $niveau==0 && $cp==0)
    {
        $mat=get_category($matiere);
        $args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'role'         => 'prof',
            'meta_query'   => array (array('key' => 'matiere', 'value' => '-'.$matiere.'-', 'type'=>'CHAR', 'compare' => 'LIKE'), array('key' => 'inactif', 'value' => 0, 'compare' => '=')),
            'orderby'      => 'last_name',
            'order'        => 'ASC',
            'offset'       => '',
            'search'       => '',
            'number'       => '',
            'count_total'  => false,
            'fields'       => 'all',
            'who'          => ''
            );
            $profs=get_users( $args );
            if(count($profs)>0)
            {
                $ret='<ul>';
                foreach ($profs as $prof)
                {
                    $ret.='<li><a href="professeur?id='.$prof->ID.'&matiere='.$mat->slug.'">'.$prof->first_name.' '.$prof->last_name.'</a><li>'; 
                }
                $ret.='</ul>';
            }else{
                $ret=0;
            }
    }
    
    if($matiere>0 && $niveau>0 && $cp==0)
    {
        $mat=get_category($matiere);
        $table=$wpdb->prefix.'profmatinterv';
        $rows=$wpdb->get_results("SELECT profid FROM $table WHERE $table.matiereid=$matiere AND $table.nivscolid=$niveau");
        if(count($rows)>0)
        {
            $ret='<ul>';
            foreach($rows as $row)
            {
                $prof=get_user_by( 'id', $row->profid );
                $ret.='<li><a href="professeur?id='.$prof->ID.'&matiere='.$mat->slug.'">'.$prof->first_name.' '.$prof->last_name.'</a><li>'; 
            }
            $ret.='</ul>';
        }else{
            $ret=0;
        }
    }
    
    if($matiere>0 && $niveau>0 && $cp>0)
    {
        $mat=get_category($matiere);
        $table=$wpdb->prefix.'profmatinterv';
        
        $args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'role'         => 'prof',
            'meta_query'   => array (array('key' => 'zonegeo', 'value' => $cp, 'type'=>'CHAR', 'compare' => 'LIKE'), array('key' => 'inactif', 'value' => 0, 'compare' => '=')),
            'orderby'      => 'last_name',
            'order'        => 'ASC',
            'offset'       => '',
            'search'       => '',
            'number'       => '',
            'count_total'  => false,
            'fields'       => 'all',
            'who'          => ''
            );
            $profs=get_users( $args );
            if(count($profs)>0)
            {
                $ret='<ul>';
                foreach($profs as $prof)
                {
                    $row=$wpdb->get_row("SELECT profid FROM $table WHERE $table.matiereid=$matiere AND $table.nivscolid=$niveau AND profid=$prof->ID");
                    if(is_object($row))
                    {
                        $ret.='<li><a href="professeur?id='.$prof->ID.'&matiere='.$mat->slug.'">'.$prof->first_name.' '.$prof->last_name.'</a><li>'; 
                    }
                }
                $ret.='</ul>';
            }else{
                $ret=0;
            }
    }
    
    wp_send_json($ret);
}



// mofidication du profil

function _ajax_modifprofil()
{
     
    // securite

    check_ajax_referer('ajax_modifprofil_nonce','security');
    
    // tableau erreur
    $errors = array();
    
    $nomeleve = wp_strip_all_tags($_POST['nomeleve']);
    $prenomeleve = wp_strip_all_tags($_POST['prenomeleve']);
    $nomparent = wp_strip_all_tags($_POST['nomparent']);
    $prenomparent = wp_strip_all_tags($_POST['prenomparent']);
    $lieunaiss = wp_strip_all_tags($_POST['parentnaiss']);
    $naisseleve = wp_strip_all_tags($_POST['naisseleve']);
    $scolniv = wp_strip_all_tags($_POST['nivscol']);
    $adress = wp_strip_all_tags($_POST['adress']);
    $cp = wp_strip_all_tags($_POST['cp']);
    $ville = wp_strip_all_tags($_POST['ville']);
    $cmail = wp_strip_all_tags($_POST['cmail']);
    $tel = wp_strip_all_tags($_POST['tel']);
    $mobile = wp_strip_all_tags($_POST['mobile']);
    
    $tel= str_replace(CHR(32),'',$tel); 
    $mobile=str_replace(CHR(32),'',$mobile); 
    $cp=str_replace(CHR(32),'',$cp);
    $naisseleve=str_replace(CHR(32),'',$naisseleve);
    
    // test email
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(!preg_match($Syntaxe,$cmail)) array_push($errors,'cemail');
    
    // test tel
    if(!preg_match ("(^[0-9]*$)", $tel) || strlen($tel)!=10) array_push($errors,'ctel');
    //if(!is_numeric($tel) || ceil(log10($tel))!=10)  array_push($errors,'ctel');
    
    // test mobile
    if(!preg_match ("(^[0-9]*$)", $mobile) || strlen($mobile)!=10) array_push($errors,'cmob');
    //if(!is_numeric($mobile) || ceil(log10($mobile))!=10)  array_push($errors,'cmob');
    
    //test cp
    if(!preg_match ("(^[0-9]*$)", $cp) || strlen($cp)!=5) array_push($errors,'cpost');
    //if(!is_numeric($cp) || ceil(log10($cp))!=5)  array_push($errors,'cpost');
    
    // test date naissance
    if(!preg_match( '`^\d{1,2}/\d{1,2}/\d{4}$`' , $naisseleve ))
    {
        array_push($errors,'bitrhday');
    }else{
        $dates = explode("/", $naisseleve); 
        if(intval($dates[0])>31 || intval($dates[1])>12 || intval($dates[0])==0 || intval($dates[1])==0 ) array_push($errors,'bitrhday');
    }
    
    $rappel=0;
    if(isset($_POST['rappel'])) $rappel=1;
    
    
    // test si cp présent dans la base
    
    global $wpdb;

    $table = $wpdb->prefix.'cpost';
    $cps = $wpdb->get_results("SELECT id FROM $table WHERE cp=$cp");
    if(count($cps)==0 && !in_array('cpost', $errors) && $rappel==0) array_push($errors,'cps');
    
    
    
    if(count($errors)==0)
    { 
        $current_user = wp_get_current_user();
    
        // met à jour profil etendu
    
        update_user_meta( $current_user->ID, 'first_name', $prenomeleve );
        update_user_meta( $current_user->ID, 'last_name', $nomeleve );
        update_user_meta( $current_user->ID, 'nomparent', $nomparent );
        update_user_meta( $current_user->ID, 'lieunaissparent', $lieunaiss );
        update_user_meta( $current_user->ID, 'datenaissance', $naisseleve );
        update_user_meta( $current_user->ID, 'nivscol', $scolniv );
        update_user_meta( $current_user->ID, 'adresse', $adress );
        update_user_meta( $current_user->ID, 'ville', $ville );
        update_user_meta( $current_user->ID, 'cp', $cp );
        update_user_meta( $current_user->ID, 'tel', $tel );
        update_user_meta( $current_user->ID, 'mobile', $mobile );
         
        wp_send_json('confirmmodif');
    
     }else{
         
         wp_send_json($errors);
     }
    
    
}



// ajoute un cours sur l'agenda

function _ajax_addcours()
{
    
    global $wpdb;
    
    // securite

    check_ajax_referer('ajax_addcours_nonce','security');
    
    // check if prof
    
    $current_user = wp_get_current_user();
    
    if($current_user->roles[0]=='prof')
    {
        
        //protect
        
        $idprof = $current_user->ID;
        $titre = wp_strip_all_tags($_POST['eventTitle']);
        $hdebtxt = wp_strip_all_tags($_POST['eventStart']);
        $hfintxt = wp_strip_all_tags($_POST['eventEnd']);
        $comment = wp_strip_all_tags($_POST['comment']);
        $heuredeb = wp_strip_all_tags($_POST['hdeb']);
        $heurefin = wp_strip_all_tags($_POST['hfin']);
        $start = wp_strip_all_tags($_POST['start']);
        $end = wp_strip_all_tags($_POST['end']);
        if(isset($_POST['matiere']))
        {
            $matiere = wp_strip_all_tags($_POST['matiere']);
        }else{
            wp_send_json('error');
            return;
        }

        
        $date=date("Y-m-d H:i:s");
        
        // enregistrement
        
        $table = $wpdb->prefix.'agendaprof';
        $wpdb->insert(
            $table,
            array(
            'id_prof'=>$idprof,
            'titre'=>$titre,
            'hdebtxt'=>$hdebtxt,
            'hfintxt'=>$hfintxt,
            'comment'=>$comment,
            'heuredeb'=>$heuredeb,
            'heurefin'=>$heurefin,
            'tstart'=>$start,
            'tend'=>$end,
            'cdate'=>$date,
            'idmatiere'=>$matiere
            )
            );
        
        wp_send_json('success');
    
    }else{
        wp_send_json('error');
    }
    
    
}



// refresh calendar prof connecte

function _ajax_refreshprof()
{
    check_ajax_referer('refresh_nonce_calprof');
    
    $current_user = wp_get_current_user();
    
    if($current_user->ID!=0 && $current_user->roles[0]=='prof')
    {
        global $wpdb;
        
        $id=$current_user->ID;
        
        $date = new DateTime();
        $curdate=$date->getTimestamp();
        
        $start = wp_strip_all_tags($_POST['start']);
        $end = wp_strip_all_tags($_POST['end']);
        
        $table = $wpdb->prefix.'agendaprof';
        $tableb = $wpdb->prefix.'terms';
        $tablec = $wpdb->prefix.'users';
        //$res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$id AND id_prof IN (SELECT id_prof FROM $table where start>=$start AND end<=$end)", ARRAY_A);
        $res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$id AND tstart>=$start AND tend<=$end ORDER BY id_prof ASC", ARRAY_A);
        //$res = $wpdb->get_results("SELECT * FROM $table WHERE (tstart BETWEEN $start AND $end) AND id_prof=$id ORDER BY id_prof ASC", ARRAY_A);
        
        $arret=array();
        
        
        foreach($res as $rs)
        {
            $arr=array();
            $arr['id']=$rs['idagn'];
            $arr['title']=stripslashes($rs['titre']);
            $arr['start']=$rs['tstart'];
            $arr['end']=$rs['tend'];
            $arr['allDay']=false;
            $cat=get_category( $rs['idmatiere'] );
            $arr['matiere']=$cat->name;
            $arr['eleve']='';
            $arr['retenu']=$rs['retenu'];
            $arr['validee']=$rs['validee'];
            if($rs['eleveid']>0)
            {
                $id=$rs['eleveid'];
                $infelevef=get_userdata( $id );
                $arr['eleve']=$infelevef->last_name.' '.$infelevef->first_name;
                $arr['adresse']=esc_attr( get_the_author_meta( 'adresse', $infelevef->ID ) );
                $arr['ville']=esc_attr( get_the_author_meta( 'ville', $infelevef->ID ) );
                $arr['cp']=esc_attr( get_the_author_meta( 'cp', $infelevef->ID ) );
                $arr['tel']=esc_attr( get_the_author_meta( 'tel', $infelevef->ID ) );
                $arr['mobile'] = esc_attr( get_the_author_meta( 'mobile', $infelevef->ID ) );
                $arr['nivscol']=esc_attr( get_the_author_meta( 'nivscol', $infelevef->ID ) );
            }
            
            // gestion couleur
            if($rs['tend']<$curdate)
                {
                    $arr['allow']=false;
                    $color='#ccc';
                    $arr['color']=$color;
                }else{
            if($rs['retenu']==0 && $rs['validee']==0) $color='#7cd316';
            if($rs['validee']==1) $color='#747474';
            if($rs['retenu']==1 && $rs['validee']==0) $color='#f07700';
            }
            $arr['color']=$color;
            
            array_push($arret,$arr);
        }
        
        
        
        wp_send_json($arret);
        
       // wp_send_json($res);
        
    }
    
    
}



// enregistre resize event

function _ajax_eventResize()
{
    check_ajax_referer('resize_nonce_event');
    
    $current_user = wp_get_current_user();
    
    if($current_user->roles[0]=='prof')
    {
        global $wpdb;
        
        $id=$current_user->ID;
        
        $idevent = wp_strip_all_tags($_POST['id']);
        $tend = wp_strip_all_tags($_POST['tend']);
        $heurefin = wp_strip_all_tags($_POST['end']);
        $hfintxt = wp_strip_all_tags($_POST['hfintxt']);
        
     
        $table = $wpdb->prefix.'agendaprof';
        
        $res = $wpdb->get_row("SELECT * FROM $table WHERE idagn=$idevent AND id_prof=$id AND eleveid>0");
        
        $upres = $wpdb->update($table,
                            array(
                                'tend'=>$tend,
                                'heurefin'=>$heurefin,
                                'hfintxt'=>$hfintxt
                            ),
                            array(
                                'idagn'=>$idevent,
                                'id_prof'=>$id
                            )
                        );
        
        // envoi mail à l'élève si inscrit au cours
        if($res)
        {
            $eleve=get_user_by( 'id', $res->eleveid );
            
            $deb=intval($res->tstart);
            $MNTTZ = new DateTimeZone('Europe/Paris');
            $hdeb=new DateTime("@$deb");
            $hdeb->setTimezone($MNTTZ);
            
            $fin=intval($res->tend);
            $hfin=new DateTime("@$fin");
            $diff = $hdeb->diff($hfin);
            $conso1=60*$diff->format('%h')+$diff->format('%i');
            
            $fin=intval($tend);
            $hfin=new DateTime("@$fin");
            $diff2 = $hdeb->diff($hfin);
            $conso2=60*$diff2->format('%h')+$diff2->format('%i');
            
             // update conso heure
            $conso=$conso2-$conso1;
            $table=$wpdb->prefix.'heures';
            $add=$wpdb->query("UPDATE $table SET consomme=consomme+$conso WHERE eleve_id=$eleve->ID");
            
            $row=$wpdb->get_row("SELECT * FROM $table WHERE eleve_id=$eleve->ID");
                
                if(is_object($row))
                {
                    $reste=($row->credit*60)-$row->consomme;
                    $consom=floor($row->consomme/60).'h '.($row->consomme%60).'mn';
                    $rst=floor($reste/60).'h '.($reste%60);
                    $ret=array('success',$row->credit,$consom,$rst);
                   // wp_send_json('success');
                }else{
                   // wp_send_json('error');
                }
            
                add_filter( 'wp_mail_content_type', 'set_html_content_type' );
            
                

                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Modification horaire de cours :</strong></font><td>';
                $message.='</tr>';
                
                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                
               
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$res->idmatiere;
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $message.='<td><font size="3">Professeur : '.$current_user->first_name.' '.$current_user->last_name.'</font><td>';
                $message.='</tr>';
            
                $message.='<tr><td><font size="3" color="#ff0000"><strong>Ce cours a été modifié</strong></font></td></tr>';
                $message.='<tr><td><font size="3" color="#ff0000"><strong>Nouvelle durée du cours : </strong></font></td></tr>';
                
                $message.='<tr>';
                $message.='<td><font size="3">'.$diff2->format('%h').'h '.$diff2->format('%i').'mn</font></td>';
                $message.='</tr>';
            
                $message.='<tr><td><strong>Votre compte d\'heures :</strong></td></tr>';
                $message.='<tr><td>Crédit initial : '.$row->credit.'</td></tr>';
                $message.='<tr><td>Heures consommées : '.$consom.'</td></tr>';
                $message.='<tr><td>Heures disponibles : '.$rst.'</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                

                $m=wp_mail( $eleve->user_email, 'dpg-formation.fr : Modification horaire de cours', $message, $headers );
                
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
            
               
                        
        }
        
         wp_send_json('success');
        
        
    }
}

// enregistre drop event

function _ajax_eventDrop()
{
    check_ajax_referer('drop_nonce_event');
    
    $current_user = wp_get_current_user();
    
    if($current_user->roles[0]=='prof')
    {
        global $wpdb;
        
        $id=$current_user->ID;
        
        $idevent = wp_strip_all_tags($_POST['id']);
        $tend = wp_strip_all_tags($_POST['tend']);
        $tstart = wp_strip_all_tags($_POST['tstart']);
        $heurefin = wp_strip_all_tags($_POST['end']);
        $heuredeb = wp_strip_all_tags($_POST['start']);
        $hfintxt = wp_strip_all_tags($_POST['hfintxt']);
        $hdebtxt = wp_strip_all_tags($_POST['hdebtxt']);
        
        $table = $wpdb->prefix.'agendaprof';
        
        $res = $wpdb->get_row("SELECT * FROM $table WHERE idagn=$idevent AND id_prof=$id AND eleveid>0");
        
        // mise à jour des horaires
        $upres = $wpdb->update($table,
                            array(
                                'tend'=>$tend,
                                'tstart'=>$tstart,
                                'heuredeb'=>$heuredeb,
                                'heurefin'=>$heurefin,
                                'hdebtxt'=>$hdebtxt,
                                'hfintxt'=>$hfintxt
                            ),
                            array(
                                'idagn'=>$idevent,
                                'id_prof'=>$id
                            )
                        );
        // envoi mail à l'élève si inscrit au cours
        if($res)
        {
            
             add_filter( 'wp_mail_content_type', 'set_html_content_type' );

                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Modification horaire de cours :</strong></font><td>';
                $message.='</tr>';
                $deb=intval($res->tstart);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                $fin=intval($res->tend);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$res->idmatiere;
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $message.='<td><font size="3">Professeur : '.$current_user->first_name.' '.$current_user->last_name.'</font><td>';
                $message.='</tr>';
            
                $message.='<tr><td><font size="3" color="#ff0000"><strong>Ce cours a été modifié</strong></font></td></tr>';
                $message.='<tr><td><font size="3" color="#ff0000"><strong>Nouvel horaire : </strong></font></td></tr>';
            
                $deb=intval($tstart);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                $fin=intval($tend);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
            
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                $eleve=get_user_by( 'id', $res->eleveid );

                $m=wp_mail( $eleve->user_email, 'dpg-formation.fr : Modification horaire de cours', $message, $headers );
                
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                        
        }
        
        wp_send_json('success');
  
    }
    
}



// efface ou valide un cours

function _ajax_modifcours()
{
    check_ajax_referer('ajax_modifcours_nonce','security');
    
    $current_user = wp_get_current_user();
    
    if($current_user->roles[0]=='prof')
    {
        global $wpdb;
        $idprof=$current_user->ID;
        $action = wp_strip_all_tags($_POST['actiontype']);
        $idevt = wp_strip_all_tags($_POST['id']);
        switch($action)
        {
            case 'effacer':
            
            $table = $wpdb->prefix.'agendaprof';
            
            $res=$wpdb->get_row("SELECT * FROM $table WHERE idagn=$idevt AND eleveid>0");
        
            $del = $wpdb->delete($table,
                            array(
                                'idagn'=>$idevt,
                                'id_prof'=>$idprof
                            ),
                            array('%d')
                        );
            
            
            // envoi d'un mail à l'eleve si inscrit au cours
                      
            if($res)
            {
                $deb=intval($res->tstart);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                
                $fin=intval($res->tend);
                $hfin=new DateTime("@$fin");
                $diff2 = $hdeb->diff($hfin);
                
                // update conso heure
                $conso=60*$diff->format('%h')+$diff->format('%i');
                $table=$wpdb->prefix.'heures';
                $add=$wpdb->query("UPDATE $table SET consomme=consomme-$conso WHERE eleve_id=$eleve->ID");
                
                $row=$wpdb->get_row("SELECT * FROM $table WHERE eleve_id=$eleve->ID");
                
                if(is_object($row))
                {
                    $reste=($row->credit*60)-$row->consomme;
                    $consom=floor($row->consomme/60).'h '.($row->consomme%60).'mn';
                    $rst=floor($reste/60).'h '.($reste%60);
                    $ret=array('success',$row->credit,$consom,$rst);
                }

                add_filter( 'wp_mail_content_type', 'set_html_content_type' );

                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Suppression de cours :</strong></font><td>';
                $message.='</tr>';
                
                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
               
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff2->format('%h').'h '.$diff2->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$res->idmatiere;
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                //$prof=get_user_by( 'id', $idprof );
                $message.='<td><font size="3">Professeur : '.$current_user->first_name.' '.$current_user->last_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr><td><font size="3" color="#ff0000"><strong>Ce cours a été supprimé</strong></font></td></tr>';
                
                $message.='<tr><td><strong>Votre compte d\'heures :</strong></td></tr>';
                $message.='<tr><td>Crédit initial : '.$row->credit.'</td></tr>';
                $message.='<tr><td>Heures consommées : '.$consom.'</td></tr>';
                $message.='<tr><td>Heures disponibles : '.$rst.'</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                $eleve=get_user_by( 'id', $res->eleveid );

                wp_mail( $eleve->user_email, 'dpg-formation.fr : suppression de cours', $message, $headers );
                
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
                
            }
            
            
            
            break;
            
            case 'valider':
            
            $table = $wpdb->prefix.'agendaprof';
            
            $upd=$wpdb->update($table, array('validee'=>1),array('id_prof'=>$idprof));
            
            break;
        }
    }
    
    wp_send_json('success');
    
}



// refresh agenda utilisateur non connecte

function _ajax_refreshprofnopriv()
{


        $idprof=wp_strip_all_tags($_POST['id']);
        $userprof=get_user_by( 'id', $idprof ); 
    
        // verifie si a id correspond un prof
        if($userprof && $userprof->roles[0]=='prof')
        {
            
            global $wpdb;
            
            $start = wp_strip_all_tags($_POST['start']);
            $end = wp_strip_all_tags($_POST['end']);
            
            $date = new DateTime();
            $curdate=$date->getTimestamp();
            
            $table = $wpdb->prefix.'agendaprof';
            //$res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$id AND id_prof IN (SELECT id_prof FROM $table where start>=$start AND end<=$end)", ARRAY_A);
            $res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$idprof AND tstart>=$start AND tend<=$end ORDER BY id_prof ASC", ARRAY_A);
            //$res = $wpdb->get_results("SELECT * FROM $table WHERE (tstart BETWEEN $start AND $end) AND id_prof=$id ORDER BY id_prof ASC", ARRAY_A);
            
            $arret=array();
            
            
            foreach($res as $rs)
            {
                $arr=array();
                $arr['id']=$rs['idagn'];
                $arr['title']=stripslashes($rs['titre']);
                $arr['start']=$rs['tstart'];
                $arr['end']=$rs['tend'];
                $arr['allDay']=false;
                $arr['matiere']=$rs['idmatiere'];
                // gestion couleur
                if($rs['tend']<$curdate)
                {
                    $arr['allow']=false;
                    $color='#ccc';
                    $arr['color']=$color;
                }else{
                if($rs['retenu']==0 && $rs['validee']==0) $color='#7cd316';
                if($rs['validee']==1) $color='#9f9f9f';
                if($rs['retenu']==1 && $rs['validee']==0) $color='#f07700';
                }
                $arr['color']=$color;
                
                array_push($arret,$arr);
            }
            
        }
        
         wp_send_json($arret);
    
        //wp_send_json('ok');
}



// refresh agenda eleve connecte

function _ajax_refreshprofappren()
{
    check_ajax_referer('refresh_nonce_appren','security');
    
    $idprof=wp_strip_all_tags($_POST['id']);
    $userprof=get_user_by( 'id', $idprof ); 
    $current_user = wp_get_current_user();
    $ideleve=$current_user->ID;
    
    if($userprof && $userprof->roles[0]=='prof' && $current_user->roles[0]=='appren')
    {
        global $wpdb;
        
        
        $start = wp_strip_all_tags($_POST['start']);
        $end = wp_strip_all_tags($_POST['end']);
        
        $table = $wpdb->prefix.'agendaprof';
        //$res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$id AND id_prof IN (SELECT id_prof FROM $table where start>=$start AND end<=$end)", ARRAY_A);
        $res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$idprof AND tstart>=$start AND tend<=$end ORDER BY id_prof ASC", ARRAY_A);
        //$res = $wpdb->get_results("SELECT * FROM $table WHERE (tstart BETWEEN $start AND $end) AND id_prof=$id ORDER BY id_prof ASC", ARRAY_A);
        
        $arret=array();
        
        
        foreach($res as $rs)
        {
            $arr=array();
            $arr['id']=$rs['idagn'];
            $arr['idprof']=$idprof;
            $arr['retenu']=$rs['retenu'];
            $arr['validee']=$rs['validee'];
            $arr['title']=stripslashes($rs['titre']);
            $arr['start']=$rs['tstart'];
            $arr['end']=$rs['tend'];
            $arr['allDay']=false;
            $arr['matiere']=$rs['idmatiere'];
            $mat=get_the_category_by_ID( $rs['idmatiere'] );
            $arr['matierename']=$mat;
            $arr['comment']=$rs['comment'];
            // gestion couleur
            if($rs['retenu']==0 && $rs['validee']==0) $color='#7cd316';
            if($rs['validee']==1) $color='#9f9f9f';
            if($rs['retenu']==1 && $rs['validee']==0) $color='#f07700';
            $arr['allow']=true;
            if($rs['eleveid']!=$ideleve && $rs['eleveid']!=0 ){
                $arr['allow']=false;
                $color='#ccc';
            }
            $arr['color']=$color;
            array_push($arret,$arr);
        }
        
        
        
        wp_send_json($arret);
        
       // wp_send_json($res);
        
    }
    
    
}


// test conso eleve
function _ajax_testconso()
{
    check_ajax_referer('testconso_nonce','security');
    $id=wp_strip_all_tags($_POST['id']);
    $duree=wp_strip_all_tags($_POST['duree']);
    
    $ret='notallowed';
    
    global $wpdb;
    $table=$wpdb->prefix.'heures';
    $row=$wpdb->get_row("SELECT * FROM $table WHERE eleve_id=$id");
    if(is_object($row))
    {
        if(($row->consomme+$duree)<=($row->credit*60))
        {
            $ret='allowed';
        }
    }
    wp_send_json($ret);
    
}


// reservation d'un cours
function _ajax_reserve()
{
    
    check_ajax_referer('ajax_reserve_nonce','security');
    
     if(is_user_logged_in()){
         $current_user = wp_get_current_user();
        if($current_user->roles[0]!='appren')
        {
            wp_send_json('noappren');
        }else{
            $idprof=wp_strip_all_tags($_POST['idprof']);
            $idagen=wp_strip_all_tags($_POST['id']);
            $ideleve=$current_user->ID;
            
            global $wpdb;
            // verifie si creneau pas retenu
            $table = $wpdb->prefix.'agendaprof';
            $res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$idprof AND idagn=$idagen AND retenu=0", ARRAY_A);
            if(count($res)>0)
            {
                
                $upd = $wpdb->update($table,
                            array(
                                'retenu'=>1,
                                'eleveid'=>$ideleve
                            ),
                            array(
                                'idagn'=>$idagen,
                                'id_prof'=>$idprof
                            )
                        );
                
                $table = $wpdb->prefix.'agendaeleve';
                // reprogramme dans la table agenda eleve si present
                
                $upd2 = $wpdb->update($table,
                            array(
                                'deprog'=>0

                            ),
                            array(
                                'eleveid'=>$ideleve,
                                'idprof'=>$idprof,
                                'idagenprof'=>$idagen
                            )
                        );
                
                // sinon ajout dans la table agenda eleve
                if(!$upd2)
                {    
                    $upd =  $wpdb->insert(
                        $table,
                        array(
                        'eleveid'=>$ideleve,
                        'idprof'=>$idprof,
                        'idagenprof'=>$idagen
                        )
                    );
                }
                
                
                $deb=intval($res[0]['tstart']);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                
                $fin=intval($res[0]['tend']);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                
                // update conso heure
                $conso=60*$diff->format('%h')+$diff->format('%i');
                $table=$wpdb->prefix.'heures';
                $add=$wpdb->query("UPDATE $table SET consomme=consomme+$conso WHERE eleve_id=$ideleve");
                
                $row=$wpdb->get_row("SELECT * FROM $table WHERE eleve_id=$current_user->ID");
                
                if(is_object($row))
                {
                    $reste=($row->credit*60)-$row->consomme;
                    $consom=floor($row->consomme/60).'h '.($row->consomme%60).'mn';
                    $rst=floor($reste/60).'h '.($reste%60);
                    $ret=array('success',$row->credit,$consom,$rst);
                    //wp_send_json($ret);
                }else{
                   // wp_send_json('error');
                }
                
                // envoi des mails
                // à l'eleve
                
               
                
                add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Récapitulatif de réservation de cours :</strong></font><td>';
                $message.='</tr>';

                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$res[0]['idmatiere'];
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $prof=get_user_by( 'id', $idprof );
                $message.='<td><font size="3">Professeur : '.$prof->first_name.' '.$prof->last_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><strong>Votre compte d\'heures :</strong></td></tr>';
                $message.='<tr><td>Crédit initial : '.$row->credit.'</td></tr>';
                $message.='<tr><td>Heures consommées : '.$consom.'</td></tr>';
                $message.='<tr><td>Heures disponibles : '.$rst.'</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';

                wp_mail( $current_user->user_email, 'dpg-formation.fr : reservation de cours', $message, $headers );
                                
                
                // envoi d'un mail au professeur
                
                $headers='From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr><td><font size="3">';
                $message.='<p><strong>Nouvelle réservation :</strong></p>';
                $message.='<p><strong>Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</strong></p>';
                $message.='<p><strong>Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</strong></p>';
                $message.='<p><strong>Matière enseignée : </strong>'.$mat->cat_name.'</p>';
                $message.='<br/>';
                $message.='<p><strong>Coordonnées de l\'élève</strong></p>';
                $message.='<p><strong>Nom : </strong>'.$current_user->last_name.'</p>';
                $message.='<p><strong>Prénom : </strong>'.$current_user->first_name.'</p>';
                $message.='<p><strong>Nom et prénom responsable : </strong>'.esc_attr( get_the_author_meta( 'nomparent', $current_user->ID ) ).' '.esc_attr( get_the_author_meta( 'prenomparent', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Adresse : </strong>'.esc_attr( get_the_author_meta( 'adresse', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Ville : </strong>'.esc_attr( get_the_author_meta( 'ville', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Code postal : </strong>'.esc_attr( get_the_author_meta( 'cp', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Téléphone : </strong>'.esc_attr( get_the_author_meta( 'tel', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Mobile : </strong>'.esc_attr( get_the_author_meta( 'mobile', $current_user->ID ) ).'</p>';
                $nivscol=esc_attr( get_the_author_meta( 'nivscol', $current_user->ID ) );
                $table=$wpdb->prefix.'niveauscolaire';
                $niv=$wpdb->get_row("SELECT nivscol FROM $table WHERE id_niv=$nivscol");
                $message.='<p><strong>Niveau scolaire : </strong>'.$niv->nivscol.'</p>';
                $message.='</font></td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                wp_mail( $prof->user_email, 'reservation de cours : '.$current_user->last_name.' '.$current_user->first_name, $message, $headers );
                
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
                
                
                if(is_object($row))
                {
                    wp_send_json($ret);
                }else{
                    wp_send_json('error');
                }
            
                
            }
            
        }
     }else{
         wp_send_json('notlogged');
     }
}


function set_html_content_type() {

	return 'text/html';
}


// deprogrammation d'un cours par l'élève
function _ajax_deprogramm()
{
    check_ajax_referer('ajax_deprogramm_nonce','security');
    
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        if($current_user->roles[0]=='appren'){
            
            $iduser = $current_user->ID;
            $idprof = wp_strip_all_tags($_POST['idprof']);
            $idevent = wp_strip_all_tags($_POST['id']);
             
            global $wpdb;
            
            // reinitialise la table prof    
            $table = $wpdb->prefix.'agendaprof';       
            $res = $wpdb->update($table,
                            array(
                                'retenu'=>0,
                                'validee'=>0,
                                'eleveid'=>0
                            ),
                            array(
                                'idagn'=>$idevent,
                                'id_prof'=>$idprof
                            )
                        );
            
            // reinitialise la table prof    
            $table = $wpdb->prefix.'agendaeleve';       
            $res = $wpdb->update($table,
                            array(
                                'deprog'=>1,
                            ),
                            array(
                                'idagenprof'=>$idevent,
                                'idprof'=>$idprof,
                                'eleveid'=>$iduser
                            )
                        );
            
            // envoi des mails
            // à l'eleve
            
            $table = $wpdb->prefix.'agendaprof';
            $res = $wpdb->get_results("SELECT * FROM $table WHERE id_prof=$idprof AND idagn=$idevent", ARRAY_A);
            if(count($res)>0)
            {
                add_filter( 'wp_mail_content_type', 'set_html_content_type' );
            
                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Annulation du cours :</strong></font><td>';
                $message.='</tr>';
                $deb=intval($res[0]['tstart']);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                $message.='<tr>';
                $message.='<td><font size="3">Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                $fin=intval($res[0]['tend']);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                
                // update conso heure
                $conso=60*$diff->format('%h')+$diff->format('%i');
                $table=$wpdb->prefix.'heures';
                $add=$wpdb->query("UPDATE $table SET consomme=consomme-$conso WHERE eleve_id=$iduser");
                
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$res[0]['idmatiere'];
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $prof=get_user_by( 'id', $idprof );
                $message.='<td><font size="3">Professeur : '.$prof->first_name.' '.$prof->last_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';

                wp_mail( $current_user->user_email, 'dpg-formation.fr : deprogrammation de cours', $message, $headers );
                
                // envoi d'un mail au professeur
                
                $headers='From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr><td><font size="3">';
                $message.='<p><strong>Annulation réservation :</strong></p>';
                $message.='<p><strong>Le '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</strong></p>';
                $message.='<p><strong>Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</strong></p>';
                $message.='<p><strong>Matière enseignée : </strong>'.$mat->cat_name.'</p>';
                $message.='<br/>';
                $message.='<p><strong>Coordonnées de l\'élève</strong></p>';
                $message.='<p><strong>Nom : </strong>'.$current_user->last_name.'</p>';
                $message.='<p><strong>Prénom : </strong>'.$current_user->first_name.'</p>';
                $message.='<p><strong>Nom et prénom responsable : </strong>'.esc_attr( get_the_author_meta( 'nomparent', $current_user->ID ) ).' '.esc_attr( get_the_author_meta( 'prenomparent', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Adresse : </strong>'.esc_attr( get_the_author_meta( 'adresse', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Ville : </strong>'.esc_attr( get_the_author_meta( 'ville', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Code postal : </strong>'.esc_attr( get_the_author_meta( 'cp', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Téléphone : </strong>'.esc_attr( get_the_author_meta( 'tel', $current_user->ID ) ).'</p>';
                $message.='<p><strong>Mobile : </strong>'.esc_attr( get_the_author_meta( 'mobile', $current_user->ID ) ).'</p>';
                $nivscol=esc_attr( get_the_author_meta( 'nivscol', $current_user->ID ) );
                $table=$wpdb->prefix.'niveauscolaire';
                $niv=$wpdb->get_row("SELECT nivscol FROM $table WHERE id_niv=$nivscol");
                $message.='<p><strong>Niveau scolaire : </strong>'.$niv->nivscol.'</p>';
                $message.='</font></td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                wp_mail( $prof->user_email, 'Annulation reservation de cours : '.$current_user->last_name.' '.$current_user->first_name, $message, $headers );
                
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
            }
            
            
        }
    }
    
    $table=$wpdb->prefix.'heures';
    $row=$wpdb->get_row("SELECT * FROM $table WHERE eleve_id=$current_user->ID");
                
    if(is_object($row))
    {
        
        $reste=($row->credit*60)-$row->consomme;
        $consom=floor($row->consomme/60).'h '.($row->consomme%60).'mn';
        $rst=floor($reste/60).'h '.($reste%60);
        $ret=array('success',$row->credit,$consom,$rst);
        wp_send_json($ret);
        }else{
            wp_send_json('error');
        }
}



function _ajax_refreshapprenpriv()
{
    
    check_ajax_referer('refreshpriv_nonce_appren','security');
    
    if(is_user_logged_in())
    {
        $current_user = wp_get_current_user();
        if($current_user->roles[0]=='appren')
        {
            $date = new DateTime();
            $curdate=$date->getTimestamp();
            
            $iduser = $current_user->ID;
            
            global $wpdb;
            
            //$tablea = $wpdb->prefix.'agendaeleve';
            $tableb = $wpdb->prefix.'agendaprof';
            $res = $wpdb->get_results("SELECT * FROM $tableb WHERE eleveid=$iduser", ARRAY_A);
            
            $arret=array();
        
        
            foreach($res as $rs)
            {
                $arr=array();
                $arr['id']=$rs['idagn'];
                $arr['idprof']=$rs['id_prof'];
                $arr['retenu']=$rs['retenu'];
                $arr['title']=stripslashes($rs['titre']);
                $arr['start']=$rs['tstart'];
                $arr['end']=$rs['tend'];
                $arr['allDay']=false;
                $arr['matiere']=$rs['idmatiere'];
                $mat=get_the_category_by_ID( $rs['idmatiere'] );
                $arr['matierename']=$mat;
                
                $userprof=get_user_by( 'id',$rs['id_prof'] ); 
                $arr['prof']=$userprof->first_name.' '.$userprof->last_name;
                
                if($rs['tend']<$curdate)
                {
                    $arr['allow']=false;
                    $color='#ccc';
                    $arr['color']=$color;
                }else{
                    // gestion couleur
                    if($rs['retenu']==0 && $rs['validee']==0) $color='#7cd316';
                    if($rs['validee']==1) $color='#9f9f9f';
                    if($rs['retenu']==1 && $rs['validee']==0) $color='#f07700';
                    $arr['allow']=true;
                    $arr['color']=$color;
                }
                /*
                
                if($rs['eleveid']!=$ideleve && $rs['eleveid']!=0 ){
                    $arr['allow']=false;
                    $color='#ccc';
                }
                
                */
                array_push($arret,$arr);
            }
            
            wp_send_json($arret);
            
        }
    }
}

function _ajax_resumecours()
{ 
    
    check_ajax_referer('resumecours_nonce_appren','security');
    
    if(is_user_logged_in())
    {
        $current_user = wp_get_current_user();
        if($current_user->roles[0]=='appren')
        {
            $iduser=$current_user->ID;
            
            global $wpdb;
            $tablea = $wpdb->prefix.'agendaeleve';
            $tableb = $wpdb->prefix.'agendaprof';
            $tablec = $wpdb->prefix.'terms';
            $tabled = $wpdb->prefix.'users';
            
            $res = $wpdb->get_results("SELECT * FROM $tablea, $tableb, $tablec, $tabled WHERE $tablea.eleveid=$iduser AND $tableb.eleveid=$tablea.eleveid AND $tablec.term_id=$tableb.idmatiere AND $tabled.ID=$tableb.id_prof GROUP BY $tableb.idagn ORDER BY $tableb.cdate DESC ", ARRAY_A);
            
            if($res)
            {
                $ret='<table class="tbresume">';
                foreach($res as $rs)
                {
                    $ret.='<tr>'."\n";
                    $ret.='<td>'.$rs['display_name'].'</td>';
                    $ret.='<td>'.$rs['name'].'</td>';
                    $deb=intval($rs['tstart']);
                    $MNTTZ = new DateTimeZone('Europe/Paris');
                    $hdeb=new DateTime("@$deb");
                    $hdeb->setTimezone($MNTTZ);
                    $ret.='<td>'.date_format($hdeb,"d-m-Y").' / '.date_format($hdeb,"H:i").'</td>';
                    $fin=intval($rs['tend']);
                    $hfin=new DateTime("@$fin");
                    $diff = $hdeb->diff($hfin); 
                    $ret.='<td>'.$diff->format('%h').'h '.$diff->format('%i').'mn</td>';
                    if(time()>$rs['tend'] && $rs['evalue']==0 && $rs['validee']==1)
                    {
                        $ret.='<td style="width:12%"><a href="#'.$rs['idagn'].'" class="evaluer" id="cours'.$rs['idagn'].'">Evaluer ce cours</a></td>';
                    }
                    if($rs['validee']==0) //time()>=$rs['tend'] && 
                    {
                        $ret.='<td style="width:12%"><a href="#'.$rs['idagn'].'" class="valider" id="cours'.$rs['idagn'].'">Valider ce cours</a></td>';
                    }
                    if($rs['evalue']==1 && $rs['validee']==1)
                    {
                        $ret.='<td style="width:12%"><a href="#'.$rs['idagn'].'" class="affeval" id="cours'.$rs['idagn'].'">Votre évaluation</a></td>';
                    }

                    $ret.='<tr>'."\n";
                }
                $ret.='</table>';
                 wp_send_json($ret);
            }else{
                wp_send_json('error');
                
            }
           
            
        }
     }
}

function _ajax_evaluercours()
{
    check_ajax_referer('evaluercours_nonce_appren','security');
    
    
    if(is_user_logged_in())
    {
        $current_user = wp_get_current_user();
        if($current_user->roles[0]=='appren')
        {
            $agn = wp_strip_all_tags($_POST['id']);
            $id=substr($agn,1);
            global $wpdb;
            
            $table=$wpdb->prefix.'agendaprof';
            $res=$wpdb->get_row("SELECT * FROM $table WHERE idagn=$id");
            
            $table2 = $wpdb->prefix.'questiontitre';
            $table3 = $wpdb->prefix.'questions';
            $res2=$wpdb->get_results("SELECT * FROM $table2, $table3 WHERE $table3.titreid=$table2.idtitrequest AND $table3.actif=1 ORDER BY ordretitre, ordrequestion");
            
            $ret='';
            $titre='';
            $bg=0;
            
            if($res2)
            {
                $prof=get_user_by( 'id', $res->id_prof );
                $ret.='<form id="frmevalu">';
                $ret.='<table class="evaluation">';
                $ret.='<tr><td colspan="2" style="font-size:100%;text-transform:uppercase;text-align:center"><strong>Questionnaire d\'évaluation</strong></td></tr>';
                foreach($res2 as $rs)
                {
                    $ret.='<tr>';
                    if ($titre!=$rs->titrequest){
                        $ret.='<td colspan="2" style="font-size:100%"><strong>'.$rs->titrequest.'</strong></td></tr><tr>';
                        $titre=$rs->titrequest;
                    }
                    $ret.='<tr>';
                    $bg++;
                    $class=$bg%2?'class="bkgd1"':'class="bkgd2"';
                    $ret.='<td width="85%" valign="center" '.$class.'>'.$rs->quest_texte.'</td>';
                    $ret.='<td width="15%" style="padding-left:8px" '.$class.'><span class="etoiles"><a href="#1" class="points"></a><a href="#2" class="points"></a><a href="#3" class="points"></a><a href="#4" class="points"></a></span><input type="hidden" name="point'.$rs->quest_id.'" value="0"/></td>';
                    $ret.='</tr>';
                }
                $ret.='<tr>';
                $ret.='<td  width="85%" valign="center" style="font-size:100%"><strong>Seriez-vous prêt à nous recommander ?</strong></td>';
                $ret.='<td valign="middle" height="20" width="15%" style="padding-left:8px"><input type="checkbox" id="recomm" name="recommander"/><strong> OUI</strong></td>';
                $ret.='</tr>';
                $ret.='</table>';
                $ret.='<table class="evaluation norecomm" style="border-top:0">';
                $ret.='<tr>';
                $ret.='<td colspan="2" style="border-bottom:0">Sinon pourquoi ?</td>';
                $ret.='</tr>';
                $ret.='<tr>';
                $ret.='<td colspan="2"><textarea style="width:98.5%; height:80px" name="commentaires"></textarea></td>';
                $ret.='</tr>';
                $ret.='</table>';
                $ret.='<table class="evaluation" style="border:0">';
                $ret.='<tr>';
                $ret.='<td colspan="2" style="text-align:center;border:0;padding-top:1%"><input type="submit" value="Envoyer" id="envoyer"/></td>';
                $ret.='</tr>';
                $ret.='</table>';
                $ret.='<input type="hidden" name="profid" value="'.$prof->ID.'"/>';
                $ret.='<input type="hidden" name="agenid" value="'.$id.'"/>';
                $ret.='<input type="hidden" name="action" value="evaluation"/>';
                $ret.=wp_nonce_field('ajax_evaluation_nonce','security',true,false);
                $ret.='<script>var ajaxurl="'.admin_url('admin-ajax.php').'"</script>';
                $ret.='</form>';
                
                $ret.='<script src="../wp-content/themes/dpg/js/points.js" type="text/javascript">';
            }
            
            wp_send_json($ret);
        }
    }
}



function _ajax_affeval()
{
    
    check_ajax_referer('affeval_nonce_appren','security');
    $id=wp_strip_all_tags($_POST['id']);
    $id=substr($id,1);
    $ret='<table class="evaluation">';
    
    global $wpdb;
    $table=$wpdb->prefix.'evaluation';
    $table2=$wpdb->prefix.'evaluationrep';
    $table3=$wpdb->prefix.'questions';
    $res=$wpdb->get_results("SELECT * FROM $table, $table2, $table3 WHERE $table.agenid=$id AND $table2.ideval=$table.eval_id AND $table2.questid=$table3.quest_id ORDER BY $table3.quest_id ASC");

    if($res)
    {
        $bg=0;
        foreach($res as $rs)
        {
            //$ret.=$rs->quest_texte.' / '.$rs->points.'<br/>';
            $bg++;
            $class=$bg%2?'class="bkgd1"':'class="bkgd2"';
            $ret.='<tr>';
            $ret.='<td width="85%" valign="center" '.$class.'>'.$rs->quest_texte.'</td>';
            $ret.='<td width="15%" style="padding-left:8px" '.$class.'><span class="etoiles point'.$rs->points.'"></span>';
            $ret.='</tr>';
        }
    }
    $ret.='</table>';
    wp_send_json($ret);
}



function _ajax_evaluation(){ // formulaire evaluation
    
    check_ajax_referer('ajax_evaluation_nonce','security');
    $current_user = wp_get_current_user();
    if($current_user->roles[0]!='appren') return;
    $agenid=wp_strip_all_tags($_POST['agenid']);
    $profid=wp_strip_all_tags($_POST['profid']);
    $eleveid= $current_user->ID;
    $comment=wp_strip_all_tags($_POST['commentaires']);
    
    global $wpdb;
    // evaluation =1
    $table=$wpdb->prefix.'agendaprof';
    $res = $wpdb->update($table,
            array(
            'evalue'=>1
            ),
            array(
            'idagn'=>$agenid
            )
     );
    
    
    // enregistrement entete evaluation
    $table=$wpdb->prefix.'evaluation';
    $wpdb->insert(
            $table,
            array(
                'eleveid'=>$eleveid,
                'profid'=>$profid,
                'agenid'=>$agenid,
                'comment'=>$comment
                ),
            array( 
                '%d', 
                '%d',
                '%d',
                '%s'
                ) 
            );
    $ideval=$wpdb->insert_id;
    
    // si $comment non vide on envoie un mail
    
    
    // recuperation des numeros id des questions
    
    $table=$wpdb->prefix.'questions';
    $table2=$wpdb->prefix.'evaluationrep';
    $res=$wpdb->get_results("SELECT quest_id FROM $table");
    if($res)
    {
        foreach($res as $rs)
        {
            if(isset($_POST['point'.$rs->quest_id]))
            {
                $point=wp_strip_all_tags($_POST['point'.$rs->quest_id]);
                $wpdb->insert(
                $table2,
                array(
                    'points'=>$point,
                    'ideval'=>$ideval,
                    'questid'=>$rs->quest_id
                    ),
                array( 
                    '%d', 
                    '%d',
                    '%d'
                    ) 
                );
                
            }
        }
    }
    $ret=array($agenid,'ok');
    wp_send_json($ret);
    
}

function _ajax_valider(){ // valide un cours
    
    check_ajax_referer('valider_nonce_appren','security');
    $id=wp_strip_all_tags($_POST['id']);
    $id=substr($id,1);
    global $wpdb;
    $table=$wpdb->prefix.'agendaprof';
    $res = $wpdb->update($table,
            array(
            'validee'=>1
            ),
            array(
            'idagn'=>$id
            )
     );
    wp_send_json(array('<div style="text-align:center;height:90px"><span style="line-height:90px;vertical-align:middle;font-size:120%"><strong>Le cours est validé !</strong></span></div>',$id));
    
}

function _ajax_validerplann(){ // valide un cours
    
    check_ajax_referer('validerplann_nonce_appren','security');
    $id=wp_strip_all_tags($_POST['id']);
    //$id=substr($id,1);
    global $wpdb;
    $table=$wpdb->prefix.'agendaprof';
    $res = $wpdb->update($table,
            array(
            'validee'=>1
            ),
            array(
            'idagn'=>$id
            )
     );
    wp_send_json(array('ok'));
    
}


function _ajax_modifprofilprof()
{
     
    $errors=array();
    $current_user = wp_get_current_user();
    
     if($current_user->roles[0]=='prof')
     {
    
        // zone geo
        $incr=0;
         
        if(!empty($_POST['nzoneprof']))
        {
            $nzgeo=wp_strip_all_tags($_POST['nzoneprof']);
            if(!preg_match ("(^[0-9]*$)", $nzgeo) || strlen($nzgeo)!=5)
            {
                array_push($errors,'nzoneprof');
                $nzgeo='0';
            }else{
                // ajout du code postal dans la table cpost (zone geographique d'intervention)
                global $wpdb;
                $table = $wpdb->prefix.'cpost';
                $res=$wpdb->insert(
                    $table,
                    array('cp'=>$nzgeo)
                    );
                if($res) $incr=$wpdb->insert_id;
            }
       }

        if(isset($_POST['zonegeo']) && is_array($_POST['zonegeo']))
        {
            $tbgeo=$_POST['zonegeo'];

            foreach ($tbgeo as &$value) {
                $value = str_replace(" ", "", $value);
            }
            unset($value);
              if($incr>0) array_push($tbgeo, $incr);
            $zonegeo=implode(" ", $tbgeo);
            update_user_meta( $current_user->ID, 'zonegeo', $zonegeo );
        }else{
            update_user_meta( $current_user->ID, 'zonegeo', '' );
            if($incr>0) update_user_meta(  $current_user->ID, 'zonegeo', $incr );
        }
         
        $adresseprof=wp_strip_all_tags($_POST['adresseprof']);
         if(empty($adresseprof)) {
             array_push($errors,'adresseprof');
         }else{
              update_user_meta( $current_user->ID, 'adresse', $adresseprof );
         }
         
        $cpprof=wp_strip_all_tags($_POST['cpprof']);
         if(!preg_match ("(^[0-9]*$)", $cpprof) || strlen($cpprof)!=5) {
             array_push($errors,'cpprof');
         }else{
            update_user_meta( $current_user->ID, 'cp', $cpprof );
         }
         
         
        $villeprof=wp_strip_all_tags($_POST['villeprof']);
         if(empty($villeprof)) {
             array_push($errors,'villeprof');
         }else{
            update_user_meta( $current_user->ID, 'ville', $villeprof );
         }
         
         
        $ancient=wp_strip_all_tags($_POST['ancient']);
         if(empty($ancient)) {
             array_push($errors,'ancient');
         }else{
            update_user_meta(  $current_user->ID, 'ancient', $ancient );
         }
        
        $titre1=wp_strip_all_tags($_POST['titre1']);
         if(empty($titre1)) {
             array_push($errors,'titre1');
         }else{
             update_user_meta(  $current_user->ID, 'titre1', $titre1 );
         }
         
        $expe1=wp_strip_all_tags($_POST['expe1']);
        if(empty($expe1)) {
             array_push($errors,'expe1');
         }else{
            update_user_meta(  $current_user->ID, 'expe1', $_POST['expe1'] );
        }
         
         
        $descrip1=wp_strip_all_tags($_POST['descrip1']);
         if(empty($descrip1)) {
             array_push($errors,'descrip1');
         }else{
             update_user_meta( $current_user->ID, 'descrip1', $descrip1 );
         }
        
         
        $titre2=wp_strip_all_tags($_POST['titre2']);
         if(empty($titre2)) {
             array_push($errors,'titre2');
         }else{
            update_user_meta(  $current_user->ID, 'titre2', $titre2 );
         }
         
         $expe2=wp_strip_all_tags($_POST['expe2']);
         if(empty($expe2)) {
             array_push($errors,'expe2');
         }else{
            update_user_meta(  $current_user->ID, 'expe2', $expe2 );
         }
         
         $descrip2=wp_strip_all_tags($_POST['descrip2']);
         if(empty($descrip2)) {
             array_push($errors,'descrip2');
         }else{
            update_user_meta(  $current_user->ID, 'descrip2', $descrip2 );
         }
           
        $titre3=wp_strip_all_tags($_POST['titre3']);
         if(empty($titre3)) {
             array_push($errors,'titre3');
         }else{
            update_user_meta(  $current_user->ID, 'titre3', $titre3 );
         }
         
         $expe3=wp_strip_all_tags($_POST['expe3']);
         if(empty($expe3)) {
             array_push($errors,'expe3');
         }else{
             update_user_meta(  $current_user->ID, 'expe3', $expe3 );
         }
         
         $descrip3=wp_strip_all_tags($_POST['descrip3']);
         if(empty($descrip3)) {
             array_push($errors,'descrip3');
         }else{
            update_user_meta(  $current_user->ID, 'descrip3', $descrip3 );
         }
         
         
         if(count($errors)==0)
        { 
              wp_send_json('ok');
         }else{
            wp_send_json($errors);
         }
         
     }
}


function _ajax_resumecoursprof()
{ 
    if(is_user_logged_in())
    {
        $current_user = wp_get_current_user();
        if($current_user->roles[0]=='prof')
        {
           $iduser=$current_user->ID;
            
            global $wpdb;
            $tablea = $wpdb->prefix.'agendaeleve';
            $tableb = $wpdb->prefix.'agendaprof';
            $tablec = $wpdb->prefix.'terms';
            $tabled = $wpdb->prefix.'users';
            
            $res = $wpdb->get_results("SELECT * FROM $tableb, $tablec, $tabled WHERE $tableb.id_prof=$iduser AND $tablec.term_id=$tableb.idmatiere AND $tabled.ID=$tableb.eleveid ORDER BY $tableb.cdate DESC", ARRAY_A);
            
            if($res)
            {
                $ret='<table class="tbresume">';
                foreach($res as $rs)
                {
                    $ret.='<tr>'."\n";
                    $ret.='<td>'.$rs['display_name'].'</td>';
                    $ret.='<td>'.$rs['name'].'</td>';
                    $deb=intval($rs['tstart']);
                    $MNTTZ = new DateTimeZone('Europe/Paris');
                    $hdeb=new DateTime("@$deb");
                    $hdeb->setTimezone($MNTTZ);
                    $ret.='<td>'.date_format($hdeb,"d-m-Y").' / '.date_format($hdeb,"H:i").'</td>';
                    $fin=intval($rs['tend']);
                    $hfin=new DateTime("@$fin");
                    $diff = $hdeb->diff($hfin); 
                    $ret.='<td>'.$diff->format('%h').'h '.$diff->format('%i').'mn</td>';
                    $ret.='<td style="width:12%">'.'action'.'</td>';
                    $ret.='<tr>'."\n";
                }
                $ret.='</table>';
                 wp_send_json($ret);
            }else{
                wp_send_json('error');
                
            }
        }
    }
}

?>