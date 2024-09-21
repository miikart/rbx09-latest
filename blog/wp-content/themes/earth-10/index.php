<?php 
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<div class="post" id="post-<?php the_ID(); ?>">
	 <span class="posted-by"><?php the_time('F jS Y') ?></span>
	 <h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	
	<div class="storycontent">
		<?php the_content(__('(more...)')); ?>
		<div><a href="#" title="Bookmark using any bookmark manager!" target="_blank"><img src="http://web.archive.org/web/20090627022815im_/http://s9.addthis.com/button1-bm.gif" width="125" height="16" border="0" alt="AddThis Social Bookmark Button"></a></div>
<br><br>
	</div>

	<div class="post-footer">
		<div class="meta"><?php _e("Posted in:"); ?> <?php the_category(',') ?> <?php edit_post_link(__('Edit This')); ?></div>
		<div class="feedback">
	            <?php wp_link_pages(); ?>
	            <?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
		</div>
		<div class="clear"></div>
	</div>

</div>

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?>

<?php get_footer(); ?>
