<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
// do_action( 'woocommerce_account_navigation' ); 
?>

<script>
    // jQuery(document).ready(function($) {
    //     // Get the current URL
    //     var currentUrl = window.location.href;
    //     // Check if the current URL is "home_url/my-account/"
    //     if (currentUrl.indexOf('<?//php echo home_url('/my-account/'); ?>') !== -1) {
	// 		console.log("My Account");
    //         // Check if the element with class .woocommerce-MyAccount-navigation exists
    //         if ($('.woocommerce-MyAccount-content').length) {
	// 			console.log("My Acc Nav")
    //             // Trigger a click event on the specified link
	// 			window.location.replace('<?//php echo home_url(); ?>');
                
    //         }
    //     }
    // });
</script>
<div class="woocommerce-MyAccount-content">
	<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		// do_action( 'woocommerce_account_content' );
	?>
</div>
