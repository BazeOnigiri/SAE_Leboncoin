import './bootstrap';
const overlay = document.getElementById('filter-overlay');
const panel = document.getElementById('filter-panel');
const header = document.getElementById('main-header');
// alert("COUCOU")
        function openFilters() {
            overlay.classList.remove('hidden');
            // alert("CA VA ?")
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
            }, 10);

            panel.classList.remove('translate-x-full');
            
            document.body.style.overflow = 'hidden';
        }

        function closeFilters() {
            panel.classList.add('translate-x-full');

            overlay.classList.add('opacity-0');

            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
        window.addEventListener('scroll', () => {
            if (window.scrollY > 1) {
                header.classList.add('shadow-md'); 
                header.classList.remove('border-gray-200'); 
                } else {
                header.classList.remove('shadow-md');
                header.classList.add('border-gray-200');
            }
        });
        window.openFilters = openFilters;
        window.closeFilters = closeFilters;



