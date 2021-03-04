=== Gravity Forms Extended Merge Tags ===
Contributors: ericjuden
Tags: gravity, gravityforms, form, session, cookie, server
Requires at least: 3.7
Tested up to: 5.7
Stable tag: trunk 

== Description ==
This plugin allows you to use $_COOKIE, $_SERVER, $_SESSION, $_GET, $_POST and $_REQUEST data in a Gravity Form through the merge tags of a field. I’ve only really tested this using a hidden field.

== Screenshots ==
1. Showing the merge tags dropdown

== Installation ==
1. Copy the plugin files to <code>wp-content/plugins/</code>

2. Activate plugin from Plugins page

3. Add a hidden field to your Gravity Form

4. Go to the Advanced tab in the field settings and hit the down arrow to the right of the Default Value text box

5. Scroll down to Custom and choose either Cookie Data, Server Data or Session Data

6. Change the "key" text with the data you are looking for

== Changelog ==
= 1.1 =
* Added support for $_GET, $_POST and $_REQUEST variables
* Cleaned up the code to better adhere to WordPress coding standards

= 1.0 =
* Initial release
