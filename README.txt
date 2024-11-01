=== Plugin Name ===
License Keys for WooCommerce
Contributors: 10quality
Donate link: https://www.10quality.com/donations/
Tags: woocommerce, license keys, software license manager
Requires at least: 3.2
Requires PHP: 5.4
Tested up to: 5.5
Stable tag: 1.5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Enable and handle "License Keys" with WooCommerce. Software license keys manager for Wordpress.

== Description ==

**License Keys** for **WooCommerce** is a simple and powerfull license keys manager, that adds a new product type to your Wordpress and WooCommerce setup, and lets you create and handle license keys that can be used to validate your licensed products.

== Built-in API ==

This plugin will enable your Wordpress to act as a validator API (service) with endpoints that will let you activate, validate and deactivate license keys via HTTP requests; we provide various solutions (as PHP libraries, packages and documentation) to integrate the API with your products.

== License Keys ==

**License Keys** will be generated automatically when a WooCommerce order is completed, the code is appended in the "Order completed" email notification sent by WooCommerce. Each License Key data is generated per product configuration. The License Key product type will add a new options tab that will allow you to:

* **Expire options**: Set the expiration of a license key.
* **Virtual**: Check the virtual checkbox to set the product as physical or virtual.
* **Downloadable**: Check the downloadable checkbox to append downloadable files to the product.

You can customize this plugin and add more options by your own, or purchase our extension that will allow you to:

* **Limit options**: Set the limitation of a license key (per number of activations, per domain), and unlimit on development environments.
* **Offline options**: Set offline options to allow a product to run when not connected to the internet.
* **Downloadable data**: Set to append the downloadable data on API responses, to allow automatic updates.

== Customer account pages ==

**License Keys** for **WooCommerce** will add management pages to WooCommerce "My Account" section to allow license keys to be self-managed by users; these pages will allow them to view purchased licenses and license key activations.

Aside from the API endpoint, customers will be able to deactivate their licenses at these pages.

== Admin management ==

