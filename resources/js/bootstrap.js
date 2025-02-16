import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import { createIcons, icons } from 'lucide';
import * as lucide from "lucide";

document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons({icons});
});

window.createLucideIcons = createIcons; // Ensure icons refresh after Livewire updates

document.addEventListener('livewire:init', () => {
    lucide.createIcons({icons});
    window.createLucideIcons({icons});
});
