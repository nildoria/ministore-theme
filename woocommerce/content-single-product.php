<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
<?php 

    $product_banner = get_post_meta( get_the_ID(), 'product_banner', true );
    $product_banner = ! empty( $product_banner ) ? wp_get_attachment_url( (int) $product_banner ) : get_the_post_thumbnail_url('full');

?>
    <div class="allarnd--single-product-thumb">
        <img src="<?php echo esc_url( $product_banner ); ?>" alt="">
    </div>
    <main id="main" class="site-main" role="main">
        <?php woocommerce_breadcrumb(); ?>
        <div class="alarnd--single-product-hero">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );

            ?>
            <div class="alarnd--single-product-info">
                <div class="alar-single-title-wrap">
                    <?php echo woocommerce_template_single_title(); ?>
                    <span class="allaround--review-counter">
                        <a href="#customer-reviews">
                        <?php echo alarnd_total_review_icons(); ?>
                        <p><?php echo alarnd_all_review_count(); ?> <?php esc_html_e('Reviews', 'hello-elementor'); ?></p>
                        </a>
                    </span>
                </div>
                <?php echo woocommerce_template_single_excerpt(); ?>
                <?php
                $promo_link = get_field('order_sample_link', get_the_ID());
                $promo_link_text = get_field('sample_link_text', get_the_ID());
                if( ! empty( $promo_link ) ) : ?>
                <div class="allaround--promo-link">
                    <a href="<?php echo esc_attr( $promo_link ); ?>"><?php echo ! empty( $promo_link_text ) ? esc_html( $promo_link_text ) : esc_html__( 'Order Sample', 'hello-elementor' ); ?></a>
                </div>
                <?php endif; ?>
            </div>

            <div class="allarnd--single-product-thumb allarnd--single-product-thumb-mobile">
                <img src="<?php echo esc_url( $product_banner ); ?>" alt="">
            </div>
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>
            </div>
        </div>
    </div>

    <div class="alarnd--content-wrap">
        <?php the_content(); ?>
    </div>

    <div class="alarnd--full-width-wrapper">
        <div class="alarnd--product-bottom">
            <?php
            echo alarnd_all_reviews();

            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            do_action( 'woocommerce_after_single_product_summary' );
            ?>
        </div>
    </div>
	
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
