console.log('--- HELP SYSTEM ABSOLUTE MODULE LOADED ---');
export class AbsoluteHelpSystem {
    constructor() {
        this.activeBubble = null;
        this.currentSequence = null;
        this.currentIndex = 0;
        this.overlay = null;
    }

    /**
     * Shows a bubble at specific coordinates
     * @param {number} x - Left position in percentage (0-100)
     * @param {number} y - Top position in percentage (0-100)
     * @param {string} content - HTML content
     * @param {object} options - Optional settings (width, placement)
     */
    show(x, y, content, options = {}) {
        console.log('AbsoluteHelpSystem.show called', { x, y, content });
        this.removeBubble();
        
        const bubble = document.createElement('div');
        // Explicit inline styles to guarantee visibility
        const width = options.width || 300;

        bubble.style.position = options.position || 'fixed';
        bubble.style.zIndex = '999999';
        bubble.style.backgroundColor = '#ffffff'; // White background
        bubble.style.border = '2px solid #ea580c'; // Orange border
        bubble.style.borderRadius = '12px';
        bubble.style.padding = '16px';
        bubble.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        bubble.style.width = width + 'px';
        bubble.style.left = x + '%';
        
        if (options.position === 'absolute') {
            // For absolute positioning, treat y as % of VIEWPORT height, but projected onto the DOCUMENT top
            // This ensures it starts at the right visual spot on load (assuming top scroll) but scrolls away
            bubble.style.top = (window.innerHeight * (y / 100)) + 'px';
        } else {
            // Default fixed positioning
            bubble.style.top = y + '%';
        }
        bubble.style.color = '#333';
        bubble.style.fontSize = '14px';
        bubble.style.display = 'block'; // Ensure it's not hidden
        bubble.style.visibility = 'visible'; // Ensure it's visible

        // Debug: forcing red border to see it clearly if white usage failed
        // bubble.style.border = '4px solid red'; 

        console.log('Bubble inline styles applied:', bubble.style.cssText);

        // Inner Content
        bubble.innerHTML = `
            <div class="relative">

                <div class="pr-2">
                    ${content}
                </div>
                <!-- Navigation buttons if in sequence -->
                ${options.sequence ? `
                    <div class="mt-3 flex justify-between items-center border-t border-gray-100 pt-2">
                        <span class="text-xs text-gray-400">${options.step} / ${options.total}</span>
                        <div class="flex gap-2">
                            ${options.hasPrev ? `<button class="prev-btn text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded">Précédent</button>` : ''}
                            <button class="next-btn text-xs px-2 py-1 bg-[#ea580c] text-white hover:bg-[#c2410c] rounded shadow-sm">
                                ${options.isLast ? 'Terminer' : 'Suivant'}
                            </button>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;

        // Event Listeners


        if (options.sequence) {
            const nextBtn = bubble.querySelector('.next-btn');
            if (nextBtn) nextBtn.addEventListener('click', () => this.next());

            const prevBtn = bubble.querySelector('.prev-btn');
            if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
        }

        document.body.appendChild(bubble);
        this.activeBubble = bubble;
    }

    /**
     * Starts a sequence of steps
     * @param {Array} steps - Array of objects { x, y, content }
     */
    startSequence(steps) {
        console.log('AbsoluteHelpSystem.startSequence called with', steps);
        if (!steps || steps.length === 0) return;
        
        this.currentSequence = steps;
        this.currentIndex = 0;
        this.renderStep();
    }

    renderStep() {
        if (!this.currentSequence) return;

        const step = this.currentSequence[this.currentIndex];
        this.show(step.x, step.y, step.content, {
            sequence: true,
            step: this.currentIndex + 1,
            total: this.currentSequence.length,
            hasPrev: this.currentIndex > 0,
            isLast: this.currentIndex === this.currentSequence.length - 1,
            position: step.position || 'fixed' // Pass positioning preference
        });
    }

    next() {
        if (!this.currentSequence) return;
        
        if (this.currentIndex < this.currentSequence.length - 1) {
            this.currentIndex++;
            this.renderStep();
        } else {
            this.stop();
        }
    }

    prev() {
        if (!this.currentSequence) return;

        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.renderStep();
        }
    }

    removeBubble() {
        if (this.activeBubble) {
            this.activeBubble.remove();
            this.activeBubble = null;
        }
    }

    stop() {
        this.removeBubble();
        this.currentSequence = null;
        this.currentIndex = 0;
    }


    initDemo() {
        // Simple check to see if we are on a page that needs the demo
        // We can check for a specific element unique to the list page, e.g., the save search button or filter panel
        const saveSearchBtn = document.body.innerText.includes('Sauvegarder la recherche'); 
        // Or better, just check if we are on the right URL mechanism or just run it if elements exist.
        
        // Let's rely on finding the specific elements we want to point to relative to:
        // But since we use absolute coordinates, we just need to know if we are on the "List" page.
        // We can assume if "Sauvegarder la recherche" text is present/visible, we show it?
        // Or check URL?
        
        // For now, let's just wait 1s and trigger if we aren't in a specific 'no-help' state
        setTimeout(() => {
             console.log('--- AbsoluteHelpSystem: Auto-initiating Demo Sequence ---');
            this.startSequence([
                {
                    x: 22, y: 8, 
                    position: 'absolute', // Scrolls with page as requested
                    content: '<strong>C\'est parti !</strong><br>Cliquez sur "Déposer une annonce" pour vendre vos biens.',
                    width: 300
                },
                { 
                    x: 22, y: 42, 
                    position: 'absolute', // Scrolls with content
                    content: '<strong>Sauvegarder la recherche</strong><br>Cliquez ici pour être notifié des nouvelles annonces !',
                    width: 350
                }
            ]);
        }, 2000);
    }
}

window.AbsoluteHelpSystem = new AbsoluteHelpSystem();
// Auto-start validation
if (document.readyState === 'complete') {
    window.AbsoluteHelpSystem.initDemo();
} else {
    window.addEventListener('load', () => window.AbsoluteHelpSystem.initDemo());
}
