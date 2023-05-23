<?php

ob_start();

$value = get_option('uvd-plugin-settings');
$cat_slug = $value['discount_category'];
$button_text = $value['button_text'];
$button_pre_text = $value['button_pretext'];
$button_url = $value['button_url'];
$notice_link_text = $value['notice_link_text'];
$enable_buttons = $value['ui_elements_checkbox'];
$config = $value['config_setting'];

ob_end_clean();

if ($enable_buttons !== 'true' || $button_url == ''){
    return;
}

if ($config == 'cart') {
    // Add custom button to coupon section of checkout page.

    add_action('woocommerce_after_cart_table', 'uvd_plugin_add_button_to_cart_page', 999);

    function uvd_plugin_add_button_to_cart_page()
    {
        global $button_pre_text;
        global $button_text;
        global $button_url;

        echo '<span style="padding-left: 16px; margin-right: .8rem;">' . $button_pre_text . '</span>';
        echo '<a href="' . $button_url . '" target="_blank" class="button wp-element-button" aria-label="' . $button_text . '" rel="nofollow">' . $button_text . '</a>';
    }
} elseif ($config == 'category' && $cat_slug !== '') {
    add_action('woocommerce_before_cart', 'misha_if_product_in_cart');

    function misha_if_product_in_cart()
    {

        global $cat_slug;
        global $button_url;
        global $notice_link_text;

        $cat_in_cart = false;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

            if (has_term($cat_slug, 'product_cat', $cart_item['product_id'])) {
                $cat_in_cart = true;
                break;
            }
        }

        // Do something if the category is in the Cart      
        if ($cat_in_cart) {
            wc_print_notice(
                sprintf('<a href="' . $button_url . '" target="_blank" rel="noopener noreferrer">' . $notice_link_text . '</a>', 333),
                'notice'
            );
        }

    }

}

/*
*
* Code below is not currently being used by plugin, but may be used in the future
*
*/

// if ($config == 'category'){
//     // Determine the category ID for the student discount category to be used in other functions.

// add_action('init', 'uvd_plugin_determine_category_id');

// function uvd_plugin_determine_category_id()
// {
//     global $cat_slug;
//     global $cat_id;

//     $term = get_term_by('slug', $cat_slug, 'product_cat');

//     if ($term) {
//         $cat_id = $term->term_id;
//     }
// }

// Remove the Add to Cart button from product pages if the category matches the discount category.

// add_filter('woocommerce_loop_add_to_cart_link', 'uvd_plugin_remove_add_to_cart_specific_products', 25, 2);

// function uvd_plugin_remove_add_to_cart_specific_products($add_to_cart_html, $product)
// {

//     global $cat_id;
//     $product_cat = $product->get_category_ids();

//     //var_dump($product->get_id());

//     foreach ($product_cat as $key => $value) {
//         if ($value == $cat_id) {
//             return '';
//         }
//     }

//     return $add_to_cart_html;

// }

// Remove the Add to Cart button from single product page if the category matches the discount category.

// add_action('woocommerce_single_product_summary', 'uvd_plugin_remove_wc_loop_add_to_cart_button', 1);
// function uvd_plugin_remove_wc_loop_add_to_cart_button()
// {
//     global $product;
//     global $cat_id;

//     $product_cat = $product->get_category_ids();


//     foreach ($product_cat as $key => $value) {
//         if ($value === $cat_id) {
//             remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
//         }
//     }
// }



// Add custom Add to Cart button to replace the existing button on shop page.

// add_action('woocommerce_after_shop_loop_item', 'uvd_plugin_add_button_to_shop', 999);

// function uvd_plugin_add_button_to_shop()
// {

//     global $product;
//     global $cat_id;

//     global $button_pre_text;
//     global $button_text;
//     global $button_url;

//     $product_cat = $product->get_category_ids();
//     $product_id = $product->get_id();


//     foreach ($product_cat as $key => $value) {
//         if ($cat_id === $value) {
//             echo '<a href="' . $button_url . '?add-to-cart=' . $product_id . '" target="_blank" class="button wp-element-button" aria-label="' . $button_text . '" rel="nofollow">' . $button_text . '</a>';
//         }
//     }

// }

// Add custom Add to Cart button to replace the existing button on single product page.

// add_action('woocommerce_single_product_summary', 'uvd_plugin_replace_button_on_product_page', 30);

// function uvd_plugin_replace_button_on_product_page()
// {

//     global $product;
//     global $cat_id;

//     global $button_pre_text;
//     global $button_text;
//     global $button_url;


//     $product_cat = $product->get_category_ids();
//     $product_id = $product->get_id();


//     foreach ($product_cat as $key => $value) {
//         if ($cat_id === $value) {
//             echo '<p>' . $button_pre_text . '</p>';
//             echo '<a href="' . $button_url . '?add-to-cart=' . $product_id . '" target="_blank" class="button wp-element-button" aria-label="' . $button_text . '" rel="nofollow">' . $button_text . '</a>';
//         }
//     }

// }
// }