License keys can be searched by admins at the "Orders" section of WooCommerce, details (such as the customer's license key code, expiration date and the number of activations) can be reviewed when viewing and editing an order.

== Product integration ==

We offer an SDK, software libraries and documentation to implement easily the API's integration in your products.

== Customization ==

We have built this plugin with customization in mind. We have placed WordPress hooks all over the source code, making it flexible to be customized or extended. All HTML templates can also be modified in themes.

== Paid extension ==

If you don't wish to customize this your self, we offer the option to add an extension that will expand its capabilities with the following features:

* Additional product options for the License Key product type.
* Product variable and price variations support.
* License keys admin management module, these management pages will allow admins to edit generated license keys options, deactivate activations and view more details.
* Built-in semi-automatic subscription system supported by any WooCommerce payment gateway.
* Automatic subscriptions (Recurring payments) through compatibility with WooCommerce Subscriptions and Subscriptio.
* Analytics module and reporting.
* Import custom license key codes.
* Add, edit and delete custom license key codes.

You can opt to obtain these features [here](https://www.10quality.com/product/woocommerce-license-keys/).

== Installation ==

1. Head to your Wordpress Admin Dashboard and click at the options **Plugins** then **Add new**.
2. Search for this plugin usign the search input or if have downloaded the zip file, upload it manually.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Integrate the validator API at "WooCommerce->Settings->API->License Keys API".

== Screenshots ==

1. License Key tab and options when adding or editing a product. Note: Some features displayed are from the extension we offer.
2. Customer's "License Keys" option at "My Account" page. *Note:* "Renew" and "Extend" buttons are only available with our [extended features](https://www.10quality.com/product/woocommerce-license-keys/).
3. Customer's "License Key" page view at "My Account" page, with no activations.
4. Customer's "License Key" page view at "My Account" page, with activations.
5. License key list per order's item at Wordpress dashboard.
6. License key product, with expiry, added in shopping cart.

== Changelog ==

= 1.5.6 =
*Release Date - 4 Oct 2020*

* Added new hooks to filter error API responses.
* Ability to use API functions (inside setup) with a string parameter.
* Framework files added.

= 1.5.5 =
*Release Date - 23 Sep 2020*

* Added new hook to whitelist license key "find" arguments during API request.
* Framework files added.

= 1.5.4 =
*Release Date - 8 Sep 2020*

* ApiValidator refactoring for better customization options.

= 1.5.3 =
*Release Date - 3 Sep 2020*

* Fixes reported PHP notice related to WP Rest on WP 5.5.
* Refactores and cleans code.
* Framework files updated.
* Added hooks.

= 1.5.2 =
*Release Date - 23 Aug 2020*

* Fixes JS bug related to expiration event binding.

= 1.5.1 =
*Release Date - 19 Aug 2020*

* Activation, validation and deactivation are now available within WP through global functions.
* Tested with WooCommerce 4.4.
* Minor refactoring.
* Added hooks.

= 1.5.0 =
*Release Date - 17 Aug 2020*

* ApiValidator customizable class.
* New settings that will allow to add extra data to API's response.
* Better integration with WooCommerce "My Account".
* Added hooks.

= 1.4.1 =
*Release Date - 16 Aug 2020*

* Framework files updated.
* Tested with WooCommerce 4.3.
* Updated JS dependencies.
* Added assets.

= 1.4.0 =
*Release Date - 03 Apr 2020*

* Framework files updated.
* Removed double sanitization as `WPMVC\Request` now sanitizes the request.
* Additional customization hooks.
* Use of `QueryBuilder` class.
* Tested with PHP 7.4.
* Tested with WooCommerce 4.0.

= 1.3.8 =
*Release Date - 9 Feb 2020*

* Framework files updated.
* Tested with WooCommerce 3.9.
* Bug fixing.

= 1.3.7 =
*Release Date - 31 Dec 2019*

* Added filter `woocommerce_license_keys_generate_for_order`.
* Updated bold markup typo on readme text.

= 1.3.6 =
*Release Date - 20 Dec 2019*

* Fixes deactivation endpoint.

= 1.3.5 =
*Release Date - 19 Dec 2019*

* Added missing `woocommerce_license_key` filter in collections.
* Settings updated to reduce documentation section in order to access save button faster.
* Ability to disable domain validation.
* Translations updated.
* Refactored code comments.

= 1.3.4 =
*Release Date - 13 Dec 2019*

* Framework and addon updated.
* Plugin readme updated.

= 1.3.3 =
*Release Date - 11 Dec 2019*

* Reviewer WPMVC add-on configured.

= 1.3.2 =
*Release Date - 10 Dec 2019*

* Fixes bug that was preventing product's "General" tab to display on Simple and External products.

= 1.3.1 =
*Release Date - 5 Dec 2019*

* Framework files updated.
* API validations section in settings. Ability to disable SKU validation.
* Translations updated.

= 1.3.0 =
*Release Date - 30 Nov 2019*

* Multiple API handler support (WP-Ajax by default and WP Rest API supported).
* Updated Spanish translations.
* Refactored call validations to support better customization.
* Tested with Wordpress 5.3 and WooCommerce 3.8.1.
* New customization hooks added.
* Code comments refactored.

= 1.2.13.1 =
*Release Date - 5 Sep 2019*

* Plugin name changed, requested by Wordpress.org.

= 1.2.13 =
*Release Date - 4 Sep 2019*

* Sanitize security WP recomendations applied on admin backend forms.

= 1.2.12 =
*Release Date - 8 Jul 2019*

* Added hook to support license key generation loop to be stopped.
* Framework update.

= 1.2.11 =
*Release Date - 21 May 2019*

* Added hooks to support additional customizations and variable products.
* Bug fixes.
* Framework update.

= 1.2.10 =
*Release Date - 30 April 2019*

* Fixes bug.
* Adds global `wc_get_license_key` function.

= 1.2.9 =
*Release Date - 30 April 2019*

* Added extra hooks to extend customization.
* Patched so products without `get_type` method will not breack admin page.
* Other small fixes with `show_if` woo admin js behavior.
* Tested with latest WooCommerce.

= 1.2.8 =
*Release Date - 16 March 2019*

* Framework upgrade.

= 1.2.6 =
*Release Date - 4 March 2019*

* Framework upgrade.
* Added option to allow license key to be sold individually.

= 1.2.5 =
*Release Date - 17 February 2019*

* Additional hooks added.
* Fixes bug on spanish translations.

= 1.2.4 =
*Release Date - 13 February 2019*

* Fixes singular/plural expiration message on cart and checkout pages.
* Updates desktop software related strings and translations.

= 1.2.3 =
*Release Date - 11 February 2019*

* Enqueues dashicons on my account page.
* Updates localization strings.
* Updates spanish translations.

= 1.2.2 =
*Release Date - 16 January 2019*

* Small Bug fixes.

= 1.2.1 =
*Release Date - 6 December 2018*

* Added errors output format options for API responses.
* Added API error codes.
* Fixes copy to clipboard on Firefox and other specific browsers.
* Screenshots and readme updated.
* Bug fixes.

= 1.2.0 =
*Release Date - 29 Nov 2018*

* Added CORS control options in WooCommerce settings tab.
* "Copy" to clipboard option added for customers to copy license key displayed on "My Account" page. 

= 1.1.6 =
*Release Date - 28 Nov 2018*

* WPMVC log/cache path changes.
* Tutorial link added to documentation.

= 1.1.5 =
*Release Date - 1 Nov 2018*

* Fixes license key url with different permalink settings.
* WPMVC changes: Cache folder and disabled unused auto-enqueue setting.
* Code comments refactoring.

= 1.1.4 =
*Release Date - 28 Oct 2018*

* Ability to search for customer license keys in orders using the "search" tool found in WooCommerce orders admin dashboard.

= 1.1.3 =
*Release Date - 28 Oct 2018*

* Fixes WooCommerce hook change related to order bulk actions.
* More customization hooks on API endpoints.
* Code refactoring.

= 1.1.2 =
*Release Date - 5 Sep 2018*

* Adds better support for desktop products.
* Fixes WooCommerce "Not doing it right" notices.

= 1.1.1 =
*Release Date - 3 Sep 2018*

* Fixes reported WooCommerce "Not doing it right" notices.

= 1.1.0 =
*Release Date - 3 Sep 2018*

* Adds icon to identify license key products on admin's listing. (Effective on new or updated products only)
* Automatically expires generated license keys on orders updated to status cancelled, refunded or failed.
* Fixed bug that was re-generating license keys when an order was set as completed multiple times.
* Fixes bug that was trying to display un-existent license keys on admin order page, when order was not completed.
* Added extra hooks and rules for further customization.
* Displays license key expiry information in cart and checkout pages.

= 1.0.11 =
*Release Date - 11 jun 2018*

* Fixes bugs.
* Framework updated.

= 1.0.10 =
*Release Date - 27 May 2018*

* Fixes compatibility issue (API settings link) with WooCommerce 3.4.0.

= 1.0.9 =
*Release Date - 22 May 2018*

* Fixes fatal error generated when WooCommerce is not activated.
* Added localization configuration.

= 1.0.8 =
*Release Date - 9 May 2018*

* Fixes order licenses bug realted to order status.

= 1.0.7 =
*Release Date - 31 March 2018*

* Framework update.

= 1.0.6 =
*Release Date - 19 March 2018*

* Framework update.

= 1.0.5 =
*Release Date - 2 March 2018*

* Readme (extended capabilities) updated.
* Framework update.

= 1.0.4 =
*Release Date - 28 Feb 2018*

* Additional customization hooks.

= 1.0.3 =
*Release Date - 28 Feb 2018*

* Fixes License keys on order received page.

= 1.0.2 =
*Release Date - 28 Feb 2018*

* License keys added to order received.
* License keys missing title added to order completed email.
* Warnings and bugs fixed.
* Added extra error logging.

= 1.0.1 =
*Release Date - 28 Feb 2018*

* Base name updated based on given wordpress.org name.

== Frequently Asked Questions ==

= Setup? =

Install and activate the plugin. Follow the settings documentation to integrate the API with your products, these are located at "WooCommerce->Settings->API->License Keys API".

= How to validate my license keys =

The plugin comes with a built-in validator API that does this job for you. Follow the settings documentation to integrate the API with your products, these are located at "WooCommerce->Settings->Advanced->License Keys API".

= Documentation? =

For full documentation [click here](https://www.10quality.com/docs/woocommerce-license-keys/).

Or follow the quick documentation in the plugin, located at "WooCommerce->Settings->Advanced->License Keys API".

= My Account > License Keys option not displaying? =

Refresh and flush permalinks. To do this, go to "Settings->Permalinks" at your Wordpress setup and click the "Save Settings" button.

= How to modify a template? =

Customizable templates are located at folder */templates* inside the plugin. Follow WooCommerce guidelines to modify them. Aside from the templates provided by this plugin, use WooCommerce template *single-product/add-to-cart/simple.php* to customize the "Add to cart" button.

= Enable extended features =

Enable extended features [here](https://www.10quality.com/product/woocommerce-license-keys/).

= Which WordPress versions are supported? =

At the time this document was created, we only tested on Wordpress 4.9.4, we believe, based on the software requirements, that any Wordpress version above 3.2 will work.

= Which WooCommerce versions are supported? =

At the time this document was created, we only tested on WooCommerce 3.3.3, any version above 3.3.3 will work.

= Which PHP versions are supported? =

Any greater or equal to **5.4** is supported.

== Who do I thank for all of this? ==

* [10Quality](https://www.10quality.com/)
* [Ale Mostajo](http://about.me/amostajo)
* [Wordpress MVC](https://www.wordpress-mvc.com/)