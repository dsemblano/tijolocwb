import domReady from '@roots/sage/client/dom-ready';
import 'flowbite/dist/flowbite.js';
import './logoscroll.js';
import './arrowtop.js';

const images = document.querySelectorAll('img');

images.forEach(image => {
  image.addEventListener('load', () => {
    image.classList.remove('image-loading');
  });
});

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

// Menu overlay
const menuOverlay = document.getElementById('menu-overlay');
const menuToggle = document.getElementById('menu-toggle');
const menuClose = document.getElementById('menu-close');

// Toggle the main menu visibility
menuToggle.addEventListener('click', toggleMenu);
menuClose.addEventListener('click', toggleMenu);

// Submenu toggle logic
document.addEventListener('DOMContentLoaded', () => {
  // Handle submenu toggle button clicks
  const submenuToggles = document.querySelectorAll('.submenu-toggle');
  
  submenuToggles.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent default button behavior
      
      const parentLi = this.closest('li'); // Get the parent <li>
      const submenu = parentLi.querySelector('.submenu'); // Find the submenu

      if (submenu) {
        submenu.classList.toggle('hidden'); // Toggle visibility
        submenu.classList.toggle('block'); // Ensure proper display when shown

        // Accessibility: Update aria-expanded
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
      }
    });
  });

  // Allow parent link (`a`) to remain navigable
  const parentLinks = document.querySelectorAll('.menu-item-has-children > a');

  parentLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      // Do not prevent default; allow navigation
    });
  });
});

// Toggle the menu overlay
function toggleMenu() {
  menuOverlay.classList.toggle('menu-open');

  // Prevent body scrolling when menu is open
  if (menuOverlay.classList.contains('menu-open')) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
}
