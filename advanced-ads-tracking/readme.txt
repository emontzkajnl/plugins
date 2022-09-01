=== Advanced Ads – Tracking ===
Requires at least: 4.9
Tested up to: 5.9
Stable tag: 2.3.1
Requires PHP: 5.6.20

Track ad impressions and clicks.

== Distribution ==

The distribution of the software might be limited by copyright and trademark laws.
Copyright and trademark holder: Advanced Ads GmbH.
Please see also https://wpadvancedads.com/terms/.

== Description ==

This add-on for the Advanced Ads plugin provides tracking ad impressions and clicks.

**Tracking:**

* count ad impressions and clicks either locally or in Google Analytics
* enable or disable tracking for all or individual ads
* enable link cloaking to hide the target URL
* open individual links in a new window
* add nofollow or sponsored attribute to links
* works on JavaScript-based ads, iframes and on AMP pages

**Ad Planning**

* limit ad views to a certain amount of impressions or clicks
* spread impressions and clicks over a given period

**Statistics**

* see stats of all or individual ads in your dashboard based on predefined and custom periods, grouped by day, week, or month
* display stats in a table and graph
* compare stats for ads
* compare stats with the previous or next period
* remove stats for all or single ads
* filter stats by ad groups
* public stats for a single ad – e.g. to show clients

**Reports**

* send email reports for all or individual ads to different emails
* create public reports for clients
* combine impressions and clicks with any other metrics in Google Analytics

**Statistic Management**

* export stats as csv
* import stats from csv
* remove old stats

Software included:

* [jqPlot](http://www.jqplot.com), GPL 2

== Installation ==

The Tracking add-on is based on the Advanced Ads plugin, a simple and powerful ad management solution for WordPress.

== Changelog ==

= 2.3.1 (April 28, 2022) =

* Fix: will prevent corrupted tracking records to appear on certain web server configuration
* Fix: prevent syntax error in autoloading caused by Composer 2.3.0

= 2.3.0 =

* Improvement: modernize statistics page and graph optics
* Improvement: update Arabic, Danish and German translations

= 2.2.0 =

* Improvement: introduce filters `advanced-ads-tracking-ajax-dropin-path` and `advanced-ads-tracking-ajax-dropin-url` to allow changing the location of the ajax-handler drop-in file
* Fix: correct event name `advanced_ads_decode_inserted_ads` while handling events from Advanced Ads Pro

= 2.1.3 =

* Improvement: add Arabic translation
* Fix: undefined variable wpdb on multisite installation when network deactivating the plugin

= 2.1.2 =

* Fix: correct limiter not counting impressions if no click limit had been set

= 2.1.1 =

* Improvement: reduce database queries for limited ads if hourly limit has already been reached
* Fix: correct loading of jQuery UI on database operation page
* Fix: make sure click/impression limit can never have a negative value
* Fix: correct display metrics on ad list page when tracking or parts of it are disabled
* Fix: redirect links aren't getting tracked with Google Analytics on plain text ads
* Fix: redirect links were always cloaked independent of UI settings
* Fix: improve handling and tracking inconsistencies of pop-up and layer placements with cache-busting and delays
* Fix: only send one more individual ad email report after an ad has expired

= 2.1.0 =

* Feature: allow tracking for the Google Ad Manager ad type
* Improvement: switch Google Analytics integration from using `analytics.js` and the measurement protocol to `gtag.js` and custom event to support GA4 measurement IDs
* Improvement: improve compatibility with delay JavaScript functionality from plugins like WP Rocket and Complianz
* Improvement: add unexpected output of `ajax-handler.php` to admin notice
* Improvement: add `rel="noopener"` if an ad link is supposed to open in a new target
* Fix: correct condition for showing/hiding cloaking checkbox on URLs
* Fix: ensure Ad Admin can save tracking options.
* Fix: correct undefined function in JavaScript when tracking with Google Analytics
* Fix: show tomorrow's day for timezones with high offset in the graph on ad edit pages

= 2.0.4 =

* Fix: checked if `advanced-ads-pro/cache_busting` is enqueued in deciding whether to add Pro-specific tracking code, instead of merely checking if Advanced Ads Pro is active

= 2.0.3 =

* fix grouped ads not getting tracked in delayed placement

= 2.0.2 =

* add additional bots that should not trigger tracking (`FlyingPress`, `WP Rocket/preload`, `Sogou web spider`, `Seekport Crawler`, `Barkrowler`)
* check for WP_Filesystem_Base before trying to use the filesystem
* improve activating/deactivating debugging option and check if file writable
* fix disabled tracking on AMP pages
* add possible to import and export options
* fix JS type error in click-tracking when closing an ad


= 2.0.1 =

* fix overwriting attributes set on link tag by the user
* fix escaped placeholders in external links, process all placeholders through link redirect

= 2.0.0 =

Tracking 2.0 is a major release. The various fixes and improvements can lead to changes in recorded impressions and clicks. See https://wpadvancedads.com/tracking-2-0/ for more details.

Major changes:

- the "JavaScript (AJAX)" method was highly improved and is now called Frontend and the default method for new sites
- the "On load" method is now called Database
- the "After page loaded" method was removed
- click tracking works on JavaScript-based ads and iframes
- target URLs in custom HTML code no longer need to be defined to being tracked
- cloak individual target URLs
- all Tracking methods work on AMP pages without requiring the Responsive add-on
- more bots are filtered out
- added option for "sponsored" attribute on links
- delayed ads, like popups, are tracked only when they displayed. Users no longer need to explicitly choose that behavior

For a full description of the changes and developers who used hooks and constants to adjust Tracking, please see https://wpadvancedads.com/tracking-2-0/.

= 1.21.0 =

* integrate with TCF 2.0 compatible consent management platforms
* fix parallel tracking for AJAX cache-busting

= 1.20.3 =

- added deprecation notice for "track after page loaded" method

= 1.20.2 =

* marked feature for [tracking of events on external sites](https://wpadvancedads.com/manual/tracking-external-events-and-affiliate-clicks/) as deprecated
* moved certain settings to an Advanced section on the Tracking settings page
* fixes tracking of impressions in the wrong database table when an ad is used from another site in a multisite network

= 1.20.1 =

* fixed potential theme conflict. Please update to the latest Advanced Ads version as well
* fixed missing index issue

= 1.20 =

* fixed CTR on ad overview list
* fixed ad stats being summed up as "Deleted ads" on the Stats page if they are from another language as set up in the WPML plugin

= 1.19 =

* added option to track ads that have a trigger only when they show up (applies to users of the Sticky Ads and PopUp add-ons)
* prevent browsers from caching the click-tracking redirect
* decrease height of ad stats graph
* show click-through-rate on ad overview page
* fixed bug with Google Analytics tracking + Cache Busting + Lazy Load not tracking reliably

Build: 2022-05-7c91a0d4