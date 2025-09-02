/**
 * WhatsApp Widget - Modern Admin Panel JavaScript
 * Interactive features and real-time preview
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initWhatsAppAdmin();
    });

    function initWhatsAppAdmin() {
        // Initialize all components
        initColorPicker();
        initPreview();
        initFormValidation();
        initTooltips();
        initAnimations();
        initRealTimePreview();
    }

    // Color Picker Functionality
    function initColorPicker() {
        const colorInput = document.getElementById('WHATSAPP_WIDGET_COLOR');
        const colorPreview = document.querySelector('.whatsapp-color-preview');
        
        if (colorInput && colorPreview) {
            // Update preview when color changes
            colorInput.addEventListener('input', function() {
                colorPreview.style.backgroundColor = this.value;
                updateWidgetPreview();
            });
            
            // Set initial color
            colorPreview.style.backgroundColor = colorInput.value;
            
            // Click preview to open color picker
            colorPreview.addEventListener('click', function() {
                colorInput.click();
            });
        }
    }

    // Real-time Preview Updates
    function initRealTimePreview() {
        const inputs = [
            'WHATSAPP_WIDGET_ENABLED',
            'WHATSAPP_WIDGET_PHONE',
            'WHATSAPP_WIDGET_MESSAGE',
            'WHATSAPP_WIDGET_POSITION',
            'WHATSAPP_WIDGET_COLOR',
            'WHATSAPP_WIDGET_SIZE',
            'WHATSAPP_WIDGET_STYLE',
            'WHATSAPP_WIDGET_HOOK'
        ];

        inputs.forEach(function(inputId) {
            const element = document.getElementById(inputId);
            if (element) {
                element.addEventListener('change', updateWidgetPreview);
                element.addEventListener('input', debounce(updateWidgetPreview, 300));
            }
        });
    }

    // Update Widget Preview
    function updateWidgetPreview() {
        const preview = document.querySelector('.whatsapp-widget-preview');
        if (!preview) return;

        // Get current settings
        const enabled = document.getElementById('WHATSAPP_WIDGET_ENABLED')?.checked;
        const color = document.getElementById('WHATSAPP_WIDGET_COLOR')?.value || '#25D366';
        const size = document.getElementById('WHATSAPP_WIDGET_SIZE')?.value || 'medium';
        const position = document.getElementById('WHATSAPP_WIDGET_POSITION')?.value || 'bottom-right';
        const style = document.getElementById('WHATSAPP_WIDGET_STYLE')?.value || 'floating';

        // Update preview visibility
        if (!enabled) {
            preview.style.opacity = '0.3';
            preview.style.pointerEvents = 'none';
        } else {
            preview.style.opacity = '1';
            preview.style.pointerEvents = 'auto';
        }

        // Update preview color
        preview.style.backgroundColor = color;

        // Update preview size
        const sizes = {
            'small': '50px',
            'medium': '60px',
            'large': '70px'
        };
        const sizeValue = sizes[size] || '60px';
        preview.style.width = sizeValue;
        preview.style.height = sizeValue;

        // Update preview position
        const previewContainer = preview.parentElement;
        previewContainer.style.position = 'relative';
        
        if (style === 'floating') {
            preview.style.position = 'absolute';
            
            switch(position) {
                case 'bottom-right':
                    preview.style.bottom = '20px';
                    preview.style.right = '20px';
                    preview.style.top = 'auto';
                    preview.style.left = 'auto';
                    break;
                case 'bottom-left':
                    preview.style.bottom = '20px';
                    preview.style.left = '20px';
                    preview.style.top = 'auto';
                    preview.style.right = 'auto';
                    break;
                case 'top-right':
                    preview.style.top = '20px';
                    preview.style.right = '20px';
                    preview.style.bottom = 'auto';
                    preview.style.left = 'auto';
                    break;
                case 'top-left':
                    preview.style.top = '20px';
                    preview.style.left = '20px';
                    preview.style.bottom = 'auto';
                    preview.style.right = 'auto';
                    break;
            }
        } else {
            preview.style.position = 'relative';
            preview.style.top = 'auto';
            preview.style.right = 'auto';
            preview.style.bottom = 'auto';
            preview.style.left = 'auto';
            preview.style.margin = '20px auto';
        }

        // Add pulse animation for changes
        preview.classList.add('pulse');
        setTimeout(() => preview.classList.remove('pulse'), 600);
    }

    // Form Validation
    function initFormValidation() {
        const form = document.querySelector('form[name="whatsapp_widget_config"]');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('WHATSAPP_WIDGET_PHONE');
            const messageInput = document.getElementById('WHATSAPP_WIDGET_MESSAGE');

            // Validate phone number
            if (phoneInput && phoneInput.value) {
                const phone = phoneInput.value.replace(/\D/g, '');
                if (phone.length < 10) {
                    e.preventDefault();
                    showNotification('Lütfen geçerli bir telefon numarası girin.', 'error');
                    phoneInput.focus();
                    return;
                }
            }

            // Validate message
            if (messageInput && messageInput.value.length > 500) {
                e.preventDefault();
                showNotification('Mesaj 500 karakterden uzun olamaz.', 'error');
                messageInput.focus();
                return;
            }

            showNotification('Ayarlar kaydediliyor...', 'info');
        });
    }

    // Tooltips
    function initTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(function(element) {
            element.addEventListener('mouseenter', showTooltip);
            element.addEventListener('mouseleave', hideTooltip);
        });
    }

    function showTooltip(e) {
        const text = e.target.getAttribute('data-tooltip');
        if (!text) return;

        const tooltip = document.createElement('div');
        tooltip.className = 'whatsapp-tooltip';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;

        document.body.appendChild(tooltip);

        const rect = e.target.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';

        setTimeout(() => tooltip.style.opacity = '1', 10);

        e.target._tooltip = tooltip;
    }

    function hideTooltip(e) {
        if (e.target._tooltip) {
            e.target._tooltip.remove();
            delete e.target._tooltip;
        }
    }

    // Animations
    function initAnimations() {
        // Fade in cards on load
        const cards = document.querySelectorAll('.whatsapp-card');
        cards.forEach(function(card, index) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(function() {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add pulse animation class
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            .pulse {
                animation: pulse 0.6s ease;
            }
        `;
        document.head.appendChild(style);
    }

    // Notification System
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `whatsapp-notification whatsapp-notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;

        // Set background color based on type
        const colors = {
            'success': '#4CAF50',
            'error': '#F44336',
            'warning': '#FF9800',
            'info': '#2196F3'
        };
        notification.style.backgroundColor = colors[type] || colors.info;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.style.transform = 'translateX(0)', 10);

        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Utility Functions
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Phone number formatting
    function formatPhoneNumber(input) {
        const phone = input.value.replace(/\D/g, '');
        let formatted = '';
        
        if (phone.length > 0) {
            if (phone.startsWith('90')) {
                // Turkish format
                formatted = '+90 ' + phone.substring(2, 5) + ' ' + phone.substring(5, 8) + ' ' + phone.substring(8, 10) + ' ' + phone.substring(10, 12);
            } else {
                // International format
                formatted = '+' + phone;
            }
        }
        
        input.value = formatted.trim();
    }

    // Export for global access
    window.WhatsAppAdmin = {
        updatePreview: updateWidgetPreview,
        showNotification: showNotification,
        formatPhone: formatPhoneNumber
    };

})();