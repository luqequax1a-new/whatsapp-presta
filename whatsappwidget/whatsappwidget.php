<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

// Autoload will be handled in constructor

use WhatsAppWidget\Util\Phone;
use WhatsAppWidget\Util\Template;
use WhatsAppWidget\Util\WorkingHours;
use WhatsAppWidget\Security\Validator;
use WhatsAppWidget\Security\Security as WwSecurity;

class WhatsAppWidget extends Module
{
    private const CONFIG_PREFIX = 'WHATSAPP_WIDGET_';
    
    private array $hooks = [
        'header',
        'displayAfterBodyOpeningTag',
        'displayFooter',
        'displayFooterAfter',
        'displayProductButtons'
    ];
    
    private array $configKeys = [
        'ENABLED',
        'PHONE',
        'DEFAULT_MESSAGE',
        'PRODUCT_MESSAGE',
        'VISIBILITY_PAGES',
        'VISIBILITY_DEVICES',
        'POSITION',
        'THEME_COLOR',
        'BUTTON_SIZE',
        'BORDER_RADIUS',
        'DARK_MODE',
        'WORKING_HOURS_ENABLED',
        'WORKING_DAYS',
        'START_TIME',
        'END_TIME',
        'OFFLINE_MESSAGE',
        'CONSENT_REQUIRED',
        'CONSENT_COOKIES',
        'FORCE_WA_ME',
        'DATALAYER_ENABLED',
        'DATALAYER_EVENT'
    ];

    public function __construct()
    {
        // Handle autoloading with vendor check
        $vendor = __DIR__ . '/vendor/autoload.php';
        $installed = __DIR__ . '/vendor/composer/InstalledVersions.php';
        
        if (is_file($vendor) && is_file($installed)) {
            require_once $vendor; // Vendor tam ise Composer autoload
        } else {
            $this->registerFallbackAutoload(); // Aksi halde PSR-4 fallback
        }
        
        $this->name = 'whatsappwidget';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'WhatsApp Widget Team';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '8.0', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('WhatsApp Widget Advanced');
        $this->description = $this->l('Advanced WhatsApp widget with consent management, working hours, and performance optimization');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall? All configuration will be lost.');

        if (!Configuration::get(self::CONFIG_PREFIX . 'ENABLED')) {
            $this->warning = $this->l('Module is disabled. Configure it in the settings.');
        }
    }

    public function install(): bool
    {
        if (!parent::install()) {
            return false;
        }
        
        // Register hooks with fallback strategy
        $primaryHooks = ['header', 'displayAfterBodyOpeningTag'];
        $fallbackHooks = ['displayFooter', 'displayFooterAfter'];
        $optionalHooks = ['displayProductButtons'];
        
        // Register primary hooks (required)
        foreach ($primaryHooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }
        
        // Register fallback hooks (best effort)
        foreach ($fallbackHooks as $hook) {
            $this->registerHook($hook); // Don't fail if these don't exist
        }
        
        // Register optional hooks
        foreach ($optionalHooks as $hook) {
            $this->registerHook($hook);
        }
        
        // Set default configuration
        $defaultConfig = [
            'ENABLED' => false,
            'PHONE' => '',
            'DEFAULT_MESSAGE' => 'Hello! I am interested in your products. Page: {page_url}',
            'PRODUCT_MESSAGE' => 'Hello! I am interested in this product: {product_name} - {price}. Link: {product_url}',
            'VISIBILITY_PAGES' => json_encode(['home', 'category', 'product']),
            'VISIBILITY_DEVICES' => json_encode(['desktop', 'mobile']),
            'POSITION' => 'bottom-right',
            'THEME_COLOR' => '#25D366',
            'BUTTON_SIZE' => 'md',
            'BORDER_RADIUS' => 'lg',
            'DARK_MODE' => false,
            'WORKING_HOURS_ENABLED' => false,
            'WORKING_DAYS' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            'START_TIME' => '09:00',
            'END_TIME' => '18:00',
            'OFFLINE_MESSAGE' => 'We are currently offline. Please leave a message!',
            'CONSENT_REQUIRED' => false,
            'CONSENT_COOKIES' => '',
            'FORCE_WA_ME' => false,
            'DATALAYER_ENABLED' => false,
            'DATALAYER_EVENT' => 'whatsapp_click'
        ];
        
        foreach ($defaultConfig as $key => $value) {
            if (!Configuration::updateValue(self::CONFIG_PREFIX . $key, $value)) {
                return false;
            }
        }
        
        return true;
    }

