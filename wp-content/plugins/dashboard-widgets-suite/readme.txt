=== Dashboard Widgets Suite ===

Plugin Name: Dashboard Widgets Suite
Plugin URI: https://perishablepress.com/dashboard-widgets-suite/
Description: Provides a suite of sweet widgets for your WP Dashboard.
Tags: dashboard, widget, widgets, user notes, notes, todo, memo, rss, social, server, debug, log
Author: Jeff Starr
Contributors: specialk
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Requires at least: 4.1
Tested up to: 4.9.8
Stable tag: 1.8
Version: 1.8
Requires PHP: 5.2
Text Domain: dashboard-widgets-suite
Domain Path: /languages
License: GPL v2 or later

Rock your�Dashboard with a sweet collection of awesome widgets!



== Description ==

__1 Plugin. 9 Widgets. Awesome Dashboard.__

_Streamline your Admin Area by reducing the number of plugins required for widgets._

> Works perfectly with or without Gutenberg!

Dashboard Widgets Suite provides an entire set of awesome widgets that you can add to your WordPress Dashboard:

* __Control Panel__ - Control widgets directly from the Dashboard
* __User Notes__    - Add, edit, delete notes for any user role
* __Feed Box__      - Display and customize any RSS Feed
* __Social Box__    - Display social links from Twitter, Facebook, etc.
* __List Box__      - Display custom lists created via the Menu screen
* __Widget Box__    - Display any theme or WP widget (e.g., Search, Text)
* __System Info__   - Display basic or advanced system information
* __Debug Log__     - Display, customize, reset your WP debug log
* __PHP Error Log__ - Display, customize, reset your server error log

Each widget includes its own set of options for customizing display. 

The notes widget is super awesome, designed for serious note takers :)

