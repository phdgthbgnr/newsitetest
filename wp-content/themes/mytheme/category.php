<?php 
    get_header(); 
    $cur_cat = get_cat_ID( single_cat_title("",false) );
	query_posts( 'posts_per_page=-1&cat='.$cur_cat);
    $posts = get_posts('posts_per_page=-1&post_type=post&category='.$cur_cat); 
    $count = count($posts); 
	//wp_reset_query();
?>
    <div class="nbcat">x<span><?php echo $count; ?></span></div>
        
        <!-- <ul class="rubrique"> -->
        <div class="container">
            <div class="row">
            <?php
       
                while ( have_posts() ) : the_post(); 
    
                    get_template_part( 'content-category', get_post_format() ); 

					endwhile; // end of the loop. 
					//wp_reset_query();
				?>
            <!-- </ul> -->
            </div>
        </div>

<?php get_footer(); ?>