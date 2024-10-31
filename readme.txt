=== Next Page Caching ===
Contributors: bfintal, gambitph
Tags: cache, caching, performance, optimize, super cache, prefetch, preconnect, preload, resource hint, next page
Requires at least: 4.8
Tested up to: 4.9.4
Requires PHP: 5.3
Stable tag: 0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Speed up the loading of the NEXT page your visitors will go to.

== Description ==

Your website visitors usually jump from page to page, or read your blog posts one after the other. Current caching plugins perform caching on the current page you are on. This plugin caches the next page your visitor will go to. This means your visitors can navigate your website faster.

= How Does This Work? =

Next Page Caching implements awesome "next page" caching to your site - this is different from your typical caching plugins like WP Rocket and W3 Total Cache.

Instead of only trying to serve the current page faster to your visitors, this plugin lets your browser load parts of the next page while the visitor is busy reading the current page he/she is on.

It does this with your help. We ask you two things in every post/page: First is what you think the next webpage your visitor will most likely go to afterwards, and second is a short list of critical files that you think should be loaded first in your page to make the top part of the page become visible quickly.

With those two items, the plugin can now preload the important parts of the next page he'll go to. The plugin also prioritizes the loading of the chosen critical files to ensure that the next page is visible as soon as possible.

> For example, if you find that a majority of your visitors in your front page check out the pricing page, then pick your pricing page as the "next page". The visitors who navigate from your front page to your pricing page should see some speed benefits.

**This is not meant to replace your current caching plugin, but instead, this is meant to be used alongside it.**

= What Caching Does This Do? =

- The plugin prefetches the main HTML of the chosen next page, as well as the critical files specified.
- Prefetches the first post when viewing a blog list or archive page.
- Preloads the post's chosen critical files to prioritize them.
- Preloads the theme stylesheet so that it gets loaded first.
- Preloads the featured image of a blog post or page when needed (and if it's large) so that it shows up faster.
- Preconnects to the Google Fonts domain for faster font downloading when needed.

= Important Notice =

- Do not replace your current caching plugin with this one, use this one together with your existing one.
- This plugin does not predict / analyze where your visitors will go next
- This plugin only has benefits in Modern Browsers

== Installation ==

== Frequently Asked Questions ==

**How to use**

Edit each page or post, and modify the Next Page Caching Settings below the content editor. Some caching methods are done automatically - like caching the first blog post while browsing your blog page.

**Will this automatically cache all the "next pages"?**

This plugin does not predict / analyze where your visitors will go next

The plugin doesn't have any statistical methods or analytics in it. It relies on you to supply what the "next page" is and it uses that to perform the caching.

You'll have to edit your posts and pages and adjust the Next Page Caching settings.

**How will I know what the correct "next page" is?**

You can use analytics tools like Google Analytics, where you can find out the behavior of your visitors while browsing your site.

For example, if you find that a majority of your visitors in your front page check out the pricing page, then pick your pricing page as the "next page". The visitors who navigate from your front page to your pricing page should see some speed benefits.

**How much speed benefits will this bring?**

You can probably expect an loading time decrease to the next page anywhere from 100ms to 500ms depending on the situation.

However, the benefits with this plugin is less on the page loading time, but it's more on the page interactivity time - the next page will feel faster since the critical parts prioritized during loading and the visitor can interact with the page sooner. This is opposed to the visitor waiting on a white screen while waiting for the page, featured image, or Google Fonts to finish loading.

Since we're essentially loading parts of the next page within the current page, normal speed testing tools like YSlow, Pingdom Tools or GTMetrix will not show much difference - those only test the speed of the current page you're at. What you need is to test the speed from transitioning between one page to the next.

You can open an incognito window, disable caching and check the network tab while navigating through your site.

**Do I need to deactivate the current caching plugin I'm using?**

No. Do not replace your current caching plugin with this one, use this one together with your existing plugin.

**Can I use this with WP Rocket, W3 Total Cache, WP Super Cache, etc?**

Yes! You can use this plugin alongside those without any problems. This plugin is not meant to replace your current caching plugin.

== Screenshots ==

== Changelog ==

= 0.1 =

* First release
