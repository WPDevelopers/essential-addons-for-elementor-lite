<article class="eael-timeline-post eael-timeline-column">
    <div class="eael-timeline-bullet"></div>
    <div class="eael-timeline-post-inner">
        <a class="eael-timeline-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title();?>">
            <time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time>
            <div class="eael-timeline-post-image" <?php if ($post_args['eael_show_image'] == 1) {?> style="background-image: url('<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), $post_args['image_size']) ?>');" <?php }?>></div>
            <?php if ($post_args['eael_show_excerpt']) {?>
                <div class="eael-timeline-post-excerpt">
                    <p><?php echo $this->eael_get_excerpt_by_id(get_the_ID(), $post_args['eael_excerpt_length']); ?></p>
                </div>
            <?php }?>

            <?php if ($post_args['eael_show_title']) {?>
                <div class="eael-timeline-post-title">
                    <h2><?php the_title();?></h2>
                </div>
            <?php }?>
        </a>
    </div>
</article>