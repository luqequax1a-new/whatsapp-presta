/**
 * WhatsApp Widget Frontend JavaScript
 * Vanilla JS implementation for WhatsApp widget functionality
 * @version 1.0.0
 */

(function() {
    'use strict';

    // DataLayer guard for stores without GA/Tag Manager
    window.dataLayer = window.dataLayer || [];

    // Widget configuration
    let widgetConfig = {};
    let isInitialized = false;
    
    // Debug mode detection
    const urlParams = new URLSearchParams(window.location.search);
    const debugMode = urlParams.get('ww_debug') === '1' || localStorage.getItem('ww_debug') === '1';
    
    // Debug logging function
    function debugLog(message, data = null) {
        if (debugMode) {
            if (data) {
                console.log(`[WW Debug] ${message}`, data);
            } else {
                console.log(`[WW Debug] ${message}`);
            }
        }
    }

    // Main widget object
    window.whatsappWidget = {
        init: function(config) {
            if (isInitialized) return;
            
            widgetConfig = config || {};
            widgetConfig.debug = debugMode; // Set debug mode in config
            
            debugLog('Widget initializing', widgetConfig);
            
            this.setupEventListeners();
            this.checkWorkingHours();
            this.checkConsent();
            
            debugLog('Widget initialized successfully');
            isInitialized = true;
        },

        /**
         * Setup event listeners for the widget
         */
        setupEventListeners: function() {
            // Handle keyboard navigation
            document.addEventListener('keydown', function(e) {
                const widget = document.getElementById('whatsapp-widget');
                if (!widget) return;
                
                if (e.key === 'Enter' || e.key === ' ') {
                    if (e.target === widget) {
                        e.preventDefault();
                        whatsappWidget.openChat();
                    }
                }
            });

            // Handle consent changes
            window.addEventListener('storage', function(e) {
                if (e.key && e.key.includes('consent')) {
                    whatsappWidget.checkConsent();
                }
            });
        },

        /**
         * Handle keyboard events for accessibility
         */
        handleKeydown: function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.openChat();
            }
        },

        /**
         * Open WhatsApp chat
         */
        openChat: function() {
            debugLog('Opening WhatsApp chat');
            
            const widget = document.getElementById('whatsapp-widget');
            if (!widget) return;

            // Check consent first
            if (!this.hasConsent()) {
                debugLog('Consent not granted, showing consent prompt');
                this.showConsentPrompt();
                return;
            }

            // Check working hours
            if (widgetConfig.workingHoursEnabled && !this.isWorkingHours()) {
                debugLog('Outside working hours, showing offline message');
                this.showOfflineMessage();
                return;
            }

            const phone = widget.dataset.phone;
            const message = this.generateMessage();
            const url = this.generateWhatsAppUrl(phone, message);

            // Track event with enhanced data
            this.trackEvent('whatsapp_click', {
                event: 'whatsapp_click',
                page_type: widget.dataset.pageType,
                product_id: widgetConfig.productId || '',
                product_name: widgetConfig.productName || '',
                currency: widgetConfig.currency || '',
                price: widgetConfig.productPrice || '',
                message_length: message.length
            });

            // Open WhatsApp with popup blocker compatibility
            this.openWhatsAppLink(url);
        },

        /**
         * Generate WhatsApp URL based on device type with fallback policy
         */
        generateWhatsAppUrl: function(phone, message) {
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            const encodedMessage = encodeURIComponent(message);
            
            // Add UTM parameters
            const utmParams = '&utm_source=whatsappwidget&utm_medium=chat&utm_campaign=contact';
            
            // Force wa.me if configured or as fallback policy
            if (widgetConfig.forceWaMe || !this.isDesktopDevice()) {
                return `https://wa.me/${cleanPhone}?text=${encodedMessage}${utmParams}`;
            }

            // Use web.whatsapp.com only for confirmed desktop devices
            return `https://web.whatsapp.com/send?phone=${cleanPhone}&text=${encodedMessage}${utmParams}`;
        },

        /**
         * Generate message with token replacement
         */
        generateMessage: function() {
            const widget = document.getElementById('whatsapp-widget');
            let message = widget.dataset.message || '';

            // Replace tokens
            const tokens = {
                '{page_url}': window.location.href,
                '{shop_name}': widgetConfig.shopName || '',
                '{currency}': widgetConfig.currency || '',
                '{product_name}': widgetConfig.productName || '',
                '{product_ref}': widgetConfig.productRef || '',
                '{price}': widgetConfig.productPrice || ''
            };

            Object.keys(tokens).forEach(token => {
                message = message.replace(new RegExp(token, 'g'), tokens[token]);
            });

            return message;
        },

        /**
         * Detect if device is mobile (fallback to wa.me for uncertain cases)
         */
        isMobileDevice: function() {
            const userAgent = navigator.userAgent.toLowerCase();
            const mobileKeywords = [
                'android', 'webos', 'iphone', 'ipad', 'ipod', 
                'blackberry', 'windows phone', 'mobile', 'tablet'
            ];
            
            return mobileKeywords.some(keyword => userAgent.includes(keyword)) ||
                   (window.innerWidth <= 768) ||
                   ('ontouchstart' in window);
        },

        /**
         * Detect if device is definitely desktop (conservative approach)
         */
        isDesktopDevice: function() {
            const userAgent = navigator.userAgent.toLowerCase();
            const mobileKeywords = [
                'android', 'webos', 'iphone', 'ipad', 'ipod', 
                'blackberry', 'windows phone', 'mobile', 'tablet'
            ];
            
            // Only return true for confirmed desktop
            return !mobileKeywords.some(keyword => userAgent.includes(keyword)) &&
                   window.innerWidth > 1024 &&
                   !('ontouchstart' in window) &&
                   navigator.maxTouchPoints === 0;
        },

        /**
         * Open WhatsApp link with popup blocker compatibility
         */
        openWhatsAppLink: function(url) {
            // Try creating a temporary link element (popup blocker friendly)
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.style.display = 'none';
            
            document.body.appendChild(link);
            
            try {
                // Simulate click on the link
                link.click();
                
                // Log success for debugging
                debugLog('WhatsApp link opened via a[target=_blank]', { url });
            } catch (error) {
                // Fallback to window.open
                debugLog('Fallback to window.open', { error: error.message, url });
                window.open(url, '_blank', 'noopener,noreferrer');
            } finally {
                // Clean up
                document.body.removeChild(link);
            }
        },

        /**
         * Check if current time is within working hours
         */
        isWorkingHours: function() {
            if (!widgetConfig.workingHoursEnabled) return true;
            
            // Use store timezone if available
            const timezone = widgetConfig.timezone || 'Europe/Istanbul';
            let now;
            
            try {
                // Create date in store timezone
                now = new Date(new Date().toLocaleString('en-US', { timeZone: timezone }));
                debugLog('Working hours check using timezone', { timezone, localTime: now.toLocaleString() });
            } catch (error) {
                // Fallback to local time if timezone is invalid
                now = new Date();
                debugLog('Timezone fallback to local time', { error: error.message, timezone });
            }
            
            const currentDay = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
            const currentTime = now.getHours() * 60 + now.getMinutes();
            
            const workingHours = widgetConfig.workingHours || {};
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const todayName = dayNames[currentDay];
            
            if (!workingHours[todayName] || !workingHours[todayName].enabled) {
                debugLog('Not a working day', { day: todayName, workingHours: workingHours[todayName] });
                return false;
            }
            
            const startTime = this.timeToMinutes(workingHours[todayName].start || '09:00');
            const endTime = this.timeToMinutes(workingHours[todayName].end || '18:00');
            
            const isWorking = currentTime >= startTime && currentTime <= endTime;
            debugLog('Working hours check result', {
                day: todayName,
                currentTime: Math.floor(currentTime / 60) + ':' + String(currentTime % 60).padStart(2, '0'),
                startTime: workingHours[todayName].start,
                endTime: workingHours[todayName].end,
                isWorking
            });
            
            return isWorking;
        },

        /**
         * Convert time string to minutes
         */
        timeToMinutes: function(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        },

        /**
         * Check working hours and update widget visibility
         */
        checkWorkingHours: function() {
            if (!widgetConfig.workingHoursEnabled) return;
            
            const widget = document.getElementById('whatsapp-widget');
            if (!widget) return;
            
            if (!this.isWorkingHours()) {
                widget.classList.add('offline');
            } else {
                widget.classList.remove('offline');
            }
        },

        /**
         * Check if user has given consent with debug logging
         */
        hasConsent: function() {
            if (!widgetConfig.consentRequired) return true;
            
            const consentCookies = widgetConfig.consentCookies || [];
            let hasAnyConsent = false;
            let debugInfo = [];
            
            for (const cookieName of consentCookies) {
                // Check localStorage first
                const localValue = localStorage.getItem(cookieName);
                const cookieValue = this.getCookie(cookieName);
                
                const localConsent = localValue === 'true' || localValue === '1';
                const cookieConsent = cookieValue === 'true' || cookieValue === '1';
                
                debugInfo.push({
                    name: cookieName,
                    localStorage: localValue,
                    cookie: cookieValue,
                    localConsent,
                    cookieConsent
                });
                
                if (localConsent || cookieConsent) {
                    hasAnyConsent = true;
                }
            }
            
            // Debug logging for consent issues
            if (!hasAnyConsent && consentCookies.length > 0) {
                console.info('[WW] Consent not satisfied. Checked keys:', debugInfo);
            }
            
            debugLog('Consent check completed', { hasConsent: hasAnyConsent, details: debugInfo });
            
            return hasAnyConsent;
        },

        /**
         * Get cookie value
         */
        getCookie: function(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        },

        /**
         * Check consent and update widget visibility
         */
        checkConsent: function() {
            const widget = document.getElementById('whatsapp-widget');
            if (!widget) return;
            
            if (widgetConfig.consentRequired && !this.hasConsent()) {
                widget.classList.add('consent-required');
            } else {
                widget.classList.remove('consent-required');
            }
        },

        /**
         * Enable consent (called from consent button)
         */
        enableConsent: function() {
            const consentCookies = widgetConfig.consentCookies || ['whatsapp_consent'];
            
            // Set consent in localStorage
            consentCookies.forEach(cookieName => {
                localStorage.setItem(cookieName, 'true');
            });
            
            // Update widget
            this.checkConsent();
            
            // Track consent event
            this.trackEvent('whatsapp_consent_granted');
            
            // Show success message
            this.showToast('WhatsApp widget enabled successfully!');
        },

        /**
         * Show consent prompt
         */
        showConsentPrompt: function() {
            this.showToast('Please enable WhatsApp widget to start chatting.');
        },

        /**
         * Show offline message when clicked during non-working hours
         */
        showOfflineMessage: function() {
            if (!widgetConfig.workingHoursEnabled) return;
            
            // Get next working time info
            const nextWorkingInfo = this.getNextWorkingTime();
            let offlineMessage = widgetConfig.offlineMessage || 'We are currently offline. Please leave a message and we will get back to you soon!';
            
            if (nextWorkingInfo) {
                offlineMessage += ' ' + nextWorkingInfo;
            }
            
            debugLog('Showing offline message', { message: offlineMessage, nextWorkingInfo });
            this.showToast(offlineMessage, 'warning', 7000);
        },

        /**
         * Get next working time information
         */
        getNextWorkingTime: function() {
            if (!widgetConfig.workingHoursEnabled || !widgetConfig.workingHours) {
                return null;
            }
            
            const timezone = widgetConfig.timezone || 'Europe/Istanbul';
            let now;
            
            try {
                now = new Date(new Date().toLocaleString('en-US', { timeZone: timezone }));
            } catch (error) {
                now = new Date();
            }
            
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const workingHours = widgetConfig.workingHours;
            
            // Check if we're in the same day but before working hours
            const currentDay = now.getDay();
            const todayName = dayNames[currentDay];
            const currentTime = now.getHours() * 60 + now.getMinutes();
            
            if (workingHours[todayName] && workingHours[todayName].enabled) {
                const startTime = this.timeToMinutes(workingHours[todayName].start);
                if (currentTime < startTime) {
                    return `We'll be available today from ${workingHours[todayName].start}.`;
                }
            }
            
            // Find next working day
            for (let i = 1; i <= 7; i++) {
                const nextDay = (currentDay + i) % 7;
                const nextDayName = dayNames[nextDay];
                
                if (workingHours[nextDayName] && workingHours[nextDayName].enabled) {
                    const dayDisplayName = nextDayName.charAt(0).toUpperCase() + nextDayName.slice(1);
                    const startTime = workingHours[nextDayName].start;
                    
                    if (i === 1) {
                        return `We'll be available tomorrow from ${startTime}.`;
                    } else {
                        return `We'll be available on ${dayDisplayName} from ${startTime}.`;
                    }
                }
            }
            
            return null;
        },

        /**
         * Show toast notification
         */
        showToast: function(message, type = 'info', duration = 3000) {
            // Remove existing toast
            const existingToast = document.querySelector('.whatsapp-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `whatsapp-toast whatsapp-toast-${type}`;
            
            // Create toast content with icon
            const icon = this.getToastIcon(type);
            toast.innerHTML = `
                <div class="whatsapp-toast-content">
                    <span class="whatsapp-toast-icon">${icon}</span>
                    <span class="whatsapp-toast-message">${message}</span>
                </div>
            `;
            
            // Set styles based on type
            const colors = {
                'info': '#25d366',
                'warning': '#ff9800',
                'success': '#4caf50',
                'error': '#f44336'
            };
            
            toast.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: ${colors[type] || colors.info};
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 14px;
                z-index: 10001;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideUp 0.3s ease-out;
            `;
            
            document.body.appendChild(toast);
            
            // Remove after duration
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'slideDown 0.3s ease-in';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
            
            debugLog('Toast shown', { message, type, duration });
        },
        
        /**
         * Get icon for toast type
         */
        getToastIcon: function(type) {
            const icons = {
                'info': '&#8505;',
                'warning': '&#9888;',
                'success': '&#10004;',
                'error': '&#10006;'
            };
            return icons[type] || icons.info;
        },

        /**
         * Track events to dataLayer
         */
        trackEvent: function(eventName, data = {}) {
            if (!widgetConfig.dataLayerEnabled) return;
            
            const eventData = {
                event: widgetConfig.dataLayerEvent || 'whatsapp_widget',
                widget_action: eventName,
                ...data
            };
            
            // Push to dataLayer
            if (window.dataLayer && Array.isArray(window.dataLayer)) {
                window.dataLayer.push(eventData);
            }
            
            // Debug logging
            debugLog('Event tracked', eventData);
        }
    };

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Widget config will be injected by PHP
            if (window.whatsappWidgetConfig) {
                whatsappWidget.init(window.whatsappWidgetConfig);
            }
        });
    } else {
        // DOM is already ready
        if (window.whatsappWidgetConfig) {
            whatsappWidget.init(window.whatsappWidgetConfig);
        }
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideUp {
            from { transform: translateX(-50%) translateY(100%); opacity: 0; }
            to { transform: translateX(-50%) translateY(0); opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateX(-50%) translateY(0); opacity: 1; }
            to { transform: translateX(-50%) translateY(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

})();