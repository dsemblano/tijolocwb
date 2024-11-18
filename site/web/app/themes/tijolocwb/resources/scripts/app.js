import domReady from '@roots/sage/client/dom-ready';
import 'flowbite/dist/flowbite.js';
import './logoscroll.js';
import './arrowtop.js';

// const images = document.querySelectorAll('img');

// images.forEach(image => {
//   image.addEventListener('load', () => {
//     image.classList.remove('image-loading');
//   });
// });

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

// Menu overlay
const menuOverlay = document.getElementById('menu-overlay');
const menuToggle = document.getElementById('menu-toggle');
const menuClose = document.getElementById('menu-close');

menuToggle.addEventListener('click', toggleMenu);
menuClose.addEventListener('click', toggleMenu);

function toggleMenu() {
  menuOverlay.classList.toggle('menu-open');

  // Prevent body scrolling when menu is open
  if (menuOverlay.classList.contains('menu-open')) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
}

document.querySelectorAll('.menu-item-has-children > a').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent navigation
    const submenu = this.nextElementSibling;

    if (submenu && submenu.classList.contains('submenu')) {
      submenu.classList.toggle('hidden'); // Show/hide submenu
      submenu.classList.toggle('block'); // Toggle display
    }
  });
});