    public function uninstall(): bool
    {
        // Remove all configuration
        foreach ($this->configKeys as $key) {
            Configuration::deleteByName(self::CONFIG_PREFIX . $key);
        }
        
        return parent::uninstall();
    }

    /**
     * Module configuration page
     */
    public function getContent(): string
    {
        $output = '';
        
        // Process form submission
        if (Tools::isSubmit('submitWhatsAppWidget')) {
            $output .= $this->processConfiguration();
        }
        
        // Display configuration form
        $output .= $this->displayForm();
        
        return $output;
    }
    
    /**
     * Process configuration form
     */
    private function processConfiguration(): string
    {
        // CSRF Protection
        $csrfToken = Tools::getValue('csrf_token');
        if (!WwSecurity::validateCSRFToken($csrfToken)) {
            WwSecurity::logSecurityEvent('CSRF token validation failed', [
                'module' => $this->name,
                'action' => 'configuration_update'
            ]);
            return $this->displayError($this->l('Invalid security token. Please try again.'));
        }

        // Rate limiting
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!WwSecurity::checkRateLimit($clientIp, 10, 300)) {
            WwSecurity::logSecurityEvent('Rate limit exceeded', [
                'module' => $this->name,
                'ip' => $clientIp
            ]);
            return $this->displayError($this->l('Too many requests. Please wait before trying again.'));
        }

        $errors = [];
        
        // Validate and sanitize phone number
        $phoneInput = Tools::getValue('WHATSAPP_WIDGET_PHONE');
        $phoneResult = Validator::validatePhone($phoneInput);
        if (!$phoneResult['valid']) {
            $errors[] = $this->l('Invalid phone number format. Please use E.164 format (e.g., +905551112233)');
        }
        $phone = $phoneResult['sanitized'];
        
        // Validate and sanitize message templates
        $defaultMessageInput = Tools::getValue('WHATSAPP_WIDGET_DEFAULT_MESSAGE');
        $defaultMessageResult = Validator::validateMessage($defaultMessageInput);
        if (!$defaultMessageResult['valid']) {
            $errors[] = $this->l('Default message template is invalid or too long');
        }
        $defaultMessage = $defaultMessageResult['sanitized'];
        
        $productMessageInput = Tools::getValue('WHATSAPP_WIDGET_PRODUCT_MESSAGE');
        $productMessageResult = Validator::validateMessage($productMessageInput);
        if (!$productMessageResult['valid']) {
            $errors[] = $this->l('Product message template is invalid or too long');
        }
        $productMessage = $productMessageResult['sanitized'];
        
        // Validate color
        $colorInput = Tools::getValue('WHATSAPP_WIDGET_THEME_COLOR');
        $colorResult = Validator::validateColor($colorInput);
        if (!$colorResult['valid']) {
            $errors[] = $this->l('Invalid color format. Please use hex format (e.g., #25D366)');
        }
        $color = $colorResult['sanitized'];
        
        // Validate working hours
        if (Tools::getValue('WHATSAPP_WIDGET_WORKING_HOURS_ENABLED')) {
            $workingStart = Tools::getValue('WHATSAPP_WIDGET_START_TIME');
            $workingEnd = Tools::getValue('WHATSAPP_WIDGET_END_TIME');
            
            $startTimeResult = Validator::validateTime($workingStart);
            $endTimeResult = Validator::validateTime($workingEnd);
            
            if (!$startTimeResult['valid'] || !$endTimeResult['valid']) {
                $errors[] = $this->l('Invalid time format. Please use HH:MM format');
            }
            
            $workingConfig = [
                'start_time' => $workingStart,
                'end_time' => $workingEnd
            ];
            $workingErrors = WorkingHours::validateConfig($workingConfig);
            $errors = array_merge($errors, $workingErrors);
        }
        
