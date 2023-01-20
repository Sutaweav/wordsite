<?php
$PeepSoActivity = PeepSoActivity::get_instance();
?>
<div class="ps-post__repost">
	<blockquote class="ps-post__quote ps-js-activity-quote"><?php $PeepSoActivity->content(); ?></blockquote>
	<div class="ps-post__attachments js-stream-attachments">
		<?php $PeepSoActivity->post_attachment(); ?>
	</div>
</div>
