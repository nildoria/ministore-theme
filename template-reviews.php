<?php
/**
* Template Name: Reviews Template
*
*/
get_header();
?>

<div class="allaround-section-padding alarnd_uses_wrapper">
    <div class="alarnd--container">
        <div class="alarnd--reviews-main">
            <?php
            $big = 999999999; // need an unlikely integer
            $posts_per_page = get_option('posts_per_page');
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $posts_per_page = ! empty( $posts_per_page ) ? (int) $posts_per_page : 10;

            $review_args = array(
                'posts_per_page' => $posts_per_page,
                'post_type'      => 'review',
                'post_status'    => 'publish',
                'order'          => 'DESC',
                'paged' => $paged
            );
            $review_qry  = new \WP_Query( $review_args );
            $found_reviews = $review_qry->found_posts;
            ?>
            <div class="alarnd--review-wrapper<?php echo true === $expend ? ' alarnd-expand' : ''; ?>">
                <div class="alarnd--review-header-info">
                    <div class="alarnd--single-review-header">
                        <div class="alarnd--avg-ratings">
                            <h2><?php echo alarnd_get_avarage_review(); ?> / 5</h2>
                        </div>
                        <?php echo alarnd_total_review_icons(); ?>
                    </div>
                    <div class="alarnd--single-review-header">
                        <h2><?php echo esc_html( $found_reviews ); ?></h2>
                        <div><?php esc_html_e( 'Total Reviews', 'hello-elementor' ); ?></div>
                    </div>
                </div>
                <?php if ( $review_qry->have_posts() ) : ?>
                <div class="alarnd--review-groups">
                    <?php
                    while ( $review_qry->have_posts() ) : $review_qry->the_post(); 
                    
                    $rating = get_post_meta( get_the_ID(), 'rating', true );
                    $name = get_post_meta( get_the_ID(), 'name', true );
                    $custom_date = get_post_meta( get_the_ID(), 'custom_date', true );
                    $email = get_post_meta( get_the_ID(), 'email', true );
                    $avatar = get_post_meta( get_the_ID(), 'avatar', true );
                	$thumb = get_post_meta( get_the_ID(), 'review_thumb', true );
        
                    $user_email = ! empty( $email ) ? $email : null;
                    $gravatar = ! empty( $avatar ) ? '<img src="'.wp_get_attachment_url( (int) $avatar ).'"/>' : get_avatar( $user_email, 100, 'mystery' );
                	$review_thumb = ! empty( $thumb ) ? '<a href="'.wp_get_attachment_url( (int) $thumb ).'"><img src="'.wp_get_attachment_url( (int) $thumb ).'"/></a>' : null;
                    $user_name = ! empty( $name ) ? $name : esc_html__('anonymous', 'hello-elementor');
        
        
                    $the_date = ! empty( $custom_date ) ? date_i18n('j F ,Y', strtotime($custom_date)) : get_the_date( 'j F ,Y' );
        
                    ?>
                    <div class="alarnd--single-review">
                        <div class="review-item">
                            <div class="review-avatar">
                                <?php echo $gravatar; ?>
                            </div>
                            <div class="review-body">
                                <?php echo alarnd_single_review_avg( $rating ); ?>
        
                                <span class="review-title"><?php the_title(); ?></span>
        
                                <div class="review-details">
                                    <span class="reviewer-name">
                                        <strong><?php echo $user_name; ?></strong>
                                    </span>
                                    <time class="review-date"><?php echo $the_date; ?></time>
                                </div>
                                <?php the_content(); ?>
                            </div>
							<div class="review-thumb">
								<?php echo $review_thumb; ?>
							</div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <?php else : ?>
                    <p><?php esc_html_e( 'Sorry, no review found.', 'hello-elementor' ); ?></p>
                <?php endif; ?>

                
            </div>
            
        </div>

        <div class="allaround--pagination-wrap" data-base-url="<?php echo esc_url( get_pagenum_link( $big ) ); ?>">
            <?php
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $review_qry->max_num_pages,
                'prev_text' => esc_html__( 'Previous', 'hello-elementor' ),  
                'next_text' => esc_html__('Next', 'hello-elementor'),
                'type'     => 'list',
            ) );
            ?>
        </div>
                
    </div>
</div>

<?php
get_footer();