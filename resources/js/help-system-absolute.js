console.log('--- HELP SYSTEM ABSOLUTE MODULE LOADED ---');
export class AbsoluteHelpSystem {
    constructor() {
        this.activeBubble = null;
        this.currentSequence = null;
        this.currentIndex = 0;
        this.overlay = null;
        this.handleClickOutside = this.handleClickOutside.bind(this);
    }

    // ... (show method updates)

    handleClickOutside(event) {
        if (this.activeBubble && !this.activeBubble.contains(event.target)) {
            // Check if the click target is not part of the bubble structure
            this.stop();
        }
    }

    // ...

    removeBubble() {
        if (this.activeBubble) {
            this.activeBubble.remove();
            this.activeBubble = null;
            document.removeEventListener('click', this.handleClickOutside);
        }
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

        // Add click outside listener with a slight delay to avoid immediate trigger
        setTimeout(() => {
            document.addEventListener('click', this.handleClickOutside);
        }, 100);
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

    handleClickOutside(event) {
        if (this.activeBubble && !this.activeBubble.contains(event.target)) {
             // Avoid closing if clicking on navigation elements that might be re-rendering
             // But simplest is: if outside bubble, close.
            this.stop();
        }
    }

    removeBubble() {
        if (this.activeBubble) {
            this.activeBubble.remove();
            this.activeBubble = null;
            document.removeEventListener('click', this.handleClickOutside);
        }
    }

    stop() {
        this.removeBubble();
        this.currentSequence = null;
        this.currentIndex = 0;
    }


    init() {
        const path = window.location.pathname;
        if (path === '/' || path === '/home') {
            this.initHome();
        } else if (path === '/dashboard') {
            this.initDashboard();
        }
    }

    initHome() {
        setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Home Sequence ---');
            const steps = [];

            // 1. Barre de recherche (Main Body - Location Search)
            steps.push({
                x: 25, y: 28, 
                position: 'absolute',
                content: '<strong>Barre de recherche</strong><br>Trouvez rapidement ce que vous cherchez par ville ou région.',
                width: 300
            });

            // 2. Filtres (Index Page - Filters Bar)
            steps.push({
                x: 60, y: 28, 
                position: 'absolute',
                content: '<strong>Filtres</strong><br>Affinez votre recherche par prix, dates, etc.',
                width: 300
            });

            // 3. Sauvegarder la recherche (Content)
            steps.push({
                x: 22, y: 42, 
                position: 'absolute',
                content: '<strong>Sauvegarder la recherche</strong><br>Cliquez ici pour être notifié des nouvelles annonces !',
                width: 350
            });

            // 4. Mes recherches (Nav)
            steps.push({
                x: 55, y: 4, 
                position: 'fixed',
                content: '<strong>Mes recherches</strong><br>Retrouvez ici toutes vos alertes et recherches sauvegardées.',
                width: 300
            });

            // 5. Favoris (Nav)
            steps.push({
                x: 64, y: 4, 
                position: 'fixed',
                content: '<strong>Favoris</strong><br>Retrouvez ici tous vos coups de cœur sauvegardés.',
                width: 300
            });

            // 6. Auth (Login or Profile)
            const loginLink = document.getElementById('header-login-link');
            if (loginLink) {
                steps.push({
                    x: 72, y: 4, 
                    position: 'fixed',
                    content: '<strong>Se connecter</strong><br>Connectez-vous pour accéder à toutes les fonctionnalités.',
                    width: 300
                });
            } else {
                steps.push({
                    x: 72, y: 4, 
                    position: 'fixed',
                    content: '<strong>Mon Compte</strong><br>Gérez votre profil et vos annonces ici.',
                    width: 300
                });
            }

            // 7. Déposer une annonce (Connected only)
            const createBtn = document.getElementById('header-create-annonce-btn');
            if (createBtn) {
                steps.push({
                    x: 22, y: 4, 
                    position: 'fixed',
                    content: '<strong>Déposer une annonce</strong><br>Vendez vos biens en quelques clics.',
                    width: 300
                });
            }

            // 8. BotMan (Bottom Right)
            steps.push({
                x: 72, y: 82, 
                position: 'fixed',
                content: '<strong>Besoin d\'aide ?</strong><br>Discutez avec notre assistant virtuel BotMan en bas à droite.',
                width: 300
            });

            this.startSequence(steps);
        }, 1500);
    }

    initDashboard() {
        setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Dynamic Dashboard Sequence ---');
            const steps = [];

            // Helper to find absolute position from element
            const getPos = (el) => {
                const rect = el.getBoundingClientRect();
                // Center of element
                const x = ((rect.left + rect.width / 2) / window.innerWidth) * 100;
                // Top
                const y = ((rect.top + window.scrollY) / window.innerHeight) * 100;
                // Bottom
                const yBottom = ((rect.bottom + window.scrollY) / window.innerHeight) * 100;
                return { x, y, yBottom };
            };

            // Helper to prevent right overflow
            const getSafeX = (xPct) => {
                const bubbleWidth = 300; // px
                const margin = 20; // px
                // What % is (Width + Margin)?
                const reservedPct = ((bubbleWidth + margin) / window.innerWidth) * 100;
                const maxPct = 100 - reservedPct;
                return xPct > maxPct ? maxPct : xPct;
            };

            // 1. Identity (Avatar area)
            // Look for the big avatar container w-24 h-24
            const avatarEl = document.querySelector('.w-24.h-24');
            if (avatarEl) {
                const pos = getPos(avatarEl);
                steps.push({
                    x: getSafeX(pos.x), // Remove offset, stick to safe X
                    y: pos.yBottom + 2, // Move BELOW the avatar to avoid right-side squeeze
                    position: 'absolute',
                    content: '<strong>Votre Profil</strong><br>Retrouvez ici votre profil public (Nom, Avatar et évaluation).',
                    width: 300
                });
            }

            // 2. Porte-monnaie
            // Search for "Porte-monnaie" text
            const links = Array.from(document.querySelectorAll('h2, span, div'));
            const walletEl = links.find(el => el.innerText && el.innerText.trim() === 'Porte-monnaie');
            if (walletEl) {
                const card = walletEl.closest('.rounded-xl'); // The container card
                if (card) {
                    const pos = getPos(card);
                    steps.push({
                        x: getSafeX(pos.x), // Use generic safe clamp
                        y: pos.yBottom + 2, 
                        position: 'absolute',
                        content: '<strong>Porte-monnaie</strong><br>Consultez votre solde disponible et vos transactions en cours.',
                        width: 300
                    });
                }
            }

            // Helper for Grid Cards
            const addCardStep = (title, content) => {
                const headers = Array.from(document.querySelectorAll('h2'));
                const header = headers.find(h => h.innerText && h.innerText.trim() === title);
                if (header) {
                    const card = header.closest('a') || header.closest('.rounded-xl'); // Usually the card is an <a> tag
                    if (card) {
                        const pos = getPos(card);
                        steps.push({
                            x: getSafeX(pos.x), // Use generic safe clamp
                            y: pos.yBottom + 2, 
                            position: 'absolute',
                            content: `<strong>${title}</strong><br>${content}`,
                            width: 300
                        });
                    }
                }
            };

            addCardStep('Annonces', 'Gérez vos annonces : modification, suppression ou mise en avant.');
            addCardStep('Réservations', 'Retrouvez l\'historique de vos séjours et locations.');
            addCardStep('Profil', 'Mettez à jour vos informations publiques.');
            addCardStep('Paramètres', 'Modifiez vos informations privées (email, téléphone, adresse).');
            addCardStep('Connexion et sécurité', 'Gérez votre mot de passe et la sécurité de votre compte.');

            this.startSequence(steps);
        }, 1500);
    }
}

window.AbsoluteHelpSystem = new AbsoluteHelpSystem();
// Auto-start validation
if (document.readyState === 'complete') {
    window.AbsoluteHelpSystem.init();
} else {
    window.addEventListener('load', () => window.AbsoluteHelpSystem.init());
}
