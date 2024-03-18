<?php
/**
 * Login Form with OTP
 *
 * This template can be overridden by copying it to yourtheme/templates/mobile-login-woocommerce/xoo-ml-otp-login-form.php
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/mobile-login-woocommerce/
 * @version 2.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$get_current_author = get_query_var('author_name');
$get_current_puser = get_user_by('login', $get_current_author); 
$current_user_id = isset( $get_current_puser->ID ) ? $get_current_puser->ID : '';

$phone = ml_get_user_phone($current_user_id, 'number');

?>

<span class="xoo-ml-or"><?php _e( 'Or', 'hello-elementor' ); ?></span>

<button type="button" class="xoo-ml-open-lwo-btn button btn <?php echo esc_attr( implode( ' ', $args['button_class'] ) ); ?> "><?php _e( 'Login with OTP', 'hello-elementor' ); ?></button>

<div class="xoo-ml-lwo-form-placeholder" <?php if( $args['login_first'] !== 'yes' ): ?> style="display: none;" <?php endif; ?> >

	<?php 
	if( 
		$get_current_puser && 
		isset($get_current_puser->ID) && 
		! empty( $current_user_id ) 
	) : ?>
	<div class="allarnd--login-form">
		
		<?php
        $profile_picture_url = alarnd_get_logo($current_user_id, 'second');

		if (!empty($profile_picture_url)) {
			echo '<div class="alrnd--login-form-logo"><img src="' . esc_url($profile_picture_url) . '" alt=""></div>';
		}
		?>

		<div class="alarnd--login-form-welcome">
			<span><?php printf( '%1$s, %2$s', __( "Welcome", "hello-elementor" ), esc_html( $get_current_puser->display_name ) ); ?></span>
			<?php if( ! empty( $phone ) ) : ?>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/tick.png" alt="">
			<?php endif; ?>
		</div>
		<div class="alarnd--login-form-instruction">
			<p><?php _e( 'אנא התחבר על ידי הזנת מספ הטלפון שלך והזנת הקוד שנשלח לכם', 'hello-elementor' ); ?></p>
		</div>
	</div>
	<?php endif; ?>

	<?php echo xoo_ml_get_phone_input_field( $args );  ?>

	<input type="hidden" name="redirect" value="<?php echo esc_attr( $args['redirect'] ); ?>">

	<input type="hidden" name="xoo-ml-login-with-otp" value="1">

	<button type="submit" class="xoo-ml-login-otp-btn ml_add_loading <?php echo esc_attr( implode( ' ', $args['button_class'] ) ); ?> "><?php _e( 'Login with OTP', 'hello-elementor' ); ?></button>

	<?php if( isset( $_GET ) && isset( $_GET['dev'] ) && 'true' == $_GET['dev'] ) { ?>
	<span class="xoo-ml-or"><?php _e( 'Or', 'hello-elementor' ); ?></span>

	<button type="button" class="xoo-ml-low-back <?php echo esc_attr( implode( ' ', $args['button_class'] ) ); ?>"><?php _e( 'Login with Email & Password', 'hello-elementor' ); ?></button>
	<?php } ?>

</div>