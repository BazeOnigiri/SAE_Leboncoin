class HelpSequence {
    constructor(system, steps) {
        this.system = system;
        this.steps = steps;
        this.currentIndex = -1;
        this.timer = null;
        this.cleanupFn = null;
        this.activeBubble = null;
        this.running = false;
    }

    start() {
        if (this.steps.length === 0) return;
        this.running = true;
        this.system.registerSequence(this);
        this.next();
    }

    stop() {
        this.running = false;
        if (this.timer) clearTimeout(this.timer);
        if (this.cleanupFn) this.cleanupFn();
        if (this.activeBubble) {
            this.system.removeBubble(this.activeBubble);
            this.activeBubble = null;
        }
        this.system.unregisterSequence(this);
    }

    next() {
        if (!this.running) return;

        // Clean up previous step
        if (this.timer) clearTimeout(this.timer);
        if (this.cleanupFn) {
            this.cleanupFn();
            this.cleanupFn = null;
        }
        if (this.activeBubble) {
            this.system.removeBubble(this.activeBubble);
            this.activeBubble = null;
        }

        this.currentIndex++;
        if (this.currentIndex >= this.steps.length) {
            this.stop();
            return;
        }

        const step = this.steps[this.currentIndex];

        // Find target
        const target = document.querySelector(step.element);
        if (!target) {
            console.warn(`HelpSequence: Target '${step.element}' not found. Skipping.`);
            this.next();
            return;
        }

        // Show bubble
        this.activeBubble = this.system.createBubble(step.content, step.options || {}, () => this.stop());
        this.system.positionBubble(this.activeBubble, target);
        document.body.appendChild(this.activeBubble);

        // Handle Events
        // 1. Timer
        if (step.event && typeof step.event === 'string' && step.event.startsWith('timer:')) {
            const time = parseInt(step.event.split(':')[1]) || 3000;
            this.timer = setTimeout(() => this.next(), time);
        }
        // 2. DOM Event (e.g. click)
        else if (step.event) {
            const handler = (e) => {
                setTimeout(() => this.next(), 100);
            };
            target.addEventListener(step.event, handler);
            this.cleanupFn = () => target.removeEventListener(step.event, handler);
        }
    }
}

export class HelpSystem {
    constructor() {
        this.bubbles = new Set();
        this.sequences = new Set();
        this.init();
    }

    init() {
        // Global click listener to close all bubbles (unless inside a bubble)
        document.addEventListener('click', (e) => {
            let clickedInside = false;
            for (const bubble of this.bubbles) {
                if (bubble.contains(e.target)) {
                    clickedInside = true;
                    break;
                }
            }
            if (!clickedInside) {
                this.stopAll();
            }
        }, true);

        // Reposition on resize/scroll
        const updateAll = () => {
            for (const seq of this.sequences) {
                if (seq.activeBubble && seq.steps[seq.currentIndex]) {
                    const target = document.querySelector(seq.steps[seq.currentIndex].element);
                    if (target) this.positionBubble(seq.activeBubble, target);
                }
            }
        };

        window.addEventListener('resize', updateAll);
        window.addEventListener('scroll', updateAll, true);
    }

    startSequence(steps) {
        const seq = new HelpSequence(this, steps);
        seq.start();
        return seq;
    }

    /**
     * Legacy/Simpler API: Show a single bubble.
     * Implemented as a 1-step sequence for consistency.
     */
    pointTo(selector, content, options = {}) {
        return this.startSequence([{ element: selector, content: content, options: options }]);
    }

    // Alias for compatibility
    show(target, content, options = {}) {
        // Since we refactored, we prefer 'pointTo' with selector.
        // But if 'target' is a selector string, we can redirect.
        if (typeof target === 'string') {
            return this.pointTo(target, content, options);
        }
        console.warn("HelpSystem.show() designated for selector strings. Use pointTo() or startSequence().");
    }

