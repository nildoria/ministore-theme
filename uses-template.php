<?php
/**
* Template Name: Uses Template
*
*/
get_header();
?>

<?php while ( have_posts() ) :
	the_post();
$hero_enable = get_post_meta( get_the_ID(), 'top_hero_header', true );
$hero_title = get_post_meta( get_the_ID(), 'hero_title', true );
$hero_desc = get_post_meta( get_the_ID(), 'hero_description', true );
if( ! empty( $hero_enable ) ) :
?>
<div class="allaround--breadcrumb-bg">
    <div class="alarnd--container">
        <div class="allaround--breadcrumb">
            <h2 class="allaround---page-title"><?php echo esc_html( $hero_title ); ?></h2>
            <p><?php echo allround_get_meta( $hero_desc ); ?></p>

             <div class="allaround-search-form">
                <input id="alarnd_use_search" type="search" value="" placeholder="<?php esc_attr_e( 'ונלש רצומה תא שפח', 'hello-elementor' ); ?>">
                <div class="alarnd--search-icon"><i class="fa fa-search"></i></div>
            </div>
        </div>
    </div>
</div>
<?php endif; endwhile; ?>

<div class="allaround-section-padding alarnd_prjects_wrapper">
    <div class="alarnd--container">
        <div class="alarnd--uses-wrapper">
            <div class="allaround--service-wraper">
                <?php

                $products_per_page = 9;
                $thepage = (!empty($_GET['list'])) ? $_GET['list'] : 1;
                $offset = ( ($thepage -1) * $products_per_page);

                $project_args = array(
                'posts_per_page' => $products_per_page,
                'post_type'      => 'uses',
                'offset' => $offset
                );
                $project_qry  = new WP_Query( $project_args );

                $the_total_products = $project_qry->found_posts;

                if ( $project_qry->have_posts() ) : 
                while ( $project_qry->have_posts() ) : $project_qry->the_post(); 
                ?>
                <!-- single item -->
                <a href="<?php the_permalink(); ?>" class="allaround--service-single-item">
                    <div class="allaround--service-thumbanil">
                        <?php the_post_thumbnail( 'related_thumb' ); ?>
                    </div>
    
                    <div class="allaround--service-content">
                        <h3><?php the_title(); ?></h3>
                    </div>
                </a>
                <!-- single item -->
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <?php else : ?>
                <p><?php esc_html_e( 'Sorry, no project found.', 'hello-elementor' ); ?></p>
            <?php endif; ?>
        </div>
                
        <div class="allaround--pagination-wrap" data-base-url="<?php echo esc_url( get_pagenum_link( $big ) ); ?>">
            <?php
            $total_pages = ceil($the_total_products / $products_per_page);
            if ( $total_pages > 1 ) {
                echo paginate_links( array(
                    // 'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?list=%#%',
                    'current' => $thepage,
                    'total' => $total_pages,
                    'prev_text' => esc_html__( 'Previous', 'hello-elementor' ),  
                    'next_text' => esc_html__('Next', 'hello-elementor'),
                    'type'     => 'list',
                ) );
            }
            ?>
        </div>

    </div>
</div>

<?php while ( have_posts() ) :
	the_post();
?>
<div class="allaround--project-bottom">
    <div class="alarnd--elm-container">
        <div class="alarnd_project_content">
            <?php the_content(); ?>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php
get_footer();