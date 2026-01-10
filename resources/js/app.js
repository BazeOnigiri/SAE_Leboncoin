console.log('--- APP.JS LOADED ---');
import './bootstrap';
import './cookies';
import './help-system-absolute';


const overlay = document.getElementById('filter-overlay');
const panel = document.getElementById('filter-panel');
const header = document.getElementById('main-header');
window.addEventListener('scroll', () => {
    if (window.scrollY > 1) {
        header.classList.add('shadow-md');
        header.classList.remove('border-gray-200');
    } else {
        header.classList.remove('shadow-md');
        header.classList.add('border-gray-200');
    }
});