_[Suggest a widget!](https://perishablepress.com/contact/)_



**Features**

Dashboard Widgets Suite provides a slick set of useful Dashboard widgets with some great features:

* Includes 9 awesome Dashboard widgets
* Easy to set up and customize options
* Regularly updated and "future proof"
* Secure, clean, error-free code
* Built with the WordPress API
* Lightweight, fast and flexible
* Focused on performance, loads only enabled widgets
* Enable/disable widgets via Dashboard or plugin settings
* Option to restore 1-column display for the Dashboard
* Shortcodes to display widgets on the frontend
* Many options for customizing widget display

[Check out the Screenshots](https://wordpress.org/plugins/dashboard-widgets-suite/screenshots/) for more details!

_[Suggest a feature!](https://perishablepress.com/contact/)_



**GDPR**

This plugin does not collect any user data. So it does _not_ do anything to make your site _less_ compliant with GDPR. I have done my best to ensure that this plugin is 100% GDPR compliant, but I'm not a lawyer so can't guarantee anything. To determine if your site is GDPR compliant, please consult an attorney.



== Screenshots ==

1.  DWS Dashboard: All widgets enabled
2.  DWS Settings:  General Settings
3.  DWS Settings:  User Notes
4.  DWS Settings:  Feed Box
5.  DWS Settings:  Social Box
6.  DWS Settings:  List Box
7.  DWS Settings:  Widget Box
8.  DWS Settings:  System Info
9.  DWS Settings:  Debug Log
10. DWS Settings:  Error Log



== Installation ==

**Installing Dashboard Widgets Suite**

1. Upload the plugin to your blog and activate
2. Visit the settings and enable desired widgets
3. Visit the WP Dashboard to use your new widgets

Tip: you can enable/disable widgets via the plugin settings or via the Control Panel widget on the Dashboard.

_[More info on installing WP plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)_



**Debug and Error Logs**

Note that the Debug and Error Log widgets may require a bit of configuration, depending on your WP setup. Here is a quick guide:

__Debug Log__

To enable the WP Debug Log for the Debug Log widget, make sure that debug mode is enabled in your site's `wp-config.php` file. Here is one possible way to enable, by adding the following code to your wp-config file, just before the line that says, "That's all, stop editing!":

	define('WP_DEBUG', true);
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);

Once added, this will tell WP to log all errors, warnings, and notices to a file named `debug.log`, which is located in the `/wp-content/` directory. Note that if the file does not exist, you can create it manually and give it suitable permissions. Ask your web host if unsure.

__Error Log__

To enable the Error Log for the Error Log widget, follow the same steps as for "Debug Log", but use this code instead:

	define('WP_DEBUG', true); 
	ini_set('display_errors', 'Off');
	ini_set('error_reporting', E_ALL);

And also make sure to set the correct file path under the plugin's "Error Log" tab, in the setting "Log Location".

__Debug Log and Error Log__

To enable both Debug Log and Error Log, follow the same steps as above, but use this code instead:

	define('WP_DEBUG', true);
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);
	ini_set('display_errors', 'Off');
	ini_set('error_reporting', E_ALL);

For more information, check these official resources:

* [https://codex.wordpress.org/Debugging_in_WordPress](https://codex.wordpress.org/Debugging_in_WordPress)
* [https://codex.wordpress.org/WP_DEBUG](https://codex.wordpress.org/WP_DEBUG)



**Uninstalling**

Dashboard Widgets Suite cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.



**Restore Default Options**

To restore default plugin options, either uninstall/reinstall the plugin or visit the General Settings &gt; Restore default plugin options.



**Shortcodes**

DWS provides several shortcodes for displaying widgets on the frontend of your site. Here is a summary:

	[dws_feed_box]   => Feed Box
	[dws_social_box] => Social Box
	[dws_user_notes] => User Notes
	
You can add these to any WP Post or Page to display the widget on the frontend. The same widget settings apply to both frontend and backend display.



**Customizing**

Dashboard Widgets Suite provides plenty of settings to customize your widgets. For advanced customization, developers can tap into the power of WordPress Action and Filter Hooks. Here is a complete list of the hooks provided by Dashboard Widgets Suite:

	
	Action Hooks
	
	dashboard_widgets_suite
	dashboard_widgets_suite_control_panel
	
	dashboard_widgets_suite_feed_box
	dashboard_widgets_suite_feed_box_frontend
	
	dashboard_widgets_suite_list_box
	
	dashboard_widgets_suite_log_debug
	dashboard_widgets_suite_log_error
	
	dashboard_widgets_suite_notes_user
	dashboard_widgets_suite_notes_user_submit
	dashboard_widgets_suite_notes_user_frontend
	
	dashboard_widgets_suite_social_box
	dashboard_widgets_suite_social_box_frontend
	
	dashboard_widgets_suite_system_info
	
	dashboard_widgets_suite_widget_box
	
	
	Filter Hooks
	
	dashboard_widgets_suite_options_general
	dashboard_widgets_suite_get_options_general
	
	dashboard_widgets_suite_options_feed_box
	dashboard_widgets_suite_get_options_feed_box
	dashboard_widgets_suite_feed_box_data
	dashboard_widgets_suite_feed_box_output
	dashboard_widgets_suite_feed_box_suffix
	dashboard_widgets_suite_feed_box_frontend_data
	
	dashboard_widgets_suite_options_list_box
	dashboard_widgets_suite_get_options_list_box
	dashboard_widgets_suite_list_box_menu_name
	
	dashboard_widgets_suite_options_log_debug
	dashboard_widgets_suite_get_options_log_debug
	dashboard_widgets_suite_log_debug_clear
	dashboard_widgets_suite_log_debug_errors
	dashboard_widgets_suite_log_debug_level
	dashboard_widgets_suite_log_debug_path
	
	dashboard_widgets_suite_options_log_error
	dashboard_widgets_suite_get_options_log_error
	dashboard_widgets_suite_log_error_clear
	dashboard_widgets_suite_log_error_errors
	dashboard_widgets_suite_log_error_level
	dashboard_widgets_suite_log_error_path
	
	dashboard_widgets_suite_options_notes_user
	dashboard_widgets_suite_get_options_notes_user
	dashboard_widgets_suite_notes_user_data_add
	dashboard_widgets_suite_notes_user_data_delete
	dashboard_widgets_suite_notes_user_data_edit
	dashboard_widgets_suite_notes_user_data_form
	dashboard_widgets_suite_notes_user_data_get
	dashboard_widgets_suite_notes_user_example
	dashboard_widgets_suite_notes_user_message
	dashboard_widgets_suite_notes_user_style
	dashboard_widgets_suite_notes_user_frontend_data
	dashboard_widgets_suite_notes_user_frontend_view
	
	dashboard_widgets_suite_options_social_box
	dashboard_widgets_suite_get_options_social_box
	dashboard_widgets_suite_social_box_output
	dashboard_widgets_suite_social_box_frontend_data
	
	dashboard_widgets_suite_options_system_info
	dashboard_widgets_suite_get_options_system_info
	
	dashboard_widgets_suite_options_widget_box
	dashboard_widgets_suite_get_options_widget_box
	
	dashboard_widgets_suite_allowed_tags
	dashboard_widgets_suite_editable_roles
	dashboard_widgets_suite_get_date
	

_[Suggest a hook!](https://perishablepress.com/contact/)_



== Upgrade Notice ==

To upgrade Dashboard Widgets Suite, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 

__Important:__ User Notes are not deleted, so if you want to delete them, do so via the WP Dashboard before uninstalling the plugin.



== Frequently Asked Questions ==

**Can you add this widget or that widget?**

Yeah maybe, feel free to [suggest a widget!](https://perishablepress.com/contact/)


**Got a question?**

Send any questions or feedback via my [contact form](https://perishablepress.com/contact/)



== Support development of this plugin ==

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Changelog ==

**1.8 (2018/08/14)**

* Adds option to always use the logged-in username for notes
* Adds "reload page" link to debug and error log widgets
* Adds `rel="noopener noreferrer"` to all [blank-target links](https://perishablepress.com/wordpress-blank-target-vulnerability/)
* Escapes special characters in error log and debug log display
* Improves logic of check-role function
* Regenerates default translation template
* Updates GDPR blurb and donate link
* Further tests on WP versions 4.9 and 5.0 (alpha)

**1.7 (2018/05/06)**

* Adds WP Resources info on settings page
* Adds `noopener` and `noreferrer` to blank target link in social box widget
* Fixes bug when no path is specified for Error Log widget
* Adds shortcut links to plugin settings for all widgets
* Removes unused font file, `FontAwesome.otf`
* Adds support for Windows 10 detection
* Adds support for 64-bit detection
* Generates new translation template
* Updates plugin documentation
* Updates plugin image files
* Tests on WordPress 5.0

**1.6 (2017/10/20)**

* Changes required user role settings to minimum user role
* Hides settings shortcut link on Control Panel for non-admins
* Updates some settings labels for accuracy
* Fixes custom multiple dashboard columns
* Adds Link option to the Social Box
* Adds label to Email option in Social Box
* Improves markup for Email option in Social Box
* Cleans up some unused code/comments
* Tests on WordPress 4.9

**1.5 (2017/07/31)**

* Adds fallback for `array_column()` for PHP less than 5.5
* Removes redundant function for SQL version info
* Adds GPL license file
* Tests on WordPress 4.9 (alpha)

**1.4 (2017/03/24)**

* Fixes PHP Notice for undefined constant, `COM`
* Adds [Italian translation](http://bit.ly/2nQrVXJ) (thanks to [Aldo Latino](https://www.aldolat.it/))
* Fixed some missing/incorrect translation domains
* Improves logic of IP-detection script
* Replaces global `$wp_version` with `get_bloginfo('version')`
* Fixed PHP warning with feed box excerpts
* Tests on WordPress version 4.8

**1.3 (2016/11/16)**

* Simplifies `dashboard_widgets_suite_get_lines()`
* Adds `class_exists` to `get_server_load_average()`
* Changes stable tag from trunk to latest version
* Fine-tunes styles for dialog box on settings page
* Fine-tunes styles for abbr element on settings page
* Adds `&raquo;` to rate plugin link on Plugins screen
* Adds strong tags to admin notices on settings page
* Updates URL for rate this plugin links
* Tests on WordPress version 4.7 (beta)

**1.2 (2016/08/10)**

* Updates [WP Admin Notices](https://digwp.com/2016/05/wordpress-admin-notices/)
* Replaces `_e()` with `esc_html_e()` or `esc_attr_e()`
* Replaces `__()` with `esc_html__()` or `esc_attr__()`
* Renames text-domain from "dws" to "dashboard-widgets-suite"
* Renames `/lang/` directory to `/languages/`
* Generates new languages template
* Changes default role to Admin for Control Panel widget
* Changes default role to Admin for User Notes
* Fixes PHP Warning when restoring options
* Fixes incorrect error count for Log Widgets
* Adds a rate this plugin link
* Tests on WordPress version 4.6

**1.1 (2016/03/30)**

* Adds option to delete/reset all user notes
* Fixes incorrect localization parameter
* Changes default required role to administrator for debug and error logs
* Fixes two "Undefined variable" PHP Notices in debug and error logs
* Fixes PHP Warning with array_sum() function in System Info widget
* Adds setting to display any widget area in the Widget Box ([thanks Joan Boluda](https://boluda.com/))
* Adds setting to display any menu/list in the List Box Widget ([thanks Joan Boluda](https://boluda.com/))
* Improves handling of debug and error logs
* Improves functionality of System Info Widget
* Added more security checks to System Info Widget
* Tests on WordPress version 4.5 beta

**1.0 (2016/03/17)**

* Initial release
