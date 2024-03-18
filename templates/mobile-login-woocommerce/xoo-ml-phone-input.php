<?php
/**
 * Phone Field for OTP
 *
 * This template can be overridden by copying it to yourtheme/templates/mobile-login-woocommerce/xoo-ml-phone-input.php.
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

extract( $args );

$phone_number_lengh = 9;
$default_country_code = xoo_ml_helper()->get_phone_option('r-default-country-code');

$get_current_author = get_query_var('author_name');
$get_current_puser = get_user_by('login', $get_current_author); 
$current_user_id = isset( $get_current_puser->ID ) ? $get_current_puser->ID : '';

$phone_number = ml_get_user_phone($current_user_id, 'number');
$phone_number_lengh = ! empty( $phone_number ) ? strlen($phone_number) : 9;

$phone = $phone_number;
$lastTwoDigits = str_split(substr($phone, -2));
?>



<div class="xoo-ml-phinput-cont <?php echo esc_attr( implode( ' ', $cont_class ) ); ?>">
	<?php //if( $label ): ?>

		<label class="<?php echo esc_attr( implode( ' ', $label_class ) ); ?>"> <?php echo esc_html_e( 'Phone', 'hello-elementor' ); ?><?php if( $show_phone === 'required' ): ?>&nbsp;<span class="required">*</span><?php endif; ?></label>

	<?php //endif; ?>


	<div class="<?php echo $cc_show === 'yes' ? 'xoo-ml-has-cc' : ''; ?>">

		<?php if( $cc_show === 'yes' ): ?>
			
			<?php $cc_list = include XOO_ML_PATH.'/countries/phone.php'; ?>

			<?php if( $cc_type === 'selectbox' && !empty( $cc_list ) ): ?>

				<select class="xoo-ml-phone-cc <?php echo esc_attr( implode( ' ', $input_class ) ); ?>" name="xoo-ml-reg-phone-cc">

					<option disabled><?php _e( 'Select Country Code', 'hello-elementor' ); ?></option>

					<?php foreach( $cc_list as $country_code => $country_phone_code ): ?>

						<option value="<?php echo esc_attr( $country_phone_code ); ?>" <?php echo $country_phone_code === $default_cc ? 'selected' : ''; ?> ><?php echo esc_attr( $country_code.' '.$country_phone_code ); ?></option>

					<?php endforeach; ?>

				</select>

			<?php endif; ?>

			<?php if( $cc_type === 'input' ): ?>

				<input name="xoo-ml-reg-phone-cc" class="xoo-ml-phone-cc <?php echo esc_attr( implode( ' ', $input_class ) ); ?>" value="<?php echo esc_attr( $default_cc ); ?>" placeholder="<?php __( 'Country Code', 'hello-elementor' ); ?>" <?php echo $show_phone === 'required' ? 'required' : ''; ?>>

			<?php endif; ?>

		<?php endif; ?>


		<div class="xoo-ml-regphin">

			
			<div class="alarnd--xoo-otp-wrap">
				<?php if( ! empty( $default_country_code ) ) : ?>
				<div class="xoo-ml-otp-input-cont-wrap cccode-wrap">
					<?php for ( $i= 0; $i < strlen($default_country_code); $i++ ):
						$character = $default_country_code[$i]; ?>
						<input type="tel" maxlength="1" disabled="disabled" autocomplete="off" name="xoo-ml-country-code[]" placeholder="<?php echo htmlspecialchars($character); ?>" class="alarnd--otp-input xoo-ml-ccode-obj">
					<?php endfor; ?>
				</div>
				<?php endif; ?>

				<div class="xoo-ml-otp-input-cont-wrap xoo-ml-otp-input-cont-main">
					<?php 
					for ( $i= 0; $i < $phone_number_lengh; $i++ ):
						$is_last = ($i === $phone_number_lengh - 1); // Check if it's the last iteration
						$is_second_to_last = ($i === $phone_number_lengh - 2); // Check if it's the second-to-last iteration
						$is_first = ($i === 0); // Check if it's the first iteration
						$is_second = ($i === 1);
						?>
						<?php if ($is_last): ?>
							<?php echo !empty($phone) ? '<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="' . esc_attr($lastTwoDigits[1]) . '" value="' . esc_attr($lastTwoDigits[1]) . '" disabled="disabled" class="alarnd--otp-input xoo-ml-phone-obj alrn-pre-filled-digits">' : '<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="x" value="" class="alarnd--otp-input xoo-ml-phone-obj">'; ?>
						<?php elseif ($is_second_to_last): ?>
							<?php echo !empty($phone) ? '<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="' . esc_attr($lastTwoDigits[0]) . '" value="' . esc_attr($lastTwoDigits[0]) . '" disabled="disabled" class="alarnd--otp-input xoo-ml-phone-obj alrn-pre-filled-digits">' : '<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="x" value="" class="alarnd--otp-input xoo-ml-phone-obj">'; ?>
						<?php elseif ($is_first): ?>
							<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="<?php echo !empty($phone) ? '0' : '1'; ?>" value="<?php echo !empty($phone) ? '0' : '0'; ?>" disabled="disabled" class="alarnd--otp-input xoo-ml-phone-obj alrn-pre-filled-digits">
						<?php elseif ($is_second): ?>
							<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="<?php echo !empty($phone) ? '5' : '7'; ?>" value="<?php echo !empty($phone) ? '5' : '5'; ?>" disabled="disabled" class="alarnd--otp-input xoo-ml-phone-obj alrn-pre-filled-digits">
						<?php else : ?>
							<input type="tel" maxlength="1" autocomplete="off" name="xoo-ml-reg-phone[]" placeholder="x" required class="alarnd--otp-input xoo-ml-phone-obj allrnd-inputable-fields">
						<?php endif; ?>
						
					<?php endfor; ?>
				</div>
			</div>

			<input type="text" class="xoo-ml-phone-input <?php echo esc_attr( implode( ' ', $input_class ) ); ?>" name="xoo-ml-reg-phone" autocomplete="tel" value="<?php echo esc_attr( $default_phone ); ?>" <?php echo $show_phone === 'required' ? 'required' : ''; ?>/>
			
			<?php if( $form_type !== 'login_with_otp' ): ?>
				<span class="xoo-ml-reg-phone-change"><?php _e( 'Change?', 'hello-elementor' ); ?></span>
			<?php endif; ?>

		</div>

	</div>


	<input type="hidden" name="xoo-ml-form-token" value="<?php echo esc_attr( $form_token ); ?>">

	<input type="hidden" name="xoo-ml-form-type" value="<?php echo esc_attr( $form_type ); ?>">

	<input type="hidden" name="xoo-ml-otp-form-display" value="<?php echo esc_attr( $otp_display ); ?>">

</div>