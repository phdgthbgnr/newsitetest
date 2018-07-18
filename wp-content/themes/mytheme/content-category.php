    <?php if ($post) { ?>
        <!-- <div class="col-sm-6 col-md-2"> -->
            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 d-flex align-items-stretch"> <!--col-xs-6 col-sm-2 -->
            <div class="row">
                <div class="card border-0 mr-2 my-1">
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
                
                $cat = get_category( get_query_var( 'cat' ) );
                $jsonarray = (array('type'=>'name','postid'=>$post->ID,'slug'=>$post->post_name,'page'=>intval($index)));
                $json = htmlspecialchars(json_encode($jsonarray), ENT_QUOTES, 'UTF-8');
                $tech = is_array(get_post_meta($post->ID,'gtechs'))?get_post_meta($post->ID,'gtechs')[0]:null;       
                 ?>
        
                <!-- <a href="<?php echo get_page_link($post->ID); ?>"<?php echo $class ?> style="display:inline"> -->
                <!-- <h4 class="categorie <?php echo $nohover?>"><?php echo $categ ?></h4>
                <h4 class="imagecat">    -->
                    <!-- thumbnail medium medium-large large full -->
                    <a href="<?php echo get_page_link($post->ID); ?>" class="ajaxcategory hovereffect" data-type="<?php echo $json ?>">
                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top','style' => 'border-bottom:1px solid #ccc;width:100%;height:auto;display:block')); ?>
                        <div class="overlay">
                            <h2><?php the_title() ?></h2>
                            <p class="info"><?php echo get_post_meta($post->ID,'libgal',true) ?></p>
                        </div>
                    </a>
                    <div class="card-body">
                        <!-- <span><?php echo $index ?></span> -->
                        <!-- </h4> -->
                        <!-- <span class="stitre">-->
                        <h5 class="card-title text-uppercase"><?php the_title() ?></h5>
                        <button  type="button" class="btn btn-outline-secondary btn-sm ajaxcategory" data-type="<?php echo $json ?>"><?php echo get_post_meta($post->ID,'libpost',true) ?></button>
                        <p class="card-text mt-2"><?php echo $post->post_excerpt; ?></p>
                            <!-- <h4><?php echo get_post(get_post_thumbnail_id())->post_content; ?></h4>
                            <h5><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></h5>
                            <h5><?php echo $post->post_excerpt; ?></h5> -->
                        <!--</span> -->
                        <p>&nbsp;</p>
                        <a href="#" data-toggle="popover" title="Techniques utilisées" data-content="Some content inside the popover">Tech</a>
                        <div class="card-footer" style="display:inline-block;position:absolute;left:0;bottom:0;width:100%">
                            <small class="text-muted"><?php echo $index ?></small>
                        </div>
                    </div>
                <!-- </a> -->
            </div>
        </div>
    </div>
<?php } ?>