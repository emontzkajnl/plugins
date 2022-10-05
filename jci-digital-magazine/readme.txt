=== Plugin Name ===
Contributors: nelsnose, yhall, rstevens
Tags: magazine, content-type
Requires at least: 3.3
Tested up to: 3.5
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a post-type for Digital Magazines from third parties, and displays

== Description ==

Adds a post-type for Digital Magazines to display Calameo publications through configurable shortcodes.
Can be extended to work with a Post that handles device detection to offer download of app.

== Installation ==

1. Upload `jci-digital-magazine` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the shortcodes in your pages, posts, or templates (with `<?php do_shortcode(); ?>` 

== Frequently Asked Questions ==

= What are the shortcodes =

`[jci-featured-magazine width= height= post_id=]`
Displays the most recent magazine selected as 'featured' by default.  Width and height maybe pixels or percentages, while the post_id must be a number.

`[jci-magazine-widget width= height= post_id= arrows=]`
Displays a "mini-flipper" widget (links to the Magazine post).  The arrows argument should be 0 or 1, and controls the display of navigational arrows.

`[jci-magazine-shelf width= height= count= size=]`
Displays a "bookshelf" of all magazines selected to display in bookshelf by default.  Set count to limit the magazines.  Size should be the name of pre-defined image size.

== Screenshots ==

1. Comming SoonThis screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)

== Changelog ==

= 0.1 =
* Brand new module
* Posttype Created
* Settings page started
= 0.2 =
* Created shortcodes
* Added validation to settings page
= 0.3 =
* Added additional settings
* Added additional shortcode
= 0.4 =
* Input validation for shortcodes
* Javascript added for bookshelf
* Default (basic) themeing
= 0.4.1 =
* Arrow functionality reversed
* Slider Magazine width calculation improvement
* Minor style revision

=1.0.1 =
* Change the JS to use elastislide which is a responsive carrousel
* Add Enhanced Mobility Controls to Show Badges for:
  Apple Link, Android Link & Amazon Link (check boxes show on DM Post Type)
  The links for those badges have to be filled in a regular post which should have 
  the Advanced Custom Fields for entering links to those places and the assigned category name
  "Device Detection Post"

=1.0.2 =
* Small styling changes: made shelf full width, moved arrows, adjusted title width

=1.1 = 4/29/14
* Richard Stevens changed the Calameo embed format and short code to Calameo’s iframe format which got rid of the annoying blue button and replaced with mag cover
* Responsive
* Magazine now opens in own Calameo window and procedure for Calameo publishing was changed to include TOC, etc.