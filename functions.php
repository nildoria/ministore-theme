<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_VERSION', '2.4.2');

if (!isset($content_width)) {
    $content_width = 800; // Pixels.
}

if (!function_exists('hello_elementor_setup')) {
    /**
     * Set up theme support.
     *
     * @return void
     */
    function hello_elementor_setup()
    {
        if (is_admin()) {
            hello_maybe_update_theme_version_in_db();
        }

        $hook_result = apply_filters_deprecated('elementor_hello_theme_load_textdomain', [true], '2.0', 'hello_elementor_load_textdomain');
        if (apply_filters('hello_elementor_load_textdomain', $hook_result)) {
            load_theme_textdomain('hello-elementor', get_template_directory() . '/languages');
        }

        $hook_result = apply_filters_deprecated('elementor_hello_theme_register_menus', [true], '2.0', 'hello_elementor_register_menus');
        if (apply_filters('hello_elementor_register_menus', $hook_result)) {
            register_nav_menus(['menu-1' => __('Header', 'hello-elementor')]);
            register_nav_menus(['menu-2' => __('Footer', 'hello-elementor')]);
        }

        $hook_result = apply_filters_deprecated('elementor_hello_theme_add_theme_support', [true], '2.0', 'hello_elementor_add_theme_support');
        if (apply_filters('hello_elementor_add_theme_support', $hook_result)) {
            add_theme_support('post-thumbnails');
            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support(
                'html5',
                [
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                ]
            );
            add_theme_support(
                'custom-logo',
                [
                    'height' => 100,
                    'width' => 350,
                    'flex-height' => true,
                    'flex-width' => true,
                ]
            );

            /*
             * Editor Style.
             */
            add_editor_style('classic-editor.css');

            /*
             * Gutenberg wide images.
             */
            add_theme_support('align-wide');

            /*
             * WooCommerce.
             */
            $hook_result = apply_filters_deprecated('elementor_hello_theme_add_woocommerce_support', [true], '2.0', 'hello_elementor_add_woocommerce_support');
            if (apply_filters('hello_elementor_add_woocommerce_support', $hook_result)) {
                // WooCommerce in general.
                add_theme_support('woocommerce');
                // Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
                // zoom.
                add_theme_support('wc-product-gallery-zoom');
                // lightbox.
                add_theme_support('wc-product-gallery-lightbox');
                // swipe.
                add_theme_support('wc-product-gallery-slider');
            }
        }

        add_image_size('related_thumb', 400, 270, true);
        add_image_size('blog_thumb', 450, 250, true);
    }
}
add_action('after_setup_theme', 'hello_elementor_setup');

add_action('admin_enqueue_scripts', 'alarnd_enqueue_admin_script');
function alarnd_enqueue_admin_script()
{
    wp_enqueue_style(
        'alarnd--admin',
        get_template_directory_uri() . '/assets/css/order.css',
        [],
        HELLO_ELEMENTOR_VERSION
    );
}

add_filter('single_product_archive_thumbnail_size', 'alarnd_change_thumb_size');
function alarnd_change_thumb_size()
{
    return 'related_thumb';
}

function hello_maybe_update_theme_version_in_db()
{
    $theme_version_option_name = 'hello_theme_version';
    // The theme version saved in the database.
    $hello_theme_db_version = get_option($theme_version_option_name);

    // If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
    if (!$hello_theme_db_version || version_compare($hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<')) {
        update_option($theme_version_option_name, HELLO_ELEMENTOR_VERSION);
    }
}

if (!function_exists('hello_elementor_scripts_styles')) {
    /**
     * Theme Scripts & Styles.
     *
     * @return void
     */
    function hello_elementor_scripts_styles()
    {
        $enqueue_basic_style = apply_filters_deprecated('elementor_hello_theme_enqueue_style', [true], '2.0', 'hello_elementor_enqueue_style');
        $min_suffix = '';

        if (apply_filters('hello_elementor_enqueue_style', $enqueue_basic_style)) {
            wp_enqueue_style(
                'hello-elementor',
                get_template_directory_uri() . '/style' . $min_suffix . '.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        if (apply_filters('hello_elementor_enqueue_theme_style', true)) {
            wp_enqueue_style(
                'hello-elementor-theme-style',
                get_template_directory_uri() . '/theme' . $min_suffix . '.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        wp_enqueue_style(
            'allaround-magnific',
            get_template_directory_uri() . '/assets/css/magnific-popup.css',
            [],
            HELLO_ELEMENTOR_VERSION
        );
        wp_enqueue_style(
            'allaround-style',
            get_template_directory_uri() . '/assets/css/style.css',
            [],
            filemtime(get_theme_file_path('/assets/css/style.css'))
        );

        wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery'), HELLO_ELEMENTOR_VERSION, true);
        wp_enqueue_script('allaround-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), filemtime(get_theme_file_path('/assets/js/main.js')), true);

        wp_localize_script(
            'allaround-main',
            'allaround_vars',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'admin_email' => get_bloginfo('admin_email'),
                'nonce' => wp_create_nonce("allaround_validation_nonce"),
                'assets' => get_template_directory_uri() . '/assets/',
                'redirecturl' => home_url(),
                'get_cart_url' => wc_get_cart_url(),
                'saving_text' => esc_html__('Saving', 'hello-elementor'),
                'min_msg' => esc_html__('Can\'t be less then', 'hello-elementor'),
                'required_msg' => esc_html__('This field is required', 'hello-elementor'),
                'max_msg' => esc_html__('Can\'t be more then', 'hello-elementor'),
                // 'sp_custom_label' => '<label for="cutom_quantity_special-custom">' . esc_html__( 'Custom Quantity', 'hello-elementor' ) . '</label>',
                'get_checkout_url' => wc_get_checkout_url()
            )
        );

        wp_enqueue_script('leads', get_template_directory_uri() . '/assets/js/leads.js', array(
            'jquery',
            'validate'
        ), HELLO_ELEMENTOR_VERSION, true);

        wp_localize_script(
            'leads',
            'leads_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce("leads_ajax_nonce")
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'hello_elementor_scripts_styles');

if (!function_exists('hello_elementor_register_elementor_locations')) {
    /**
     * Register Elementor Locations.
     *
     * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
     *
     * @return void
     */
    function hello_elementor_register_elementor_locations($elementor_theme_manager)
    {
        $hook_result = apply_filters_deprecated('elementor_hello_theme_register_elementor_locations', [true], '2.0', 'hello_elementor_register_elementor_locations');
        if (apply_filters('hello_elementor_register_elementor_locations', $hook_result)) {
            $elementor_theme_manager->register_all_core_location();
        }
    }
}
add_action('elementor/theme/register_locations', 'hello_elementor_register_elementor_locations');

if (!function_exists('hello_elementor_content_width')) {
    /**
     * Set default content width.
     *
     * @return void
     */
    function hello_elementor_content_width()
    {
        $GLOBALS['content_width'] = apply_filters('hello_elementor_content_width', 800);
    }
}
add_action('after_setup_theme', 'hello_elementor_content_width', 0);

if (is_admin()) {
    require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
 */

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';
require get_template_directory() . '/includes/classes/class-rules.php';
require get_template_directory() . '/includes/classes/class-utility.php';
require get_template_directory() . '/includes/classes/class-ajax.php';

/**
 * Include customizer registration functions
 */
function hello_register_customizer_functions()
{
    if (hello_header_footer_experiment_active() && is_customize_preview()) {
        require get_template_directory() . '/includes/customizer-functions.php';
    }
}
add_action('init', 'hello_register_customizer_functions');

if (!function_exists('hello_elementor_check_hide_title')) {
    /**
     * Check hide title.
     *
     * @param bool $val default value.
     *
     * @return bool
     */
    function hello_elementor_check_hide_title($val)
    {
        if (defined('ELEMENTOR_VERSION')) {
            $current_doc = Elementor\Plugin::instance()->documents->get(get_the_ID());
            if ($current_doc && 'yes' === $current_doc->get_settings('hide_title')) {
                $val = false;
            }
        }
        return $val;
    }
}
add_filter('hello_elementor_page_title', 'hello_elementor_check_hide_title');

/**
 * Wrapper function to deal with backwards compatibility.
 */
if (!function_exists('hello_elementor_body_open')) {
    function hello_elementor_body_open()
    {
        if (function_exists('wp_body_open')) {
            wp_body_open();
        } else {
            do_action('wp_body_open');
        }
    }
}

/**
 * Find matching product variation
 *
 * @param $product_id
 * @param $attributes
 * @return int
 */
function find_matching_product_variation_id($product_id, $attributes)
{
    return (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new \WC_Product($product_id),
        $attributes
    );
}

function alarnd_get_variation_id($variations, $size, $quantity)
{
    foreach ($variations as $key => $variation) {
        if ($variation['attributes']['attribute_size'] == $size && $variation['attributes']['attribute_quantity'] == $quantity) {
            return $key;
        }
    }
}


function allround_variation_radio_buttons($html, $args)
{

    $args = wp_parse_args(
        apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args),
        array(
            'options' => false,
            'attribute' => false,
            'product' => false,
            'selected' => false,
            'name' => '',
            'id' => '',
            'class' => '',
            'show_option_none' => __('Choose an option', 'hello-elementor'),
        )
    );

    // if( 'Size' != $args['attribute'] && 'Quantity' != $args['attribute'] ) {
    //     return $html;
    // }

    if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
        $selected_key = 'attribute_' . sanitize_title($args['attribute']);
        $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
    }

    $options = $args['options'];
    $product = $args['product'];
    $attribute = $args['attribute'];
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class = $args['class'];
    $show_option_none = (bool) $args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'hello-elementor');

    if (empty($options) && !empty($product) && !empty($attribute)) {
        $attributes = $product->get_variation_attributes();
        $options = $attributes[$attribute];
    }

    $variations = $product->get_available_variations();
    // $whatff = alarnd_get_variation_id( $variations, '2" x 2"', 100 );
    // error_log( print_r( $args, true ) );

    $radios = '<div class="variation-radios">';

    if (!empty($options)) {
        if ($product && taxonomy_exists($attribute)) {
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute,
                array(
                    'fields' => 'all',
                )
            );

            foreach ($terms as $term) {
                if (in_array($term->slug, $options, true)) {
                    $id = $name . '-' . $term->slug;
                    $radios .= '<div class="alarnd--single-variable"><span class="alarnd--single-var-info"><input type="radio" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($term->slug) . '" ' . checked(sanitize_title($args['selected']), $term->slug, false) . '><label for="' . esc_attr($id) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '</span></label></div>';
                }
            }

        } else {

            $drop_enable = 8;
            if ('Size' === $attribute) {
                $drop_enable = 4;
            }

            $options_count = count($options);
            $index = 0;
            foreach ($options as $key => $option) {
                if ($index >= $drop_enable) {
                    break;
                } else {
                    $id = $name . '-' . $option;
                    $checked = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);

                    if (isset($variations[$key]['variation_id'])) {
                        $saving_value = get_post_meta(intval($variations[$key]['variation_id']), 'alarnd_save', true);
                        $saving = !empty($saving_value) ? '<span class="alarnd--saving">' . $saving_value . '</span>' : '';
                    }
                    $radios .= '<div class="alarnd--single-variable"><span class="alarnd--single-var-info"><input type="radio" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($option) . '" id="' . sanitize_title($option) . '" ' . $checked . '><label for="' . esc_attr($id) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</label></span></div>';
                    $index++;
                }
            }
            // error_log( print_r( $attribute, true ) );

            if ($options_count > $drop_enable) {
                $select_options = '';
                $box = '';
                $list = '';
                $index = 0;
                foreach ($options as $key => $option) {
                    if ($index >= $drop_enable) {
                        $id = $name . '-' . $option;
                        $checked = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);

                        $label = esc_html(apply_filters('woocommerce_variation_option_name', $option));

                        $box .= '<div class="alarnd__select-box__value">
                        <input type="radio" data-variation-id="' . $variations[$key]['variation_id'] . '" id="' . esc_attr($id) . '" class="alarnd__select-box__input" name="' . esc_attr($name) . '" value="' . esc_attr($option) . '" ' . $checked . '>
                        <p class="alarnd__select-box__input-text"><span class="alarnd--option-var">' . $label . '</span></p>
                    </div>';

                        $list .= '<li><label class="alarnd__select-box__option" for="' . esc_attr($id) . '" aria-hidden="aria-hidden">' . $label . '</label></li>';
                    }
                    $index++;

                }
                $icon = get_template_directory_uri() . '/assets/images/arrow.svg';
                // $radios    .= '<div class="alarnd--single-variable"><select class="alarnd--variation-select" name="'.esc_attr($name).'">'.$select_options.'</select></div>';
                $radios .= '<div class="alarnd--single-variable"><span class="alarnd--single-box-wrapper"><span class="alarnd--single-box-info"><div class="alarnd__select-box">
              <div class="alarnd__select-box__current" tabindex="1">
                ' . $box . '
                <img class="alarnd__select-box__icon" src="' . $icon . '" alt="Arrow Icon" aria-hidden="true"/>
              </div>
              <ul class="alarnd__select-box__list">
                ' . $list . '
              </ul>
            </div></span></span></div>';
            }


        }
    }

    $radios .= '</div>';

    return $html . $radios;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'allround_variation_radio_buttons', 20, 2);



