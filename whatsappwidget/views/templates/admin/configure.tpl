<div class="panel">
    <div class="panel-heading">
        <i class="icon-whatsapp"></i>
        {l s='WhatsApp Widget Configuration' mod='whatsappwidget'}
    </div>
    
    <div class="panel-body">
        <div class="alert alert-info">
            <p><strong>{l s='Advanced WhatsApp Widget' mod='whatsappwidget'}</strong></p>
            <p>{l s='Configure your WhatsApp widget with advanced features including consent management, working hours, and performance optimization.' mod='whatsappwidget'}</p>
        </div>
        
        <form id="configuration_form" class="defaultForm form-horizontal" action="{$smarty.server.REQUEST_URI}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="{$csrf_token}" />
            
            <!-- Basic Settings -->
            <div class="form-wrapper">
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Enable or disable the WhatsApp widget' mod='whatsappwidget'}">
                            {l s='Enable Widget' mod='whatsappwidget'}
                        </span>
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_ENABLED" id="WHATSAPP_WIDGET_ENABLED_on" value="1" {if $config_values.WHATSAPP_WIDGET_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_ENABLED_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_ENABLED" id="WHATSAPP_WIDGET_ENABLED_off" value="0" {if !$config_values.WHATSAPP_WIDGET_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_ENABLED_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3 required">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='WhatsApp phone number in E.164 format (e.g., +905551112233)' mod='whatsappwidget'}">
                            {l s='Phone Number' mod='whatsappwidget'}
                        </span>
                    </label>
                    <div class="col-lg-9">
                        <input type="text" name="WHATSAPP_WIDGET_PHONE" value="{$config_values.WHATSAPP_WIDGET_PHONE|escape:'html':'UTF-8'}" class="form-control" placeholder="+905551112233" required>
                        <p class="help-block">{l s='Enter phone number in E.164 format (country code + number)' mod='whatsappwidget'}</p>
                    </div>
                </div>
            </div>
            
            <!-- Message Templates -->
            <fieldset>
                <legend><i class="icon-comment"></i> {l s='Message Templates' mod='whatsappwidget'}</legend>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Default message for general pages. Available tokens: {page_url}, {shop_name}, {currency}' mod='whatsappwidget'}">
                            {l s='Default Message' mod='whatsappwidget'}
                        </span>
                    </label>
                    <div class="col-lg-9">
                        <textarea name="WHATSAPP_WIDGET_DEFAULT_MESSAGE" class="form-control" rows="3" maxlength="1000">{$config_values.WHATSAPP_WIDGET_DEFAULT_MESSAGE|escape:'html':'UTF-8'}</textarea>
                        <p class="help-block">
                            {l s='Available tokens:' mod='whatsappwidget'} <code>{literal}{page_url}{/literal}</code>, <code>{literal}{shop_name}{/literal}</code>, <code>{literal}{currency}{/literal}</code>
                        </p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Message template for product pages. Additional tokens: {product_name}, {product_ref}, {price}, {product_url}' mod='whatsappwidget'}">
                            {l s='Product Page Message' mod='whatsappwidget'}
                        </span>
                    </label>
                    <div class="col-lg-9">
                        <textarea name="WHATSAPP_WIDGET_PRODUCT_MESSAGE" class="form-control" rows="3" maxlength="1000">{$config_values.WHATSAPP_WIDGET_PRODUCT_MESSAGE|escape:'html':'UTF-8'}</textarea>
                        <p class="help-block">
                            {l s='Additional tokens:' mod='whatsappwidget'} <code>{literal}{product_name}{/literal}</code>, <code>{literal}{product_ref}{/literal}</code>, <code>{literal}{price}{/literal}</code>, <code>{literal}{product_url}{/literal}</code>
                        </p>
                    </div>
                </div>
            </fieldset>
            
            <!-- Visibility Settings -->
            <fieldset>
                <legend><i class="icon-eye"></i> {l s='Visibility Settings' mod='whatsappwidget'}</legend>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Show on Pages' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_PAGES[]" value="home" {if 'home'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_PAGES}checked{/if}> {l s='Home Page' mod='whatsappwidget'}</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_PAGES[]" value="category" {if 'category'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_PAGES}checked{/if}> {l s='Category Pages' mod='whatsappwidget'}</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_PAGES[]" value="product" {if 'product'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_PAGES}checked{/if}> {l s='Product Pages' mod='whatsappwidget'}</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_PAGES[]" value="cart" {if 'cart'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_PAGES}checked{/if}> {l s='Cart Page' mod='whatsappwidget'}</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_PAGES[]" value="checkout" {if 'checkout'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_PAGES}checked{/if}> {l s='Checkout Pages' mod='whatsappwidget'}</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Show on Devices' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_DEVICES[]" value="desktop" {if 'desktop'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_DEVICES}checked{/if}> {l s='Desktop' mod='whatsappwidget'}</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="WHATSAPP_WIDGET_VISIBILITY_DEVICES[]" value="mobile" {if 'mobile'|in_array:$config_values.WHATSAPP_WIDGET_VISIBILITY_DEVICES}checked{/if}> {l s='Mobile & Tablet' mod='whatsappwidget'}</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            
            <!-- Appearance Settings -->
            <fieldset>
                <legend><i class="icon-paint-brush"></i> {l s='Appearance' mod='whatsappwidget'}</legend>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Position' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <select name="WHATSAPP_WIDGET_POSITION" class="form-control">
                            <option value="bottom-right" {if $config_values.WHATSAPP_WIDGET_POSITION == 'bottom-right'}selected{/if}>{l s='Bottom Right' mod='whatsappwidget'}</option>
                            <option value="bottom-left" {if $config_values.WHATSAPP_WIDGET_POSITION == 'bottom-left'}selected{/if}>{l s='Bottom Left' mod='whatsappwidget'}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Theme Color' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <input type="color" name="WHATSAPP_WIDGET_THEME_COLOR" value="{$config_values.WHATSAPP_WIDGET_THEME_COLOR|escape:'html':'UTF-8'}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Button Size' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <select name="WHATSAPP_WIDGET_BUTTON_SIZE" class="form-control">
                            <option value="sm" {if $config_values.WHATSAPP_WIDGET_BUTTON_SIZE == 'sm'}selected{/if}>{l s='Small' mod='whatsappwidget'}</option>
                            <option value="md" {if $config_values.WHATSAPP_WIDGET_BUTTON_SIZE == 'md'}selected{/if}>{l s='Medium' mod='whatsappwidget'}</option>
                            <option value="lg" {if $config_values.WHATSAPP_WIDGET_BUTTON_SIZE == 'lg'}selected{/if}>{l s='Large' mod='whatsappwidget'}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Border Radius' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <select name="WHATSAPP_WIDGET_BORDER_RADIUS" class="form-control">
                            <option value="md" {if $config_values.WHATSAPP_WIDGET_BORDER_RADIUS == 'md'}selected{/if}>{l s='Medium' mod='whatsappwidget'}</option>
                            <option value="lg" {if $config_values.WHATSAPP_WIDGET_BORDER_RADIUS == 'lg'}selected{/if}>{l s='Large (Rounded)' mod='whatsappwidget'}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Dark Mode' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_DARK_MODE" id="WHATSAPP_WIDGET_DARK_MODE_on" value="1" {if $config_values.WHATSAPP_WIDGET_DARK_MODE}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_DARK_MODE_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_DARK_MODE" id="WHATSAPP_WIDGET_DARK_MODE_off" value="0" {if !$config_values.WHATSAPP_WIDGET_DARK_MODE}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_DARK_MODE_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
            </fieldset>
            
            <!-- Working Hours -->
            <fieldset>
                <legend><i class="icon-clock-o"></i> {l s='Working Hours' mod='whatsappwidget'}</legend>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Enable Working Hours' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED" id="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED_on" value="1" {if $config_values.WHATSAPP_WIDGET_WORKING_HOURS_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED" id="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED_off" value="0" {if !$config_values.WHATSAPP_WIDGET_WORKING_HOURS_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                
                <div class="working-hours-settings" {if !$config_values.WHATSAPP_WIDGET_WORKING_HOURS_ENABLED}style="display:none;"{/if}>
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='Working Days' mod='whatsappwidget'}
                        </label>
                        <div class="col-lg-9">
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="monday" {if 'monday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Monday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="tuesday" {if 'tuesday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Tuesday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="wednesday" {if 'wednesday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Wednesday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="thursday" {if 'thursday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Thursday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="friday" {if 'friday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Friday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="saturday" {if 'saturday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Saturday' mod='whatsappwidget'}</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="WHATSAPP_WIDGET_WORKING_DAYS[]" value="sunday" {if 'sunday'|in_array:$config_values.WHATSAPP_WIDGET_WORKING_DAYS}checked{/if}> {l s='Sunday' mod='whatsappwidget'}</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='Working Hours' mod='whatsappwidget'}
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="time" name="WHATSAPP_WIDGET_START_TIME" value="{$config_values.WHATSAPP_WIDGET_START_TIME|escape:'html':'UTF-8'}" class="form-control">
                                    <p class="help-block">{l s='Start Time' mod='whatsappwidget'}</p>
                                </div>
                                <div class="col-lg-6">
                                    <input type="time" name="WHATSAPP_WIDGET_END_TIME" value="{$config_values.WHATSAPP_WIDGET_END_TIME|escape:'html':'UTF-8'}" class="form-control">
                                    <p class="help-block">{l s='End Time' mod='whatsappwidget'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='Offline Message' mod='whatsappwidget'}
                        </label>
                        <div class="col-lg-9">
                            <textarea name="WHATSAPP_WIDGET_OFFLINE_MESSAGE" class="form-control" rows="2">{$config_values.WHATSAPP_WIDGET_OFFLINE_MESSAGE|escape:'html':'UTF-8'}</textarea>
                            <p class="help-block">{l s='Message to show when outside working hours' mod='whatsappwidget'}</p>
                        </div>
                    </div>
                </div>
            </fieldset>
            
            <!-- Advanced Settings -->
            <fieldset>
                <legend><i class="icon-cogs"></i> {l s='Advanced Settings' mod='whatsappwidget'}</legend>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Consent Required' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_CONSENT_REQUIRED" id="WHATSAPP_WIDGET_CONSENT_REQUIRED_on" value="1" {if $config_values.WHATSAPP_WIDGET_CONSENT_REQUIRED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_CONSENT_REQUIRED_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_CONSENT_REQUIRED" id="WHATSAPP_WIDGET_CONSENT_REQUIRED_off" value="0" {if !$config_values.WHATSAPP_WIDGET_CONSENT_REQUIRED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_CONSENT_REQUIRED_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                        <p class="help-block">{l s='Require user consent before loading widget' mod='whatsappwidget'}</p>
                    </div>
                </div>
                
                <div class="consent-settings" {if !$config_values.WHATSAPP_WIDGET_CONSENT_REQUIRED}style="display:none;"{/if}>
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='Consent Cookies' mod='whatsappwidget'}
                        </label>
                        <div class="col-lg-9">
                            <input type="text" name="WHATSAPP_WIDGET_CONSENT_COOKIES" value="{$config_values.WHATSAPP_WIDGET_CONSENT_COOKIES|escape:'html':'UTF-8'}" class="form-control" placeholder="marketing_consent,analytics_consent">
                            <p class="help-block">{l s='Comma-separated list of cookie names to check for consent' mod='whatsappwidget'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Force wa.me' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_FORCE_WA_ME" id="WHATSAPP_WIDGET_FORCE_WA_ME_on" value="1" {if $config_values.WHATSAPP_WIDGET_FORCE_WA_ME}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_FORCE_WA_ME_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_FORCE_WA_ME" id="WHATSAPP_WIDGET_FORCE_WA_ME_off" value="0" {if !$config_values.WHATSAPP_WIDGET_FORCE_WA_ME}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_FORCE_WA_ME_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                        <p class="help-block">{l s='Always use wa.me instead of web.whatsapp.com' mod='whatsappwidget'}</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='DataLayer Tracking' mod='whatsappwidget'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="WHATSAPP_WIDGET_DATALAYER_ENABLED" id="WHATSAPP_WIDGET_DATALAYER_ENABLED_on" value="1" {if $config_values.WHATSAPP_WIDGET_DATALAYER_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_DATALAYER_ENABLED_on">{l s='Yes' mod='whatsappwidget'}</label>
                            <input type="radio" name="WHATSAPP_WIDGET_DATALAYER_ENABLED" id="WHATSAPP_WIDGET_DATALAYER_ENABLED_off" value="0" {if !$config_values.WHATSAPP_WIDGET_DATALAYER_ENABLED}checked="checked"{/if}>
                            <label for="WHATSAPP_WIDGET_DATALAYER_ENABLED_off">{l s='No' mod='whatsappwidget'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                
                <div class="datalayer-settings" {if !$config_values.WHATSAPP_WIDGET_DATALAYER_ENABLED}style="display:none;"{/if}>
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='DataLayer Event Name' mod='whatsappwidget'}
                        </label>
                        <div class="col-lg-9">
                            <input type="text" name="WHATSAPP_WIDGET_DATALAYER_EVENT" value="{$config_values.WHATSAPP_WIDGET_DATALAYER_EVENT|escape:'html':'UTF-8'}" class="form-control" placeholder="whatsapp_click">
                            <p class="help-block">{l s='Event name to push to dataLayer on widget click' mod='whatsappwidget'}</p>
                        </div>
                    </div>
                </div>
            </fieldset>
            
            <div class="panel-footer">
                <button type="button" id="preview_whatsapp_url" class="btn btn-info pull-left">
                    <i class="icon-eye"></i> {l s='Preview WhatsApp URL' mod='whatsappwidget'}
                </button>
                <button type="submit" value="1" id="configuration_form_submit_btn" name="submitWhatsAppWidget" class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> {l s='Save' mod='whatsappwidget'}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // CSRF token validation
    $('#configuration_form').on('submit', function(e) {
        var csrfToken = $('input[name="csrf_token"]').val();
        if (!csrfToken) {
            e.preventDefault();
            alert('Security token is missing. Please refresh the page and try again.');
            return false;
        }
    });
    
    // Toggle working hours settings
    $('input[name="WHATSAPP_WIDGET_WORKING_HOURS_ENABLED"]').change(function() {
        if ($(this).val() == '1' && $(this).is(':checked')) {
            $('.working-hours-settings').show();
        } else if ($(this).val() == '0' && $(this).is(':checked')) {
            $('.working-hours-settings').hide();
        }
    });
    
    // Toggle consent settings
    $('input[name="WHATSAPP_WIDGET_CONSENT_REQUIRED"]').change(function() {
        if ($(this).val() == '1' && $(this).is(':checked')) {
            $('.consent-settings').show();
        } else if ($(this).val() == '0' && $(this).is(':checked')) {
            $('.consent-settings').hide();
        }
    });
    
    // Toggle datalayer settings
    $('input[name="WHATSAPP_WIDGET_DATALAYER_ENABLED"]').change(function() {
        if ($(this).val() == '1' && $(this).is(':checked')) {
            $('.datalayer-settings').show();
        } else if ($(this).val() == '0' && $(this).is(':checked')) {
            $('.datalayer-settings').hide();
        }
    });
    
    // Preview WhatsApp URL functionality
    $('#preview_whatsapp_url').click(function() {
        var phone = $('input[name="WHATSAPP_WIDGET_PHONE"]').val();
        var defaultMessage = $('textarea[name="WHATSAPP_WIDGET_DEFAULT_MESSAGE"]').val();
        var forceWaMe = $('input[name="WHATSAPP_WIDGET_FORCE_WA_ME"]:checked').val();
        
        if (!phone) {
            alert('{l s="Please enter a phone number first" mod="whatsappwidget"}');
            return;
        }
        
        // Clean phone number
        var cleanPhone = phone.replace(/[^+\d]/g, '');
        if (!cleanPhone.startsWith('+')) {
            cleanPhone = '+' + cleanPhone;
        }
        
        // Generate preview URL
        var baseUrl = (forceWaMe === '1') ? 'https://wa.me/' : 'https://web.whatsapp.com/send?phone=';
        var message = defaultMessage || 'Hello! I\'m interested in your products.';
        var encodedMessage = encodeURIComponent(message.replace(/\{[^}]+\}/g, '[TOKEN]'));
        
        var previewUrl;
        if (forceWaMe === '1') {
            previewUrl = baseUrl + cleanPhone.substring(1) + '?text=' + encodedMessage;
        } else {
            previewUrl = baseUrl + cleanPhone + '&text=' + encodedMessage;
        }
        
        // Show preview in modal or new window
        var previewWindow = window.open(previewUrl, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
        if (!previewWindow) {
            // Fallback if popup blocked
            var previewHtml = '<div class="alert alert-info">' +
                '<h4><i class="icon-info"></i> {l s="WhatsApp URL Preview" mod="whatsappwidget"}</h4>' +
                '<p><strong>{l s="Generated URL:" mod="whatsappwidget"}</strong></p>' +
                '<p><a href="' + previewUrl + '" target="_blank">' + previewUrl + '</a></p>' +
                '<p><small>{l s="Note: Tokens like {page_url}, {product_name} will be replaced with actual values on the frontend." mod="whatsappwidget"}</small></p>' +
                '</div>';
            
            // Remove existing preview
            $('.whatsapp-preview').remove();
            
            // Add preview after the button
            $(this).closest('.panel-footer').after(previewHtml);
            $(this).closest('.panel-footer').next().addClass('whatsapp-preview');
        }
    });
    
    // Enhanced phone number validation
    $('input[name="WHATSAPP_WIDGET_PHONE"]').on('input', function() {
        // Remove any non-digit characters except +
        var value = $(this).val().replace(/[^+\d]/g, '');
        
        // Ensure it starts with +
        if (value && !value.startsWith('+')) {
            value = '+' + value;
        }
        
        $(this).val(value);
    });
    
    $('input[name="WHATSAPP_WIDGET_PHONE"]').on('blur', function() {
        var phone = $(this).val();
        var e164Regex = /^\+[1-9]\d{1,14}$/;
        
        if (phone && !e164Regex.test(phone)) {
            $(this).addClass('error').css({
                'border-color': '#dc3545',
                'background-color': '#fff5f5'
            });
            if (!$(this).next('.error-message').length) {
                $(this).after('<p class="error-message text-danger">{l s="Phone number must be in E.164 format (e.g., +905551112233)" mod="whatsappwidget"}</p>');
            }
        } else {
            $(this).removeClass('error').css({
                'border-color': '',
                'background-color': ''
            });
            $(this).next('.error-message').remove();
        }
    });
    
    // Message template validation
    $('textarea[name*="MESSAGE"]').on('input', function() {
        var maxLength = 1000;
        var currentLength = $(this).val().length;
        
        if (currentLength > maxLength) {
            $(this).css({
                'border-color': '#dc3545',
                'background-color': '#fff5f5'
            });
        } else {
            $(this).css({
                'border-color': '',
                'background-color': ''
            });
        }
    });
});
</script>