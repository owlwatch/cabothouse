<?php
/**
 * Fires after the main content, before the footer is output.
 *
 * @since ??
 */
do_action( 'et_after_main_content' );

if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>
	<span class="et_pb_scroll_top et-pb-icon"></span>
<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

<footer id="main-footer">
	<?php get_sidebar( 'footer' ); ?>
	
	<?php
	if ( has_nav_menu( 'footer-menu' ) ) : ?>
	
		<div id="et-footer-nav">
			<div class="container">
				<?php
				wp_nav_menu( array(
				'theme_location' => 'footer-menu',
				'depth'          => '1',
				'menu_class'     => 'bottom-nav',
				'container'      => '',
				'fallback_cb'    => '',
				) );
				?>
			</div>
		</div> <!-- #et-footer-nav -->
	
	<?php endif; ?>

</footer> <!-- #main-footer -->

<div class="ch-footer-bar">
	<div class="footer-credits">
		<?php echo et_get_footer_credits() ?>
	</div>
	<div class="social-icons">
		<?php
		get_template_part( 'includes/social_icons', 'footer' );
		?>
	</div>
</div>

</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

</div> <!-- #page-container -->


<!-- Facebook Pixel Code -->
<script type='text/javascript'>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
</script>
<!-- End Facebook Pixel Code -->
<script type='text/javascript'>
  fbq('init', '691642787671980', {
  	"agent": "wordpress-5.6-3.0.0"
});
</script><script type='text/javascript'>
  fbq('track', 'PageView', []);
</script>
<!-- Facebook Pixel Code -->
<noscript>
<img height="1" width="1" style="display:none" alt="fbpx"
src="https://www.facebook.com/tr?id=691642787671980&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->

<script>
jQuery(document).ready(function(){
	jQuery('.et_pb_gallery_item').attr('data-action','inquire');

	jQuery('.et_pb_gallery_item').click(function(){
		var getSource = jQuery(this).find('.et_pb_gallery_image a').attr('href');
		var getSourceTitle = jQuery('.et_pb_gallery_title',this).html();
		
		setTimeout( function(){
			jQuery('.contact-widget__form .gform_wrapper').prepend('<div class="contact-widget__inquiry"><img class="contact-widget__inquiry-image" src="...."><div class="contact-widget__inquiry-title">Inquiring about <strong>....</strong></div></div>');
			
			jQuery('.contact-widget__inquiry-image').attr('src',getSource);
			jQuery('.contact-widget__inquiry-title strong').html(getSourceTitle);
			
			jQuery('#input_5_7').val(getSource);
			jQuery('#input_5_6').val(getSourceTitle);
			
			console.log(getSource);
		}, 200);	
	});
	
	
	// Show pop-form close button
	jQuery('.et_pb_gallery_image').click(function(){
		jQuery('.contact-widget-close').show();
	});
	
	// Adding close button to pop-form
	jQuery('.contact-widget').prepend('<div class="contact-widget-close">×</div>');
	jQuery('.contact-widget-close').hide();
	
	// Close pop-form when clicking image X button
	jQuery('.mfp-close').click(function(){
		console.log('close is clicked');
	    jQuery('.contact-widget').removeClass('open');
	});
		
	// Close form and hide close button
	jQuery('.contact-widget-close').click(function(){
		console.log('contact-widget-close clicked');
	    jQuery('.contact-widget').removeClass('open');
	    jQuery('.contact-widget-close').hide();
	});
	
	// Adding close when clicking .mfp-details-buttons button on furniture image
	jQuery('.et_pb_gallery_image img').click(function(){
		setTimeout( function(){
			console.log('button has been found');
			jQuery('.mfp-details-buttons .mfp-info').click(function(){
				jQuery('.contact-widget').prepend('<div class="contact-widget-close">×</div>');
				jQuery('.contact-widget-close').show();
			    console.log('mfp-info clicked');
			});
		}, 1000);
	});
	
});
</script>

<?php if (is_page('new-years-sale')) { ?>
	<!--
	Start of Floodlight Tag: Please do not remove
	Activity name of this tag: Retargeting Floodlight
	URL of the webpage where the tag is expected to be placed: http://cabothousefurniture.com/new-years-sale/
	This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
	Creation Date: 12/28/2020
	-->
	<script type="text/javascript">
	var axel = Math.random() + "";
	var a = axel * 10000000000000;
	document.write('<iframe src="https://10589887.fls.doubleclick.net/activityi;src=10589887;type=rtgzn0;cat=retar0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
	</script>
	<noscript>
	<iframe src="https://10589887.fls.doubleclick.net/activityi;src=10589887;type=rtgzn0;cat=retar0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
	</noscript>
	<!-- End of Floodlight Tag: Please do not remove -->
	
	<script>
	jQuery(document).ready(function(){
		jQuery('.click-telephone').click(function(){

			var axel = Math.random() + "";
			var a = axel * 10000000000000;
			
			jQuery('.telephone-frame-script').append('<iframe src="https://10589887.fls.doubleclick.net/activityi;src=10589887;type=conv90;cat=click0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');

		});
		
		
		jQuery('#gform_submit_button_17').click(function(){

			var axel = Math.random() + "";
			var a = axel * 10000000000000;
			
			jQuery('.telephone-frame-script').append('<iframe src="https://10589887.fls.doubleclick.net/activityi;src=10589887;type=conv90;cat=submi0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');

		});
	});
	</script>
	<div class="telephone-frame-script"></div>
<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