function ml_update_cart_after_removeitem($cart_item_key, $cart)
{

    $alarnd_group_id = isset($cart->cart_contents[$cart_item_key]['alarnd_group_id']) ? $cart->cart_contents[$cart_item_key]['alarnd_group_id'] : "";

    if (!empty($alarnd_group_id)) {

        $remove_qty = (int) $cart->cart_contents[$cart_item_key]['quantity'];

        $cart_content = $cart->cart_contents;

        $is_anyone = false;
        foreach ($cart->cart_contents as $i_cart_item_key => $cart_item) {
            if (isset($cart_item['alarnd_group_id']) && $alarnd_group_id === $cart_item['alarnd_group_id']) {
                $cart_item['alarnd_group_qty'] = $cart_item['alarnd_group_qty'] - $remove_qty;
                $cart_content[$i_cart_item_key] = $cart_item;
                $is_anyone = true;
            }
        }

        if ($is_anyone === true) {
            $cart->set_cart_contents($cart_content);
        }

    }

}
;
add_action('woocommerce_remove_cart_item', 'ml_update_cart_after_removeitem', 10, 2);


add_action('woocommerce_before_calculate_totals', 'discounted_cart_item_price', 20, 1);
function discounted_cart_item_price($cart)
{
    // Not for wholesaler user role
    if ((is_admin() && !defined('DOING_AJAX')) || current_user_can('wholesaler'))
        return;

    // Required since Woocommerce version 3.2 for cart items properties changes
    if (did_action('woocommerce_before_calculate_totals') >= 2)
        return;

    $user_id = ml_manage_user_session();
    $bump_price = null;
    if ($user_id) {
        $bump_price = get_user_meta($user_id, 'bump_price', true);
    }

    // Log user_id and bump_price
    error_log("User ID discounted_cart_item_price: $user_id");
    error_log("Bump Price discounted_cart_item_price: $bump_price");

    $get_group_products = [];

    $filter_items = [];
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        // error_log( print_r( $cart_item, true ) );
        $_product = wc_get_product($cart_item['product_id']);
        $group_enable = get_field('group_enable', $cart_item['product_id']);
        $custom_quanity = get_field('enable_custom_quantity', $cart_item['product_id']);
        if (
            $_product->is_type('simple') &&
            !empty($group_enable) &&
            empty($custom_quanity) &&
            isset($cart_item['quantity'])
        ) {

            // Check if the product ID exists in $filter_items, if not, initialize it to 0
            if (!isset($filter_items[$cart_item['product_id']])) {
                $filter_items[$cart_item['product_id']] = 0;
            }

            // Add the current quantity to the existing total for this product
            $filter_items[$cart_item['product_id']] += $cart_item['quantity'];
        }
    }

    // 1st Loop: get category items count  
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

        $_product = wc_get_product($cart_item['product_id']);
        $regular_price = $_product->get_regular_price();
        $group_enable = get_field('group_enable', $_product->get_id());
        $custom_quanity = get_field('enable_custom_quantity', $_product->get_id());

        if (
            $_product->is_type('simple') &&
            !empty($group_enable) &&
            empty($custom_quanity) &&
            isset($cart_item['quantity']) &&
            isset($filter_items[$cart_item['product_id']])
        ) {
            $final_price = Alarnd_Utility::instance()->get_final_amount($cart_item['product_id'], $filter_items[$cart_item['product_id']], $regular_price);

            if (
                isset($cart_item['art_item_price']) &&
                !empty($cart_item['art_item_price'])
            ) {
                $final_price = $final_price + $cart_item['art_item_price'];
            }

            if (!empty($bump_price)) {
                $final_price = round($final_price + ($final_price * $bump_price / 100));
            }

            $cart_item['data']->set_price($final_price);

        } elseif (
            $_product->is_type('simple') &&
            empty($group_enable) &&
            !empty($custom_quanity)
        ) {
            $custom_price = Alarnd_Utility::instance()->get_custom_amount($cart_item['product_id'], $cart_item['quantity'], $regular_price);

            if (
                isset($cart_item['art_item_price']) &&
                !empty($cart_item['art_item_price'])
            ) {
                $custom_price = $custom_price + $cart_item['art_item_price'];
            }

            if (!empty($bump_price)) {
                $custom_price = round($custom_price + ($custom_price * $bump_price / 100));
            }

            $cart_item['data']->set_price($custom_price);
        }
    }

    $special_product = WC()->session->get('special_product');

    // if( ! empty( $get_group_products ) ) {
    //     $group_products_count = [];
    //     foreach( $get_group_products as $product_id => $obj ) {
    //         $total_qty = array_sum($obj['qty']);
    //         $final_price = Alarnd_Utility::instance()->get_final_amount( $product_id, $total_qty, $obj['price'] );
    //         $group_products_count[$product_id] = $final_price;
    //     }

    //     foreach ( WC()->cart->get_cart() as $cart_item ) {
    //         // If product categories is found
    //         if ( isset( $group_products_count[$cart_item['product_id']] ) && ! empty( $group_products_count[$cart_item['product_id']] ) ) {
    //             $the_price = $group_products_count[$cart_item['product_id']];
    //             if(
    //                 isset($special_product['product_id']) &&
    //                 ! empty($special_product['product_id']) &&
    //                 $special_product['product_id'] === $cart_item['product_id'] &&
    //                 isset($special_product['price'])
    //             ) {
    //                 $the_price = $the_price+$special_product['price'];
    //             }
    //             $cart_item['data']->set_price( $the_price );
    //         }
    //     }
    // }

    // error_log( print_r( WC()->cart->get_cart(), true ) );

    WC()->session->__unset('special_product'); // Remove session variable
}


function variation_check($active, $variation)
{
    if (!$variation->is_in_stock() && !$variation->backorders_allowed()) {
        return false;
    }
    return $active;
}
add_filter('woocommerce_variation_is_active', 'variation_check', 10, 2);


add_action('woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3);
add_action('woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2);
add_filter('woocommerce_available_variation', 'load_variation_settings_fields');

function variation_settings_fields($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(
        array(
            'id' => "alarnd_save{$loop}",
            'name' => "alarnd_save[{$loop}]",
            'value' => get_post_meta($variation->ID, 'alarnd_save', true),
            'label' => __('Save Percentage Info', 'hello-elementor'),
            'desc_tip' => true,
            // 'description'   => __( 'Some description.', 'woocommerce' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
}

function save_variation_settings_fields($variation_id, $loop)
{
    $text_field = $_POST['alarnd_save'][$loop];

    if (!empty($text_field)) {
        update_post_meta($variation_id, 'alarnd_save', esc_attr($text_field));
    }
}

function load_variation_settings_fields($variation)
{
    $variation['alarnd_save'] = get_post_meta($variation['variation_id'], 'alarnd_save', true);

    return $variation;
}

remove_action('wp_ajax_nopriv_woocommerce_get_variation', array('WC_AJAX', 'get_variation'));
remove_action('wp_ajax_woocommerce_get_variation', array('WC_AJAX', 'get_variation'));
remove_action('wc_ajax_get_variation', array('WC_AJAX', 'get_variation'));

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);


add_action('wp_ajax_nopriv_woocommerce_get_variation', 'alrnd_get_variation', 20);
add_action('wp_ajax_woocommerce_get_variation', 'alrnd_get_variation', 20);
add_action('wc_ajax_get_variation', 'alrnd_get_variation', 20);

/**
 * Get a matching variation based on posted attributes.
 */
function alrnd_get_variation()
{
    ob_start();

    // phpcs:disable WordPress.Security.NonceVerification.Missing
    if (empty($_POST['product_id'])) {
        wp_die();
    }

    global $woocommerce;

    $variable_product = wc_get_product(absint($_POST['product_id']));

    if (!$variable_product) {
        wp_die();
    }

    $attributes = $variable_product->get_variation_attributes();

    $data_store = WC_Data_Store::load('product');
    $get_attributes = wp_unslash($_POST);
    $variation_id = $data_store->find_matching_product_variation($variable_product, $get_attributes);
    $variation = $variation_id ? $variable_product->get_available_variation($variation_id) : false;

    $all_keys_attrs = array_keys($attributes);
    $last_attribute = end($all_keys_attrs);
    $lower_lastitem = 'attribute_' . strtolower(trim($last_attribute));

    $data_by_variable = [];
    if (1 === count($all_keys_attrs) && isset($attributes[$last_attribute]) && isset($get_attributes[$lower_lastitem])) {
        foreach ($attributes[$last_attribute] as $attribute) {
            $single_variation_id = $data_store->find_matching_product_variation($variable_product, [$lower_lastitem => $attribute, 'product_id' => $variable_product->get_id()]);
            $single_variation = $single_variation_id ? $variable_product->get_available_variation($single_variation_id) : false;

            $attribute_ttile = sanitize_title($attribute);
            $data_by_variable[$attribute_ttile]['display_price'] = wc_price($single_variation['display_price']);
            $data_by_variable[$attribute_ttile]['alarnd_save'] = htmlspecialchars_decode($single_variation['alarnd_save']);
        }
    } elseif (isset($attributes['Quantity']) && isset($get_attributes['attribute_size']) && isset($get_attributes['attribute_quantity'])) {
        foreach ($attributes['Quantity'] as $attribute) {
            $single_variation_id = $data_store->find_matching_product_variation($variable_product, ['attribute_size' => $get_attributes['attribute_size'], 'attribute_quantity' => $attribute, 'product_id' => $variable_product->get_id()]);
            $single_variation = $single_variation_id ? $variable_product->get_available_variation($single_variation_id) : false;
            $attribute_ttile = sanitize_title($attribute);
            $data_by_variable[$attribute_ttile]['display_price'] = wc_price($single_variation['display_price']);
            $data_by_variable[$attribute_ttile]['alarnd_save'] = htmlspecialchars_decode($single_variation['alarnd_save']);
        }
    }

    $format_attributes = array();
    foreach ($variation['attributes'] as $key => $val) {
        $format_attributes[$key] = sanitize_title($val);
    }

    $variation['attributes'] = $format_attributes;
    $variation['alarnd_data'] = $data_by_variable;
    wp_send_json($variation);
    // phpcs:enable
}

function alarnd_woocommerce_after_add_to_cart_button()
{
    echo '<p class="alarnd--next-step">' . esc_html__('Next: upload artwork', 'hello-elementor') . ' ←</p>';
}
// add_action( 'woocommerce_after_add_to_cart_button', 'alarnd_woocommerce_after_add_to_cart_button' );




/**
 * get percentage by quantity
 */
function sakib_get_discount($quantity)
{
    switch ($quantity) {
        case 6:
            $discount = 20;
            break;
        case 10:
            $discount = 30;
            break;
        default:
            $discount = 0;
    }
    return $discount / 100;
}











function alarnd_acf_load_field($field)
{

    $current_date = date('Y/m/d', current_time('timestamp')); // phpcs:ignore

    $field['default_value'] = $current_date;
    return $field;

}

add_filter('acf/load_field/name=custom_date', 'alarnd_acf_load_field');

function alarnd_get_products($field)
{

    // reset choices
    $field['sub_fields'][0]['choices'] = array();

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'numberposts' => 5000
    );
    $postslist = get_posts($args);

    // loop through array and add to field 'choices'
    if (is_array($postslist)) {
        foreach ($postslist as $post) {
            $field['sub_fields'][0]['choices'][$post->ID] = $post->post_title;
        }
    }

    // return the field
    return $field;

}

add_filter('acf/load_field/name=product_link', 'alarnd_get_products');


/**
 * Check if the current variable is set, and is not NULL, in the WP_Query class.
 *
 * @see    WP_Query::$query_vars
 * @uses   $wp_query
 *
 * @param  string $var  The variable key to be checked.
 * @return bool         True if the current variable is set.
 */

function allaround_query_var($var)
{
    global $wp_query;

    return isset($wp_query->query_vars[$var]);
}

function alarnd_all_review_count()
{
    $review_args = array(
        'posts_per_page' => 50000,
        'post_type' => 'review',
        'post_status' => 'publish',
        'order' => 'DESC'
    );
    $review_qry = new WP_Query($review_args);
    return number_format($review_qry->found_posts);
}
function alarnd_get_avarage_round()
{
    return round(alarnd_get_avarage_review());
}
function alarnd_get_avarage_review()
{
    $review_args = array(
        'numberposts' => 50000,
        'post_type' => 'review',
        'post_status' => 'publish',
        'order' => 'DESC'
    );
    $all_review = get_posts($review_args);
    $number_review = count($all_review);
    $totalStars = 0;
    foreach ($all_review as $review) {
        $rating = get_field('rating', $review->ID);
        $rating = (int) $rating;
        $totalStars += $rating;
    }
    $average = $totalStars / $number_review;
    return number_format($average, 1);
}

function alarnd_get_product_by_use($term_id)
{
    $product_args = array(
        'numberposts' => 50000,
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'uses',
                'field' => 'term_id',
                'terms' => $term_id
            )
        )
    );
    $all_products = get_posts($product_args);
    $get_products_count = count($all_products);
    if ($get_products_count === 1) {
        return $all_products[0]->ID;
    }
    return false;
}

