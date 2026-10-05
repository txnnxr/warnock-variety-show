import './bootstrap';

import Alpine from 'alpinejs';
import photos from './photos';

window.Alpine = Alpine;

photos(Alpine);

Alpine.start();
