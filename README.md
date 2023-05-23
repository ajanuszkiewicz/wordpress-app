# WordPress Plugin for Verification and Discounts

This plugin allows merchants using WordPress and WooCommerce to provide select users with access to discounts. The plugin interfaces with a verification service API to facilitate the configuration of WooCommerce discounts and user verification. 

## How it works

After installing the plugin the merchant is prompted to add a API token and authorize the verification service to make use of their WooCommerce instance. This will provide the merchant with a link to use for verification and ensures that coupon/discount rules can be configured within WooCommerce. 

Merchants will then be able to configure discount rules and provide their users with access to the discount via verification link. The plugin provides the ability to customize how a discount can be applied (e.g. product categories or entire cart), how much of a discount should be provided, and whether the discount is a percentage or dollar value. 

Depending on discount configuration the plugin will display buttons and text that allow users to easily verify their eligibility for the discount. This is done by redirecting users to the verification service using a verification link. After successful verification the user is redirected back to the merchants store with the appropriate discount applied. 

The plugin also allows the merchant to disable text and buttons produced by the plugin if they wish to create their own. 