function alarnd_get_product_by_term($term_id)
{
    $product_args = array(
        'numberposts' => 50000,
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $term_id
            )
        )
    );
    $all_products = get_posts($product_args);
    $get_products_count = count($all_products);
    if ($get_products_count === 1) {
        return $all_products[0]->ID;
    }
    return false;
}


// add_filter('wpcf7_form_elements', function($content) {
//     $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

//     return $content;
// });


function alarnd_total_review_icons()
{
    $avarage_rating = alarnd_get_avarage_round();
    ?>
                                                                    <span class="rating-stars rating-<?php echo $avarage_rating; ?>">
                                                                        <?php
                                                                        $review_range = range(1, $avarage_rating);
                                                                        for ($i = 1; $i <= 5; $i++) {
                                                                            if ($i <= $avarage_rating) {
                                                                                echo '<i class="fa fa-star"></i>';
                                                                            } else {
                                                                                echo '<i class="fa fa-star-o"></i>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    <?php
}

function alarnd_single_review_avg($rattings)
{
    $avarage_rating = empty($rattings) ? 0 : $rattings;
    ?>
                                                                    <span class="rating-stars rating-<?php echo $avarage_rating; ?>">
                                                                        <?php
                                                                        $review_range = range(1, $avarage_rating);
                                                                        for ($i = 1; $i <= 5; $i++) {
                                                                            if ($i <= $avarage_rating) {
                                                                                echo '<i class="fa fa-star"></i>';
                                                                            } else {
                                                                                echo '<i class="fa fa-star-o"></i>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    <?php
}


function alarnd_all_reviews($expend = false)
{
    $ppp = 3;
    $review_args = array(
        'posts_per_page' => $ppp,
        'post_type' => 'review',
        'post_status' => 'publish',
        'order' => 'DESC'
    );
    $review_qry = new WP_Query($review_args);
    $found_reviews = $review_qry->found_posts;
    ?>
                                                                    <div id="customer-reviews" class="alarnd--review-wrapper<?php echo true === $expend ? ' alarnd-expand' : ''; ?>">
                                                                        <h3><?php esc_html_e('םישמתשמ בושמ', 'hello-elementor'); ?></h3>
                                                                        <?php if ($review_qry->have_posts()): ?>
                                                                                                                                            <div class="alarnd--review-groups" data-ppp="<?php echo esc_attr($ppp); ?>">
                                                                                                                                                <?php
                                                                                                                                                while ($review_qry->have_posts()):
                                                                                                                                                    $review_qry->the_post();

                                                                                                                                                    $rating = get_post_meta(get_the_ID(), 'rating', true);
                                                                                                                                                    $name = get_post_meta(get_the_ID(), 'name', true);
                                                                                                                                                    $custom_date = get_post_meta(get_the_ID(), 'custom_date', true);
                                                                                                                                                    $email = get_post_meta(get_the_ID(), 'email', true);
                                                                                                                                                    $avatar = get_post_meta(get_the_ID(), 'avatar', true);
                                                                                                                                                    $thumb = get_post_meta(get_the_ID(), 'review_thumb', true);

                                                                                                                                                    $user_email = !empty($email) ? $email : null;
                                                                                                                                                    $gravatar = !empty($avatar) ? '<img src="' . wp_get_attachment_url((int) $avatar) . '"/>' : get_avatar($user_email, 100, 'mystery');
                                                                                                                                                    $review_thumb = !empty($thumb) ? '<a href="' . wp_get_attachment_url((int) $thumb) . '"><img src="' . wp_get_attachment_url((int) $thumb) . '"/></a>' : null;
                                                                                                                                                    $user_name = !empty($name) ? $name : esc_html__('Anonymous', 'hello-elementor');


                                                                                                                                                    $the_date = !empty($custom_date) ? date_i18n('j F ,Y', strtotime($custom_date)) : get_the_date('j F ,Y');

                                                                                                                                                    ?>
                                                                                                                                                                                                                    <div class="alarnd--single-review">
                                                                                                                                                                                                                        <div class="review-item">
                                                                                                                                                                                                                            <div class="review-avatar">
                                                                                                                                                                                                                                <?php echo $gravatar; ?>
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            <div class="review-body">
                                                                                                                                                                                                                                <?php echo alarnd_single_review_avg($rating); ?>

                                                                                                                                                                                                                                <span class="review-title"><?php the_title(); ?></span>

                                                                                                                                                                                                                                <div class="review-details">
                                                                                                                                                                                                                                    <div class="review-avatar-mobile">
                                                                                                                                                                                                                                        <?php echo $gravatar; ?>
                                                                                                                                                                                                                                    </div>
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
                                                                                                                                            <?php if ($found_reviews > $ppp): ?>
                                                                                                                                                                                                                <div class="alarnd--all-review-page">
                                                                                                                                                                                                                    <a href="#" class="alarn--load-review alarnd_simple_button">לחצו לביקורות נוספות</a>
                                                                                                                                                                                                                </div>
                                                                                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                                                                                            <p><?php esc_html_e('Sorry, no review found.', 'hello-elmentor'); ?></p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php
}

function alarn_get_review_page_link()
{
    return '#';
}


function alarnd_add_to_cart_redirect($url, $adding_to_cart)
{

    if (!isset($_REQUEST['add-to-cart']) || !is_numeric($_REQUEST['add-to-cart'])) {
        return $url;
    }

    // error_log( print_r( $adding_to_cart, true ) );
    // error_log( print_r( $_REQUEST, true ) );
    // error_log( print_r( $_POST, true ) );

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['add-to-cart']));
    $alarnd__group_id = (isset($_POST['alarnd__group_id']) && !empty($_POST['alarnd__group_id'])) ? $_POST['alarnd__group_id'] : '';

    $is_group_enable = get_field('group_enable', $product_id);
    $sizes = get_field('size', $product_id);

    $product = wc_get_product($product_id);

    $cart_data = $_REQUEST;

    $add_query_arg = [];
    $add_query_arg['configure'] = true;
    $add_query_arg['product_id'] = $product_id;

    if ('variable' === $product->get_type()) {
        $add_query_arg['variation_id'] = $cart_data['variation_id'];
    }

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (
            !empty($is_group_enable) &&
            !empty($sizes) &&
            $cart_item['product_id'] === $product_id &&
            isset($cart_item['alarnd_group_id']) &&
            !empty($cart_item['alarnd_group_id']) &&
            $cart_item['alarnd_group_id'] === $alarnd__group_id
        ) {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }

    global $woocommerce;

    $product_id = '';
    if (isset($_POST) && isset($_POST['add-to-cart']) && !empty($_POST['add-to-cart'])) {
        $product_id = absint($_POST['add-to-cart']);
    }

    if (empty($product_id))
        return;

    $product = wc_get_product($product_id);

    $colors = get_field('color', $product_id);

    $alarnd__color = (isset($_POST['alarnd__color']) && !empty($_POST['alarnd__color'])) ? sanitize_text_field($_POST['alarnd__color']) : '';
    $alarnd__sizes = (isset($_POST['alarnd__size']) && !empty($_POST['alarnd__size'])) ? $_POST['alarnd__size'] : '';
    $alarnd__color_qty = (isset($_POST['alarnd__color_qty']) && !empty($_POST['alarnd__color_qty'])) ? $_POST['alarnd__color_qty'] : '';
    $get_total_qtys = ml_get_total_qty($alarnd__color_qty);

    $group_enable = get_field('group_enable', $product->get_id());
    $custom_quanity = get_field('enable_custom_quantity', $product->get_id());
    $colors = get_field('color', $product->get_id());
    $sizes = get_field('size', $product->get_id());

    if (!empty($alarnd__group_id)) {
        $add_query_arg['group_uniqid'] = $alarnd__group_id;
    }

    if (
        'simple' === $product->get_type() &&
        !empty($group_enable) &&
        !empty($alarnd__color_qty)
    ) {

        foreach ($alarnd__color_qty as $color_key => $item) {
            foreach ((array) $item as $i_size => $i_qty) {
                if (empty($i_qty)) {
                    continue;
                }

                $cart_item_meta = array();
                $cart_item_meta['alarnd_color'] = $colors[$color_key]['title'];
                $cart_item_meta['alarnd_size'] = $i_size;
                $cart_item_meta['alarnd_group_qty'] = $get_total_qtys;
                $cart_item_meta['alarnd_quantity'] = $i_qty;
                $cart_item_meta['alarnd_group_id'] = $alarnd__group_id;

                // error_log( print_r( $cart_item_meta, true ) );
                WC()->cart->add_to_cart($product_id, (int) $i_qty, '', '', $cart_item_meta);
            }
        }


    }

    // if( 
    //     'simple' === $product->get_type() && 
    //     ! empty( $custom_quanity )
    // ) {
    //     $steps = get_field( 'quantity_steps', $product->get_id() );
    //     $sp_quanity = get_field( 'custom_quanity', $product->get_id() );

    //     $steps_key = isset( $_POST['cutom_quantity'] ) ? $_POST['cutom_quantity'] : '';
    //     $attr_qty = (isset( $_POST['attribute_quantity'] ) && ! empty( $_POST['attribute_quantity'] )) ? sanitize_text_field( $_POST['attribute_quantity'] ) : '';

    //     $size = isset( $_POST['cutom_quantity'] ) && isset( $setps[$steps_key] ) ? $setps[$steps_key]['quantity'] : 1;
    //     if( ! empty( $attr_qty ) ) {
    //         $size = $attr_qty;
    //     }

    //     $cart_item_meta = array();
    //     $cart_item_meta['alarnd_step_key'] = $steps_key;
    //     $cart_item_meta['custom_'] = $size;
    //     $cart_item_meta['alarnd_custom_attr'] = $attr_qty;
    //     WC()->cart->add_to_cart( $product_id, 1, '', '', $cart_item_meta );
    // }


    $add_query_arg = add_query_arg($add_query_arg, '');

    return $add_query_arg;
}
// add_filter ('woocommerce_add_to_cart_redirect', 'alarnd_add_to_cart_redirect', 10, 2 ); 


add_filter('woocommerce_add_cart_item_data', 'alarnd_save_custom_product_data', 10, 2);
function alarnd_save_custom_product_data($cart_item_data, $product_id)
{

    $product = wc_get_product($product_id);
    $custom_quanity = get_field('enable_custom_quantity', $product->get_id());
    $is_position_enable = get_field('art_position_enable', $product->get_id());

    if (isset($_REQUEST['alarnd__group_id']) && !empty($_POST['alarnd__group_id'])) {
        $cart_item_data['alarnd_group_id'] = $_REQUEST['alarnd__group_id'];
    }

    if (isset($_REQUEST['cutom_quantity'])) {
        $cart_item_data['alarnd_step_key'] = $_REQUEST['cutom_quantity'];
    }
    if (isset($_REQUEST['custom_color'])) {
        $cart_item_data['alarnd_custom_color'] = $_REQUEST['custom_color'];
    }
    if (isset($_REQUEST['attribute_quantity']) && !empty($_POST['attribute_quantity'])) {
        $cart_item_data['alarnd_custom_quantity'] = $_REQUEST['attribute_quantity'];
    }


    return $cart_item_data;
}

add_filter('wc_add_to_cart_message_html', '__return_false');


/**
 * Get product id
 */
function alarnd_product_id()
{

    $product_id = '';
    if (isset($_POST) && isset($_POST['add-to-cart']) && !empty($_POST['add-to-cart'])) {
        $product_id = absint($_POST['add-to-cart']);
    } elseif (isset($_GET) && isset($_GET['product_id']) && !empty($_GET['product_id'])) {
        $product_id = absint($_GET['product_id']);
    }

    return $product_id;
}

/**
 * Get variation id
 */
function alarnd_variation_id()
{

    $variation_id = '';
    if (isset($_POST) && isset($_POST['variation_id']) && !empty($_POST['variation_id'])) {
        $variation_id = absint($_POST['variation_id']);
    } elseif (isset($_GET) && isset($_GET['variation_id']) && !empty($_GET['variation_id'])) {
        $variation_id = absint($_GET['variation_id']);
    }

    return $variation_id;
}



/**
 * Display engraving text in the cart.
 *
 * @param array $item_data
 * @param array $cart_item
 *
 * @return array
 */
function alarnd_display_artwork_id_to_cart($item_data, $cart_item)
{
    if (empty($cart_item['allaround_artwork_id']) || empty($cart_item['allaround_artwork_id2'])) {
        return $item_data;
    }
    // $art_attachment_id = absint( $cart_item['allaround_artwork_id'] );
    // $filename_only = basename( get_attached_file( $art_attachment_id ) ); // Just the file name
    $item_data[] = array(
        'key' => 'attachment',
        'value' => esc_html__('הקובץ הועלה בהצלחה!', 'hello-elementor'),
        // 'value'   => $filename_only,
        'display' => '',
    );


    return $item_data;
}

add_filter('woocommerce_get_item_data', 'alarnd_display_artwork_id_to_cart', 10, 2);

function alarnd_display_group_meta_to_cart($item_data, $cart_item)
{
    if (empty($cart_item['alarnd_color']) || empty($cart_item['alarnd_size'])) {
        return $item_data;
    }

    $item_data[] = array(
        'key' => esc_html__('Color', 'hello-elementor'),
        'value' => wc_clean($cart_item['alarnd_color']),
        'display' => '',
    );

    $item_data[] = array(
        'key' => esc_html__('Size', 'hello-elementor'),
        'value' => wc_clean($cart_item['alarnd_size']),
        'display' => '',
    );

    return $item_data;
}

add_filter('woocommerce_get_item_data', 'alarnd_display_group_meta_to_cart', 10, 2);

function alarnd_display_custom_qty($item_data, $cart_item)
{
    if (empty($cart_item['alarnd_custom_color'])) {
        return $item_data;
    }

    $item_data[] = array(
        'key' => esc_html__('Color', 'hello-elementor'),
        'value' => wc_clean($cart_item['alarnd_custom_color']),
        'display' => '',
    );

    return $item_data;
}

add_filter('woocommerce_get_item_data', 'alarnd_display_custom_qty', 10, 2);




/**
 * Add engraving text to order.
 *
 * @param WC_Order_Item_Product $item
 * @param string                $cart_item_key
 * @param array                 $values
 * @param WC_Order              $order
 */
function alarnd_variations_to_order_items($item, $cart_item_key, $values, $order)
{
    if (isset($values['alarnd_color']) && !empty($values['alarnd_color'])) {
        $item->add_meta_data(__('Color', 'hello-elementor'), $values['alarnd_color']);
    }
    if (isset($values['alarnd_custom_color']) && !empty($values['alarnd_custom_color'])) {
        $item->add_meta_data(__('Color', 'hello-elementor'), $values['alarnd_custom_color']);
    }
    if (isset($values['alarnd_size']) && !empty($values['alarnd_size'])) {
        $item->add_meta_data(__('Size', 'hello-elementor'), $values['alarnd_size']);
    }
    if (isset($values['allaround_art_pos_key']) && !empty($values['allaround_art_pos_key'])) {
        $get_product_id = $values['product_id'];
        $art_positions = get_field('art_positions', $get_product_id);
        $art_item = isset($art_positions[$values['allaround_art_pos_key']]) ? $art_positions[$values['allaround_art_pos_key']] : '';
        if (!empty($art_item)):
            $info = '<div class="allarnd__order_item">';
            $info .= '<p>' . $art_item['title'] . ' ( ' . $art_item['max'] . ' )</p>';
            $info .= '</div>';
            $pos_serialize = maybe_serialize($art_item);
            $item->add_meta_data(__('Art Position', 'hello-elementor'), $info);
            $item->add_meta_data('_allaround_art_pos_key', $pos_serialize, true);
        endif;
    }
    if (isset($values['allaround_artwork_id']) && !empty($values['allaround_artwork_id'])) {
        $art_attachment_id = absint($values['allaround_artwork_id']);
        $thumb_url = wp_get_attachment_image_url($art_attachment_id);
        $get_file_url = wp_get_attachment_url($art_attachment_id);
        $filename_only = basename(get_attached_file($art_attachment_id)); // Just the file name
        $media_info = '<div class="allarnd__order_item">';
        $media_info = '<p>' . $filename_only . '</p>';
        $media_info .= '<a href="' . esc_url($get_file_url) . '" target="_blank"><img class="alarnd__artwork_img" src="' . esc_url($thumb_url) . '" /></a>';
        $media_info .= '</div>';
        $item->add_meta_data(__('Attachment', 'hello-elementor'), $media_info);
        $item->add_meta_data('_allaround_artwork_id', $values['allaround_artwork_id'], true);
    }
    if (isset($values['allaround_artwork_id2']) && !empty($values['allaround_artwork_id2'])) {
        $art_attachment_id = absint($values['allaround_artwork_id2']);
        $thumb_url = wp_get_attachment_image_url($art_attachment_id);
        $get_file_url = wp_get_attachment_url($art_attachment_id);
        $filename_only = basename(get_attached_file($art_attachment_id)); // Just the file name
        $media_info = '<div class="allarnd__order_item">';
        $media_info = '<p>' . $filename_only . '</p>';
        $media_info .= '<a href="' . esc_url($get_file_url) . '" target="_blank"><img class="alarnd__artwork_img" src="' . esc_url($thumb_url) . '" /></a>';
        $media_info .= '</div>';
        $item->add_meta_data(__('Additional Attachment', 'hello-elementor'), $media_info);
        $item->add_meta_data('_allaround_artwork_id2', $values['allaround_artwork_id2'], true);
    }

    if (isset($values['allaround_instruction_note']) && !empty($values['allaround_instruction_note'])) {
        $item->add_meta_data(__('Instruction Note', 'hello-elementor'), $values['allaround_instruction_note']);
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'alarnd_variations_to_order_items', 10, 4);

add_filter('woocommerce_product_variation_title_include_attributes', '__return_false');

function custom_woocommerce_hidden_order_itemmeta($arr)
{
    $arr[] = '_allaround_art_pos_key';
    $arr[] = '_allaround_artwork_id';
    $arr[] = '_allaround_artwork_id2';
    return $arr;
}

add_filter('woocommerce_hidden_order_itemmeta', 'custom_woocommerce_hidden_order_itemmeta', 10, 1);

/**
 * Meta Output
 *
 * @since 1.0
 *
 * @return array
 */
if (!function_exists('allround_get_meta')) {
    function allround_get_meta($data)
    {
        global $wp_embed;
        $content = $wp_embed->autoembed($data);
        $content = $wp_embed->run_shortcode($content);
        $content = do_shortcode($content);
        $content = wpautop($content);
        return $content;
    }
}

if (!function_exists("array_key_last")) {
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array) - 1];
    }
}



