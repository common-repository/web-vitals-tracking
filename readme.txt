=== Web Vitals Tracking ===
Contributors: eatzeni
Tags: web vitals, core web vitals, web vitals tracking, web vitals analytics, web vitals monitoring, site health
Requires at least: 4.2
Tested up to: 5.4.2
Stable tag: 5.4.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Track core web vitals data and save history, powers a dashboard with 24h and one month of historic data, shows worsts pages. 
Send also into Google Analytics (both gtag and ga supported) and can be extended to send also to Google Tag Manager and third parties

NOTE: This plugin does not send data to Google Tag Manager events, it only adds them to the dataLayer object but requires additional configuration on the Tag manager dashboard.

== What is Core Web Vitals? ==
From the official page (https://web.dev/vitals/) Web Vitals is:
    [...] an initiative by Google to provide [...] quality signals that are essential to delivering a great user experience on the web.
    [...]
    Core Web Vitals are the subset of Web Vitals that apply to all web pages, should be measured by all site owners, and will be surfaced across all Google tools. 
    Each of the Core Web Vitals represents a distinct facet of the user experience, is measurable in the field, and reflects the real-world experience of a critical user-centric outcome.

== Why are them important? ==
They are important not only to improve user experience on websites but also because since 2021 Core Web Vitals will become official ranking signals for Google (https://webmasters.googleblog.com/2020/05/evaluating-page-experience.html).

== Installation ==
1) upload zip file to your server and extract it in your plugins directory.
2) go to your administration plugin dashboard and enable it


== HOW TO track into Google Tag Manager ==
This plugin adds following data to the dataLayer object:
    dataLayer.push({
        event: "web-vitals",
        event_category: "Web Vitals",
        event_action: <event_name>,
        event_value: <event_value>,
        event_label: <event_id>,
    });

You only need to create a custom event trigger in your Google Tag Manager (see https://support.google.com/tagmanager/answer/7679219?hl=en#), create Data Layer Variables and track those as Google Analytics Events on Tag Manager.


== HOW TO track into third party tools or custom Google Tag Manager syntax ==
This plugins allows developers to listen for data captured and track them as their prefer. You only need to create a global Javascript function named
    window.wpWebVitalTrack
and you're done!
E.g.:
    window.wpWebVitalTrack = function(event_id, event_name, event_value, event_delta) {
        console.log("Ready to do something amazing!", event_id, event_name, event_value, event_delta);
        // do something amazing!
    }

NOTE: create this method as early as possible to avoid missing some event!

== Screenshots ==

1. Last month metrics average and last 24h report
2. Last 24h table report
3. Last month report; graph will auto expand to 30 days based on collected data
4. Worst urls report


== Changelog ==

= 1.0.1 =
* Fix warning

= 1.0.0 =
* First release
