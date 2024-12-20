=== Beautiful Cookie Consent Banner ===
Tags: cookie consent, cookie banner, ccpa, eu cookie law, gdpr cookie, banner, gdpr, dsgvo, cookie, responsive, cookie consent banner, insites, osano, cookieconsent
Requires at least: 4.0
Donate link: https://www.paypal.me/nikelschubert/6.00EUR
Tested up to: 6.1
Requires PHP: 5.2.17
License: GPLv3
Stable tag: 2.11.1

== Description ==
A free and beautiful way to get a Cookie Banner without loading any external resources from 3rd parties. Customize it to match your compliance requirements and website layout. This Banner is super responsive and highly customizable.

= Key Features =
 - fully customizable texts, colors, fonts and position of the banner and buttons. 
 - multilanguage support with premium add-on: [beautiful-cookie-banner.com](https://beautiful-cookie-banner.com/)
 - auto block support with premium add-on.
 - choose between different compliance types: Just Inform, Opt-in, Opt-out, Differentiated. Cookies will not be stored by default. With differentiated you can define cookie groups, and for each group a user can give a consent.
 - google tag manager support.
 - show the Banner until user accepts all cookies.
 - prevent user setting cookie from automatic deletion by many browsers (e.g. ITP => 2.1).
 - no resources loaded from third parties: **everything is hosted on your domain**.
 - Developer friendly: filter hook to change cookie message: nsc_bar_cookie_bar_message

The plugin helps you preparing your website for a lot cookie laws and regulations, for example:

 - GDPR: The General Data Protection Regulation (European Union)
 - CCPA: The California Consumer Privacy Act (California, United States)
 - PIPEDA: The Personal Information Protection and Electronic Documents Act (Canada)
 - LGPD: The Brazilian General Data Protection Law (Brazil)
 - OAIC: Australia’s Privacy Principles (Australia)

= These cookies are set by this plugin =
You can customize the cookie name, though.

 - **cookieconsent_status** -> stores the user setting, if cookies are allowed or not. If you choose "differentiated consent" it stores, if the user closed the banner.
 - **cookieconsent_status_{cookiesuffix}** -> only set in case of "differentiated consent". It stores the user setting for the cookie group. One cookie for each group is set.
 - **nsc_bar_cs_done** -> set if you activate ITP Protection (use backend cookies). Stores the information of when the cookie was set, to give them a duration.
= Localstorage is used =
If you have the premium add on and activate the stats module and activate the banner open counter, then a counter is written to localstorage. The key is "beautiful_cookie_banner_open_counter".


= Features =
1. Fully responsive.
2. Make consent cookie a backend cookie to prevent automatic deletion which many browsers do for javascript/frontend cookies.
3. reload after acceptance
4. Easily export your settings to another installation: just copy and paste the json string.
5. Show banner until user accepts cookies
6. Google Tag Manager supported: Push consent to dataLayer for easy configuration
6. Users can easily change cookie settings: choose between an extra tab or with a shortcode.
7. Fully customizable colors and text.
8. It is for all kind of compliance: opt-out, opt-in, info only, differentiated
9. choose between different themes
10. choose the position on your website
11. choose cookie name, duration, domain.
12. cookie setting management via shortcode: [cc_show_cookie_banner_nsc_bar]
13. preview banner in backend.
14. auto dismiss
... and there are a lot more.

= Credits =
This really beautiful plugin wraps the solution provided by osano (https://www.osano.com/cookieconsent/download/) into a wordpress plugin.
This Version uses 3.1.0 from osano as basis, but it has a lot modifications to the original source code. It is optimized for performance and for a low database impact.

= NOTE: Using this plugin alone does not makes your site automatically compliant. As each website and country is different you must make sure you have the right configurations in place. Google tag manager can help you with that for free. =

== Frequently Asked Questions ==

= Why backend cookies? (ITP >= 2.1) =
Most Cookie Banner plugins set javascript cookies. In Safari and Firefox these cookies have a short lifetime, even if the cookie is set with an very long expiration date.
**Consequence:** Your user have to opt in every seven days again. And sees the banner every seven days. Which is kind of annoying.
With this Plugin the consent cookie stays save and is not limited in lifetime. If you use the option.
If you want to save more cookies, check out this plugin: [https://wordpress.org/plugins/itp-cookie-saver](https://wordpress.org/plugins/itp-cookie-saver/).

= How to remove tab at the bottom? =
Just go to Settings > Cookie Consent Banner > Consent Management and uncheck the checkbox "Show 'Cookie Settings' tab"

= Does this plugin have a setting for blocking scripts or cookies until acceptance? =
Yes. With the premium add-on ([beautiful-cookie-banner.com](https://beautiful-cookie-banner.com/)) you can enable autoblocking. But be aware: there is no 100% guarantee that this will always work. As this is heavily dependent from your wordpress installation.

= How should I organize my trackingscripts and cookies? =
The technical recommandation is to use the google tag manager. It is an awesome and free tagmanager. Since version 2.2 this plugin natively supports it. But as always: check if it is legal to use it in your jurisdication☝️.

= I have a custom template and want to modify the cookie message. How? =
You can use the filter hook "nsc_bar_cookie_bar_message" in your plugin or theme.

== Screenshots ==

1. Cookie Banner Mobile Example - two buttons.
2. Cookie Banner admin settings area with banner example.
3. Set the colors and general appearence of the Cookie Banner. 
4. Set the text and customize the links of the Cookie Banner.
5. How should the Cookie Banner behave? Two Buttons, one button? Consent categories? Everything is possible.
6. Define how user can interact with the Cookie Baner after they closed it? Only per shortcode link? Or a tab?
7. Block scripts which set cookies automatically with the premium add-on of this cookie banner.
8. Basic cookie settings. The consents are stored in a cookie.
9. You can easily import and export most of the settings of your cookie banner. Very handy to apply it to multiple pages.
10. Mobile Example of the cookie banner.
11. Mobile Example of the cookie banner.
12. Desktop Example of the cookie banner.
13. Desktop Example of the cookie banner with top bar.

== Installation ==
Just install this plugin and go to Settings > Cookie Consent Banner to change the default config and to activate the banner.

== Changelog ==

= 2.11.1 =
* REFACTOR: Added compatibility for upcoming addon version. 

= 2.11.0 =
* PLEASE REVIEW BEFORE UPDATING: change the standard permission to edit the banner from edit_published_posts to manage_options. If you do not want that, you can change it back again in the admin area.
* IMPROVMENT: added additional security safeguard as recommended from the external security audit.
* IMPROVEMENT: added better validation for color inputs

= 2.10.2 =
* Improvement: If the in verison 2.10.1 fixed vulnerabulity was exploited on your site, this update is going to disable the malicious code.  
* Refactor: Improved validation

= 2.10.1 =
* Fix: Authentication issues.
* Fix: XXS vulnerabulity fixed.

= 2.10.0 =
* IMPROVEMENT: performance - js for dataLayer push is not inline anymore.
* IMPROVEMENT: preparations for premium add-on version 1.7.0

= 2.9.2 =
* IMPROVEMENT: Minified some JS
* IMPROVEMENT: Support for show banner with delay of premium add-on

= 2.9.1 =
* FIX: XSS vulnerabulity in admin area. Was only possible for admins to exploit.

= 2.9.0 =
* Feature: added option to customize the text for closing.
* Refactor: made the admin js a little bit nicer.

= 2.8.2 =
* Bugfix: sometimes when using elementor preloader, the banner did not disapear. Now it works.

= 2.8.1 =
* Improvement: the "x" is now within the banner and not outside.

= 2.8.0 =
* NEW: added possibility to close banner with an "x"
* IMPROVEMENT: increased the z-index of the banner to max integer. So the banner and the blocking screen will be always on top of everything.

= 2.7.3 =
* Refactor: removed unnecessary admin check.

= 2.7.2 =
* Improvement: filter changes for premium plugin: nsc_bar_plugin_settings_as_an_object
* Bugfix: Removed duplicate input sanitation, which lead in some cases to errors.

= 2.7.1 =
* Bugfix: If the [cc_show_cookie_banner_nsc_bar] shortcode was used more then once per page it only worked for the first link.
* Refactor: changes to JS library for an upcoming statistics module

= 2.7.0 =
* Feature: Disable the banner, if the page is loaded in an iFrame.
* Refactor: Preparation for a statistic module.
* Refactor: dataLayer push more separated in code, to be able to load it on wp_loaded.
* Bugfix: Now it works with the weglot language plugin.
* Improvement: adding filter: nsc_bar_filter_data_layer_values.

= 2.6.7 =
* Improvement: Cookie samesite attribute is now fully set
* Improvement: You can now interact with the buttons by pushing "return"

= 2.6.6 =
* Bug fix: in WP 5.8 Banner showed up in new widget area.

= 2.6.5 =
* Refactor: added filter for field validation
* Improvements: added warning to "improve speed" setting.
* Improvements: 'Learn more' Link and customLink can now be configured, if they should open in a new tab/window or not.

= 2.6.4 =
* Improvements: added setting to improve the banner loading speed.

= 2.6.3 =
* Improvements: adding more filter: nsc_bar_filter_json_config_string_before_js, nsc_bar_filter_banner_config_array_init, nsc_bar_filter_banner_is_active

= 2.6.2 =
* Improvements: added filter for the final config json as valid JS object: nsc_bar_filter_json_config_string_with_js

= 2.6.1 =
* Improvements: no new features, only the admin area is now nicer and cleaner.

= 2.6.0 =
* FEATURE: you can now block scripts that set cookies with the premium add-on, see here for more information: [beautiful-cookie-banner.com](https://beautiful-cookie-banner.com/)

= 2.5.3 =
* BUG FIX: Some issues with caching plugins.

= 2.5.2 =
* BUG FIX: since 2.5.1 the dataLayer push for "banner initialized" was fired pretty late. Now it is fired earlier again.

= 2.5.1 =
* IMPROVEMENT: Filter for the cookie message. Now you can easily change the cookie message in your theme or template.
* REFACTOR: some changes regarding how the scripts are included in the page.

= 2.5.0 =
* ADDED: Possibility to place a second link freely in the cookie banner text.

= 2.4.0 =
* ADDED: Support for multilanguage add-on, see here for more information: [beautiful-cookie-banner.com](https://beautiful-cookie-banner.com/)

= 2.3.1 =
* IMPROVEMENT: Colour fields now supprt rgb as well, e.g.: rgba(200, 54, 54, 0.5)

= 2.3 =
* FEATURE: Adjust the position of the Banner in HTML source code of the page. Per default it is directly beneath the <body>. Now you can use any other selector to change that.

= 2.2.1 =
* IMPROVEMENT: Default Setting name of "cookie settings"
* IMPROVEMENT: When adding cookie types the added form is now in focus.
* FIX: length of "cookie_suffix" not correct validated.

= 2.2 =
* ADDED: Google Tag Manager support: dataLayer pushes - events: beautiful_cookie_consent_initialized and beautiful_cookie_consent_updated
* ADDED: setting for font customisation.
* ADDED: setting to make buttons look equal.
* ADDED: automatic consent: after time or scroll.

= 2.1.1 =
* IMPROVEMENT: changed way scripts are loaded. Now there should be a better support of caching plugins.

= 2.1 =
* ADDED: "differentiated consent" with two buttons: "save all" and "save settings"
* ADDED: Option to block screen, when banner is shown.

= 2.0 =
* ADDED: "differentiated consent" as compliance type: users can now give consent to only a special type of cookie.
* ADDED: a shortcode for showing banner again, for managing consent: [cc_show_cookie_banner_nsc_bar].
* ADDED: more positions for the banner
* CHANGED: changed revoke tab handling: now possible to use for all banner types.
* DEPRECATED: shortcode: [cc_revoke_settings_link_nsc_bar]
* REFACTOR: a lot refactorings, for better feature development in future. A lot.
* REFACTOR: not using unchanged banner JS from osano anymore.

= 1.7.1 =
* bug fixes

= 1.7 =
* added feature "Ask until acceptance": show banner until user consents.

= 1.6 =
* added "reload after banner close" for more accurate tracking after tracking opt in.

= 1.5 =
* added a field to configure the text of "Cookie Policy" tab.
* "Cookie Policy" tab can now be deactivated
* users can manage their cookie settings now independently from "Cookie Policy" tab: you can add the link easily easily by shortcode: [cc_revoke_settings_link_nsc_bar].
* bit of clean up of admin area

= 1.4.1 =
* Bug Fix: sometimes the Banner was behind the content.
* Bug Fix: minor Performance Issue.

= 1.4 =
* New Feature: cookie banner is now configurable with a form and not only with a json string. If you want to, you can still use the json.
* a lot stuff under the hood.

= 1.3 =

* New Feature: Added prevention for ITP 2.1 cookie deletion. Safari and Firefox limit the lifetime of a cookie which is set by javascript to seven days.
Most cookie banner plugins set javascript cookies, that means that your returning user will see every seven days your banner and have to opt in again.
* Improvement: Cookie in preview modus only deleted if you visit the settings page of that plugin, not admin in general.

= 1.2 =

* Improvement: When visiting an admin page, consent cookie is only deleted, if "preview banner" setting is activated.

= 1.1.2 =

* minor bug fixes.

= 1.1.1 =

* fixes for PHP 5.4

= 1.1 =

New Features

* you can activate or deactivate the banner now.
* you see a preview in the plugin settings area now.

= 1.0.5 =

* Improvement: Updated to cookie consent library 3.1. Now dismissOn... functions are working.

= 1.0.4 =

* FIX: Now Really: with an update of this plugin the configuration of the cookie banner is not overwritten anymore.

= 1.0.3 =

* FIX: small fixes and improvements, e.g. admin errors will only be displayed on admin page of this plugin.
* Improvement: added Icon for this plugin.

= 1.0.2 =

* FIX: with an update of this plugin the configuration of the cookie banner is not overwritten anymore.

= 1.0.1 =

* Improvement: added compatibility in readme.txt

= 1.0 =

* First Version of this lightweight Plugin. More to come!