        // Validate consent cookies
        $consentCookiesInput = Tools::getValue('WHATSAPP_WIDGET_CONSENT_COOKIES');
        $cookiesResult = Validator::validateCookieNames($consentCookiesInput);
        if (!$cookiesResult['valid']) {
            $errors[] = $this->l('Invalid cookie names: ') . $cookiesResult['error'];
        }
        $consentCookies = $cookiesResult['sanitized'];
        
        // Validate dataLayer event name
        $dataLayerEventInput = Tools::getValue('WHATSAPP_WIDGET_DATALAYER_EVENT');
        $eventResult = Validator::validateEventName($dataLayerEventInput);
        if (!$eventResult['valid']) {
            $errors[] = $this->l('Invalid dataLayer event name: ') . $eventResult['error'];
        }
        $dataLayerEvent = $eventResult['sanitized'];
        
        if (!empty($errors)) {
            return $this->displayError(implode('<br>', $errors));
        }
        
        // Sanitize and save configuration
        $visibilityPagesResult = Validator::validateAllowedValues(Tools::getValue('WHATSAPP_WIDGET_VISIBILITY_PAGES', []), ['home', 'category', 'product', 'cms', 'cart']);
        $visibilityDevicesResult = Validator::validateAllowedValues(Tools::getValue('WHATSAPP_WIDGET_VISIBILITY_DEVICES', []), ['desktop', 'mobile']);
        $positionResult = Validator::validateAllowedValues([Tools::getValue('WHATSAPP_WIDGET_POSITION')], ['bottom-right', 'bottom-left']);
        $buttonSizeResult = Validator::validateAllowedValues([Tools::getValue('WHATSAPP_WIDGET_BUTTON_SIZE')], ['sm', 'md', 'lg']);
        $borderRadiusResult = Validator::validateAllowedValues([Tools::getValue('WHATSAPP_WIDGET_BORDER_RADIUS')], ['md', 'lg']);
        $workingDaysResult = Validator::validateAllowedValues(Tools::getValue('WHATSAPP_WIDGET_WORKING_DAYS', []), ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
        
        $offlineMessageInput = Tools::getValue('WHATSAPP_WIDGET_OFFLINE_MESSAGE');
        $offlineMessageResult = Validator::validateMessage($offlineMessageInput);
        
        $configUpdates = [
            'ENABLED' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_ENABLED'), 'bool'),
            'PHONE' => $phone,
            'DEFAULT_MESSAGE' => $defaultMessage,
            'PRODUCT_MESSAGE' => $productMessage,
            'VISIBILITY_PAGES' => json_encode($visibilityPagesResult['sanitized']),
            'VISIBILITY_DEVICES' => json_encode($visibilityDevicesResult['sanitized']),
            'POSITION' => $positionResult['sanitized'][0] ?? 'bottom-right',
            'THEME_COLOR' => $color,
            'BUTTON_SIZE' => $buttonSizeResult['sanitized'][0] ?? 'md',
            'BORDER_RADIUS' => $borderRadiusResult['sanitized'][0] ?? 'md',
            'DARK_MODE' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_DARK_MODE'), 'bool'),
            'WORKING_HOURS_ENABLED' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_WORKING_HOURS_ENABLED'), 'bool'),
            'WORKING_DAYS' => json_encode($workingDaysResult['sanitized']),
            'START_TIME' => Tools::getValue('WHATSAPP_WIDGET_START_TIME'),
            'END_TIME' => Tools::getValue('WHATSAPP_WIDGET_END_TIME'),
            'OFFLINE_MESSAGE' => $offlineMessageResult['sanitized'],
            'CONSENT_REQUIRED' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_CONSENT_REQUIRED'), 'bool'),
            'CONSENT_COOKIES' => $consentCookies,
            'FORCE_WA_ME' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_FORCE_WA_ME'), 'bool'),
            'DATALAYER_ENABLED' => WwSecurity::sanitizeConfigValue(Tools::getValue('WHATSAPP_WIDGET_DATALAYER_ENABLED'), 'bool'),
            'DATALAYER_EVENT' => $dataLayerEvent
        ];
        
        foreach ($configUpdates as $key => $value) {
            Configuration::updateValue(self::CONFIG_PREFIX . $key, $value);
        }
        
        return $this->displayConfirmation($this->l('Settings updated successfully'));
    }

