import domReady from '@roots/sage/client/dom-ready';
import 'flowbite/dist/flowbite.js';
import './logoscroll.js';
import './arrowtop.js';

// import { Partytown } from '@builder.io/partytown/react';

// export function Head() {
//   return (
//     <>
//       <Partytown debug={true} forward={['dataLayer.push']} />
//     </>
//   );
// }

// import '@builder.io/partytown/integration/index.mjs'

// import { partytownSnippet } from '@builder.io/partytown/integration';

// const snippetText = partytownSnippet();

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

// Depois acrescentar "build": "bud build && node resources/scripts/copyPartytown.js", em package.json