    <?php if ($post) { ?>
        <div class="col-xs-4 col-sm-4">
            <div class="card mb-4">
                <?php
                
                $index = $wp_query->current_post + 1;
                $index = $index<10?'0'.$index:$index;
                //$cat = wp_get_post_categories( $post->ID );
                $root = get_category_by_slug( 'realisations' );
                $categ='&nbsp;';
                $nohover='';
                $product_terms = wp_get_object_terms( $post->ID, 'category', array() );
                if($product_terms[0]->parent!=$root->term_id){
                    $categ=$product_terms[0]->name;
                }

                $class=$index%3 == 0 ? 'class="nomarginr"' : '';
                $nohover=$categ=='&nbsp;'?'nohover':'';
                $class='';
                ?>
        
                <a href="<?php echo get_page_link($post->ID); ?>"<?php echo $class ?> style="display:inline">
                <!-- <h4 class="categorie <?php echo $nohover?>"><?php echo $categ ?></h4>
                <h4 class="imagecat">    -->
                    <?php the_post_thumbnail('thumbnail', array('class' => 'card-img-top','style' => 'width:100%;height:auto;display:block')); ?>
                    <!-- <span><?php echo $index ?></span> -->
                    <!-- </h4> -->
                    <!-- <span class="stitre">
                        <h3><?php the_title() ?></h3>
                        <h4><?php echo get_post(get_post_thumbnail_id())->post_content; ?></h4>
                        <h5><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></h5>
                        <h5><?php echo $post->post_excerpt; ?></h5>
                    </span> -->
                </a>
        </div>
    </div>
<?php } ?>
