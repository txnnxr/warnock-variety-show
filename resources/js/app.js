import './bootstrap';

import Alpine from 'alpinejs';
import lightbox from './lightbox';

window.Alpine = Alpine;

Alpine.data('lightbox', lightbox);

Alpine.start();
