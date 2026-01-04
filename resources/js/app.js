import './bootstrap';

// Fonts are imported in css
// import "@fontsource/inter";
// import "@fontsource/outfit"; 

// Alpine
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Prism
// Prism
import Prism from 'prismjs';

// Expose Prism to window for plugins that depend on it
window.Prism = Prism;

// Plugins & Components
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-bash';
import 'prismjs/plugins/line-numbers/prism-line-numbers';
import 'prismjs/plugins/autoloader/prism-autoloader';

// Manually trigger highlight if needed, or let Prism auto-highlight
// Prism.highlightAll();
