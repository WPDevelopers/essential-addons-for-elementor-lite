<?php
$post_hover_style    = !empty($post_args['eael_post_grid_hover_style']) ? ' grid-hover-style-' . $post_args['eael_post_grid_hover_style'] : 'none';
$post_carousel_image = wp_get_attachment_image_url(get_post_thumbnail_id(), $post_args['image_size']);

?>
<article class="eael-grid-post eael-post-grid-column">
    <div class="eael-grid-post-holder">
        <div class="eael-grid-post-holder-inner">
            <?php if (has_post_thumbnail() && $post_args['eael_show_image'] == 1) { ?>
                <div class="eael-entry-media<?php echo esc_attr($post_hover_style); ?>">
                    <?php if ('none' !== $post_args['eael_post_grid_hover_animation']){ ?>
                        <div class="eael-entry-overlay<?php echo ' ' . esc_attr($post_args['eael_post_grid_hover_animation']); ?>">
                            <?php if (!empty($post_args['eael_post_grid_bg_hover_icon'])) { ?>
                                <i class="<?php echo esc_attr($post_args['eael_post_grid_bg_hover_icon']); ?>" aria-hidden="true"></i>
                            <?php } else { ?>
                                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                            <?php }?>
                            <a href="<?php echo get_permalink(); ?>"></a>
                        </div>
                    <?php }?>

                    <?php if (!empty($post_carousel_image)){ ?>
                        <div class="eael-entry-thumbnail">
                            <img src="<?php echo esc_url($post_carousel_image); ?>" alt="<?php echo esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)); ?>">
                        </div>
                    <?php }?>
                </div>
            <?php }?>

            <?php if ($post_args['eael_show_title'] || $post_args['eael_show_meta'] || $post_args['eael_show_excerpt']) {?>
                <div class="eael-entry-wrapper">
                    <header class="eael-entry-header">
                        <?php if ($post_args['eael_show_title']) {?>
                            <h2 class="eael-entry-title"><a class="eael-grid-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
                        <?php }?>

                        <?php if ($post_args['eael_show_meta'] && $post_args['meta_position'] == 'meta-entry-header') {?>
                            <div class="eael-entry-meta">
                                <span class="eael-posted-by"><?php the_author_posts_link();?></span>
                                <span class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>
                            </div>
                        <?php }?>
                    </header>

                    <?php if ($post_args['eael_show_excerpt']) {?>
                        <div class="eael-entry-content">
                            <div class="eael-grid-post-excerpt">
                                <p><?php echo $this->eael_get_excerpt_by_id(get_the_ID(), $post_args['eael_excerpt_length']); ?></p>
                            </div>
                        </div>
                    <?php }?>
                </div>

                <?php if ($post_args['eael_show_meta'] && $post_args['meta_position'] == 'meta-entry-footer') {?>
                    <div class="eael-entry-footer">
                        <div class="eael-author-avatar">
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>"><?php echo get_avatar(get_the_author_meta('ID'), 96); ?> </a>
                        </div>
                        <div class="eael-entry-meta">
                            <div class="eael-posted-by"><?php the_author_posts_link();?></div>
                            <div class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></div>
                        </div>
                    </div>
                <?php }?>
            <?php }?>
        </div>
    </div>
</article>