{*
* WhatsApp Widget Template
* @author Your Name
* @copyright 2024
* @license MIT
*}

{if $widget_enabled}
<div id="whatsapp-widget" 
     class="whatsapp-widget {$widget_style} {$widget_position} {$widget_size}"
     data-phone="{$widget_phone|escape:'htmlall':'UTF-8'}"
     data-message="{$widget_message|escape:'htmlall':'UTF-8'}"
     data-product-url="{if isset($product_url)}{$product_url|escape:'htmlall':'UTF-8'}{/if}"
     data-hook="{$hook_name|escape:'htmlall':'UTF-8'}"
     style="{if $widget_color}background-color: {$widget_color|escape:'htmlall':'UTF-8'};{/if}">
    
    <div class="whatsapp-widget-button" onclick="openWhatsApp()">
        <svg class="whatsapp-icon" viewBox="0 0 24 24" width="24" height="24">
            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.690"/>
        </svg>
        {if $widget_style == 'inline'}
            <span class="whatsapp-text">{l s='WhatsApp' mod='whatsappwidget'}</span>
        {/if}
    </div>
    
    {if $widget_style == 'floating'}
        <div class="whatsapp-tooltip">
            {l s='Chat with us on WhatsApp' mod='whatsappwidget'}
        </div>
    {/if}
</div>

<script>
function openWhatsApp() {
    const widget = document.getElementById('whatsapp-widget');
    const phone = widget.dataset.phone;
    let message = widget.dataset.message || '';
    const productUrl = widget.dataset.productUrl;
    
    // Add product URL to message if available
    if (productUrl && productUrl.trim() !== '') {
        message += (message ? '\n\n' : '') + 'Product: ' + productUrl;
    }
    
    // Create WhatsApp URL
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    
    // Open WhatsApp
    window.open(whatsappUrl, '_blank');
    
    // Analytics tracking (optional)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'whatsapp_click', {
            'event_category': 'engagement',
            'event_label': widget.dataset.hook
        });
    }
}

// Add hover effects for floating widget
if (document.getElementById('whatsapp-widget').classList.contains('floating')) {
    const widget = document.getElementById('whatsapp-widget');
    const tooltip = widget.querySelector('.whatsapp-tooltip');
    
    widget.addEventListener('mouseenter', function() {
        if (tooltip) tooltip.style.opacity = '1';
    });
    
    widget.addEventListener('mouseleave', function() {
        if (tooltip) tooltip.style.opacity = '0';
    });
}
</script>
{/if}