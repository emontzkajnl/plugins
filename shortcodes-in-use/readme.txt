=== Shortcodes In Use ===
Contributors: wizzud
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KP2LVCBXNCEB4
Tags: admin,tool,shortcode,find,locate,search,post,page,widget,theme,plugin,content
Requires at least: 3.8
Tested up to: 4.4
Stable tag: 1.2.1
License: GPLv2 or Later

List all the shortcodes that you have used within your content or custom fields, and find out exactly where they have been used.


== Description ==

* Can't recall where, or if, you've used a certain shortcode?
* Want to remove a plugin and unsure if it provides shortcodes or whether you're using them?
* Don't know what shortcodes come with WordPress?
* Found one unrecognised shortcode but unsure where there might be others?
* Need to swap out a theme and don't know if it has shortcodes that you've used?
* Want to use a shortcode for a specific bit of information but can't remember exactly what it's called?
* Need to a change a parameter for all occurences of a shortcode?

This is a simple administration tool that lists occurences of shortcodes within post content and/or custom fields, and/or widget settings.
You can select, or search for, specific shortcodes, and it can filter down to a provider, location, or post type.
It is intended to help administrators/editors locate where shortcodes have been used, so that they can be updated, renamed, deleted, or whatever.

Features include :

* Search string(s) to match against shortcode tags - space or comma delimited for multple search strings
* Filter by the type of provider of shortcode - whether it is provided by a plugin, your theme, internal to WordPress (eg. `[gallery]`), or unknown (an inactive/deleted plugin, maybe?)
* Filter by a specific provider - a named plugin, for example
* Filter by any number of specific, recognised shortcodes
* Filter by where to look for the shortcode - post content, post meta data (custom fields), or widgets
* Filter by the type of post that contains the shortcode
* Results include (where relevant and available) : either the widget name and its sidebar, or a linked post title and the type of post; the shortcode and its parameters; the shortcode provider (WordPress, plugin, theme, or unknown), and where it was found
* Has its own shortcode, for use when a plugin or theme only declares a shortcode when not in the admin backend

What it does **not** do :

* It does *not* provide any insight as to what any shortcode does, or how to use/configure it.
* It does *not* look at custom tables, theme options, transients.