function alarnd_cart_inner_before()
{

    global $product;
    $group_enable = get_field('group_enable', $product->get_id());
    $saving_info = get_field('saving_info', $product->get_id());
    $colors = get_field('colors', $product->get_id());
    $colors_title = get_field('title_for_colors', $product->get_id());
    $the_color_title = !empty($colors_title) ? $colors_title : esc_html__('Select a Color', 'hello-elementor');
    $custom_quanity = get_field('enable_custom_quantity', $product->get_id());
    if ("simple" === $product->get_type() && !empty($custom_quanity)):
        $steps = get_field('quantity_steps', $product->get_id());
        $last_step = array_key_last($steps);
        if (!empty($steps) && !empty($custom_quanity)): ?>
                                                                                                                                                                                                            <div class="alarnd--cart-inner">
                                                                                                                                                                                                                <?php if (!empty($colors)): ?>
                                                                                                                                                                                                                                                                                    <div class="alarnd--single-cart-row">
                                                                                                                                                                                                                                                                                        <span><?php echo esc_html($the_color_title); ?></span>
                                                                                                                                                                                                                                                                                        <?php
                                                                                                                                                                                                                                                                                        foreach ($colors as $key => $item): ?>
                                                                                                                                                                                                                                                                                                                                                            <div class="alarnd--custom-qtys-wrap">
                                                                                                                                                                                                                                                                                                                                                                <div class="alarnd--single-variable">
                                                                                                                                                                                                                                                                                                                                                                    <span class="alarnd--single-var-info">
                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" id="custom_color-<?php echo $key; ?>" name="custom_color"
                                                                                                                                                                                                                                                                                                                                                                            value="<?php echo esc_attr($item['color']); ?>" <?php echo 0 === $key ? 'checked="checked"' : ''; ?>>
                                                                                                                                                                                                                                                                                                                                                                        <label for="custom_color-<?php echo $key; ?>"><?php echo esc_html($item['color']); ?></label>
                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                    <span class="woocommerce-Price-amount amount"></span>
                                                                                                                                                                                                                                                                                                                                                                    <span class="alarnd--single-saving"></span>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        <?php endforeach; ?>
                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                <?php endif; ?>
                                                                                                                                                                                                                <div class="alarnd--single-cart-row" data-reqular-price="<?php echo $product->get_regular_price(); ?>">
                                                                                                                                                                                                                    <span><?php esc_html_e('Select a Quantity', 'hello-elementor'); ?></span>
                                                                                                                                                                                                                    <?php $the_price = isset($steps[$last_step]['amount']) ? $steps[$last_step]['amount'] : $product->get_regular_price(); ?>
                                                                                                                                                                                                                    <div class="alarnd--custom-qtys-wrap alarnd--single-custom-qty alarnd--single-var-labelonly">
                                                                                                                                                                                                                        <div class="alarnd--single-variable alarnd--hide-price"
                                                                                                                                                                                                                            data-min="<?php echo esc_attr($steps[0]['quantity']); ?>"
                                                                                                                                                                                                                            data-price="<?php echo esc_attr($the_price); ?>">
                                                                                                                                                                                                                            <span class="alarnd--single-var-info">
                                                                                                                                                                                                                                <input type="radio" name="cutom_quantity" id="cutom_quantity_special-custom"
                                                                                                                                                                                                                                    value="<?php echo esc_attr($the_price); ?>" checked="checked">
                                                                                                                                                                                                                                <input type="text" name="attribute_quantity" autocomplete="off" pattern="[0-9]*"
                                                                                                                                                                                                                                    class="alarnd_custom_input" inputmode="numeric"
                                                                                                                                                                                                                                    placeholder="<?php esc_html_e('הקלידו כמות…', 'hello-elementor'); ?>"
                                                                                                                                                                                                                                    id="attribute_quanity_custom_val">
                                                                                                                                                                                                                                <!-- <label id="custom_quantity_label" for="cutom_quantity_special-custom"><//?//php esc_html_e( 'Custom Quantity', 'hello-elementor' ); ?></label> -->
                                                                                                                                                                                                                            </span>
                                                                                                                                                                                                                            <?php echo wc_price(0, array('decimals' => 0)); ?>
                                                                                                                                                                                                                            <span class="alarnd--single-saving"><span
                                                                                                                                                                                                                                    class="alarnd__cqty_amount"><?php echo esc_html($steps[$last_step]['amount']); ?></span>
                                                                                                                                                                                                                                <?php echo esc_html($saving_info); ?></span>
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <?php if (!empty($steps)):
                                                                                                                                                                                                                        foreach ($steps as $key => $step):
                                                                                                                                                                                                                            $item_price = !empty($step['amount']) ? $step['amount'] : $product->get_regular_price();
                                                                                                                                                                                                                            $price = (int) $step['quantity'] * floatval($item_price);
                                                                                                                                                                                                                            $hide = isset($step['hide']) && !empty($step['hide']) ? true : false;
                                                                                                                                                                                                                            ?>
                                                                                                                                                                                                                                                                                                                                                            <div class="alarnd--custom-qtys-wrap<?php echo true === $hide ? ' alarnd--hide-qty' : ''; ?>"
                                                                                                                                                                                                                                                                                                                                                                data-qty="<?php echo esc_attr($step['quantity']); ?>" data-price="<?php echo esc_attr($item_price); ?>">
                                                                                                                                                                                                                                                                                                                                                                <div class="alarnd--single-variable">
                                                                                                                                                                                                                                                                                                                                                                    <span class="alarnd--single-var-info">
                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" id="cutom_quantity-<?php echo $key; ?>" name="cutom_quantity"
                                                                                                                                                                                                                                                                                                                                                                            value="<?php echo $key; ?>" <?php echo 0 === $key ? 'checked="checked"' : ''; ?>>
                                                                                                                                                                                                                                                                                                                                                                        <label for="cutom_quantity-<?php echo $key; ?>"><?php echo esc_html($step['quantity']); ?></label>
                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                    <?php echo wc_price((int) $price, array('decimals' => 0)); ?>
                                                                                                                                                                                                                                                                                                                                                                    <span class="alarnd--single-saving"><?php echo esc_html($item_price); ?>
                                                                                                                                                                                                                                                                                                                                                                        <?php echo esc_html($saving_info); ?></span>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        <?php endforeach; endif;
                                                                                                                                                                                                                    ?>
                                                                                                                                                                                                                </div>

                                                                                                                                            <?php endif;

    elseif ("simple" === $product->get_type() && !empty($group_enable)):
        $colors = get_field('color', $product->get_id());
        $sizes = get_field('size', $product->get_id());
        $pricing_description = get_field('pricing_description', $product->get_id());
        $discount_steps = get_field('discount_steps', $product->get_id());
        $price_steps = get_field('price_steps', $product->get_id());
        $adult_sizes = get_field('adult_sizes', 'option', false);
        $adult_sizes = ml_filter_string_to_array($adult_sizes);
        $child_sizes = get_field('child_sizes', 'option', false);
        $child_sizes = ml_filter_string_to_array($child_sizes);
        $first_line_keyword = get_field('first_line_keyword', $product->get_id());
        $size_popup_show = get_field('size_popup_show', $product->get_id());
        $size_popup_text = get_field('size_popup_text', $product->get_id());
        $size_popup_title = get_field('size_chart_title', $product->get_id());
        $size_img = get_post_meta($product->get_id(), 'size_image', true);
        $child_size_img = get_post_meta($product->get_id(), 'child_size_image', true);
        $second_line_keyword = get_field('second_line_keyword', $product->get_id());
        $remove_child_size_container = get_field('remove_child_size_title', $product->get_id());

        $all_sizes = array_merge($child_sizes, $adult_sizes);

        $selected_omit_sizes = get_field('omit_sizes_from_chart', $product->get_id());

        $product_thumbmail = get_the_post_thumbnail($product->get_id(), 'full');
        $first_line_keyword = !empty($first_line_keyword) ? $first_line_keyword : esc_html__('Shirt', 'hello-elementor');
        $second_line_keyword = !empty($second_line_keyword) ? $second_line_keyword : esc_html__('Total Shirts', 'hello-elementor');
        $prod_size_img = !empty($size_img) ? '<a href="' . wp_get_attachment_url((int) $size_img) . '"><img src="' . wp_get_attachment_url((int) $size_img) . '"/></a>' : null;
        $prod_child_size_img = !empty($child_size_img) ? '<a href="' . wp_get_attachment_url((int) $child_size_img) . '"><img src="' . wp_get_attachment_url((int) $child_size_img) . '"/></a>' : null;

        $discount_steps = ml_filter_disount_steps($discount_steps);

        $json_data = array(
            "regular_price" => $product->get_regular_price(),
            "data" => $discount_steps
        );
        ?>
                                                                                                                                            <?php if (!empty($group_enable) && !empty($sizes)): ?>
                                                                                                                                                                                                                <div id="alarnd__pricing_info" class="mfp-hide white-popup-block alarnd--info-modal">
                                                                                                                                                                                                                    <div class="alarnd--modal-inner alarnd--modal-chart-info">
                                                                                                                                                                                                                        <!-- <h2><?//php echo get_the_title( $product->get_id() ); ?></h2> -->

                                                                                                                                                                                                                        <div class="alarnd--pricing-wrapper">
                                                                                                                                                                                                                            <?php if (!empty($pricing_description)): ?>
                                                                                                                                                                                                                                                                                                <div class="alarn--pricing-column alarn--pricing-column-desc">
                                                                                                                                                                                                                                                                                                    <?php echo allround_get_meta($pricing_description); ?>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                            <?php endif; ?>
                                                                                                                                                                                                                            <?php if (!empty($pricing_description)): ?>
                                                                                                                                                                                                                                                                                                <div class="alarn--pricing-column alarn--pricing-column-chart">
                                                                                                                                                                                                                                                                                                    <div class="alarn--price-chart">
                                                                                                                                                                                                                                                                                                        <div class="alarnd--price-chart-price">
                                                                                                                                                                                                                                                                                                            <div class="alarnd--price-chart-item">
                                                                                                                                                                                                                                                                                                                <span>כַּמוּת</span>
                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                            <?php
                                                                                                                                                                                                                                                                                                            $index = 0;
                                                                                                                                                                                                                                                                                                            foreach ($discount_steps as $step):
                                                                                                                                                                                                                                                                                                                $prev = ($index == 0) ? false : $discount_steps[$index - 1];
                                                                                                                                                                                                                                                                                                                $qty = ml_get_price_range($step['quantity'], $step['amount'], $prev);

                                                                                                                                                                                                                                                                                                                ?>
                                                                                                                                                                                                                                                                                                                                                                                <div class="alarnd--price-chart-item">
                                                                                                                                                                                                                                                                                                                                                                                    <span><?php echo esc_html($qty); ?></span>
                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                <?php $index++; endforeach; ?>
                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                        <div class="alarnd--price-chart-qty">
                                                                                                                                                                                                                                                                                                            <div class="alarnd--price-chart-item">
                                                                                                                                                                                                                                                                                                                <span>מחיר (כולל מע"מ)</span>
                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                            <?php foreach ($discount_steps as $step): ?>
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
                                                                                                                                                                                                                            <button type="button"
                                                                                                                                                                                                                                class="alarnd_view_select button alt"><?php esc_html_e('הוסף לעגלה שלך', 'hello-elementor'); ?></button>
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                            <?php endif; ?>
                                                                                                                                            <?php if (!empty($group_enable) && !empty($size_popup_show)): ?>
                                                                                                                                                                                                                <div id="alarnd__sizes_info" class="mfp-hide white-popup-block alarnd--info-modal">
                                                                                                                                                                                                                    <div class="alarnd--modal-inner">
                                                                                                                                                                                                                        <?php if (!empty($size_popup_show)): ?>
                                                                                                                                                                                                                                                                                            <?php
                                                                                                                                                                                                                                                                                            $show_chart_title = ($size_popup_title) ? esc_html($size_popup_title) : 'Size Guide';
                                                                                                                                                                                                                                                                                            echo '<h2>' . $show_chart_title . '</h2>';
                                                                                                                                                                                                                                                                                            ?>
                                                                                                                                                                                                                        <?php endif; ?>

                                                                                                                                                                                                                        <?php if (!empty($prod_size_img) && !empty($prod_child_size_img)): ?>
                                                                                                                                                                                                                                                                                            <div class="tabset">
                                                                                                                                                                                                                                                                                                <!-- Tab 1 -->
                                                                                                                                                                                                                                                                                                <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
                                                                                                                                                                                                                                                                                                <label for="tab1"><?php esc_html_e('Adult Size Chart', 'hello-elementor'); ?></label>
                                                                                                                                                                                                                                                                                                <!-- Tab 2 -->
                                                                                                                                                                                                                                                                                                <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
                                                                                                                                                                                                                                                                                                <label for="tab2"><?php esc_html_e('Children Size Chart', 'hello-elementor'); ?></label>

                                                                                                                                                                                                                                                                                                <div class="tab-panels">
                                                                                                                                                                                                                                                                                                    <section id="marzen" class="tab-panel">
                                                                                                                                                                                                                                                                                                        <?php echo $prod_size_img; ?>
                                                                                                                                                                                                                                                                                                    </section>
                                                                                                                                                                                                                                                                                                    <section id="rauchbier" class="tab-panel">
                                                                                                                                                                                                                                                                                                        <?php echo $prod_child_size_img; ?>
                                                                                                                                                                                                                                                                                                    </section>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                        <?php else: ?>
                                                                                                                                                                                                                                                                                            <?php echo $prod_size_img; ?>
                                                                                                                                                                                                                                                                                            <?php echo $prod_child_size_img; ?>
                                                                                                                                                                                                                        <?php endif; ?>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                            <?php endif; ?>
                                                                                                                                            <div class="alarnd--overlay loading"></div>
                                                                                                                                            <div class="alarnd--cart-inner">

                                                                                                                                                <?php if (!empty($group_enable) && !empty($sizes)): ?>
                                                                                                                                                                                                                    <div class="alarnd--single-cart-row">
                                                                                                                                                                                                                        <span><?php esc_html_e('Select sizes', 'hello-elementor'); ?>
                                                                                                                                                                                                                            <?php if (!empty($size_popup_show)): ?>
                                                                                                                                                                                                                                                                                                <a class="alarnd__info_trigger" href="#alarnd__sizes_info">
                                                                                                                                                                                                                                                                                                    <?php
                                                                                                                                                                                                                                                                                                    $show_chart_text = ($size_popup_text) ? esc_html($size_popup_text) : 'טבלת מידות';
                                                                                                                                                                                                                                                                                                    echo $show_chart_text;
                                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                            <?php endif; ?>
                                                                                                                                                                                                                        </span>
                                                                                                                                                                                                                        <?php if (!empty($adult_sizes)): ?>
                                                                                                                                                                                                                                                                                            <div class="alarnd--sizes-wrapper alarnd--sizes-wrapper-b-15">
                                                                                                                                                                                                                                                                                                <h4><?php esc_html_e('מבוגרים', 'hello-elementor'); ?></h4>
                                                                                                                                                                                                                                                                                                <div class="alarnd--sizes-wrap">
                                                                                                                                                                                                                                                                                                    <?php foreach ($adult_sizes as $key => $size): ?>
                                                                                                                                                                                                                                                                                                                                                                        <?php if (!ml_is_omit($size, $selected_omit_sizes)): ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="alarnd--single-size">
                                                                                                                                                                                                                                                                                                                                                                                                                                                <label
                                                                                                                                                                                                                                                                                                                                                                                                                                                    for="alarnd__size_<?php echo strtolower($size); ?>"><?php echo esc_html($size); ?></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        <?php endif; ?>
                                                                                                                                                                                                                                                                                                    <?php endforeach; ?>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                        <?php endif; ?>
                                                                                                                                                                                                                        <?php if (!empty($child_sizes) && empty($remove_child_size_container)): ?>
                                                                                                                                                                                                                                                                                            <div class="alarnd--sizes-wrapper">
                                                                                                                                                                                                                                                                                                <h4><?php esc_html_e('ילדים', 'hello-elementor'); ?></h4>
                                                                                                                                                                                                                                                                                                <div class="alarnd--sizes-wrap">
                                                                                                                                                                                                                                                                                                    <?php foreach ($child_sizes as $key => $size): ?>
                                                                                                                                                                                                                                                                                                                                                                        <?php if (!ml_is_omit($size, $selected_omit_sizes)): ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="alarnd--single-size">
                                                                                                                                                                                                                                                                                                                                                                                                                                                <label
                                                                                                                                                                                                                                                                                                                                                                                                                                                    for="alarnd__size_<?php echo strtolower($size); ?>"><?php echo esc_html($size); ?></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        <?php endif; ?>
                                                                                                                                                                                                                                                                                                    <?php endforeach; ?>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                        <?php endif; ?>
                                                                                                                                                                                                                    </div>
                                                                                                                                                <?php endif; ?>
                                                                                                                                                <?php if (!empty($group_enable) && !empty($colors)): ?>
                                                                                                                                                                                                                    <div class="alarnd--single-cart-row">
                                                                                                                                                                                                                        <span><?php esc_html_e('Select Color', 'hello-elementor'); ?></span>
                                                                                                                                                                                                                        <div class="alarnd--colors-wrapper">
                                                                                                                                                                                                                            <div class="alarnd--colors-wrap">
                                                                                                                                                                                                                                <?php foreach ($colors as $key => $color): ?>
                                                                                                                                                                                                                                                                                                    <input type="radio" name="alarnd__color" id="alarnd__color_<?php echo esc_html($color['title']); ?>"
                                                                                                                                                                                                                                                                                                        value="<?php echo esc_html($color['title']); ?>" <?php echo 0 == $key ? 'checked' : ''; ?>>
                                                                                                                                                                                                                                                                                                    <label for="alarnd__color_<?php echo esc_html($color['title']); ?>" class="alarnd--single-color"
                                                                                                                                                                                                                                                                                                        data-key="<?php $key; ?>" data-name="<?php echo esc_html($color['title']); ?>"
                                                                                                                                                                                                                                                                                                        style="background-color: <?php echo $color['color_hex_code']; ?>">
                                                                                                                                                                                                                                                                                                    </label>
                                                                                                                                                                                                                                <?php endforeach; ?>
                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                            <p class="alarnd--color-name"><?php echo !empty($colors) ? $colors[0]['title'] : ''; ?></p>
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class="alarnd--button-groups">
                                                                                                                                                                                                                        <button type="button"
                                                                                                                                                                                                                            class="alarnd_view_select button alt"><?php esc_html_e('בחר גדלים וכמות', 'hello-elementor'); ?></button>
                                                                                                                                                                                                                        <button type="button"
                                                                                                                                                                                                                            class="alarnd_view_pricing button alt"><?php esc_html_e('התמחור שלנו', 'hello-elementor'); ?></button>
                                                                                                                                                                                                                    </div>
                                                                                                                                                <?php endif; ?>
                                                                                                                                                <div class="alarnd--single-cart-row alarnd--single-cart-price">
                                                                                                                                                    <span
                                                                                                                                                        class="alarnd--total-price"><?php echo wc_price($product->get_regular_price(), array('decimals' => 0)); ?></span>
                                                                                                                                                    <div class="alarnd--price-by-shirt">
                                                                                                                                                        <p class="alarnd--group-price">
                                                                                                                                                            <?php echo wc_price($product->get_regular_price(), array('decimals' => 0)); ?> /
                                                                                                                                                            <?php echo $first_line_keyword; ?>
                                                                                                                                                        </p>
                                                                                                                                                        <p><?php echo esc_html($second_line_keyword); ?>: <span
                                                                                                                                                                class="alarnd__total_qty"><?php esc_html_e('0', 'hello-elementor'); ?></span></p>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                            <?php endif;
}
function alarnd_cart_inner_after()
{

    global $product;
    $group_enable = get_field('group_enable', $product->get_id());
    $custom_quanity = get_field('enable_custom_quantity', $product->get_id());
    if ("simple" === $product->get_type() && (!empty($group_enable) || !empty($custom_quanity))): ?>
                                                                                                                                            </div>
                                                                        <?php endif;
}
add_action('woocommerce_before_add_to_cart_button', 'alarnd_cart_inner_before', 10);
add_action('woocommerce_before_add_to_cart_button', 'alarnd_cart_inner_after', 10);


function alarnd_form_after()
{
    global $product;
    $group_enable = get_field('group_enable', $product->get_id());

    $colors = get_field('color', $product->get_id());

    $discount_steps = get_field('discount_steps', $product->get_id());
    $adult_sizes = get_field('adult_sizes', 'option', false);
    $adult_sizes = ml_filter_string_to_array($adult_sizes);
    $child_sizes = get_field('child_sizes', 'option', false);
    $child_sizes = ml_filter_string_to_array($child_sizes);
    $first_line_keyword = get_field('first_line_keyword', $product->get_id());
    $second_line_keyword = get_field('second_line_keyword', $product->get_id());
    $quantity_table_title = get_field('quantity_table_title', $product->get_id());

    $all_sizes = array_merge($child_sizes, $adult_sizes);

    $selected_omit_sizes = get_field('omit_sizes_from_chart', $product->get_id());

    $discount_steps = ml_filter_disount_steps($discount_steps);

    $json_data = array(
        "regular_price" => $product->get_regular_price(),
        "data" => $discount_steps
    );

    $uniqid = uniqid('alrnd');

    ?>

                                                                        <?php if (!empty($group_enable)): ?>


                                                                                                                                            <div id="alarnd__select_options_info" class="mfp-hide white-popup-block alarnd--slect-opt-modal alarnd--info-modal">
                                                                                                                                                <div class="alarnd--modal-inner alarnd--modal-chart-info">
                                                                                                                                                    <h2><?php
                                                                                                                                                    $qty_table_title = (!empty($quantity_table_title)) ? esc_html($quantity_table_title) : get_the_title($product->get_id());
                                                                                                                                                    echo $qty_table_title;
                                                                                                                                                    ?>
                                                                                                                                                    </h2>

                                                                                                                                                    <form id="alarnd__select_options_form" class="cart"
                                                                                                                                                        action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                                                                                                                                                        method="post" data-settings='<?php echo wp_json_encode($json_data); ?>' enctype='multipart/form-data'>

                                                                                                                                                        <div class="alarnd--select-options-cart-wrap">
                                                                                                                                                            <div class="alarnd--select-options">

                                                                                                                                                                <div class="alarnd--select-opt-wrapper">
                                                                                                                                                                    <div class="alarnd--select-opt-header">
                                                                                                                                                                        <?php foreach ($all_sizes as $size): ?>
                                                                                                                                                                                                                                            <?php if (!ml_is_omit($size, $selected_omit_sizes)): ?>
                                                                                                                                                                                                                                                                                                                <span><?php echo esc_html($size); ?></span>
                                                                                                                                                                                                                                            <?php endif; ?>
                                                                                                                                                                        <?php endforeach; ?>
                                                                                                                                                                    </div>

                                                                                                                                                                    <div class="alarnd--select-qty-body">
                                                                                                                                                                        <?php foreach ($colors as $key => $color): ?>
                                                                                                                                                                                                                                            <div class="alarn--opt-single-row">
                                                                                                                                                                                                                                                <?php foreach ($all_sizes as $size):
                                                                                                                                                                                                                                                    $disabled = '';
                                                                                                                                                                                                                                                    if (!empty($color['omit_sizes']) && ml_is_omit($size, $color['omit_sizes'])) {
                                                                                                                                                                                                                                                        $disabled = 'disabled="disabled"';
                                                                                                                                                                                                                                                    } ?>
                                                                                                                                                                                                                                                                                                                    <?php if (!ml_is_omit($size, $selected_omit_sizes)): ?>
                                                                                                                                                                                                                                                                                                                                                                                        <div class="tshirt-qty-input-field">
                                                                                                                                                                                                                                                                                                                                                                                            <input
                                                                                                                                                                                                                                                                                                                                                                                                style="box-shadow: 0px 0px 0px 1px <?php echo $color['color_hex_code']; ?>;"
                                                                                                                                                                                                                                                                                                                                                                                                type="text" class="three-digit-input" placeholder="" pattern="^[0-9]*$"
                                                                                                                                                                                                                                                                                                                                                                                                autocomplete="off"
                                                                                                                                                                                                                                                                                                                                                                                                name="alarnd__color_qty[<?php echo $key; ?>][<?php echo $size; ?>]" <?php echo $disabled; ?>>
                                                                                                                                                                                                                                                                                                                                                                                            <span class="alarnd--limit-tooltip">Can't order more than 999</span>
                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                    <?php endif; ?>
                                                                                                                                                                                                                                                <?php endforeach; ?>
                                                                                                                                                                                                                                                <div class="alarnd--opt-color">
                                                                                                                                                                                                                                                    <span
                                                                                                                                                                                                                                                        style="background-color: <?php echo $color['color_hex_code']; ?>"><?php echo $color['title']; ?></span>
                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                        <?php endforeach; ?>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>

                                                                                                                                                        <div class="alarnd--next-target-message">
                                                                                                                                                            <h6><?php printf('%1$s <span class="ml_next_target"></span> %2$s %3$s %4$s', __("Add", "hello-elementor"), __("more items to reduce your cost to", "hello-elementor"), wc_price(0, array('decimals' => 0)), __("per item", "hello-elementor")); ?>
                                                                                                                                                            </h6>
                                                                                                                                                        </div>

                                                                                                                                                        <div class="alarnd--limit-message">
                                                                                                                                                            <h6><?php esc_html_e("Can't order more than 999", "hello-elementor"); ?></h6>
                                                                                                                                                        </div>

                                                                                                                                                        <div class="alarnd--price-show-wrap">
                                                                                                                                                            <div class="alarnd--single-cart-row alarnd--single-cart-price">

                                                                                                                                                                <button type="button"
                                                                                                                                                                    class="alarnd_view_pricing button alt"><?php esc_html_e('התמחור שלנו', 'hello-elementor'); ?></button>
                                                                                                                                                                <div class="alarnd--price-by-shirt">
                                                                                                                                                                    <p class="alarnd--group-price">
                                                                                                                                                                        <?php echo wc_price($product->get_regular_price(), array('decimals' => 0)); ?> /
                                                                                                                                                                        <?php echo $first_line_keyword; ?>
                                                                                                                                                                    </p>
                                                                                                                                                                    <p><?php echo esc_html($second_line_keyword); ?>: <span
                                                                                                                                                                            class="alarnd__total_qty"><?php esc_html_e('0', 'hello-elementor'); ?></span></p>
                                                                                                                                                                    <span class="alarnd--total-price">סה"כ:
                                                                                                                                                                        <?php echo wc_price($product->get_regular_price(), array('decimals' => 0)); ?></span>
                                                                                                                                                                </div>
                                                                                                                                                                <button type="submit" name="add-to-cart" id="alarnd__group_submit"
                                                                                                                                                                    value="<?php echo esc_attr($product->get_id()); ?>"
                                                                                                                                                                    class="single_add_to_cart_button button alt ml_add_loading ml_add_to_cart_trigger"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                                                                                                                                                            </div>
                                                                                                                                                            <input type="hidden" name="alarnd__group_id" value="<?php echo $uniqid; ?>">
                                                                                                                                                        </div>
                                                                                                                                                    </form>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                        <?php endif;
}
add_action('woocommerce_after_add_to_cart_form', 'alarnd_form_after', 10);





add_filter('woocommerce_add_cart_item_data', 'set_custom_cart_item_key', 10, 4);
function set_custom_cart_item_key($cart_item_data, $product_id, $variation_id, $quantity)
{
    $cart_item_data['unique_key'] = md5(microtime() . rand());
    WC()->session->set('custom_data', $cart_item_data['unique_key']);

    return $cart_item_data;
}

function alarnd_get_paged($url)
{
    $requestUri = $url;
    $requestUri = rtrim($requestUri, "/");

    preg_match("/[^\/]+$/", $requestUri, $matches);
    $last_word = $matches[0];

    return (int) $last_word;
}




add_filter('woocommerce_add_to_cart_fragments', 'alarnd_woo_cart_but_count');
/**
 * Add AJAX Shortcode when cart contents update
 */
function alarnd_woo_cart_but_count($fragments)
{

    ob_start();

    $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
    $cart_url = wc_get_cart_url();
    ?>
                                                                        <div class="alarnd__cart_menu_item"><span class="alarnd__cart_icon"
                                                                                data-counter="<?php echo esc_attr($cart_count); ?>"></span></div>
                                                                        <?php

                                                                        $fragments['div.alarnd__cart_menu_item'] = ob_get_clean();

                                                                        return $fragments;
}

/** Remove product data tabs */

// add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

/** Email Field at First */
add_filter('woocommerce_checkout_fields', 'allaround_email_first');

function allaround_email_first($checkout_fields)
{
    $checkout_fields['billing']['billing_email']['priority'] = 4;
    return $checkout_fields;
}

function alarnd_wc_remove_checkout_fields($fields)
{
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_address_2']);
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'alarnd_wc_remove_checkout_fields');



function divi_engine_remove_required_fields_checkout($fields)
{
    $fields['billing_country']['required'] = false;
    $fields['billing_postcode']['required'] = false;
    $fields['shipping_country']['required'] = false;
    $fields['shipping_state']['required'] = false;
    return $fields;
}
add_filter('woocommerce_billing_fields', 'divi_engine_remove_required_fields_checkout');


remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);

