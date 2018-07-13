jQuery(document).ready(function($){   
    $('.effcp').each(function(){
        $(this).click(function(){
            var id=$(this).attr('data-id');
            $.ajax({
                url: gestion_tech.ajaxurl,//'<?php echo get_template_directory_uri(); ?>/inc/suppr_tp.php',
                type: 'POST',
                data: {
                    action:'acgestion_tech',
                    cid:id
                },
                success: function(response){
                    window.location.reload();          
                }
            });
            return false;
        });
        
    });
});