    /**
     * Hook: Header - Register CSS/JS assets conditionally
     */
    public function hookHeader(): void
    {
        // CRITICAL: Check consent FIRST before any processing
        // This prevents FOUC and SEO issues by not loading any assets without consent
        if ($this->isConsentRequired() && !$this->hasUserConsent()) {
            // Set flag to prevent HTML injection in other hooks
            $this->context->smarty->assign('whatsapp_widget_consent_blocked', true);
            return;
        }
        
        // Only load assets if widget should be displayed
        if (!$this->shouldDisplayWidget()) {
            return;
        }
        
        // Register CSS with media attribute for performance
        $this->context->controller->addCSS(
            $this->_path . 'views/css/front.css',
            'all',
            null,
            false
        );
        
        // Register JS with defer attribute
        $this->context->controller->addJS(
            $this->_path . 'views/js/front.js',
            false
        );
        
        // Add inline critical CSS for immediate positioning
        $this->addInlineCriticalCSS();
        
        // Add widget configuration as JSON for JS
        $this->addWidgetConfig();
    }

    /**
     * Hook: After body opening tag - Main widget container
     */
    public function hookDisplayAfterBodyOpeningTag(): string
    {
        // Check if consent blocked any asset loading
        if ($this->context->smarty->getTemplateVars('whatsapp_widget_consent_blocked')) {
            return '';
        }
        
        if (!$this->shouldDisplayWidget()) {
            return '';
        }
        
        // Mark that primary hook was used
        $this->context->smarty->assign('whatsapp_widget_rendered', true);
        
        return $this->renderWidget();
    }
    
    /**
     * Hook: Footer - Fallback for themes that don't support displayAfterBodyOpeningTag
     */
    public function hookDisplayFooter(): string
    {
        // Check if consent blocked any asset loading
        if ($this->context->smarty->getTemplateVars('whatsapp_widget_consent_blocked')) {
            return '';
        }
        
        // Only render if primary hook wasn't used
        if ($this->context->smarty->getTemplateVars('whatsapp_widget_rendered')) {
            return '';
        }
        
        if (!$this->shouldDisplayWidget()) {
            return '';
        }
        
        return $this->renderWidget();
    }
    
    /**
     * Hook: Footer After - Secondary fallback
     */
    public function hookDisplayFooterAfter(): string
    {
        // Check if consent blocked any asset loading
        if ($this->context->smarty->getTemplateVars('whatsapp_widget_consent_blocked')) {
            return '';
        }
        
        // Only render if primary hook wasn't used
        if ($this->context->smarty->getTemplateVars('whatsapp_widget_rendered')) {
            return '';
        }
        
        if (!$this->shouldDisplayWidget()) {
            return '';
        }
        
        return $this->renderWidget();
    }
    
    /**
     * Hook: Product buttons - Optional product-specific trigger
     */
    public function hookDisplayProductButtons(): string
    {
        // Only show on product pages if specifically enabled
        if (!$this->shouldDisplayWidget() || $this->getCurrentPageType() !== 'product') {
            return '';
        }
        
        // Check if product-specific display is enabled
        $productButtonEnabled = Configuration::get($this->getConfigKey('PRODUCT_BUTTON_ENABLED'));
        if (!$productButtonEnabled) {
            return '';
        }
        
        return $this->renderProductButton();
    }
    
    /**
     * Add inline critical CSS for immediate widget positioning
     */
    private function addInlineCriticalCSS(): void
    {
        $position = Configuration::get($this->getConfigKey('POSITION')) ?: 'bottom-right';
        $size = Configuration::get($this->getConfigKey('BUTTON_SIZE')) ?: 'md';
        
        $css = $this->generateCriticalCSS($position, $size);
        
        $this->context->controller->addCSS(
            false,
            'all',
            null,
            false,
            $css
        );
    }
    
