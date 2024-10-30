=== UTM tags + Landing page + "gclid" tracking for Contact Form 7 ===
Contributors: kaminskym
Tags: cf7, contact-form-7, utm, utm-tracking
Requires at least: 4.6
Tested up to: 5.2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will save UTM tags + Referrer (Landing page) (like google.com, etc) + "gclid" to the cookies on first user visit and on the CF7 submit adds all info to mail body.

Setup: place [utm_and_referer] tag in the Admin email body

== Description ==

This plugin will save UTM tags + Referrer (like google.com, etc) + landing (first page) to cookies on first user visit and on CF7 submit adds all info to mail body.

Please note that for security/privacy reasons, the Referer URL is stripped out when navigating from a HTTPS site to a HTTP site by the browser and in some other cases.

Setup: place [utm_and_referer] tag in the Admin email body - [example](https://res.cloudinary.com/dxo61viuo/image/upload/v1559999389/wp-vote.net/CF7_utm_mail_tag.jpg)

A *UTM codes* is a simple query variables that you can attach to a custom URL in order to track a source, medium, and campaign name. This allow you better understand from where visitors are coming and doing some actions (like submitting contact form).

== Screenshots ==

1. Plugin status
2. Mail example

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/cf7-utm-tracking` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the 'Contact' -> 'CF7 UTM tracking' screen to check the plugin state

== Changelog ==

/* VER 1.4 - 17/08/2019 */

- [new] Added landing (first page) tracking

/* VER 1.3 - 07/06/2019 */

- [new] Now to add UTM tracking info for the email need to use [utm_and_referer] tag in the email body

/* VER 1.1 - 26/06/2017 */

- [new] Added "gclid" parameter tracking
- [tweak] Move from "Tools" menu to the "Contact" menu
- [tweak] Security improvements
