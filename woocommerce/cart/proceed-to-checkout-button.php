<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<h2 class="alardn--cart-total"><?php esc_html_e('םייניב םוכס', 'hello-elementor'); ?>: <?php wc_cart_totals_order_total_html(); ?> </h2>
<a href="#ministore--custom-checkout-section" class="checkout-button button alt wc-forward">
	<?php esc_html_e( 'קודבל', 'hello-elementor' ); ?>
</a>
<!-- <p class="alarnd--continue-shopping"><?php //esc_html_e( 'וא', 'hello-elementor' ); ?>, <a href="<?php //echo esc_url( home_url('catalog') ); ?>"><?php esc_html_e( 'תוינק ךשמה', 'hello-elementor' ); ?></a> </p> -->
<div class="alarnd--payment-methods">
    <p><?php esc_html_e( 'םילבקמ ונחנא', 'hello-elementor' ); ?></p>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pay-methods.png" alt="">
</div>