    /**
     * Generate critical CSS for widget positioning
     */
    private function generateCriticalCSS(string $position, string $size): string
    {
        $sizeMap = [
            'sm' => '48px',
            'md' => '56px',
            'lg' => '64px'
        ];
        
        $buttonSize = $sizeMap[$size] ?? $sizeMap['md'];
        
        $positionStyles = match($position) {
            'bottom-left' => 'left: 20px; right: auto;',
            'bottom-right' => 'right: 20px; left: auto;',
            default => 'right: 20px; left: auto;'
        };
        
        return "
        .whatsapp-widget {
            position: fixed;
            bottom: 20px;
            {$positionStyles}
            z-index: 999999;
            width: {$buttonSize};
            height: {$buttonSize};
        }
        .whatsapp-widget.hidden {
            display: none !important;
        }
        ";
    }
    
    /**
     * Add widget configuration as JSON for JavaScript
     */
    private function addWidgetConfig(): void
    {
        $config = [
            'phone' => Configuration::get($this->getConfigKey('PHONE')),
            'forceWaMe' => (bool)Configuration::get($this->getConfigKey('FORCE_WA_ME')),
            'dataLayerEnabled' => (bool)Configuration::get($this->getConfigKey('DATALAYER_ENABLED')),
            'dataLayerEvent' => Configuration::get($this->getConfigKey('DATALAYER_EVENT')) ?: 'whatsapp_click',
            'workingHoursEnabled' => (bool)Configuration::get($this->getConfigKey('WORKING_HOURS_ENABLED')),
            'consentRequired' => $this->isConsentRequired(),
            'consentCookies' => $this->getConsentCookies(),
            'pageType' => $this->getCurrentPageType(),
            'productData' => $this->getProductData(),
            'timezone' => Configuration::get('PS_TIMEZONE') ?: 'Europe/Istanbul',
            'workingHours' => $this->getWorkingHoursConfig()
        ];
        
        $configJson = json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        
        $script = "
        <script>
        window.whatsappWidgetConfig = {$configJson};
        </script>
        ";
        
        $this->context->controller->addJS(false, false, $script);
    }
    
    /**
     * Check if consent is required
     */
    private function isConsentRequired(): bool
    {
        return (bool)Configuration::get($this->getConfigKey('CONSENT_REQUIRED'));
    }
    
    /**
     * Check if user has given consent
     */
    private function hasUserConsent(): bool
    {
        if (!$this->isConsentRequired()) {
            return true;
        }
        
        $consentCookies = $this->getConsentCookies();
        if (empty($consentCookies)) {
            return true;
        }
        
        foreach ($consentCookies as $cookieName) {
            if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName]) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get consent cookie names
     */
    private function getConsentCookies(): array
    {
        $cookies = Configuration::get($this->getConfigKey('CONSENT_COOKIES'));
        if (empty($cookies)) {
            return [];
        }
        
        return array_map('trim', explode(',', $cookies));
    }
    
    /**
     * Get working hours configuration for frontend
     */
    private function getWorkingHoursConfig(): array
    {
        if (!Configuration::get($this->getConfigKey('WORKING_HOURS_ENABLED'))) {
            return [];
        }
        
        $workingDays = json_decode(Configuration::get($this->getConfigKey('WORKING_DAYS')), true) ?: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $startTime = Configuration::get($this->getConfigKey('START_TIME')) ?: '09:00';
        $endTime = Configuration::get($this->getConfigKey('END_TIME')) ?: '18:00';
        
        $config = [];
        $dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        foreach ($dayNames as $day) {
            $config[$day] = [
                'enabled' => in_array($day, $workingDays),
                'start' => $startTime,
                'end' => $endTime
            ];
        }
        
        return $config;
    }
    
    /**
     * Render product-specific button
     */
    private function renderProductButton(): string
    {
        $productData = $this->getProductData();
        if (empty($productData)) {
            return '';
        }
        
        $this->context->smarty->assign([
            'widget_type' => 'product_button',
            'product_data' => $productData,
            'widget_config' => $this->getWidgetDisplayConfig()
        ]);
        
        return $this->display(__FILE__, 'views/templates/hook/product_button.tpl');
    }
    