    registerSequence(seq) {
        this.sequences.add(seq);
    }

    unregisterSequence(seq) {
        this.sequences.delete(seq);
    }

    removeBubble(bubble) {
        if (bubble && bubble.parentNode) {
            bubble.parentNode.removeChild(bubble);
        }
        this.bubbles.delete(bubble);
    }

    stopAll() {
        for (const seq of this.sequences) {
            seq.stop();
        }
        // Force cleanup of any stray bubbles
        for (const bubble of this.bubbles) {
            this.removeBubble(bubble);
        }
    }

    // Explicit global hide
    hide() {
        this.stopAll();
    }

    createBubble(content, options, onClose) {
        const bubble = document.createElement('div');
        const baseClasses = "fixed max-w-sm bg-white border border-orange-200 shadow-xl rounded-xl p-4 text-sm text-gray-700 animate-in fade-in zoom-in-95 duration-200";
        bubble.className = baseClasses;
        bubble.style.zIndex = "9999";

        bubble.innerHTML = `
            <div class="relative">
                <button class="close-btn absolute -top-2 -right-2 text-gray-400 hover:text-gray-600 rounded-full p-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="pr-4">
                    ${content}
                </div>
                <!-- Arrow element -->
                <div id="help-bubble-arrow" class="absolute w-3 h-3 bg-white border-l border-b border-orange-200 transform rotate-45"></div>
            </div>
        `;

        const btn = bubble.querySelector('.close-btn');
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (onClose) onClose();
        });

        this.bubbles.add(bubble);
        return bubble;
    }

    positionBubble(bubble, targetElement) {
        if (!bubble || !targetElement) return;

        const targetRect = targetElement.getBoundingClientRect();
        const bubbleRect = bubble.getBoundingClientRect();

        const arrow = bubble.querySelector('#help-bubble-arrow');
        const offset = 12;
        const headerHeight = 80;
        const spaceBelow = window.innerHeight - targetRect.bottom;
        const spaceAbove = targetRect.top - headerHeight;

        let top, left;
        let placement = 'bottom';

        if (spaceBelow < bubbleRect.height + offset && spaceAbove > bubbleRect.height + offset) {
            placement = 'top';
        }
        if (targetRect.top < headerHeight) {
            placement = 'bottom';
        }

        left = targetRect.left + (targetRect.width / 2) - (bubbleRect.width / 2);
        const padding = 10;
        if (left < padding) left = padding;
        if (left + bubbleRect.width > window.innerWidth - padding) {
            left = window.innerWidth - bubbleRect.width - padding;
        }

        if (placement === 'bottom') {
            top = targetRect.bottom + offset;
            if (arrow) {
                arrow.style.top = '-6px';
                arrow.style.bottom = 'auto';
                arrow.style.borderTop = '1px solid #fed7aa';
                arrow.style.borderLeft = '1px solid #fed7aa';
                arrow.style.borderBottom = 'none';
                arrow.style.borderRight = 'none';
            }
        } else {
            top = targetRect.top - bubbleRect.height - offset;
            if (arrow) {
                arrow.style.bottom = '-6px';
                arrow.style.top = 'auto';
                arrow.style.borderBottom = '1px solid #fed7aa';
                arrow.style.borderRight = '1px solid #fed7aa';
                arrow.style.borderTop = 'none';
                arrow.style.borderLeft = 'none';
            }
        }

        bubble.style.top = `${top}px`;
        bubble.style.left = `${left}px`;

        if (arrow) {
            const centerOfTargetRelativeToBubble = (targetRect.left + targetRect.width / 2) - left;
            let arrowLeft = centerOfTargetRelativeToBubble - 6;
            if (arrowLeft < 8) arrowLeft = 8;
            if (arrowLeft > bubbleRect.width - 20) arrowLeft = bubbleRect.width - 20;
            arrow.style.left = `${arrowLeft}px`;
        }
    }
}

window.HelpSystem = new HelpSystem();
