{*
* WhatsApp Widget - Modern Admin Configuration Panel
* Material Design inspired interface
*}

<link href="{$module_dir}views/css/admin.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="whatsapp-admin-panel">
    <!-- Header Section -->
    <div class="whatsapp-card whatsapp-fade-in">
        <div class="whatsapp-card-header">
            <h3>
                <i class="fab fa-whatsapp icon"></i>
                {l s='WhatsApp Widget Configuration' mod='whatsappwidget'}
            </h3>
        </div>
        <div class="whatsapp-card-body">
            <p>{l s='Configure your WhatsApp widget to provide instant customer support. Choose from multiple display positions and customize the appearance to match your store design.' mod='whatsappwidget'}</p>
            
            <div class="whatsapp-flex whatsapp-justify-between whatsapp-align-center whatsapp-mt-3">
                <div>
                    <span class="whatsapp-status {if $WHATSAPP_WIDGET_ENABLED}enabled{else}disabled{/if}">
                        <i class="fas fa-circle"></i>
                        {if $WHATSAPP_WIDGET_ENABLED}{l s='Active' mod='whatsappwidget'}{else}{l s='Inactive' mod='whatsappwidget'}{/if}
                    </span>
                </div>
                <div>
                    <small class="text-muted">{l s='Last updated' mod='whatsappwidget'}: {date('d/m/Y H:i')}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <form method="post" class="whatsapp-fade-in">
        <div class="whatsapp-grid">
            <!-- Basic Settings -->
            <div class="whatsapp-card">
                <div class="whatsapp-card-header">
                    <h3>
                        <i class="fas fa-cog icon"></i>
                        {l s='Basic Settings' mod='whatsappwidget'}
                    </h3>
                </div>
                <div class="whatsapp-card-body">
                    <!-- Enable Widget -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_ENABLED">
                            {l s='Enable Widget' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-switch">
                            <input type="checkbox" id="WHATSAPP_WIDGET_ENABLED" name="WHATSAPP_WIDGET_ENABLED" value="1" {if $WHATSAPP_WIDGET_ENABLED}checked{/if}>
                            <span class="slider"></span>
                        </div>
                        <small class="text-muted">{l s='Turn on/off the WhatsApp widget on your store' mod='whatsappwidget'}</small>
                    </div>

                    <!-- Phone Number -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_PHONE">
                            <i class="fas fa-phone"></i>
                            {l s='WhatsApp Phone Number' mod='whatsappwidget'} *
                        </label>
                        <input type="tel" 
                               id="WHATSAPP_WIDGET_PHONE" 
                               name="WHATSAPP_WIDGET_PHONE" 
                               class="form-control" 
                               value="{$WHATSAPP_WIDGET_PHONE|escape:'html':'UTF-8'}" 
                               placeholder="+90 555 123 45 67"
                               data-tooltip="{l s='Enter your WhatsApp business number with country code' mod='whatsappwidget'}">
                        <small class="text-muted">{l s='Include country code (e.g., +90 for Turkey)' mod='whatsappwidget'}</small>
                    </div>

                    <!-- Default Message -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_MESSAGE">
                            <i class="fas fa-comment"></i>
                            {l s='Default Message' mod='whatsappwidget'}
                        </label>
                        <textarea id="WHATSAPP_WIDGET_MESSAGE" 
                                  name="WHATSAPP_WIDGET_MESSAGE" 
                                  class="form-control" 
                                  rows="3" 
                                  maxlength="500"
                                  placeholder="{l s='Hello! I would like to get information about this product.' mod='whatsappwidget'}">{$WHATSAPP_WIDGET_MESSAGE|escape:'html':'UTF-8'}</textarea>
                        <small class="text-muted">{l s='This message will be pre-filled when customers click the widget' mod='whatsappwidget'}</small>
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="whatsapp-card">
                <div class="whatsapp-card-header">
                    <h3>
                        <i class="fas fa-eye icon"></i>
                        {l s='Display Settings' mod='whatsappwidget'}
                    </h3>
                </div>
                <div class="whatsapp-card-body">
                    <!-- Widget Style -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_STYLE">
                            <i class="fas fa-paint-brush"></i>
                            {l s='Widget Style' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-select">
                            <select id="WHATSAPP_WIDGET_STYLE" name="WHATSAPP_WIDGET_STYLE" class="form-control">
                                <option value="floating" {if $WHATSAPP_WIDGET_STYLE == 'floating'}selected{/if}>
                                    {l s='Floating (Fixed Position)' mod='whatsappwidget'}
                                </option>
                                <option value="inline" {if $WHATSAPP_WIDGET_STYLE == 'inline'}selected{/if}>
                                    {l s='Inline (In Content)' mod='whatsappwidget'}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Display Hook -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_HOOK">
                            <i class="fas fa-map-pin"></i>
                            {l s='Display Position' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-select">
                            <select id="WHATSAPP_WIDGET_HOOK" name="WHATSAPP_WIDGET_HOOK" class="form-control">
                                <option value="displayFooter" {if $WHATSAPP_WIDGET_HOOK == 'displayFooter'}selected{/if}>
                                    {l s='Footer' mod='whatsappwidget'}
                                </option>
                                <option value="displayHeader" {if $WHATSAPP_WIDGET_HOOK == 'displayHeader'}selected{/if}>
                                    {l s='Header' mod='whatsappwidget'}
                                </option>
                                <option value="displayTop" {if $WHATSAPP_WIDGET_HOOK == 'displayTop'}selected{/if}>
                                    {l s='Top of Page' mod='whatsappwidget'}
                                </option>
                                <option value="displayLeftColumn" {if $WHATSAPP_WIDGET_HOOK == 'displayLeftColumn'}selected{/if}>
                                    {l s='Left Column' mod='whatsappwidget'}
                                </option>
                                <option value="displayRightColumn" {if $WHATSAPP_WIDGET_HOOK == 'displayRightColumn'}selected{/if}>
                                    {l s='Right Column' mod='whatsappwidget'}
                                </option>
                                <option value="displayHome" {if $WHATSAPP_WIDGET_HOOK == 'displayHome'}selected{/if}>
                                    {l s='Home Page Only' mod='whatsappwidget'}
                                </option>
                                <option value="displayProductButtons" {if $WHATSAPP_WIDGET_HOOK == 'displayProductButtons'}selected{/if}>
                                    {l s='Product Buttons Area' mod='whatsappwidget'}
                                </option>
                                <option value="displayShoppingCartFooter" {if $WHATSAPP_WIDGET_HOOK == 'displayShoppingCartFooter'}selected{/if}>
                                    {l s='Shopping Cart Footer' mod='whatsappwidget'}
                                </option>
                                <option value="displayCustomWhatsAppWidget" {if $WHATSAPP_WIDGET_HOOK == 'displayCustomWhatsAppWidget'}selected{/if}>
                                    {l s='Custom Position (Manual)' mod='whatsappwidget'}
                                </option>
                            </select>
                        </div>
                        <small class="text-muted">{l s='Choose where to display the widget on your pages' mod='whatsappwidget'}</small>
                    </div>

                    <!-- Widget Position (for floating style) -->
                    <div class="whatsapp-form-group" id="position-group">
                        <label for="WHATSAPP_WIDGET_POSITION">
                            <i class="fas fa-arrows-alt"></i>
                            {l s='Floating Position' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-select">
                            <select id="WHATSAPP_WIDGET_POSITION" name="WHATSAPP_WIDGET_POSITION" class="form-control">
                                <option value="bottom-right" {if $WHATSAPP_WIDGET_POSITION == 'bottom-right'}selected{/if}>
                                    {l s='Bottom Right' mod='whatsappwidget'}
                                </option>
                                <option value="bottom-left" {if $WHATSAPP_WIDGET_POSITION == 'bottom-left'}selected{/if}>
                                    {l s='Bottom Left' mod='whatsappwidget'}
                                </option>
                                <option value="top-right" {if $WHATSAPP_WIDGET_POSITION == 'top-right'}selected{/if}>
                                    {l s='Top Right' mod='whatsappwidget'}
                                </option>
                                <option value="top-left" {if $WHATSAPP_WIDGET_POSITION == 'top-left'}selected{/if}>
                                    {l s='Top Left' mod='whatsappwidget'}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appearance Settings -->
        <div class="whatsapp-card whatsapp-fade-in">
            <div class="whatsapp-card-header">
                <h3>
                    <i class="fas fa-palette icon"></i>
                    {l s='Appearance Settings' mod='whatsappwidget'}
                </h3>
            </div>
            <div class="whatsapp-card-body">
                <div class="whatsapp-grid">
                    <!-- Widget Color -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_COLOR">
                            <i class="fas fa-fill-drip"></i>
                            {l s='Widget Color' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-color-picker">
                            <div class="whatsapp-color-preview" style="background-color: {$WHATSAPP_WIDGET_COLOR}"></div>
                            <input type="color" 
                                   id="WHATSAPP_WIDGET_COLOR" 
                                   name="WHATSAPP_WIDGET_COLOR" 
                                   value="{$WHATSAPP_WIDGET_COLOR}" 
                                   class="form-control">
                            <small class="text-muted">{l s='Choose the widget background color' mod='whatsappwidget'}</small>
                        </div>
                    </div>

                    <!-- Widget Size -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_SIZE">
                            <i class="fas fa-expand-arrows-alt"></i>
                            {l s='Widget Size' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-select">
                            <select id="WHATSAPP_WIDGET_SIZE" name="WHATSAPP_WIDGET_SIZE" class="form-control">
                                <option value="small" {if $WHATSAPP_WIDGET_SIZE == 'small'}selected{/if}>
                                    {l s='Small (50px)' mod='whatsappwidget'}
                                </option>
                                <option value="medium" {if $WHATSAPP_WIDGET_SIZE == 'medium'}selected{/if}>
                                    {l s='Medium (60px)' mod='whatsappwidget'}
                                </option>
                                <option value="large" {if $WHATSAPP_WIDGET_SIZE == 'large'}selected{/if}>
                                    {l s='Large (70px)' mod='whatsappwidget'}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Visibility Settings -->
        <div class="whatsapp-card whatsapp-fade-in">
            <div class="whatsapp-card-header">
                <h3>
                    <i class="fas fa-toggle-on icon"></i>
                    {l s='Page Visibility Settings' mod='whatsappwidget'}
                </h3>
            </div>
            <div class="whatsapp-card-body">
                <div class="whatsapp-grid">
                    <!-- Show on Product Pages -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_SHOW_PRODUCT">
                            {l s='Show on Product Pages' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-switch">
                            <input type="checkbox" id="WHATSAPP_WIDGET_SHOW_PRODUCT" name="WHATSAPP_WIDGET_SHOW_PRODUCT" value="1" {if $WHATSAPP_WIDGET_SHOW_PRODUCT}checked{/if}>
                            <span class="slider"></span>
                        </div>
                    </div>

                    <!-- Show on Category Pages -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_SHOW_CATEGORY">
                            {l s='Show on Category Pages' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-switch">
                            <input type="checkbox" id="WHATSAPP_WIDGET_SHOW_CATEGORY" name="WHATSAPP_WIDGET_SHOW_CATEGORY" value="1" {if $WHATSAPP_WIDGET_SHOW_CATEGORY}checked{/if}>
                            <span class="slider"></span>
                        </div>
                    </div>

                    <!-- Show on Home Page -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_SHOW_HOME">
                            {l s='Show on Home Page' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-switch">
                            <input type="checkbox" id="WHATSAPP_WIDGET_SHOW_HOME" name="WHATSAPP_WIDGET_SHOW_HOME" value="1" {if $WHATSAPP_WIDGET_SHOW_HOME}checked{/if}>
                            <span class="slider"></span>
                        </div>
                    </div>

                    <!-- Show on All Pages -->
                    <div class="whatsapp-form-group">
                        <label for="WHATSAPP_WIDGET_SHOW_ALL">
                            {l s='Show on All Pages' mod='whatsappwidget'}
                        </label>
                        <div class="whatsapp-switch">
                            <input type="checkbox" id="WHATSAPP_WIDGET_SHOW_ALL" name="WHATSAPP_WIDGET_SHOW_ALL" value="1" {if $WHATSAPP_WIDGET_SHOW_ALL}checked{/if}>
                            <span class="slider"></span>
                        </div>
                        <small class="text-muted">{l s='Override page-specific settings and show on all pages' mod='whatsappwidget'}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="whatsapp-card whatsapp-fade-in">
            <div class="whatsapp-card-header">
                <h3>
                    <i class="fas fa-eye icon"></i>
                    {l s='Live Preview' mod='whatsappwidget'}
                </h3>
            </div>
            <div class="whatsapp-card-body">
                <div class="whatsapp-preview">
                    <div class="whatsapp-preview-title">
                        {l s='Widget Preview' mod='whatsappwidget'}
                    </div>
                    <div class="whatsapp-widget-preview" 
                         style="background-color: {$WHATSAPP_WIDGET_COLOR};"
                         data-tooltip="{l s='Click to test WhatsApp link' mod='whatsappwidget'}">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Hook Instructions -->
        {if $WHATSAPP_WIDGET_HOOK == 'displayCustomWhatsAppWidget'}
        <div class="whatsapp-card whatsapp-fade-in">
            <div class="whatsapp-card-header">
                <h3>
                    <i class="fas fa-code icon"></i>
                    {l s='Custom Hook Usage' mod='whatsappwidget'}
                </h3>
            </div>
            <div class="whatsapp-card-body">
                <p>{l s='To use the custom position, add the following code to your theme template files:' mod='whatsappwidget'}</p>
                <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; font-family: monospace; margin: 15px 0;">
                    {literal}{hook h='displayCustomWhatsAppWidget'}{/literal}
                </div>
                <p><strong>{l s='Example locations:' mod='whatsappwidget'}</strong></p>
                <ul>
                    <li>{l s='themes/your-theme/templates/_partials/header.tpl' mod='whatsappwidget'}</li>
                    <li>{l s='themes/your-theme/templates/_partials/footer.tpl' mod='whatsappwidget'}</li>
                    <li>{l s='themes/your-theme/templates/catalog/product.tpl' mod='whatsappwidget'}</li>
                    <li>{l s='themes/your-theme/templates/index.tpl' mod='whatsappwidget'}</li>
                </ul>
            </div>
        </div>
        {/if}

        <!-- Action Buttons -->
        <div class="whatsapp-card whatsapp-fade-in">
            <div class="whatsapp-card-body whatsapp-text-center">
                <button type="submit" name="submitWhatsAppWidget" class="whatsapp-btn whatsapp-btn-primary">
                    <i class="fas fa-save"></i>
                    {l s='Save Configuration' mod='whatsappwidget'}
                </button>
                <a href="{$link->getAdminLink('AdminModules')}&configure=whatsappwidget" class="whatsapp-btn whatsapp-btn-secondary">
                    <i class="fas fa-undo"></i>
                    {l s='Reset to Defaults' mod='whatsappwidget'}
                </a>
            </div>
        </div>
    </form>
</div>

<script src="{$module_dir}views/js/admin.js"></script>
<script>
// Initialize phone formatting
document.getElementById('WHATSAPP_WIDGET_PHONE').addEventListener('input', function() {
    WhatsAppAdmin.formatPhone(this);
});

// Show/hide position settings based on style
document.getElementById('WHATSAPP_WIDGET_STYLE').addEventListener('change', function() {
    const positionGroup = document.getElementById('position-group');
    if (this.value === 'floating') {
        positionGroup.style.display = 'block';
    } else {
        positionGroup.style.display = 'none';
    }
});

// Initial state
if (document.getElementById('WHATSAPP_WIDGET_STYLE').value !== 'floating') {
    document.getElementById('position-group').style.display = 'none';
}
</script>