    /**
     * Get config key with prefix
     */
    private function getConfigKey(string $key): string
    {
        return self::CONFIG_PREFIX . $key;
    }
    
    /**
     * Get widget display configuration
     */
    private function getWidgetDisplayConfig(): array
    {
        return [
            'position' => Configuration::get($this->getConfigKey('POSITION')),
            'theme_color' => Configuration::get($this->getConfigKey('THEME_COLOR')),
            'button_size' => Configuration::get($this->getConfigKey('BUTTON_SIZE')),
            'border_radius' => Configuration::get($this->getConfigKey('BORDER_RADIUS')),
            'dark_mode' => Configuration::get($this->getConfigKey('DARK_MODE'))
        ];
    }
    
    /**
     * Check if widget should be displayed
     */
    private function shouldDisplayWidget(): bool
    {
        if (!Configuration::get(self::CONFIG_PREFIX . 'ENABLED')) {
            return false;
        }
        
        $phone = Configuration::get(self::CONFIG_PREFIX . 'PHONE');
        if (empty($phone)) {
            return false;
        }
        
        // Check page visibility
        $visiblePages = json_decode(Configuration::get(self::CONFIG_PREFIX . 'VISIBILITY_PAGES'), true) ?: [];
        $currentPage = $this->getCurrentPageType();
        
        if (!in_array($currentPage, $visiblePages)) {
            return false;
        }
        
        // Check device visibility
        $visibleDevices = json_decode(Configuration::get(self::CONFIG_PREFIX . 'VISIBILITY_DEVICES'), true) ?: [];
        $currentDevice = $this->context->isMobile() ? 'mobile' : 'desktop';
        
        if (!in_array($currentDevice, $visibleDevices)) {
            return false;
        }
        
        // Check working hours
        if (Configuration::get(self::CONFIG_PREFIX . 'WORKING_HOURS_ENABLED')) {
            $workingConfig = [
                'enabled' => true,
                'timezone' => Configuration::get('PS_TIMEZONE') ?: 'Europe/Istanbul',
                'working_days' => json_decode(Configuration::get(self::CONFIG_PREFIX . 'WORKING_DAYS'), true) ?: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'start_time' => Configuration::get(self::CONFIG_PREFIX . 'START_TIME'),
                'end_time' => Configuration::get(self::CONFIG_PREFIX . 'END_TIME')
            ];
            
            if (!WorkingHours::isWorkingTime($workingConfig)) {
                return false;
            }
        }
        
        // Check consent
        if (Configuration::get(self::CONFIG_PREFIX . 'CONSENT_REQUIRED')) {
            $consentCookies = Configuration::get(self::CONFIG_PREFIX . 'CONSENT_COOKIES');
            if (!empty($consentCookies)) {
                $cookies = explode(',', $consentCookies);
                foreach ($cookies as $cookie) {
                    if (!isset($_COOKIE[trim($cookie)])) {
                        return false;
                    }
                }
            }
        }
        
        return true;
    }

    /**
     * Render widget HTML
     */
    private function renderWidget(): string
    {
        if (!$this->shouldDisplayWidget()) {
            return '';
        }
        
        $currentPage = $this->getCurrentPageType();
        $message = $this->generateMessage($currentPage);
        
        $templateVars = [
            'phone' => Phone::sanitizePhone(Configuration::get(self::CONFIG_PREFIX . 'PHONE'))['phone'],
            'message' => $message,
            'position' => Configuration::get(self::CONFIG_PREFIX . 'POSITION'),
            'theme_color' => Configuration::get(self::CONFIG_PREFIX . 'THEME_COLOR'),
            'button_size' => Configuration::get(self::CONFIG_PREFIX . 'BUTTON_SIZE'),
            'border_radius' => Configuration::get(self::CONFIG_PREFIX . 'BORDER_RADIUS'),
            'dark_mode' => Configuration::get(self::CONFIG_PREFIX . 'DARK_MODE'),
            'working_hours_enabled' => Configuration::get(self::CONFIG_PREFIX . 'WORKING_HOURS_ENABLED'),
            'offline_message' => Configuration::get(self::CONFIG_PREFIX . 'OFFLINE_MESSAGE'),
            'current_page' => $currentPage
        ];
        
        $this->context->smarty->assign($templateVars);
        
        return $this->display(__FILE__, 'views/templates/hook/widget.tpl');
    }
    
