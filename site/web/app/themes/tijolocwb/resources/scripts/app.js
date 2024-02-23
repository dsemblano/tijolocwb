import domReady from '@roots/sage/client/dom-ready';
import flowbite from 'flowbite';
import './arrowtop.js'

/**
 * Application entrypoint
 */
domReady(async () => {
  // ...
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
