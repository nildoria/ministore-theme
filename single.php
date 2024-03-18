<?php
/**
* Sinlge Post
*
*/
get_header();

$blog_page = get_field('select_blog_page', 'option');
$blog_url = home_url( 'blog' );
if( ! empty( $blog_page ) ) {
    $blog_url = get_permalink( (int) $blog_page );
}
$twitter_url = get_field('twitter_url', 'option');
$twitter_url = ! empty( $twitter_url ) ? esc_url( $twitter_url ) : '#';
$rss_url = get_field('rss_url', 'option');
$rss_url = ! empty( $rss_url ) ? esc_url( $rss_url ) : '#';
?>
<main id="main" class="site-main" role="main">
	<div class="breadcrumb"><?php get_breadcrumb(); ?></div>
    <?php while ( have_posts() ) : ?>
        <?php the_post();
            $author_id = get_the_author_meta( 'ID' );
            $product_linking = get_field( 'product_link', get_the_ID() );
        ?>

        <div class="alarnd--single-blog-header">
			<?php
			if (get_post_type() == 'post') { ?>
            <a class="alarnd--back-blog" href="<?php echo esc_url( $blog_url ); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i><?php esc_html_e('Back to blog', 'hello-elementor'); ?></a>
			<?php } else {
				echo '';
			}
			?>
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
			<?php
			if (get_post_type() == 'post') { ?>
            <a class="alarnd--back-blog" href="<?php echo esc_url( $blog_url ); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i><?php esc_html_e('Back to blog', 'hello-elementor'); ?></a>
			<?php } else {
				echo '';
			}
			?>
            <p><?php //printf( '' . esc_html__( 'Like this post? Subscribe via', 'hello-elementor' ) . ' <a href="%s">' . esc_html__( 'Twitter', 'hello-elementor' ) . '</a> ' . esc_html__( 'or', 'hello-elementor' ) . ' <a href="%s">' . esc_html__( 'RSS', 'hello-elementor' ) . '</a>',  $twitter_url, $rss_url ); ?></p>
        </div>

    <?php endwhile; // end of the loop. ?>
   
</main>
<?php
get_footer();