    /**
     * Generate message based on page type
     */
    private function generateMessage(string $pageType): string
    {
        $defaultMessage = Configuration::get(self::CONFIG_PREFIX . 'DEFAULT_MESSAGE');
        $productMessage = Configuration::get(self::CONFIG_PREFIX . 'PRODUCT_MESSAGE');
        
        if ($pageType === 'product') {
            $product = $this->getProductData();
            if ($product) {
                return Template::processTemplate($productMessage, $product);
            }
        }
        
        $pageData = [
            'page_url' => $this->context->link->getPageLink($this->context->controller->php_self),
            'page_title' => $this->context->controller->getTitle() ?: '',
            'shop_name' => Configuration::get('PS_SHOP_NAME')
        ];
        
        return Template::processTemplate($defaultMessage, $pageData);
    }
    
    /**
     * Get current page type
     */
    private function getCurrentPageType(): string
    {
        $controller = $this->context->controller;
        
        if ($controller instanceof ProductController) {
            return 'product';
        } elseif ($controller instanceof CategoryController) {
            return 'category';
        } elseif ($controller instanceof IndexController) {
            return 'home';
        } elseif ($controller instanceof CartController) {
            return 'cart';
        } elseif ($controller instanceof OrderController) {
            return 'checkout';
        }
        
        return 'other';
    }
    
    /**
     * Get product data for message template
     */
    private function getProductData(): ?array
    {
        $controller = $this->context->controller;
        
        if (!($controller instanceof ProductController)) {
            return null;
        }
        
        try {
            $product = $controller->getProduct();
            if (!$product || !isset($product->id)) {
                return null;
            }
            
            $link = new Link();
            $productUrl = $link->getProductLink($product);
            
            $productName = is_array($product->name) ? 
                $product->name[$this->context->language->id] : 
                $product->name;
            
            $price = Product::getPriceStatic($product->id, true, null, 2);
            $formattedPrice = Tools::displayPrice($price);
            
            return [
                'product_name' => $productName,
                'product_url' => $productUrl,
                'price' => $formattedPrice,
                'product_id' => $product->id,
                'reference' => $product->reference ?: ''
            ];
        } catch (Exception $e) {
            return null;
        }
    }





    /**
     * Display configuration form
     */
    private function displayForm(): string
    {
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $this->context->controller->addJS($this->_path.'views/js/admin.js');
        
        // Generate CSRF token
        $csrfToken = WwSecurity::generateCSRFToken();
        
        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'module_name' => $this->name,
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'current_index' => AdminController::$currentIndex,
            'csrf_token' => $csrfToken,
            'link' => $this->context->link,
            'config_values' => $this->getConfigFieldsValues()
        ]);
        
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
    
    /**
     * Get configuration field values
     */
    private function getConfigFieldsValues(): array
    {
        $values = [];
        
        foreach ($this->configKeys as $key) {
            $configKey = self::CONFIG_PREFIX . $key;
            $value = Configuration::get($configKey);
            
            // Handle JSON fields
            if (in_array($key, ['VISIBILITY_PAGES', 'VISIBILITY_DEVICES', 'WORKING_DAYS'])) {
                $value = json_decode($value, true) ?: [];
            }
            
            $values[$configKey] = $value;
        }
        
        return $values;
    }
    
    /**
     * Register PSR-4 fallback autoloader when Composer is not available
     */
    private function registerFallbackAutoload(): void
    {
        static $booted = false;
        if ($booted) return;
        $booted = true;
        
        spl_autoload_register(function ($class) {
            $prefix = 'WhatsAppWidget\\';
            $baseDir = __DIR__ . '/src/';
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) return;
            $relative = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) require $file;
        });
    }
}