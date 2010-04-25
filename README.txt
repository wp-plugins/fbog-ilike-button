=== Facebook I Like Button ===
Contributors: raduboncea
Tags: like button, social plugin,social, facebook, open graph, sharing, share, post, posts, page, pages, plugin, button, I like, I recommend
Requires at least: 2.0
Tested up to: 2.9.2
Stable tag: trunk
Author URI: http://raduboncea.ro/about/

A light implementation of the Facebook Open Graph: I Like Button social plugin.


== Description ==

The plugin is a light implementation of the new Facebook - <a href="http://developers.facebook.com/docs/reference/plugins/like">I Like Button social plugin</a>.
It implements both **iframe** and **XFBML**. Defaults to iframe for stability. Choose XFBML for true experience and performance.

The Like button enables readers to make connections to your pages and share content back to their friends on Facebook with one click.

The plugin is highly customizable:

* Choose platform: iframe for lame, XFBML for geeks
* Choose disposition: top, bottom
* Set styling for DIV container around the iframe
* Dimensions: width, height
* Layout Style: standard or button count
* Fonts: tahoma, arial, verdana, lucida grande, segoe ui, trebuchet ms
* Color Scheme: light or dark
* Show/Hide faces
* Verb to display: like or recommend

== Installation ==

1. Upload `fbog-ilike-button` to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. Configure it in `Settings` > `FB I Like Button`

== Frequently Asked Questions ==

= Where can I change the options? =

Go to `Settings` > `FB I Like Button`. 

= How can I force the I Like Button to appear/disappear in individual posts and pages? =

You can overide the settings as per page/post by typing the following code in your post/page: `<!--fbilike-->` if you wish to display the button or `<!--nofbilike-->` if you wish to hide it.

= How can I position the button to left/right relative to content? =

Use the option Container Style and style the div around the iframe. Eg. if you want to position the button to the right write something like
`float:right; margin:0px;`. But pay attention not to mess other elements and their position. Use clear:top,bottom, left carefully.

= Should I use XFBML? =

The default option is the iframe. XFBML was recently launched. However I recommend XFBML because is more handy, fully asynchrone and will offer more features in the future.

== Changelog ==

= 1.2 =

* Added support for XFBML. XFBML is more handy and allows much more features to be developed in the future 
* Addded ID attribute to container around the facebook content

= 1.1 =

* Added ability to style the DIV around the iframe.


== Screenshots ==

Screenshots are available at: [raduboncea.ro](http://raduboncea.ro/scripts/i-like-button-plugin/#screenshots)
