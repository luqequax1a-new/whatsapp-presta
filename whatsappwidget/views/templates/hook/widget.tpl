{*
* WhatsApp Widget Template
* Main widget display template
*}

{if $widget_enabled && $widget_visible}
<div id="whatsapp-widget" class="whatsapp-widget {$widget_position} {$widget_size} {if $dark_mode}dark{/if} {if $consent_required && !$has_consent}consent-required{/if}" 
     data-phone="{$phone|escape:'htmlall':'UTF-8'}" 
     data-message="{$message|escape:'htmlall':'UTF-8'}" 
     data-page-type="{$page_type|escape:'htmlall':'UTF-8'}"
     {if $working_hours_enabled}data-working-hours="{$working_hours|@json_encode|escape:'html':'UTF-8'}"{/if}
     {if $consent_required}data-consent-cookies="{$consent_cookies|escape:'htmlall':'UTF-8'}"{/if}
     role="button" 
     tabindex="0" 
     aria-label="{if $product_name}{$product_name|truncate:40:'...'|escape:'htmlall':'UTF-8'} - {/if}{l s='Chat on WhatsApp' mod='whatsappwidget'}"
     aria-describedby="whatsapp-widget-desc">
     
    {if $consent_required && !$has_consent}
        <!-- Consent Gate -->
        <div class="whatsapp-consent-gate">
            <div class="consent-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.488"/>
                </svg>
            </div>
            <div class="consent-content">
                <p class="consent-text">{l s='Enable WhatsApp widget to start chatting' mod='whatsappwidget'}</p>
                <button type="button" 
                        class="consent-enable-btn" 
                        onclick="whatsappWidget.enableConsent()"
                        aria-label="{l s='Enable WhatsApp widget for chatting' mod='whatsappwidget'}">
                    {l s='Enable' mod='whatsappwidget'}
                </button>
            </div>
        </div>
    {else}
        {if $working_hours_enabled && !$is_working_hours}
            <!-- Offline State -->
            <div class="whatsapp-offline">
                <div class="offline-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.488"/>
                        <line x1="4.22" y1="4.22" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="offline-content">
                    <p class="offline-text">{$offline_message|escape:'htmlall':'UTF-8'}</p>
                    {if $next_available_time}
                        <p class="next-available">{l s='Available: %s' sprintf=[$next_available_time] mod='whatsappwidget'}</p>
                    {/if}
                </div>
            </div>
        {else}
            <!-- Active Widget -->
            <div class="whatsapp-button" 
                 onclick="whatsappWidget.openChat()" 
                 onkeydown="whatsappWidget.handleKeydown(event)"
                 role="button"
                 tabindex="0"
                 aria-label="{if $product_name}{$product_name|truncate:40:'...'|escape:'htmlall':'UTF-8'} - {/if}{l s='Start WhatsApp chat' mod='whatsappwidget'}">
                <div class="button-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.488"/>
                    </svg>
                </div>
                {if $show_tooltip}
                    <div class="button-tooltip">
                        <span>{l s='Chat with us on WhatsApp' mod='whatsappwidget'}</span>
                    </div>
                {/if}
            </div>
        {/if}
    {/if}
    
    <!-- Pulse animation for attention -->
    {if !$consent_required || $has_consent}
        <div class="whatsapp-pulse" aria-hidden="true"></div>
    {/if}
    
    <!-- Screen reader description -->
    <div id="whatsapp-widget-desc" class="sr-only">
        {if $working_hours_enabled && !$is_working_hours}
            {l s='Currently offline. Available during working hours.' mod='whatsappwidget'}
        {else}
            {l s='Click to start a WhatsApp conversation with us.' mod='whatsappwidget'}
        {/if}
    </div>
</div>
{/if}