If you like this plugin (or if you don't?), please consider taking a moment or two to give it a
[Review](http://wordpress.org/support/view/plugin-reviews/shortcodes-in-use) : it helps others, and gives me valuable feedback.


== Installation ==

1. EITHER Upload the zip package via 'Plugins > Add New > Upload' in your WP Admin, OR Extract the zip package and upload `shortcodes-in-use` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in your WP Admin. The widget will now be available under Admin's **Tools** menu.


== Screenshots ==
1. Tools / Shortcodes In Use page
2. Sample results
3. Provider/Tag filter options


== Frequently Asked Questions ==

If you have a question or problem that is not covered here, please use the [integrated Support forum](https://wordpress.org/support/plugin/shortcodes-in-use).

= Does it list all available shortcodes? =
Yes, all shortcodes known to WordPress (Admin) are listed, grouped by provider, and possibly sub-grouped by plugin or theme name. You
can select to filter by any number of them, or by either the provider or the specific plugin/theme. Note that you do *not* get any
information about their use or function.

= Can it find shortcodes that are not known to WordPress? =
Yes, assuming it is in the standard format. By default, anything that looks like a shortcode is reported, the only exception being
shortcodes that are "escaped" (by doubling up on the containing square brackets). You can filter the
results to just Unknown shortcodes, or you can search for a specific shortcode regardless or whether WordPress knows about it or not.

= Does it show nested shortcodes? =
Yes, the shortcodes are processed recursively, and nesting is indicated by a level of indentation in the results table.
However, please be aware that indentation only indicates that the shortcode in question is nested *somewhere* within the shortcode at
the lesser level of indentation; it does not necessarily provide any indication of howy deeply it's nested, because the search/filter
parameters may exclude any of the containing shortcodes from being reported. For this same reason, a lack of indentation does not
necessarily mean that the shortcode is not nested within another shortcode. In other words, any indentation is only relative to the
reported shortcodes.

= How does the Provider/Tag filter work, and why the highlighting? =
It's hierarchical, with (up to) 3 levels : general provider, named provider, and tags. Select nothing amd they're all included.
Select a named provider and all its tags get included. Select a general provider and all its named providers (ie. all their tags)
get included. As soon as you select anything, whatever remains unchecked and outside the immediate scope of the checked item
(ie. not hierarchically below) gets excluded. As an example, if you check "WordPress" without checking "Plugin" then you won't get any
results for shortcodes provided by plugins.

You can select multiple tags, multiple providers (general or named). What you can't do is select, for example, a named provider
**and** one of its tags. Selecting a named provider gets all its tags. If you only want one or two of its tags then select them
individually and don't try selecting the provider. Same applies when selecting a general provider.

Checked items are highlighted. Also, because the tree is collapsible (and starts out collapsed), unchecked ancestors of checked items
are also highlighted in order to provide visual feedback that something (possibly hidden from sight) has been selected.

= What gets cached, and for how long? =
Based on the Location filters, the plugin caches the ids of database records that contain likely-looking shortcodes. It does *not* cache
the results of any inspection of those records' content. If you change the Location filters (excluding Widget, which does not require
database access) such that any cached ids do not accurately reflect the new filters, then the cache is discarded and refreshed with
a new set of ids. The cache is only kept for 15 minutes, and can be manually discarded at any time.
Enquiries run via the shortcode do not use or set the cache.

= Why would I need to use the shortcode instead of the admin Tool? =

You may well not. But...

Some plugins - for example, the WP Photo Album Plus plugin - don't add their shortcode if you are in
the admin backend (and I hasten to add that there is nothing wrong with that approach). What this means
though, is that this plugin can't then determine the provider of that shortcode when it finds it in, say,
a page's content, because WordPress hasn't been told about it. The Tool can only report it as Unknown
Provider.

So, if you get too many unknowns turning up you may want to run a quick check to make see if they can
can be resolved when running the shortcode on a front end page : stick
a `[shortcodes_in_use provider=unknown/]` shortcode onto a page and view it while logged in as Admin?

== Shortcode ==

The shortcode for this plugin is...

`[shortcodes_in_use/]`

...and output is restricted to users with **edit_posts** capability.

The attributes available are in line with the options available in the Tool, and each one is a filter.
Setting an attribute for all possible values is the same as omitting that attribute.

Separate filters are ANDed, ie. specifying `provider="wordpress" post_type="page"` limits the results
to shortcode tags that are in a page **AND** provided by WordPress core.

Multiple values within a filter are ORed, ie. specifying `post_type="post page" provider="wordpress"`
limits the results to WordPress's own shortcode tags that are in either a post **OR** a page.

When you run the Tool in admin, the equivalent shortcode for the selected options is provided at the end
of the results. Also, the *sanitized* shortcode is repeated at the top of the shortcode's output.

* **search** (string) : A space or comma is interpreted as a delimiter, so...

    `[shortcodes_in_use search="foo bar"/]`

    ...looks for any shortcode tag that contains either "foo" or "bar".

* **provider** (string) : Any one or more of *unknown*, *wordpress*, *plugin*, or *theme*,
    delimited by either a comma or a space. For example...

    `[shortcodes_in_use provider="plugin unknown"/]`

    ...reports any shortcode tag whose provider cannot be determined, or whose provider has be
    determined as being a plugin.

* **location** (string) : Any one or more of *title*, *content*, *excerpt*, *meta* or *widget*,
    delimited by either a comma or a space. For example...

    `[shortcodes_in_use location="content excerpt"/]`

    ...reports any shortcode tag found in any main content or excerpt area.

* **post_type** (string) : Any one or more of WordPress's standard post types - *post*, *page*,
    *attachment*, etc - and/or any custom post types. Multiple post types are comma- or space-delimited.
    For example...

    `[shortcodes_in_use post_type="post,page"/]`

    ...reports any shortcode tag found in a post of type 'post' or 'page'.

* **tag** (string) : Any one or more shortcode tags, delimited by either a comma or a space. For
    example...

    `[shortcodes_in_use tag="shortcodes_in_use, custom_menu_wizard"/]`

    ...reports any occurence of either of those two shortcode tags.

* **name** (string) : This allows you to specify a specific plugin and/or theme by name. Multiple
    names are comma- or space-delimited, and each name **_must_** begin with either "plugin/" or "theme/".
    For example...

    `[shortcodes_in_use name="plugin/Shortcodes In Use, theme/Twenty Fifteen"/]`

    ...reports any occurence of a shortcode tag belonging to either the Shortcodes In Use plugin or
    the Twenty Fifteen theme. Instead of the *name* of the plugin/theme, you can supply their
    containing folder, so this would be an alternative for the example above...

    `[shortcodes_in_use name="plugin/shortcodes-in-use, theme/twentyfifteen"/]`

== Changelog ==

= 1.2.1 =
* tweak : changed the plugin's h2 header to h1
* addition : a collapse/expand all button
* tweak : remove on-page javascript

= 1.2.0 =
* internationalization

= 1.1.0 =
* bugfix : handle the shortcode's callable function being specified as "someClass::someMethod"
* addition : shortcode, for when a plugin or theme doesn't declare their shortcode when in admin backend
* tweak : reformatted results to not use table, added a count, and made parts collapsible
* tweak : small optimisation changes

= 1.0.1 =
* bugfix : ensure that checked by-location and posts-by-type filters also get hilighted when displaying results

= 1.0.0 =
* Initial release


== Upgrade Notice ==

= 1.2.1 =
Changed h2 header to h1 in line with WordPress guidelines.
Added a collapse/expand all button, and removed on-page javascript.
Verified 4.4 compatibility.

= 1.2.0 =
Internationalization.

= 1.1.0 =
Fixed a bug that neglected to check for a callable method specified as a string "someClass::someMethod".
Added my own shortcode to provide a means of finding other shortcodes that don't get added when in admin backend.
Reformatted the results - removed table, added counts and collapsible regions.

= 1.0.1 =
Fixed a wee bug that prevented checked by-location and posts-by-type filters from being highlighted when the results were shown.

= 1.0.0 =
* Initial release
