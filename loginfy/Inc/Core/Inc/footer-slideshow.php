<?php

defined( 'ABSPATH' ) || die( 'No Direct Access Sir!' );

$loginfy_slides = (array) $this->get_image_urls_by_ids( $this->options['jlt_loginfy_login_bg_slideshow'] );

if ( empty( $loginfy_slides ) ) {
	return;
}

// Slideshow Effects
// fade,
// fade2,
// slideLeft,
// slideLeft2,
// slideRight,
// slideRight2,
// slideUp,
// slideUp2,
// slideDown,
// slideDown2,
// zoomIn,
// zoomIn2,
// zoomOut,
// zoomOut2,
// swirlLeft,
// swirlLeft2,
// swirlRight,
// swirlRight2,
// burn,
// burn2,
// blur,
// blur2,
// flash,
// flash2,

?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(function() {
			jQuery('body.loginfy-login-customizer .login-background').vegas({
				slides: <?php echo json_encode( $loginfy_slides ); ?>,
				transition: 'fade',
				delay: 5000,
				timer: false
			});
		});
	});
</script>
