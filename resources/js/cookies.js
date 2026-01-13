document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'cookie_consent';
    const BANNER_ID = 'cookie-banner';
    const SETTINGS_ID = 'cookie-settings-modal';

    // UI Elements
    const banner = document.getElementById(BANNER_ID);
    const settingsModal = document.getElementById(SETTINGS_ID);

    // Toggles
    const toggleAll = document.getElementById('toggle-all');
    const toggleMaps = document.getElementById('toggle-maps');
    const toggleChatbot = document.getElementById('toggle-chatbot');
    const optionalCookies = document.querySelectorAll('.optional-cookie');

    // Details Accordion
    const toggleDetailsBtn = document.getElementById('toggle-details-btn');
    const detailsList = document.getElementById('cookies-details-list');
    const detailsIcon = document.getElementById('details-icon');

    // Check saved state
    let consentState = JSON.parse(localStorage.getItem(STORAGE_KEY));

    // Default state if null: everything false
    const defaultState = {
        maps: false,
        chatbot: false,
        timestamp: null
    };

    const init = () => {
        if (!consentState) {
            showBanner();
        } else {
            applyConsent(consentState);
        }

        bindEvents();
    };

    const showBanner = () => {
        banner?.classList.remove('hidden');
    };

    const hideBanner = () => {
        banner?.classList.add('hidden');
    };

    const openSettings = () => {
        if (settingsModal) {
            settingsModal.classList.remove('hidden');
            // Sync toggles with current state or default
            const state = consentState || defaultState;
            updateToggles(state);
        }
    };

    const closeSettings = () => {
        settingsModal?.classList.add('hidden');
    };

    const updateToggles = (state) => {
        if (toggleMaps) toggleMaps.checked = !!state.maps;
        if (toggleChatbot) toggleChatbot.checked = !!state.chatbot;

        // Sync "All" toggle
        syncAllToggle();
    };

    const syncAllToggle = () => {
        if (!toggleAll) return;
        const allChecked = Array.from(optionalCookies).every(cb => cb.checked);
        toggleAll.checked = allChecked;
    };

    const toggleAllOptional = (checked) => {
        optionalCookies.forEach(cb => {
            cb.checked = checked;
        });
    };

    const saveConsent = (isGlobalAccept = null) => {
        let newState;

        if (isGlobalAccept === true) {
            newState = { maps: true, chatbot: true };
        } else if (isGlobalAccept === false) {
            newState = { maps: false, chatbot: false };
        } else {
            // From Settings
            newState = {
                maps: toggleMaps?.checked || false,
                chatbot: toggleChatbot?.checked || false
            };
        }

        newState.timestamp = new Date().toISOString();
        consentState = newState;

        localStorage.setItem(STORAGE_KEY, JSON.stringify(consentState));
        applyConsent(consentState);
        hideBanner();
        closeSettings();
    };

    const applyConsent = (state) => {
        // Handle Maps
        if (state.maps) {
            enableMaps();
        } else {
            disableMaps();
        }

        // Handle Chatbot
        if (state.chatbot) {
            enableChatbot();
        } else {
            disableChatbot();
        }
    };

    // --- Script Management ---

    const enableMaps = () => {
        const mapsScript = document.querySelector('script[data-managed="google-maps"]');
        if (mapsScript && mapsScript.type !== 'text/javascript') {
            const newScript = document.createElement('script');
            newScript.src = mapsScript.getAttribute('data-src');
            newScript.async = true;
            newScript.defer = true;
            newScript.setAttribute('data-managed', 'google-maps-active');
            newScript.id = 'google-maps-active-script'; // for removal identifying
            newScript.onload = () => {
                window.dispatchEvent(new CustomEvent('google-maps-loaded'));
            };
            mapsScript.parentNode.replaceChild(newScript, mapsScript);
        }
        // If already active (re-consent), nothing to do.
    };

    const disableMaps = () => {
        // Simple blocking on load. Runtime disabling is complex without reload.
    };

    const enableChatbot = () => {
        const chatContainer = document.getElementById('chatbot-container');
        if (chatContainer) chatContainer.style.display = 'block';
    };

    const disableChatbot = () => {
        const chatContainer = document.getElementById('chatbot-container');
        if (chatContainer) chatContainer.style.display = 'none';
    };

    // --- Event Bindings ---

    const bindEvents = () => {
        // Banner Buttons
        document.getElementById('cookie-accept-all')?.addEventListener('click', () => saveConsent(true));
        document.getElementById('cookie-reject-all')?.addEventListener('click', () => saveConsent(false));
        document.getElementById('cookie-settings-btn')?.addEventListener('click', openSettings);

        // Settings Modal Buttons
        document.getElementById('settings-save-btn')?.addEventListener('click', () => saveConsent()); // Uses toggles
        document.getElementById('settings-reject-all')?.addEventListener('click', () => {
            toggleAllOptional(false);
            saveConsent(false);
        });
        document.getElementById('settings-close-btn')?.addEventListener('click', closeSettings);
        document.getElementById('settings-backdrop')?.addEventListener('click', closeSettings);

        // Toggles interactions
        toggleAll?.addEventListener('change', (e) => toggleAllOptional(e.target.checked));
        optionalCookies.forEach(cb => {
            cb.addEventListener('change', syncAllToggle);
        });

        // Details Accordion
        toggleDetailsBtn?.addEventListener('click', () => {
            const isHidden = detailsList.classList.contains('hidden');
            if (isHidden) {
                detailsList.classList.remove('hidden');
                detailsIcon.classList.add('rotate-180');
            } else {
                detailsList.classList.add('hidden');
                detailsIcon.classList.remove('rotate-180');
            }
        });

        // Listen for open event (from footer or elsewhere)
        window.addEventListener('open-cookie-settings', openSettings);
    };

    init();
});
