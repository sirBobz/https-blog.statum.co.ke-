import './bootstrap';

// Fonts are imported in css
// import "@fontsource/inter";
// import "@fontsource/outfit"; 

// Alpine
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Prism
import Prism from 'prismjs';
import 'prismjs/components/prism-core';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-json';
import 'prismjs/plugins/line-numbers/prism-line-numbers';
import 'prismjs/plugins/autoloader/prism-autoloader';

// Manually trigger highlight if needed, or let Prism auto-highlight
// Prism.highlightAll();
