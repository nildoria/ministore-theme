<?php
/**
 * External OTP Form
 *
 * This template can be overridden by copying it to yourtheme/templates/hello-elmentor/xoo-ml-external-otp-form.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/hello-elmentor/
 * @version 2.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$get_current_author = get_query_var('author_name');
$get_current_puser = get_user_by('login', $get_current_author); 
$current_user_id = isset( $get_current_puser->ID ) ? $get_current_puser->ID : '';

?>

<form class="xoo-ml-otp-form">

	<?php 
	if( 
		$get_current_puser && 
		isset($get_current_puser->ID) && 
		! empty( $current_user_id ) 
	) : ?>
	<div class="allarnd--login-form">
		
		<?php
		$profile_picture_id = get_field('profile_picture_id', "user_{$current_user_id}");
		$profile_picture_url = wp_get_attachment_image_url($profile_picture_id, 'ful');

		if (!empty($profile_picture_url)) {
			echo '<div class="alrnd--login-form-logo"><img src="' . esc_url($profile_picture_url) . '" alt=""></div>';
		}
		?>
		<div class="alarnd--login-form-welcome">
			<span><?php printf( '%1$s, %2$s', __( "Welcome", "hello-elementor" ), esc_html( $get_current_puser->display_name ) ); ?></span>
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/tick.png" alt="">
		</div>
		<div class="xoo-ml-otp-sent-txt">
			<span class="xoo-ml-otp-no-txt"></span>
			<span class="xoo-ml-otp-no-change"> <?php _e( "Change", 'hello-elmentor' ); ?></span>
		</div>
	</div>
	<?php endif; ?>

	

	<div class="xoo-ml-otp-notice-cont">
		<div class="xoo-ml-notice"></div>
	</div>

	<div class="xoo-ml-otp-input-cont">
		<?php for ( $i= 0; $i < xoo_ml_helper()->get_phone_option('otp-digits'); $i++ ): ?>
			<input type="text" maxlength="1" autocomplete="off" name="xoo-ml-otp[]" class="xoo-ml-otp-input">
		<?php endfor; ?>
	</div>

	<input type="hidden" name="xoo-ml-otp-phone-no" >
	<input type="hidden" name="xoo-ml-otp-phone-code" >

	<button type="submit" class="button btn xoo-ml-otp-submit-btn"><?php _e( 'Verify', 'hello-elmentor' ); ?> </button>

	<div class="xoo-ml-otp-resend">
		<a class="xoo-ml-otp-resend-link"><?php _e( 'Not received your code? Resend code', 'hello-elmentor' ); ?></a>
		<span class="xoo-ml-otp-resend-timer"></span>
	</div>

	<input type="hidden" name="xoo-ml-form-token" value="">

</form>
