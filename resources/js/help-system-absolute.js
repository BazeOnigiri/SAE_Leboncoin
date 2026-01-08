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
        
        // Vertical Positioning Logic
        const topPx = (options.position === 'absolute') 
            ? (window.innerHeight * (y / 100)) 
            : (window.innerHeight * (y / 100)); // Essentially y% converted to px for consistency or keep %?
        
        // To support transform consistently, let's use px for top
        // If position is fixed, top is relative to viewport. If absolute, top is relative to document (handled by y including scroll)
        // Wait, for fixed: y is % of viewport. For absolute: y is % of document height? 
        // No, current implementation:
        // if (absolute) top = window.innerHeight * (y/100) + 'px' -> This assumes y was calculated relative to viewport height but represents absolute pixel value?
        // getAbsolutePos returns y = ((rect.top + scrollY) / window.innerHeight) * 100.
        // So y is "Screen Heights from Top of Document". 
        // So window.innerHeight * (y/100) = rect.top + scrollY. Correct.
        
        // If position is fixed: y = (rect.bottom / window.innerHeight) * 100.
        // So window.innerHeight * (y/100) = rect.bottom. Correct.

        bubble.style.top = (window.innerHeight * (y / 100)) + 'px';

        const gap = options.gap !== undefined ? options.gap : 15;
        if (options.placement === 'top') {
            // Move up by 100% of height + margin
            bubble.style.transform = `translateY(calc(-100% - ${gap}px))`;
        } else {
             // Default bottom
             bubble.style.marginTop = `${gap}px`; 
        }

        bubble.style.color = '#333';
        bubble.style.fontSize = '14px';
        bubble.style.display = 'block'; 
        bubble.style.visibility = 'visible';

        console.log('Bubble inline styles applied:', bubble.style.cssText);

        // Calculate Arrow Position
        // Default: 50% (Center of bubble)
        let arrowLeft = '50%';
        
        if (options.arrowPosition) {
             // Manual override (e.g. '20%')
             arrowLeft = options.arrowPosition;
        } else if (options.targetX !== undefined) {
             // targetX is Viewport %. x is Bubble Left Viewport %.
             // We need Arrow Left relative to Bubble Left in px.
             // ArrowX = (TargetX - BubbleX) converted to px.
             const diffPct = options.targetX - x;
             const diffPx = (diffPct / 100) * document.documentElement.clientWidth;
             
             // Clamp arrow to stay within bubble radius (optional but good)
             // Bubble width is known 'width'.
             // Min padding 10px.
             const minX = 10; 
             const maxX = width - 10;
             let safeArrowX = Math.max(minX, Math.min(diffPx, maxX));
             
             // Apply manual pixel offset if provided (to fine tune calculation)
             if (options.arrowOffset) {
                 safeArrowX += options.arrowOffset;
             }
             
             arrowLeft = safeArrowX + 'px';
        }

        const arrowStyle = `
            position: absolute;
            width: 0; 
            height: 0; 
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            left: ${arrowLeft};
            margin-left: -10px;
        `;
        
        // Arrow pointing down (for bubble on top) or up (for bubble on bottom)
        let arrowHtml = '';
        if (options.placement === 'top') {
            // Bubble is ABOVE, Arrow points DOWN
            arrowHtml = `
                <div style="${arrowStyle} bottom: -10px; border-top: 10px solid #ea580c;"></div>
                <div style="${arrowStyle} bottom: -7px; border-top: 10px solid #ffffff;"></div>
            `;
        } else {
            // Bubble is BELOW, Arrow points UP
            arrowHtml = `
                <div style="${arrowStyle} top: -10px; border-bottom: 10px solid #ea580c;"></div>
                <div style="${arrowStyle} top: -7px; border-bottom: 10px solid #ffffff;"></div>
            `;
        }

        // Inner Content
        bubble.innerHTML = `
            <div class="relative">
                ${arrowHtml}
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

        // Event Listeners (same as before)
        if (options.sequence) {
            const nextBtn = bubble.querySelector('.next-btn');
            if (nextBtn) nextBtn.addEventListener('click', () => this.next());

            const prevBtn = bubble.querySelector('.prev-btn');
            if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
        }

        document.body.appendChild(bubble);
        this.activeBubble = bubble;

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
            position: step.position || 'fixed',
            placement: step.placement || 'bottom', // Pass placement
            width: step.width,
            targetX: step.targetX, // Pass targetX for arrow positioning
            arrowPosition: step.arrowPosition, // Manual override
            arrowOffset: step.arrowOffset, // Manual offset
            gap: step.gap // Manual gap
        });
    }

    // ... next/prev/handleClickOutside/removeBubble/stop/init/getAbsolutePos/getSafeX ... 
    // (keeping them but needing to ensure replace works correctly, so including context if needed or just assuming function update)
    // Actually I need to reproduce the whole file content for replaced functions or block.

    // Let's assume the surrounding code up to initHome is fine. I'll replace from show() down to initMesRecherches.

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
        } else if (path.includes('/deposer-une-annonce')) {
            this.initCreateAnnonce();
        } else if (path.includes('/recherche')) {
            this.initMesRecherches();
        }
    }

    // Helper: Calculate absolute position % from element
    getAbsolutePos(el) {
        const rect = el.getBoundingClientRect();
        // Center of element
        const x = ((rect.left + rect.width / 2) / document.documentElement.clientWidth) * 100;
        // Top
        const y = ((rect.top + window.scrollY) / window.innerHeight) * 100;
        // Bottom
        const yBottom = ((rect.bottom + window.scrollY) / window.innerHeight) * 100;
        return { x, y, yBottom };
    }

    // Helper: Prevent bubble from overflowing right screen edge
    getSafeX(xPct, targetWidth = 300) {
        const bubbleWidth = targetWidth; // px
        const margin = 20; // px
        // What % is (Width + Margin)?
        const reservedPct = ((bubbleWidth + margin) / document.documentElement.clientWidth) * 100;
        const maxPct = 100 - reservedPct;
        return xPct > maxPct ? maxPct : xPct;
    }

    initHome() {
        // Wait for potential livewire/async loads
        setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Home Sequence (Dynamic) ---');
            const steps = [];

            // Helper to add step relative to dynamic element
            // Default offset shifted to 3vh for better clearance
            // Added isHeader param to determine if we should be Above or Below
            const addStep = (selector, title, text, width = 300, isHeader = false) => {
                let el = document.querySelector(selector);
                
                // Fallback for "Filtres" button which has no ID, find by text
                if (!el && selector === 'filter-btn') {
                     const buttons = Array.from(document.querySelectorAll('button'));
                     el = buttons.find(b => b.innerText && b.innerText.trim().includes('Filtres'));
                }

                if (el) {
                    const pos = this.getAbsolutePos(el);
                    const safeX = this.getSafeX(pos.x, width);
                    
                    if (isHeader) {
                        // Header -> Below
                        steps.push({
                            x: safeX,
                            y: pos.yBottom + 3,
                            position: 'fixed',
                            placement: 'bottom',
                            content: `<strong>${title}</strong><br>${text}`,
                            width: width
                        });
                    } else {
                        // Body -> Above
                         steps.push({
                            x: safeX,
                            y: pos.y, // Target Top for placement='top'
                            position: 'absolute',
                            placement: 'top',
                            content: `<strong>${title}</strong><br>${text}`,
                            width: width
                        });
                    }
                }
            };

            // 1. Barre de recherche (Body -> Above)
            const searchInput = document.getElementById('google-search-input');
            if (searchInput) {
                 const wrapper = searchInput.closest('div.relative'); 
                 const target = wrapper || searchInput;
                 const pos = this.getAbsolutePos(target);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.y, // Top
                    position: 'absolute',
                    placement: 'top',
                    content: '<strong>Barre de recherche</strong><br>Trouvez rapidement ce que vous cherchez par ville ou région.',
                    width: 300,
                    gap: 2 // Collées
                 });
            }

            // 2. Filtres (Body -> Above)
            // addStep('filter-btn', 'Filtres', 'Affinez votre recherche par prix, dates, etc.', 300, false);
            // Manual push for Step 2 to add arrowPosition
             const filterBtn = document.getElementById('filter-btn');
             if (filterBtn) {
                 const pos = this.getAbsolutePos(filterBtn);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.y, 
                    position: 'absolute',
                    placement: 'top',
                    content: '<strong>Filtres</strong><br>Affinez votre recherche par prix, dates, etc.',
                    width: 300,
                    arrowPosition: '70%', // Centre-droit
                    gap: 2 // Collées
                 });
             }

            // 3. Sauvegarder la recherche (Body -> Above)
             const saveBtn = document.getElementById('save-search-btn');
             if (saveBtn) {
                 const pos = this.getAbsolutePos(saveBtn);
                 steps.push({
                    x: this.getSafeX(pos.x, 350),
                    y: pos.y, 
                    position: 'absolute',
                    placement: 'top',
                    content: '<strong>Sauvegarder la recherche</strong><br>Cliquez ici pour être notifié des nouvelles annonces !',
                    width: 350,
                    arrowPosition: '20%', // Gauche
                    gap: 2 // Collées
                 });
             }

            // 4. Mes recherches (Nav)
             const searchLink = document.getElementById('header-searches-link');
            if (searchLink) {
                 const pos = this.getAbsolutePos(searchLink);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.yBottom,
                    targetX: pos.x,
                    position: 'fixed',
                    placement: 'bottom',
                    content: '<strong>Mes recherches</strong><br>Retrouvez ici toutes vos alertes et recherches sauvegardées.',
                    width: 300,
                    arrowOffset: -12, // Shift left
                    gap: 2
                 });
            }

            // 5. Favoris (Nav)
            const favLink = document.getElementById('header-favorites-link');
            if (favLink) {
                 const pos = this.getAbsolutePos(favLink);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.yBottom,
                    targetX: pos.x,
                    position: 'fixed',
                    placement: 'bottom',
                    content: '<strong>Favoris</strong><br>Retrouvez ici tous vos coups de cœur sauvegardés.',
                    width: 300,
                    arrowOffset: -12, // Shift left
                    gap: 2
                 });
            }

            // 6. Auth (Header -> Below)
            const loginLink = document.getElementById('header-login-link');
            if (loginLink) {
                const pos = this.getAbsolutePos(loginLink);
                steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.yBottom,
                    targetX: pos.x, // Pass target center
                    position: 'fixed',
                    placement: 'bottom',
                    content: '<strong>Se connecter</strong><br>Connectez-vous pour accéder à toutes les fonctionnalités.',
                    width: 300,
                    arrowOffset: -12, // Shift left
                    gap: 2
                });
            }

            // 7. Dashboard Access (Logged In)
            const dashboardLink = document.getElementById('header-user-dashboard-link');
            if (dashboardLink) {
                 const pos = this.getAbsolutePos(dashboardLink);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.yBottom,
                    targetX: pos.x,
                    position: 'fixed',
                    placement: 'bottom',
                    content: '<strong>Tableau de bord</strong><br>Accédez à votre espace personnel pour gérer vos annonces et votre profil.',
                    width: 300,
                    arrowOffset: -12, // Shift left
                    gap: 2
                 });
            }

            // 7. Déposer une annonce (Header -> Below)
            const createBtn = document.getElementById('header-create-annonce-btn');
            if (createBtn) {
                 const pos = this.getAbsolutePos(createBtn);
                 steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: pos.yBottom + 1, // Colle plus proche
                    position: 'fixed',
                    placement: 'bottom',
                    content: '<strong>Déposer une annonce</strong><br>Vendez vos biens en quelques clics.',
                    width: 300,
                    arrowPosition: '15%' // Fleche à gauche
                 });
            }

            // 8. BotMan (Bottom Right -> Above)
            // It's at bottom right, so "Above" is key.
            steps.push({
                x: 72, y: 92, 
                position: 'fixed',
                placement: 'top', 
                content: '<strong>Besoin d\'aide ?</strong><br>Discutez avec notre assistant virtuel BotMan en bas à droite.',
                width: 300,
                arrowPosition: '90%' // Droite
            });

            this.startSequence(steps);
        }, 1500);
    }

    initDashboard() {
        setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Dashboard Sequence ---');
            const steps = [];

            // Helper to determine placement
            const getPlacement = (y) => {
                 // If element is in top 25% of viewport, place bubble BELOW to avoid top overflow.
                 // Otherwise place ABOVE as requested.
                 return (y < 25) ? 'bottom' : 'top';
            };

            // 1. Identity (Avatar area)
            const avatarEl = document.querySelector('.w-24.h-24');
            if (avatarEl) {
                const pos = this.getAbsolutePos(avatarEl);
                const place = getPlacement(pos.y);
                steps.push({
                    x: this.getSafeX(pos.x, 300),
                    y: (place === 'top') ? pos.y : pos.yBottom,
                    position: 'absolute',
                    placement: place,
                    content: '<strong>Votre Profil</strong><br>Retrouvez ici votre profil public (Nom, Avatar et évaluation).',
                    width: 300,
                    gap: 2 // Collées
                });
            }

            // 2. Porte-monnaie
            const links = Array.from(document.querySelectorAll('h2, span, div'));
            const walletEl = links.find(el => el.innerText && el.innerText.trim() === 'Porte-monnaie');
            if (walletEl) {
                const card = walletEl.closest('.rounded-xl'); 
                if (card) {
                    const pos = this.getAbsolutePos(card);
                    const place = getPlacement(pos.y);
                    steps.push({
                        x: this.getSafeX(pos.x, 300), 
                        y: (place === 'top') ? pos.y : pos.yBottom,
                        position: 'absolute',
                        placement: place,
                        content: '<strong>Porte-monnaie</strong><br>Consultez votre solde disponible et vos transactions en cours.',
                        width: 300,
                        gap: 2 // Collées
                    });
                }
            }

            // Grid Cards
            const addCardStep = (title, content) => {
                const headers = Array.from(document.querySelectorAll('h2'));
                const header = headers.find(h => h.innerText && h.innerText.trim() === title);
                if (header) {
                    const card = header.closest('a') || header.closest('.rounded-xl');
                    if (card) {
                        const pos = this.getAbsolutePos(card);
                        const place = getPlacement(pos.y);
                        steps.push({
                            x: this.getSafeX(pos.x, 300),
                            y: (place === 'top') ? pos.y : pos.yBottom,
                            position: 'absolute',
                            placement: place,
                            content: `<strong>${title}</strong><br>${content}`,
                            width: 300,
                            gap: 2 // Collées
                        });
                    }
                }
            };

            addCardStep('Annonces', 'Gérez vos annonces : modification, suppression ou mise en avant.');
            addCardStep('Réservations', 'Retrouvez l\'historique de vos séjours et locations.');
            addCardStep('Profil', 'Visualiser votre profil public');
            addCardStep('Paramètres', 'Modifiez vos informations privées (email, téléphone, adresse).');
            addCardStep('Connexion et sécurité', 'Gérez votre mot de passe et la sécurité de votre compte.');

            this.startSequence(steps);
        }, 1500);
    }

    initCreateAnnonce() {
         setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Create Annonce Sequence ---');
            const steps = [];
            
            // Helper to add step from selector
            // All inputs -> Above
            const addStep = (selector, title, text) => {
                const el = document.querySelector(selector);
                if (el) {
                    const pos = this.getAbsolutePos(el);
                    steps.push({
                        x: this.getSafeX(pos.x, 300),
                        y: pos.y, // Top
                        position: 'absolute',
                        placement: 'top',
                        content: `<strong>${title}</strong><br>${text}`,
                        width: 300
                    });
                }
            };

            // 3. Localisation (Search input - x-model="query")
            addStep('input[x-model="query"]', 'Autocomplétion Adresse', 'Commencez par saisir l\'adresse et <strong>cliquez sur une suggestion</strong> pour remplir automatiquement la ville et le code postal.');

            this.startSequence(steps);

         }, 1500);
    }
    
    initMesRecherches() {
        setTimeout(() => {
           console.log('--- AbsoluteHelpSystem: Building Mes Recherches Sequence ---');
           const steps = [];

           // Look for the "Voir" button
           // Body -> Above
           const links = Array.from(document.querySelectorAll('a'));
           const voirBtn = links.find(el => el.innerText && el.innerText.trim() === 'Voir');

           if (voirBtn) {
               const pos = this.getAbsolutePos(voirBtn);
               
               steps.push({
                   x: this.getSafeX(pos.x - 5, 300), 
                   y: pos.y, // Top
                   position: 'absolute',
                   placement: 'top',
                   content: '<strong>Relancer la recherche</strong><br>Cliquez sur "Voir" pour relancer cette recherche avec tous vos critères sauvegardés.',
                   width: 300
               });
           }
           
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
