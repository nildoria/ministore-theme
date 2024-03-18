<?php
get_header(); // Include header template
?>

<div id="primary" class="content-area aum_auth_page">
    <main id="main" class="site-main" role="main">

        <?php

        $logged_user = wp_get_current_user();
        $logged_user_id = $logged_user->ID;

        // Get the current author's username from the URL
        $current_author = get_query_var('author_name');

        $token = get_field('token', "user_{$logged_user_id}");
        $phone = get_field('phone', "user_{$logged_user_id}");
        $is_tokenpayout_show = false;
        if( 
            $current_author === $logged_user->user_login &&
            ! empty( $token ) &&
            ! empty( $phone )
        )  {
            $is_tokenpayout_show = true;
        }
        

        // Get the author's user data
        $current_user = get_user_by('login', $current_author);
        $current_user_id = $current_user->ID;

        $profile_picture_id = get_field('profile_picture_id', "user_{$current_user_id}");
        $profile_picture_url = wp_get_attachment_image_url($profile_picture_id, 'medium');

        if (in_array('customer', $current_user->roles)) {
            echo '<div class="author-header aum-container">';
            echo '<div class="welcome-column">';
            echo '<h1>היי, ' . esc_html($current_user->display_name) . '</h1>';
            echo '<p>עיצבנו ויצרנו חנות אישית משלך, שבה תוכל להזמין בקלות לצרכי החברה שלך.</p>';
            // Logout button
            echo '<a href="' . wp_logout_url(home_url()) . '">Logout</a>';
            echo '</div>';
            echo '<div class="profile-picture-column">';
            if (!empty($profile_picture_url)) {
                echo '<img src="' . esc_url($profile_picture_url) . '" alt="Profile Picture">';
            } else {
                echo 'N/A';
            }
            echo '</div>';
            echo '</div>';

        }

        echo '<div class="aum-customer-elementor-widget">';
        // Load the Customer page Promo Section
        echo do_shortcode('[elementor-template id="1907"]');
        echo '</div>';

        // Selected Product Ids for the User
        // $selected_product_ids = get_user_meta($current_user->ID, 'selected_products', true);
        $selected_product_ids = get_field('selected_products', "user_{$current_user_id}");

        // Create an array to store product categories
        $product_categories = array();
        
        if (!empty($selected_product_ids)) {

            echo '<div class="product-filter">';
            // Collect categories for filtering
            foreach ($selected_product_ids as $product) {
                if( ! isset( $product['value'] ) || empty( $product['value'] ) )
                    continue;

                $product_id = $product['value'];
                $product = wc_get_product($product_id);
                if ($product) {
                    $terms = wp_get_post_terms($product_id, 'product_cat');
                    foreach ($terms as $term) {
                        $product_categories[$term->term_id] = $term;
                    }
                }
            }

            
        
            // Display category filters
            echo '<button class="filter-button" data-filter="*">All</button>';
            foreach ($product_categories as $category) {
                echo '<button class="filter-button" data-filter=".category-' . $category->term_id . '">' . esc_html($category->name) . '</button>';
            }
            
            echo '</div>';


            echo '<div class="woocommerce"><ul class="mini-store-product-list product-list-container products columns-3">';
            foreach ($selected_product_ids as $prod_object) {
                if( ! isset( $prod_object['value'] ) || empty( $prod_object['value'] ) )
                    continue;

                $product_id = $prod_object['value'];

                // check if post has thumbnail otherwise skip
                
                $product = wc_get_product($product_id);

                $group_enable = get_field( 'group_enable', $product->get_id() );
                $colors = get_field( 'color', $product->get_id() );
                $custom_quanity = get_field( 'enable_custom_quantity', $product->get_id() );
                $sizes = get_field( 'size', $product->get_id() );
                $pricing_description = get_field( 'pricing_description', $product->get_id() );
                $discount_steps = get_field( 'discount_steps', $product->get_id() );
                $discount_steps = ml_filter_disount_steps($discount_steps);

                $thumbnail = wp_get_attachment_image($product->get_image_id(), 'large');
                if( ! $thumbnail )
                    continue;

                if ($product) {
                    $terms = wp_get_post_terms($product_id, 'product_cat');

                    echo '<li class="product-item product ';
                    foreach ($terms as $term) {
                        echo 'category-' . $term->term_id . ' ';
                    }
                    echo '" data-product-id="' . esc_attr($product->get_id()) . '">';
                    
                    // Product Thumbnail
                    echo '<div class="product-thumbnail">';
                    echo wp_get_attachment_image($product->get_image_id(), 'large');
                    echo '</div>';
                    
                    echo '<div class="product-item-details">';
                    // Product Title
                    echo '<h2 class="product-title"><a href="#alarnd__pricing_info-'. $product->get_id() .'" class="alarnd_view_pricing_cb">' . esc_html($product->get_name()) . '</a></h2>';

                    if( ! empty( $colors ) && ! empty( $group_enable ) && empty( $custom_quanity ) ) : ?>
                    <div class="alarnd--colors-wrapper">
                        <div class="alarnd--colors-wrap">
                            <?php foreach( $colors as $key => $color ) : ?>
                                <input type="radio" name="alarnd__color" id="alarnd__color_<?php echo esc_html( $color['title'] ); ?>" value="<?php echo esc_html( $color['title'] ); ?>">
                                <label for="alarnd__color_<?php echo esc_html( $color['title'] ); ?>" class="alarnd--single-color" data-key="<?php $key; ?>" data-name="<?php echo esc_html( $color['title'] ); ?>" style="background-color: <?php echo $color['color_hex_code']; ?>">
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php
                    endif;

                    // Price

                    echo '<p>' . $product->get_price_html() . '</p>';
                    
                    // Buttons
                    echo '<div class="product-buttons">';
                    echo '<a href="#alarnd__pricing_info-'. $product->get_id() .'" class="view-details-button alarnd_view_pricing_cb">כמות, מחיר ומבחר</a>';
                    echo '<button class="quick-view-button ml_add_loading ml_trigger_details button" data-product-id="' . esc_attr($product->get_id()) . '">'.esc_html( $product->single_add_to_cart_text() ).'</button>';
                    echo '</div>';
                    echo '</div>';

                    if( ! empty( $group_enable ) && ! empty( $sizes ) ) : ?>
                        <div id="alarnd__pricing_info-<?php echo $product->get_id(); ?>" class="mfp-hide white-popup-block alarnd--info-modal">
                            <div class="alarnd--modal-inner alarnd--modal-chart-info">
                                <!-- <h2><?//php echo get_the_title( $product->get_id() ); ?></h2> -->

                                <div class="alarnd--pricing-wrapper">
                                    <?php if( ! empty( $pricing_description ) ) : ?>
                                    <div class="alarn--pricing-column alarn--pricing-column-desc">
                                        <?php echo allround_get_meta( $pricing_description ); ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if( ! empty( $pricing_description ) ) : ?>
                                    <div class="alarn--pricing-column alarn--pricing-column-chart">
                                        <div class="alarn--price-chart">
                                            <div class="alarnd--price-chart-price">
                                                <div class="alarnd--price-chart-item">
                                                    <span>כַּמוּת</span>
                                                </div>
                                                <?php 
                                                $index = 0;
                                                foreach( $discount_steps as $step ) :
                                                $prev = ($index == 0) ? false : $discount_steps[$index-1];                            
                                                $qty = ml_get_price_range($step['quantity'], $step['amount'], $prev);

                                                ?>
                                                <div class="alarnd--price-chart-item">
                                                    <span><?php echo esc_html( $qty); ?></span>
                                                </div>
                                                <?php $index++; endforeach; ?>
                                            </div>
                                            <div class="alarnd--price-chart-qty">
                                                <div class="alarnd--price-chart-item">
                                                    <span>מחיר (כולל מע"מ)</span>
                                                </div>
                                                <?php foreach( $discount_steps as $step ) : ?>
                                                <div class="alarnd--price-chart-item">
                                                    <span><?php echo $step['amount'] == 0 ? wc_price($product->get_regular_price(), array('decimals' => 0)) : wc_price($step['amount'], array('decimals' => 0)); ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?//php if( ! empty( $product_thumbmail ) ) : ?>
                                    <!-- <div class="alarn--pricing-column alarn--pricing-column-thumb"> -->
                                        <?//php// echo $product_thumbmail; ?>
                                    <!-- </div> -->
                                    <?//php endif; ?>
                                </div>

                                <div class="modal-bottom-btn">
                                    <button type="button" class="alarnd_view_select button alt"><?php esc_html_e( 'הוסף לעגלה שלך', 'hello-elementor' ); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endif;
                    
                    echo '</li>'; // End product-item
                }
            }
            echo '</ul></div>'; // End mini-store-product-list woocommerce
        }

        ?>
    
        <div class="cart-page alarnd--cart-wrapper-main" id="woocommerce_cart">
            <div class="alarnd--cart-wrapper-inner alarnd--full-width">
                <h2>העגלה שלך</h2>
                <?php echo do_shortcode('[woocommerce_cart]'); ?>
            </div>
        </div>

        <div class="alarnd--custom-checkout-section">

            <?php if( is_user_logged_in() ) : ?>
                <?php
                if( $is_tokenpayout_show === true ) : ?>
                <?php echo alarnd_single_checkout($logged_user_id); ?>
                <div class="alarnd--woocommerce-checkout-page">
                    <div class="alarnd-checkout-wrap-inner">
                        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                    </div>
                </div>
                <?php else : ?>
                    <div class="alarnd--woocommerce-checkout-page alarnd--default-visible">
                        <div class="alarnd-checkout-wrap-inner">
                            <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="alarnd--woocommerce-checkout-page alarnd--default-visible">
                    <div class="alarnd-checkout-wrap-inner">
                        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                    </div>
                </div>
            <?php endif; ?>
        
        </div>

        <div id="product-quick-view"></div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // Include footer template