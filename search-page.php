<?php
/**
* Template Name: Search Page Template
*
*/
get_header();
?>
<?php while ( have_posts() ) : ?>
<?php the_post();
$hero_title = get_field( 'hero_title', get_the_ID() );
$top_hero_header = get_field( 'top_hero_header', get_the_ID() );
$hero_description = get_field( 'hero_description', get_the_ID() );
$title = ! empty( $hero_title ) ? $hero_title : get_the_title();
if( ! empty( $top_hero_header ) ) : ?>
<div class="allaround--full-bg">
    <div class="alarnd--content-wrap">
        <div class="allaround--breadcrumb">
            <h2 class="allaround---page-title"><?php echo esc_html( $title ); ?></h2>
            <?php if( ! empty( $hero_description ) ) : ?>
            <p><?php echo allround_get_meta( $hero_description ); ?></p>
            <?php endif; ?>
            <div id="morphsearch" class="search_bar morphsearch">
                <form class="morphsearch-form" action="/" method="get" autocomplete="off">
                    <input type="text" name="s" placeholder="Search Product..." id="keyword" class="input_search morphsearch-input" onkeyup="fetch()">
                    <!--<button>-->
                    <!--    <i class="fa fa-search" aria-hidden="true"></i>-->
                    <!--</button>-->
                </form>
                <div class="search_result morphsearch-content" id="datafetch">
					<div class="dummy-column">
						<h2>Products</h2>
						<div class="searchresult-column-cont">
						
                            <?php
                            $params = array(
                                'posts_per_page' => 15,
                                'post_type' => 'product'
                            );
                            $wc_query = new WP_Query($params); // (2)
                            ?>
                            <?php if ($wc_query->have_posts()) : // (3) ?>
                            <?php while ($wc_query->have_posts()) : // (4)
                                            $wc_query->the_post(); // (4.1) ?>
                            <a class="dummy-media-object" href="<?php echo esc_url( get_permalink() ); ?>">
                            <?php the_post_thumbnail('thumbnail', array('class' => 'searchresult-thumb round')); ?>
                            <h3><?php the_title(); // (4.2) ?></h3>
                            </a>
                            
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); // (5) ?>
                            <?php else:  ?>
                            <p>
                                 <?php _e( 'No Products' ); // (6) ?>
                            </p>
                            <?php endif; ?>
                        </div>
					</div>
                </div>
				<span class="morphsearch-close"></span>
            </div>
			<div class="overlay"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<main id="main" class="site-main" role="main">

    <div class="alarnd--single-content default-page-content">
        <?php the_content(); ?>
    </div>

</main>

<?php endwhile; ?>

<?php
get_footer();