// Decouples Payment Box from Order Review
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);



/**
 * Change number of related products output
 */
function woo_related_products_limit()
{
    global $product;

    $args['posts_per_page'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'alarnd_related_products_args', 20);
function alarnd_related_products_args($args)
{
    $args['posts_per_page'] = 3; // 4 related products
    $args['columns'] = 3; // arranged in 2 columns
    return $args;
}


if (function_exists('acf_add_options_page')) {

    acf_add_options_page(
        array(
            'page_title' => 'Site Options',
            'menu_title' => 'Site Options',
            'menu_slug' => 'allaround-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        )
    );

}

function alarn_get_all_page_list($field)
{

    // reset choices
    $field['choices'] = array();

    $clubs = get_posts(
        array(
            'posts_per_page' => -1,
            'post_type' => 'page',
            'post_status' => 'publish',
        )
    );
    $field['choices'][] = '-- not set --';
    if ($clubs) {
        foreach ($clubs as $club) {
            $field['choices'][$club->ID] = $club->post_title;
        }
    }

    // return the field
    return $field;

}
add_filter('acf/load_field/name=select_blog_page', 'alarn_get_all_page_list');


function alarnd_change_variatoin_threshold($count, $product)
{
    return 1;
}
add_filter('woocommerce_ajax_variation_threshold', 'alarnd_change_variatoin_threshold', 10, 2);

add_filter('formatted_woocommerce_price', 'alarnd_wc_price_wrap', 10, 5);
function alarnd_wc_price_wrap($number_format, $price, $decimals, $decimal_separator, $thousand_separator)
{
    return '<span class="alarnd__wc-price">' . $number_format . '</span>';
}

function alarnd_wc_get_price_decimals($decimals)
{
    if (is_cart() || is_checkout() || is_admin()) {
        $decimals = 2;
    }
    return $decimals;
}
add_filter('wc_get_price_decimals', 'alarnd_wc_get_price_decimals');

// To change add to cart text on single product page
add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text');
function woocommerce_custom_single_add_to_cart_text()
{
    return __("הוסף להזמנה", "hello-elementor");
}

// To change add to cart text on product archives(Collection) page
add_filter('woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text');
function woocommerce_custom_product_add_to_cart_text()
{
    return __("הוסף להזמנה", "hello-elementor");
}

function wpse64458_force_recalculate_wc_totals()
{
    // Calculate totals
    WC()->cart->calculate_totals();
    // Save cart to session
    WC()->cart->set_session();
    // Maybe set cart cookies
    WC()->cart->maybe_set_cart_cookies();
}
add_action('woocommerce_before_mini_cart_contents', 'wpse64458_force_recalculate_wc_totals');

function is_decimal($val)
{
    return is_numeric($val) && floor($val) != $val;
}

function alarnd_filter_cart_item_price($price_html, $cart_item, $cart_item_key)
{

    $product_price = $cart_item['data']->get_price();

    if (is_decimal($product_price)) {
        return wc_price($product_price, array('decimals' => 1));
    }

    return $price_html;
}

add_filter('woocommerce_cart_item_price', 'alarnd_filter_cart_item_price', 10, 3);

// Mini cart: Display custom price 

function chagol_cart_item($span, $cart_item, $cart_item_key)
{

    $_product = wc_get_product($cart_item['product_id']);
    $product_price = wc_price($cart_item['data']->get_price());

    if ('variable' == $_product->get_type()) {
        $span = '<span class="quantity">' . sprintf('%s', $product_price) . '</span>';
    }

    return $span;
}
add_filter('woocommerce_widget_cart_item_quantity', 'chagol_cart_item', 10, 3);

/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function allaround_hide_shipping_when_free_is_available($rates)
{

    // Here your free shipping rate Id
    $free_shipping_rate_id = 'free_shipping:2';

    // When your Free shipping method is available
    if (array_key_exists($free_shipping_rate_id, $rates)) {
        // Loop through shipping methods rates
        foreach ($rates as $rate_key => $rate) {
            // Removing "Flat rate" shipping method
            if ('flat_rate' === $rate->method_id) {
                unset($rates[$rate_key]);
            }
        }
    }
    return $rates;

}
add_filter('woocommerce_package_rates', 'allaround_hide_shipping_when_free_is_available', 100);


/**
 * Wp Breadcrumb Function
 *
 */
function get_breadcrumb()
{
    echo '<a href="' . home_url() . '" rel="nofollow">Home</a>';
    if (is_category() || is_single()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_category(' &bull; ');
        if (is_single()) {
            echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
            the_title();
        }
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}


/**
 * Wp Projects Breadcrumb Function
 *
 */
function wp_custom_post_breadcrumbs()
{
    global $post;

    if (!is_singular('project')) {
        return;
    }

    $post_type = get_post_type_object(get_post_type());

    $separator = '&nbsp;&nbsp;&#187;&nbsp;&nbsp;';
    $breadcrumbs = '<a href="' . home_url() . '">' . __('Home', 'hello-elementor') . '</a> ' . $separator . ' ';
    $breadcrumbs .= '<a href="' . get_post_type_archive_link('project') . '">' . $post_type->labels->name . '</a> ' . $separator . ' ';

    if (is_single()) {
        $breadcrumbs .= get_the_title();
    } elseif (is_tax()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $breadcrumbs .= $term->name;
    }

    echo $breadcrumbs;
}


/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function allaround_empty_cart_redirect_url()
{
    return esc_url(home_url('/catalog/'));
}
add_filter('woocommerce_return_to_shop_redirect', 'allaround_empty_cart_redirect_url');


// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 11 );


/**
 * Change Shop page product title tag.
 *
 */
function woocommerce_template_loop_product_title()
{
    if (is_product()) {
        echo '<span class="woocommerce-loop-product__title">' . get_the_title() . '</span>';
    } elseif (is_shop()) {
        echo '<span class="woocommerce-loop-product__title">' . get_the_title() . '</span>';
    }
    // do you need an 'else' case here too?
}



/**
 * Replace Shop template Thumbnail to ACF meta.
 *
 */
function replacing_template_loop_product_thumbnail()
{

    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

    function wc_template_loop_product_replaced_thumb()
    {
        $product_icon = get_field('product_icon');
        $thumb_url = !empty($product_icon) ? wp_get_attachment_image_url($product_icon, 'full') : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
        echo "<img src='$thumb_url'>";
    }
    add_action('woocommerce_after_shop_loop_item_title', 'wc_template_loop_product_replaced_thumb', 10);
}
add_action('woocommerce_init', 'replacing_template_loop_product_thumbnail');


/**
 * Replace Shop template ptoduct title with ACF meta.
 *
 */
function woocommerce_template_loop_product_title_new()
{
    $product_name = get_field('product_short_name');

    $title = !empty($product_name) ? $product_name : get_the_title();
    echo '<span class="' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">' . $title . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function replacing_template_loop_product_title()
{
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
    add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title_new', 10);
}
add_action('woocommerce_init', 'replacing_template_loop_product_title');


// Remove product images from the shop loop
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
// Remove product add to cart from the shop loop
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
// Remove catalog dropdown from the shop page
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
// Remove product price from the shop loop
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);


/**
 * Add Category slug as li class in shop page.
 *
 */
add_filter('product_cat_class', 'add_class_to_category_list_element', 10, 3);
function add_class_to_category_list_element($classes, $class, $category)
{
    if (is_object($category))
        $classes[] = $category->slug;

    return $classes;
}

/**
 * Get products only from particular category slug.
 *
 */
function ml_wc_pre_get_posts_query($q)
{

    $tax_query = (array) $q->get('tax_query');

    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array('all')
    );

    $q->set('tax_query', $tax_query);
}
add_action('woocommerce_product_query', 'ml_wc_pre_get_posts_query');



/**
 * Remove All Category from shop page.
 *
 */
add_filter('get_terms', 'ts_get_subcategory_terms', 10, 3);
function ts_get_subcategory_terms($terms, $taxonomies, $args)
{
    $new_terms = array();
    // if it is a product category and on the shop page
    if (in_array('product_cat', $taxonomies) && !is_admin() && is_shop()) {
        foreach ($terms as $key => $term) {
            if (!in_array($term->slug, array('all'))) { //pass the slug name here
                $new_terms[] = $term;
            }
        }
        $terms = $new_terms;
    }
    return $terms;
}


/*
Code Purpose : Remove woocommerce product-category slug
*/
add_filter('request', function ($vars) {
    global $wpdb;
    if (!empty($vars['pagename']) || !empty($vars['category_name']) || !empty($vars['name']) || !empty($vars['attachment'])) {
        $slug = !empty($vars['pagename']) ? $vars['pagename'] : (!empty($vars['name']) ? $vars['name'] : (!empty($vars['category_name']) ? $vars['category_name'] : $vars['attachment']));
        $exists = $wpdb->get_var($wpdb->prepare("SELECT t.term_id FROM $wpdb->terms t LEFT JOIN $wpdb->term_taxonomy tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'product_cat' AND t.slug = %s", array($slug)));
        if ($exists) {
            $old_vars = $vars;
            $vars = array('product_cat' => $slug);
            if (!empty($old_vars['paged']) || !empty($old_vars['page']))
                $vars['paged'] = !empty($old_vars['paged']) ? $old_vars['paged'] : $old_vars['page'];
            if (!empty($old_vars['orderby']))
                $vars['orderby'] = $old_vars['orderby'];
            if (!empty($old_vars['order']))
                $vars['order'] = $old_vars['order'];
        }
    }
    return $vars;
});


add_filter('term_link', 'ml_change_term_permalink', 10, 3);

function ml_change_term_permalink($url, $term, $taxonomy)
{

    $taxonomy_name = 'product_cat';
    $taxonomy_slug = 'product-category';

    // exit the function if taxonomy slug is not in URL
    if (strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name)
        return $url;

    $url = str_replace("/./", "/", $url);

    return $url;
}

add_action('template_redirect', 'ml_old_term_redirect');

function ml_old_term_redirect()
{

    $taxonomy_name = 'product_cat'; // your taxonomy name here
    $taxonomy_slug = 'product-category/'; // your taxonomy slug here

    // exit the redirect function if taxonomy slug is not in URL
    if (strpos($_SERVER['REQUEST_URI'], $taxonomy_slug) === FALSE)
        return;

    if (is_tax($taxonomy_name)):

        wp_redirect(site_url(str_replace($taxonomy_slug, '', $_SERVER['REQUEST_URI'])), 301);
        exit();

    endif;

}


/**
 * CF7: Numbers Only Validation
 * Validate the input fields with CSS class #phone to allow numbers only.
 */
add_action('wp_footer', 'wpcf7_input_numbers_only');
function wpcf7_input_numbers_only()
{
    echo '
  <script>
  onload =function(){ 
    var ele = document.querySelectorAll(\'#phone\')[0];
    ele.onkeypress = function(e) {
      if(isNaN(this.value+""+String.fromCharCode(e.charCode)))
      return false;
    }
    ele.onpaste = function(e){
      e.preventDefault();
    }
  }
  </script>
  ';
}


function ml_filter_string_to_array($string)
{
    if (empty($string))
        return [];

    // Explode the value so each line is a new element in the array
    $arr = explode("\n", $string);

    // Remove unwanted white space
    $arr = array_map('trim', $arr);

    return $arr;
}


function ml_is_omit($needle, $array)
{
    foreach ((array) $array as $arr) {
        if (isset($arr['value']) && $arr['value'] == $needle) {
            return true;
        }
    }
    return false;
}


function compareQuantities($a, $b)
{
    return $a['quantity'] - $b['quantity'];
}

function ml_filter_disount_steps($discount_steps)
{
    if (empty($discount_steps) || !array($discount_steps))
        return $discount_steps;

    usort($discount_steps, 'compareQuantities');
    return $discount_steps;
}


function ml_get_total_qty($arr)
{
    if (empty($arr)) {
        return 0;
    }

    $get_total_qtys = 0;

    foreach ($arr as $key => $item) {
        foreach ((array) $item as $i) {
            if (empty($i))
                continue;

            $get_total_qtys += $i;
        }
    }

    return $get_total_qtys;
}

/**
 * Filter omit size select choices
 *
 * @param array $field
 * @return array
 */
function acf_load_omit_sizes_choices_cb($field)
{

    // reset choices
    $field['choices'] = array();

    // Get the Text Area values from the options page without any formatting
    $adult_sizes = get_field('adult_sizes', 'option', false);

    // Remove unwanted white space
    $adult_sizes = ml_filter_string_to_array($adult_sizes);


    $child_sizes = get_field('child_sizes', 'option', false);

    // Remove unwanted white space
    $child_sizes = ml_filter_string_to_array($child_sizes);

    $choices = array_merge($adult_sizes, $child_sizes);

    // error_log( print_r( $choices, true ) );

    // Loop through the array and add to field 'choices'
    if (is_array($choices) && !empty($choices)) {

        foreach ($choices as $choice) {

            $field['choices'][$choice] = $choice;

        }

    }

    // return the field
    return $field;

}
add_filter('acf/load_field/name=omit_sizes', 'acf_load_omit_sizes_choices_cb');


/**
 * Filter Full Chart omit size select choices
 *
 * @param array $field
 * @return array
 */
function acf_load_omit_chart_sizes_choices_cb($field)
{
    // reset choices
    $field['choices'] = array();
    // Get the Text Area values from the options page without any formatting
    $adult_sizes = get_field('adult_sizes', 'option', false);
    // Remove unwanted white space
    $adult_sizes = ml_filter_string_to_array($adult_sizes);
    $child_sizes = get_field('child_sizes', 'option', false);
    // Remove unwanted white space
    $child_sizes = ml_filter_string_to_array($child_sizes);
    $choices = array_merge($adult_sizes, $child_sizes);

    // Loop through the array and add to field 'choices'
    if (is_array($choices) && !empty($choices)) {
        foreach ($choices as $choice) {
            $field['choices'][$choice] = $choice;
        }
    }
    // return the field
    return $field;
}
add_filter('acf/load_field/name=omit_sizes_from_chart', 'acf_load_omit_chart_sizes_choices_cb');




remove_action('template_redirect', 'wc_disable_author_archives_for_customers');


/**
 * Send Customer Leeds form Submission
 *
 * @return void
 */
function customer_leads_form_shortcode()
{
    ob_start(); ?>

                                                                        <form id="customerLeadsForm">
                                                                            <div class="alarnd--footer-form uses-form">
                                                                                <div class="alarnd--form-row">
                                                                                    <div class="alarnd--single-form-item allarnd--name-field">
                                                                                        <span class="alarnd__label">שם מלא</span>
                                                                                        <input type="text" id="fullName" placeholder="הקלד שם מלא" name="fullName" required>
                                                                                    </div>
                                                                                    <div class="alarnd--single-form-item allarnd--tel-field">
                                                                                        <span class="alarnd__label">טלפון</span>
                                                                                        <input type="tel" id="phone" placeholder="570-00-0000" name="phone" required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="alarnd--form-row">
                                                                                    <div class="alarnd--single-form-item clear">
                                                                                        <span class="alarnd__label">אימייל</span>
                                                                                        <input type="email" id="emailId" placeholder="name@co.li" name="emailId" required>
                                                                                    </div>
                                                                                    <div class="alarnd--single-form-item allarnd--name-field">
                                                                                        <span class="alarnd__label">אתר אינטרנט</span>
                                                                                        <input type="text" id="website" name="website" placeholder="www.site.com">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="alarnd--form-row">
                                                                                    <div class="alarnd--single-form-item allarnd--name-field fileUpload-trick">
                                                                                        <span class="alarnd__label">סֵמֶל</span>
                                                                                        <input type="file" id="fileuploadfield" name="fileuploadfield">
                                                                                        <div class="upload-button-kit">
                                                                                            <input type="button" id="uploadbrowsebutton" value="...ץבוק רחב">
                                                                                            <input type="text" id="uploadtextfield" name="uploadtextfield">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="alarnd--form-submit">
                                                                                    <button type="submit" class="alarnd--regular-button ml_add_loading button">שלח</button>
                                                                                </div>
                                                                        </form>

                                                                        <div id="formResponse"></div>
                                                                    </div>

                                                                    <?php
                                                                    return ob_get_clean();
}
add_shortcode('customer_leads_form', 'customer_leads_form_shortcode');


/**
 * Artwork file upload
 */
function alarnd_artwork_upload()
{

    $custom_upload_dir = WP_CONTENT_DIR . '/uploads/landing-logo';

    $fileErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_files in server settings',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE from html form',
        3 => 'The uploaded file uploaded only partially',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stoped file to upload',
    );

    $file_data = isset($_FILES) ? $_FILES : array();
    $name = $file_data['logoFile']['name'];
    $filetype = wp_check_filetype($name);

    $allowed_file_types = array(
        'jpg',
        'jpeg',
        'jpe',
        'gif',
        'png',
        'bmp',
        'webp',
        'ai',
        'psd'
    );

    $response = array();

    if (!in_array($filetype['ext'], $allowed_file_types)) {
        $response['response'] = 'ERROR';
        $response['error'] = 'Wrong file format! please upload image type file only.';
        return $response;
    }

    // Create the custom directory if it doesn't exist
    if (!file_exists($custom_upload_dir)) {
        wp_mkdir_p($custom_upload_dir);
    }

    // Handle file name conflicts
    $i = 1;
    $base_name = pathinfo($image['name'], PATHINFO_FILENAME);
    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    while (file_exists($custom_upload_dir . '/' . $image['name'])) {
        $image['name'] = $base_name . '_' . $i . '.' . $extension;
        $i++;
    }

    // Set the custom upload directory using the 'upload_dir' filter
    add_filter('upload_dir', function ($upload) use ($custom_upload_dir) {
        $upload['path'] = $custom_upload_dir;
        $upload['url'] = home_url('/wp-content/uploads/custom-uploads');
        return $upload;
    });

    // Set the 'intermediate_image_sizes_advanced' filter to an empty array to prevent additional sizes
    add_filter('intermediate_image_sizes_advanced', function ($sizes) {
        return array();
    });

    $attachment_id = media_handle_upload('logoFile', 0);

    // Remove the 'upload_dir' filter after uploading
    remove_filter('upload_dir', function ($upload) use ($custom_upload_dir) {
        $upload['path'] = WP_CONTENT_DIR . '/uploads';
        $upload['url'] = content_url('/uploads');
        return $upload;
    });

    // Remove the 'intermediate_image_sizes_advanced' filter after uploading
    remove_filter('intermediate_image_sizes_advanced', '__return_empty_array');

    if (is_wp_error($attachment_id)) {
        $response['response'] = 'ERROR';
        $response['error'] = $fileErrors[$file_data['logoFile']['error']];
    } else {
        $url = get_attached_file($attachment_id);
        $pathinfo = pathinfo($url);
        $response['artwork_name'] = $name;
        $response['attachment_id'] = $attachment_id;
    }

    return $response;
}

/**
 * Send Data to Make API WebHook
 *
 * @return void
 */
add_action('wp_ajax_ml_leads_entry', 'ml_leads_entry', 10);
add_action('wp_ajax_nopriv_ml_leads_entry', 'ml_leads_entry', 10);
function ml_leads_entry()
{
    check_ajax_referer('leads_ajax_nonce', 'nonce');

    $fullName = isset($_POST['fullName']) && !empty($_POST['fullName']) ? sanitize_text_field($_POST['fullName']) : '';
    $emailId = isset($_POST['emailId']) && !empty($_POST['emailId']) ? sanitize_text_field($_POST['emailId']) : '';
    $phone = isset($_POST['phone']) && !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $website = isset($_POST['website']) && !empty($_POST['website']) ? sanitize_text_field($_POST['website']) : '';

    if (
        empty($fullName) ||
        empty($emailId) ||
        empty($phone)
    ) {
        wp_send_json_error(
            array(
                "message" => esc_html__("Required field are empty. Please fill all the field.", "hello-elementor")
            )
        );
        wp_die();
    }

    $attachment_url = '';
    $attachment_id = '';

    if (isset($_FILES) && !empty($_FILES)) {
        $file_data = alarnd_artwork_upload();
        // error_log( print_r( $file_data, true ) );
        if (isset($file_data['attachment_id'])) {
            $attachment_id = (int) $file_data['attachment_id'];
            $get_attachment_url = wp_get_attachment_url($attachment_id);
            if ($get_attachment_url && !empty($get_attachment_url)) {
                $attachment_url = $get_attachment_url;
            }
        }
    }

    $result = array(
        "attachment_url" => $attachment_url,
        "attachment_id" => $attachment_id
    );

    $body = array(
        'fullName' => $fullName,
        'emailId' => $emailId,
        'phone' => $phone,
        'website' => $website,
        'logoFile' => $attachment_url
    );

    $args = array(
        'method' => 'POST',
        'timeout' => 15,
        'sslverify' => false,
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($body),
    );

    // error_log( print_r( $args, true ) );

    $api_url = 'https://hook.eu1.make.com/rwaijpqjh5d5ymji2uguihuptrj8q4oq';

    $request = wp_remote_post(esc_url($api_url), $args);

    // retrieve reponse body
    $message = wp_remote_retrieve_body($request);

    // decode response into array
    // $response_obj = ml_response($message);

    $result['message'] = $message;

    if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) == 200) {
        wp_send_json_success($result);
    }

    wp_send_json_error($result);
    wp_die();
}