<?php
/**
 * WhatsApp Widget Module for PrestaShop 8.x
 * 
 * @author    Your Name
 * @copyright 2024
 * @license   Academic Free License (AFL 3.0)
 * @version   1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class WhatsAppWidget extends Module
{
    public function __construct()
    {
        $this->name = 'whatsappwidget';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('WhatsApp Widget');
        $this->description = $this->l('Customizable WhatsApp widget for product pages with admin configuration.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        if (!Configuration::get('WHATSAPP_WIDGET_ENABLED')) {
            $this->warning = $this->l('No configuration provided');
        }
    }

    /**
     * Install module
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayTop') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayShoppingCartFooter') &&
            $this->registerHook('displayCustomWhatsAppWidget') &&
            $this->createTables() &&
            $this->installConfiguration() &&
            $this->createCustomHook();
    }

    /**
     * Uninstall module
     */
    public function uninstall()
    {
        return parent::uninstall() &&
            $this->dropTables() &&
            $this->uninstallConfiguration();
    }

    /**
     * Create database tables
     */
    private function createTables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'whatsapp_widget_config` (
            `id_config` int(11) NOT NULL AUTO_INCREMENT,
            `phone_number` varchar(20) NOT NULL,
            `message_text` text,
            `widget_position` varchar(20) DEFAULT "bottom-right",
            `widget_color` varchar(7) DEFAULT "#25D366",
            `widget_size` varchar(10) DEFAULT "medium",
            `show_on_product` tinyint(1) DEFAULT 1,
            `show_on_category` tinyint(1) DEFAULT 1,
            `show_on_home` tinyint(1) DEFAULT 1,
            `custom_css` text,
            `is_active` tinyint(1) DEFAULT 1,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_config`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Drop database tables
     */
    private function dropTables()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'whatsapp_widget_config`';
        return Db::getInstance()->execute($sql);
    }

    /**
     * Install default configuration
     */
    private function installConfiguration()
    {
        return Configuration::updateValue('WHATSAPP_WIDGET_ENABLED', true) &&
               Configuration::updateValue('WHATSAPP_WIDGET_PHONE', '') &&
               Configuration::updateValue('WHATSAPP_WIDGET_MESSAGE', 'Merhaba! Bu ürün hakkında bilgi almak istiyorum.') &&
               Configuration::updateValue('WHATSAPP_WIDGET_POSITION', 'bottom-right') &&
               Configuration::updateValue('WHATSAPP_WIDGET_COLOR', '#25D366') &&
               Configuration::updateValue('WHATSAPP_WIDGET_SIZE', 'medium') &&
               Configuration::updateValue('WHATSAPP_WIDGET_STYLE', 'floating') &&
               Configuration::updateValue('WHATSAPP_WIDGET_HOOK', 'displayFooter') &&
               Configuration::updateValue('WHATSAPP_WIDGET_SHOW_PRODUCT', 1) &&
               Configuration::updateValue('WHATSAPP_WIDGET_SHOW_CATEGORY', 1) &&
               Configuration::updateValue('WHATSAPP_WIDGET_SHOW_HOME', 1) &&
               Configuration::updateValue('WHATSAPP_WIDGET_SHOW_ALL', 0);
    }

    /**
     * Uninstall configuration
     */
    private function uninstallConfiguration()
    {
        return Configuration::deleteByName('WHATSAPP_WIDGET_ENABLED') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_PHONE') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_MESSAGE') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_POSITION') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_COLOR') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_SIZE') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_STYLE') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_HOOK') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_SHOW_PRODUCT') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_SHOW_CATEGORY') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_SHOW_HOME') &&
               Configuration::deleteByName('WHATSAPP_WIDGET_SHOW_ALL');
    }

    /**
     * Add CSS and JS files (Performance optimized)
     */
    public function hookActionFrontControllerSetMedia()
    {
        // Only load assets if widget is enabled
        if (!Configuration::get('WHATSAPP_WIDGET_ENABLED')) {
            return;
        }

        // Check if widget should be displayed on current page
        $controller = $this->context->controller;
        $isProduct = ($controller instanceof ProductController);
        $isCategory = ($controller instanceof CategoryController);
        $isHome = ($controller instanceof IndexController);

        $showAll = Configuration::get('WHATSAPP_WIDGET_SHOW_ALL');
        if (!$showAll) {
            $showProduct = Configuration::get('WHATSAPP_WIDGET_SHOW_PRODUCT');
            $showCategory = Configuration::get('WHATSAPP_WIDGET_SHOW_CATEGORY');
            $showHome = Configuration::get('WHATSAPP_WIDGET_SHOW_HOME');

            // Don't load assets if widget won't be displayed
            if (($isProduct && !$showProduct) ||
                ($isCategory && !$showCategory) ||
                ($isHome && !$showHome) ||
                (!$isProduct && !$isCategory && !$isHome)) {
                return;
            }
        }

        // Load minified CSS with cache busting
        $cssFile = $this->_path.'views/css/widget.css';
        if (file_exists(_PS_MODULE_DIR_.$this->name.'/views/css/widget.min.css')) {
            $cssFile = $this->_path.'views/css/widget.min.css';
        }
        $this->context->controller->addCSS($cssFile, 'all', null, false);

        // Load widget JS inline for better performance (small script)
        $this->context->controller->addJqueryPlugin('fancybox');
    }

    /**
     * Create custom hook for flexible positioning
     */
    private function createCustomHook()
    {
        $hook = new Hook();
        $hook->name = 'displayCustomWhatsAppWidget';
        $hook->title = 'Custom WhatsApp Widget Position';
        $hook->description = 'Hook for displaying WhatsApp widget in custom positions';
        $hook->position = 1;
        $hook->live_edit = false;
        
        try {
            $hook->add();
        } catch (Exception $e) {
            // Hook might already exist
        }
        
        return true;
    }

    /**
     * Universal widget display method
     */
    private function displayWidget($hook_name = '')
    {
        if (!Configuration::get('WHATSAPP_WIDGET_ENABLED')) {
            return;
        }

        $phone = Configuration::get('WHATSAPP_WIDGET_PHONE');
        if (empty($phone)) {
            return;
        }

        // Check if this hook is the selected display hook
        $selected_hook = Configuration::get('WHATSAPP_WIDGET_HOOK', 'displayFooter');
        if ($hook_name !== $selected_hook) {
            return;
        }

        // Check if widget should be displayed on current page type
        $controller = $this->context->controller;
        $show_widget = false;
        
        if ($controller instanceof ProductController && Configuration::get('WHATSAPP_WIDGET_SHOW_PRODUCT', 1)) {
            $show_widget = true;
        } elseif ($controller instanceof CategoryController && Configuration::get('WHATSAPP_WIDGET_SHOW_CATEGORY', 1)) {
            $show_widget = true;
        } elseif ($controller instanceof IndexController && Configuration::get('WHATSAPP_WIDGET_SHOW_HOME', 1)) {
            $show_widget = true;
        } elseif (Configuration::get('WHATSAPP_WIDGET_SHOW_ALL', 0)) {
            $show_widget = true;
        }

        if (!$show_widget) {
            return;
        }

        // Get product URL if on product page
        $productUrl = '';
        $productName = '';
        $isProduct = ($controller instanceof ProductController);
        if ($isProduct) {
            try {
                $product = $controller->getProduct();
                if ($product && isset($product->id)) {
                    $link = new Link();
                    $productUrl = $link->getProductLink($product);
                    
                    // Get product name
                    if (isset($product->name)) {
                        $productName = is_array($product->name) ? 
                            $product->name[$this->context->language->id] : 
                            $product->name;
                    }
                }
            } catch (Exception $e) {
                // Fallback if product cannot be retrieved
                $productUrl = '';
                $productName = '';
            }
        }

        // Prepare dynamic message
        $baseMessage = Configuration::get('WHATSAPP_WIDGET_MESSAGE');
        $dynamicMessage = $baseMessage;
        
        if ($isProduct && $productUrl) {
            if ($productName) {
                $dynamicMessage = $baseMessage . "\n\n" . 
                    $this->l('Product: ') . $productName . "\n" . $productUrl;
            } else {
                $dynamicMessage = $baseMessage . "\n\n" . $productUrl;
            }
        }

        $this->context->smarty->assign([
            'widget_enabled' => true,
            'widget_phone' => Configuration::get('WHATSAPP_WIDGET_PHONE'),
            'widget_message' => $dynamicMessage,
            'widget_position' => Configuration::get('WHATSAPP_WIDGET_POSITION'),
            'widget_color' => Configuration::get('WHATSAPP_WIDGET_COLOR'),
            'widget_size' => Configuration::get('WHATSAPP_WIDGET_SIZE'),
            'widget_style' => Configuration::get('WHATSAPP_WIDGET_STYLE'),
            'hook_name' => $hook_name,
            'product_url' => $productUrl,
            'product_name' => $productName,
            'is_product_page' => $isProduct
        ]);

        return $this->display(__FILE__, 'views/templates/hook/whatsapp-widget.tpl');
    }

    /**
     * Display widget in footer
     */
    public function hookDisplayFooter()
    {
        return $this->displayWidget('displayFooter');
    }

    /**
     * Display widget in header
     */
    public function hookDisplayHeader()
    {
        return $this->displayWidget('displayHeader');
    }

    /**
     * Display widget in top
     */
    public function hookDisplayTop()
    {
        return $this->displayWidget('displayTop');
    }

    /**
     * Display widget in left column
     */
    public function hookDisplayLeftColumn()
    {
        return $this->displayWidget('displayLeftColumn');
    }

    /**
     * Display widget in right column
     */
    public function hookDisplayRightColumn()
    {
        return $this->displayWidget('displayRightColumn');
    }

    /**
     * Display widget on home page
     */
    public function hookDisplayHome()
    {
        return $this->displayWidget('displayHome');
    }

    /**
     * Display widget on product page buttons area
     */
    public function hookDisplayProductButtons()
    {
        return $this->displayWidget('displayProductButtons');
    }

    /**
     * Display widget in shopping cart footer
     */
    public function hookDisplayShoppingCartFooter()
    {
        return $this->displayWidget('displayShoppingCartFooter');
    }

    /**
     * Display widget in custom position
     */
    public function hookDisplayCustomWhatsAppWidget()
    {
        return $this->displayWidget('displayCustomWhatsAppWidget');
    }



    /**
     * Module configuration page
     */
    public function getContent()
    {
        $output = '';

        // Process form submission
        if (Tools::isSubmit('submitWhatsAppWidget')) {
            $this->processConfiguration();
            $output .= $this->displayConfirmation($this->l('Settings updated successfully!'));
        }

        // Add CSS and JS for admin panel
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $this->context->controller->addJS($this->_path.'views/js/admin.js');

        // Assign template variables
        $this->context->smarty->assign($this->getConfigFieldsValues());
        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'link' => $this->context->link
        ]);

        // Display modern admin template
        $output .= $this->display(__FILE__, 'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
     * Process configuration form submission
     */
    protected function processConfiguration()
    {
        $form_values = [
            'WHATSAPP_WIDGET_ENABLED' => (int)Tools::getValue('WHATSAPP_WIDGET_ENABLED'),
            'WHATSAPP_WIDGET_PHONE' => pSQL(Tools::getValue('WHATSAPP_WIDGET_PHONE')),
            'WHATSAPP_WIDGET_MESSAGE' => pSQL(Tools::getValue('WHATSAPP_WIDGET_MESSAGE')),
            'WHATSAPP_WIDGET_POSITION' => pSQL(Tools::getValue('WHATSAPP_WIDGET_POSITION')),
            'WHATSAPP_WIDGET_COLOR' => pSQL(Tools::getValue('WHATSAPP_WIDGET_COLOR')),
            'WHATSAPP_WIDGET_SIZE' => pSQL(Tools::getValue('WHATSAPP_WIDGET_SIZE')),
            'WHATSAPP_WIDGET_STYLE' => pSQL(Tools::getValue('WHATSAPP_WIDGET_STYLE')),
            'WHATSAPP_WIDGET_HOOK' => pSQL(Tools::getValue('WHATSAPP_WIDGET_HOOK')),
            'WHATSAPP_WIDGET_SHOW_PRODUCT' => (int)Tools::getValue('WHATSAPP_WIDGET_SHOW_PRODUCT'),
            'WHATSAPP_WIDGET_SHOW_CATEGORY' => (int)Tools::getValue('WHATSAPP_WIDGET_SHOW_CATEGORY'),
            'WHATSAPP_WIDGET_SHOW_HOME' => (int)Tools::getValue('WHATSAPP_WIDGET_SHOW_HOME'),
            'WHATSAPP_WIDGET_SHOW_ALL' => (int)Tools::getValue('WHATSAPP_WIDGET_SHOW_ALL')
        ];

        foreach ($form_values as $key => $value) {
            Configuration::updateValue($key, $value);
        }

        return true;
    }


}