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
        // Create and Display Bubble
        this.removeBubble(); // Ensure previous bubble is removed
        this.activeBubble = document.createElement('div');
        const bubble = this.activeBubble;

        // Store current options for updates
        this.currentOptions = options;
        this.currentX = x;
        this.currentY = y;

        bubble.className = "bg-white p-6 rounded-xl shadow-2xl border border-orange-100 z-[9999] text-sm text-gray-700 animate-in fade-in zoom-in duration-300";
        bubble.style.position = options.position || 'fixed';
        bubble.style.zIndex = '999999';
        bubble.style.backgroundColor = '#ffffff'; // White background
        bubble.style.border = '2px solid #ea580c'; // Orange border
        bubble.style.borderRadius = '12px';
        bubble.style.padding = '16px'; // Keep padding for consistency with className
        bubble.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'; // Keep shadow for consistency
        const width = options.width || 300; // Define width here
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
        // this.activeBubble = bubble; // Already set above

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

        // Handle resize to update positions smoothly
        // Remove existing listeners if any
        if (this.handleResize) {
            window.removeEventListener('resize', this.handleResize);
            window.removeEventListener('scroll', this.handleResize);
            this.handleResize = null;
        }

        this.handleResize = () => {
            if (this.currentSequence && this.activeBubble) {
                this.updatePosition();
            }
        };
        window.addEventListener('resize', this.handleResize);
        window.addEventListener('scroll', this.handleResize); // Also handle scroll for absolute items

        this.renderStep();
    }

    // Helper: Calculate absolute position % from element
    getAbsolutePos(el) {
        const rect = el.getBoundingClientRect();
        // Center of element
        const x = ((rect.left + rect.width / 2) / document.documentElement.clientWidth) * 100;

        // Document Relative (for absolute positioning) include ScrollY
        const docY = ((rect.top + window.scrollY) / window.innerHeight) * 100;
        const docYBottom = ((rect.bottom + window.scrollY) / window.innerHeight) * 100;

        // Viewport Relative (for fixed positioning) exclude ScrollY
        const viewY = (rect.top / window.innerHeight) * 100;
        const viewYBottom = (rect.bottom / window.innerHeight) * 100;

        return { x, docY, docYBottom, viewY, viewYBottom };
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

    // New: Update position without re-rendering DOM
    updatePosition() {
        if (!this.currentSequence || !this.activeBubble) return;

        const step = this.currentSequence[this.currentIndex];
        let x = step.x;
        let y = step.y;
        let targetX = step.targetX;
        let placement = step.placement || 'bottom';
        let width = step.width || 300;
        const positionType = step.position || 'fixed';

        // Recalculate if element exists
        if (step.element && document.body.contains(step.element)) {
            const pos = this.getAbsolutePos(step.element);

            if (step.smartPlacement) {
                // Check viewport Y for smart placement decision
                placement = (pos.viewY < 25) ? 'bottom' : 'top';
            }

            // Select correct Y based on position type (Fixed vs Absolute)
            if (positionType === 'absolute') {
                // Use Document coordinates
                y = (placement === 'top') ? pos.docY : pos.docYBottom;
            } else {
                // Use Viewport coordinates
                y = (placement === 'top') ? pos.viewY : pos.viewYBottom;
            }

            x = this.getSafeX(pos.x, width);
            targetX = pos.x;
        }

        // Apply updates to activeBubble style
        const bubble = this.activeBubble;
        bubble.style.position = positionType;
        bubble.style.left = x + '%';
        bubble.style.top = (window.innerHeight * (y / 100)) + 'px';

        // Update Transform/Margin based on new placement/gap
        const gap = step.gap !== undefined ? step.gap : 15;
        if (placement === 'top') {
            bubble.style.transform = `translateY(calc(-100% - ${gap}px))`;
            bubble.style.marginTop = '0px';
        } else {
            bubble.style.transform = 'none';
            bubble.style.marginTop = `${gap}px`;
        }

        // Update Arrow
        const arrow = bubble.querySelector('.absolute.w-4.h-4');
        if (arrow) {
            // Recalc arrow position
            let arrowLeft = '50%';
            if (step.arrowPosition) {
                arrowLeft = step.arrowPosition;
            } else if (targetX !== undefined) {
                const diffPct = targetX - x;
                const diffPx = (diffPct / 100) * document.documentElement.clientWidth;
                const minX = 10;
                const maxX = width - 10;
                let safeArrowX = Math.max(minX, Math.min(diffPx, maxX));
                if (step.arrowOffset) {
                    safeArrowX += step.arrowOffset;
                }
                arrowLeft = safeArrowX + 'px';
            }
            arrow.style.left = arrowLeft;

            // Start basic arrow style reset
            arrow.className = `absolute w-4 h-4 bg-white border-l border-t border-orange-100 transform rotate-45`;

            // Adjust based on placement
            if (placement === 'top') {
                arrow.style.bottom = '-9px'; // reduced slightly to overlap border? no standard -8px usually
                arrow.style.bottom = '-8px';
                arrow.style.top = 'auto';
                arrow.classList.remove('border-l', 'border-t');
                arrow.classList.add('border-r', 'border-b'); // Points down
            } else {
                arrow.style.top = '-8px';
                arrow.style.bottom = 'auto';
                arrow.classList.remove('border-r', 'border-b');
                arrow.classList.add('border-l', 'border-t'); // Points up
            }
        }
    }

    renderStep() {
        if (!this.currentSequence) return;

        const step = this.currentSequence[this.currentIndex];

        // Dynamic Position Calculation
        let x = step.x;
        let y = step.y;
        let targetX = step.targetX;
        let placement = step.placement || 'bottom';
        let width = step.width || 300;
        const positionType = step.position || 'fixed';

        // If step has an element reference, recalculate position now (Responsive!)
        if (step.element && document.body.contains(step.element)) {
            const pos = this.getAbsolutePos(step.element);

            // Dashboard Smart Placement Logic Check
            if (step.smartPlacement) {
                // Check viewport Y for smart placement
                placement = (pos.viewY < 25) ? 'bottom' : 'top';
            }

            // Recalculate Y based on placement and type
            if (positionType === 'absolute') {
                y = (placement === 'top') ? pos.docY : pos.docYBottom;
            } else {
                y = (placement === 'top') ? pos.viewY : pos.viewYBottom;
            }

            x = this.getSafeX(pos.x, width);
            targetX = pos.x;
        }

        this.show(x, y, step.content, {
            sequence: true,
            step: this.currentIndex + 1,
            total: this.currentSequence.length,
            hasPrev: this.currentIndex > 0,
            isLast: this.currentIndex === this.currentSequence.length - 1,
            position: positionType,
            placement: placement,
            width: width,
            targetX: targetX,
            arrowPosition: step.arrowPosition,
            arrowOffset: step.arrowOffset,
            gap: step.gap
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

    initHome() {
        // Wait for potential livewire/async loads
        setTimeout(() => {
            console.log('--- AbsoluteHelpSystem: Building Home Sequence (Dynamic) ---');
            const steps = [];

            // Helper to add step
            const addStep = (idOrSelector, title, text, options = {}) => {
                let el = document.getElementById(idOrSelector);
                if (!el) el = document.querySelector(idOrSelector);

                // Fallback for Filter button by text if not found
                if (!el && idOrSelector === 'filter-btn') {
                    const buttons = Array.from(document.querySelectorAll('button'));
                    el = buttons.find(b => b.innerText && b.innerText.trim().includes('Filtres'));
                }

                if (el) {
                    console.log(`Adding step for ${idOrSelector}`, options);
                    const pos = this.getAbsolutePos(el);
                    const width = options.width || 300;

                    // Determine Logic based on options
                    const isHeader = options.isHeader || false;
                    const position = options.position || (isHeader ? 'fixed' : 'absolute');
                    const placement = options.placement || (isHeader ? 'bottom' : 'top');

                    // Initial Y calculation (will be refined by renderStep/update)
                    let y;
                    if (position === 'absolute') {
                        y = (placement === 'top') ? pos.docY : pos.docYBottom;
                    } else {
                        y = (placement === 'top') ? pos.viewY : pos.viewYBottom;
                    }

                    // Header items usually need a bit of offset if they are 'bottom'
                    if (isHeader && placement === 'bottom') {
                        // Add slight offset for header items?
                        // Original code had: y: pos.viewYBottom (which is bottom of element)
                        // And gap handled the rest.
                    }

                    steps.push({
                        x: this.getSafeX(pos.x, width),
                        y: y,
                        position: position,
                        placement: placement,
                        content: `<strong>${title}</strong><br>${text}`,
                        width: width,
                        gap: options.gap || (isHeader ? 2 : 2),
                        element: el,
                        arrowOffset: options.arrowOffset,
                        arrowPosition: options.arrowPosition
                    });
                } else {
                    console.warn(`Element not found for step: ${idOrSelector}`);
                }
            };

            // 1. Barre de recherche (Body -> Above)
            addStep('google-search-input', 'Barre de recherche', 'Trouvez rapidement ce que vous cherchez par ville ou région.', {
                position: 'absolute',
                placement: 'top'
            });

            // 2. Filtres (Body -> Above)
            addStep('filter-btn', 'Filtres', 'Affinez votre recherche par prix, dates, etc.', {
                position: 'absolute',
                placement: 'top'
            });

            // 3. Sauvegarder la recherche (Body -> Above) - SCROLLS WITH PAGE
            addStep('save-search-btn', 'Sauvegarder la recherche', 'Cliquez ici pour être notifié des nouvelles annonces !', {
                width: 350,
                position: 'absolute',
                placement: 'top'
            });

            // 4. Mes recherches (Nav) - FIXED TO VIEWPORT
            addStep('header-searches-link', 'Mes recherches', 'Retrouvez ici toutes vos alertes et recherches sauvegardées.', {
                isHeader: true,
                position: 'fixed',
                placement: 'bottom',
                arrowOffset: -12
            });

            // 5. Favoris (Nav)
            addStep('header-favorites-link', 'Favoris', 'Retrouvez ici tous vos coups de cœur sauvegardés.', {
                isHeader: true,
                position: 'fixed',
                placement: 'bottom',
                arrowOffset: -12
            });

            // 6. Auth (Header -> Below)
            addStep('header-login-link', 'Se connecter', 'Connectez-vous pour accéder à toutes les fonctionnalités.', {
                isHeader: true,
                position: 'fixed',
                placement: 'bottom',
                arrowOffset: -12
            });

            // 7. Dashboard Access (Logged In)
            addStep('header-user-dashboard-link', 'Tableau de bord', 'Accédez à votre espace personnel pour gérer vos annonces et votre profil.', {
                isHeader: true,
                position: 'fixed',
                placement: 'bottom',
                arrowOffset: -12
            });

            // 7. Déposer une annonce (Header -> Below)
            addStep('header-create-annonce-btn', 'Déposer une annonce', 'Vendez vos biens en quelques clics.', {
                isHeader: true,
                position: 'fixed',
                placement: 'bottom',
                arrowPosition: '15%'
            });

            // 8. BotMan via #chatbot-toggle
            addStep('chatbot-toggle', 'Besoin d\'aide ?', 'Discutez avec <strong>Llama</strong>, notre assistant virtuel intelligent.', {
                position: 'fixed',
                placement: 'top',
                arrowPosition: '90%',
                gap: 10
            });

            console.log(`Starting sequence with ${steps.length} steps`);
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
                    gap: 2, // Collées
                    element: avatarEl,
                    smartPlacement: true
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
                        gap: 2, // Collées
                        element: card, // Use card logic
                        smartPlacement: true
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
                            gap: 2, // Collées
                            element: card,
                            smartPlacement: true
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
                        width: 300,
                        element: el // Store element for resize
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
                    width: 300,
                    element: voirBtn // Store element for resize
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
