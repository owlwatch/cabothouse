<div class="contact-widget">
	<div class="contact-widget__content">
		<div class="contact-widget__block">
			<div class="contact-widget__form">
				<?php
				echo do_shortcode('[gravityform id="5" ajax="true" title="false" description="false"]');
				?>
			</div>
		</div>
	</div>
	<button class="contact-widget__btn contact-widget__trigger">
		<i data-state="closed" class="far fa-comment-alt"></i>
		<i data-state="open" class="fas fa-times"></i>
	</button>
</div>
