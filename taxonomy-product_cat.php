<?php
/**
* Product Category
*
*/
get_header();

$queried_object = get_queried_object();

$taxonomy = 'product_cat';
$termchildren = get_term_children( $queried_object->term_id, $taxonomy );
$mainterm = get_term( $queried_object->term_id, $taxonomy );
?>

<div class="allaround--full-bg">
    <div class="alarnd--content-wrap">
        <div class="allaround--review-info">
            <h1><?php echo esc_html( $mainterm->name ); ?></h1>
            <div class="allaround--review-counter">
                <?php echo alarnd_total_review_icons(); ?>
                <p><?php echo alarnd_all_review_count(); ?> <?php esc_html_e('Reviews', 'hello-elementor'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="site-main-wrap">
<main id="main" class="site-main" role="main">
    <?php woocommerce_breadcrumb(); ?>
    <div class="allaround--products-cats">
        <span class="screen-reader-text"><?php esc_html_e( 'All product Categories', 'hello-elmentor' ); ?></span>
        <?php 

        $product_args = array(
            'numberposts' => 50000,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $queried_object->term_id
                )
            )
        );
        $all_products  = get_posts( $product_args );

        $excludes_product = [];

        if ( !empty( $termchildren ) && !is_wp_error( $termchildren ) ) :
        foreach( $termchildren as $children ) :
            $chilterm = get_term( $children, $taxonomy );
            if( $chilterm->count === 0 ) {
                continue;
            }
            $title = $chilterm->name;
            $term_link = get_term_link( $chilterm );
            $get_product_count = alarnd_get_product_by_term($children);
            $thumb_id = get_term_meta( $children, 'thumbnail_id', true );
            $thumb_url = ! empty( $thumb_id ) ? wp_get_attachment_image_url( $thumb_id, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
            if( $chilterm->count === 1 && ! empty( $get_product_count ) ) {
                $term_link = get_permalink( $get_product_count );
                $product_icon = get_field( 'product_icon', $get_product_count );
                $product_title = get_field( 'product_short_name', $get_product_count );
                $title = ! empty( $product_title ) ? $product_title : get_the_title( $get_product_count );
                $thumb_url = ! empty( $product_icon ) ? wp_get_attachment_image_url( $product_icon, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
                $excludes_product[] = $get_product_count;
            }
            ?>
            <a href="<?php echo esc_url( $term_link ); ?>" class="allaround--single-cat-item">
                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                <h5><?php echo esc_html( $title ); ?></h5>
            </a>
        <?php endforeach;

            if( $all_products ) :
                foreach( $all_products as $product ) : 
                    if( ! empty( $excludes_product ) && in_array( $product->ID,  $excludes_product ) ) {
                        continue;
                    }
                    $product_icon = get_field( 'product_icon', $product->ID );
                    $product_title = get_field( 'product_short_name', $product->ID );
                    $title = ! empty( $product_title ) ? $product_title : get_the_title( $product->ID );
                    $thumb_url = ! empty( $product_icon ) ? wp_get_attachment_image_url( $product_icon, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
                ?>
                <a href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>" class="allaround--single-cat-item">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                    <h5><?php echo esc_html( $title ); ?></h5>
                </a>
                <?php endforeach;
            endif;
            
        else :
            
            if( $all_products ) :
                foreach( $all_products as $product ) : 
                    $product_icon = get_field( 'product_icon', $product->ID );
                    $product_title = get_field( 'product_short_name', $product->ID );
                    $title = ! empty( $product_title ) ? $product_title : get_the_title( $product->ID );
                    $thumb_url = ! empty( $product_icon ) ? wp_get_attachment_image_url( $product_icon, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
                ?>
                <a href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>" class="allaround--single-cat-item">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                    <h5><?php echo esc_html( $title ); ?></h5>
                </a>
                <?php endforeach;
            endif;   
            
        endif; ?>
    </div>
</main>
</div>
<div class="alarnd--elementor-content">
    <?php echo do_shortcode( '[elementor-template id="8470"]' ); ?>
</div>
<?php
get_footer();