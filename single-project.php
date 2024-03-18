<?php
/**
* Sinlge Project
*
*/
get_header();

$project_url = home_url( 'projects' );

$twitter_url = get_field('twitter_url', 'option');
$twitter_url = ! empty( $twitter_url ) ? esc_url( $twitter_url ) : '#';
$rss_url = get_field('rss_url', 'option');
$rss_url = ! empty( $rss_url ) ? esc_url( $rss_url ) : '#';
?>
<main id="main" class="site-main" role="main">
	<div class="breadcrumb"><?php wp_custom_post_breadcrumbs(); ?></div>
    <?php while ( have_posts() ) : ?>
        <?php the_post();
            $author_id = get_the_author_meta( 'ID' );
            $product_linking = get_field( 'product_link', get_the_ID() );
        ?>

        <div class="alarnd--single-blog-header">
            <a class="alarnd--back-blog" href="<?php echo esc_url( $project_url ); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i><?php esc_html_e('Back to Projects', 'hello-elementor'); ?></a>
			<h2><?php the_title(); ?></h2>
            <p class="alarnd--posted-by"><?php esc_html_e('Posted by', 'hello-elementor'); ?> <strong><?php the_author(); ?></strong></p>
        </div>

        <div class="alarnd--single-content">
            <?php the_content(); ?>
        </div>

        <?php
        if( ! empty( $product_linking ) && ! empty( $product_linking['product'] ) ) :
        $title = ! empty( $product_linking['text'] ) ? $product_linking['text'] : get_the_title( (int) $product_linking['product'] );
        ?>
        <div class="alarnd--single-product-linking">
            <a href="<?php echo esc_url( get_permalink( $product_linking['product'] ) ); ?>"><?php esc_html_e('Show', 'hello-elementor'); ?> <?php echo esc_html( $title ); ?></a>
        </div>
        <?php endif; ?>

        <div class="alarnd--single-footer">
			<a class="alarnd--back-blog" href="<?php echo esc_url( $project_url ); ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i><?php esc_html_e('Back to Projects', 'hello-elementor'); ?></a>
        </div>

    <?php endwhile; // end of the loop. ?>
   
</main>
<?php
get_footer();