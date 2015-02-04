Seadragon output for Deep Zoom Composer:

- embed.html contains a Seadragon embed that uses Silverlight if the user has it installed, otherwise falls back to JavaScript. Works in all browsers.

- library.html contains a Seadragon viewer that uses the Seadragon Ajax library. This viewer is scriptable and has a JavaScript API. Works in all browsers.

In general, if you want to only show the image, stick with the embed. If you want to take this image and develop a full web app around it, use the library.

For more info, visit http://seadragon.com/developer/.