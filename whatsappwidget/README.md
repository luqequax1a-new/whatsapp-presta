# 💬 WhatsApp Widget for PrestaShop

<div align="center">

![PrestaShop](https://img.shields.io/badge/PrestaShop-8.x%20%7C%209.x-blue?style=for-the-badge&logo=prestashop)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Version](https://img.shields.io/badge/Version-2.0.0-orange?style=for-the-badge)

**🚀 Professional, lightweight and feature-rich WhatsApp widget module with advanced customization, GDPR compliance, and performance optimization.**

[📥 Download](#installation) • [📖 Documentation](#configuration) • [🎯 Features](#features) • [🛠️ Support](#support)

</div>

---

## ✨ Features

### 🎯 **Core Functionality**
- 📱 **Smart Device Detection** - Automatically opens `wa.me` on mobile, `web.whatsapp.com` on desktop
- 🎨 **Floating Action Button** - Customizable FAB with multiple positioning options
- 📝 **Dynamic Message Templates** - Token replacement for personalized messages
- 🛍️ **Product Integration** - Automatic product information inclusion
- ⏰ **Working Hours Control** - Business hours with offline state management

### 🔒 **Privacy & Compliance**
- 🍪 **GDPR Consent Management** - Customizable cookie requirements
- 🛡️ **Security First** - XSS protection, CSRF tokens, input validation
- 🔐 **Template Security** - Automatic sanitization of unknown tokens
- 📊 **Privacy-Friendly Analytics** - Optional dataLayer integration

### ♿ **Accessibility & UX**
- ⌨️ **Full Keyboard Navigation** - Tab, Enter, Space key support
- 🔊 **Screen Reader Support** - ARIA labels and descriptions
- 🌙 **Dark Mode Support** - Automatic theme detection
- 📱 **Responsive Design** - Perfect on all devices
- 🎭 **High Contrast Mode** - Enhanced visibility support

### ⚡ **Performance & Technical**
- 🚀 **Lightweight** - Minimal asset footprint with deferred loading
- 🌍 **Timezone Support** - Store timezone with DST handling
- 🔧 **Debug Mode** - Advanced logging with `?ww_debug=1`
- 📦 **No Dependencies** - Pure vanilla JavaScript, no jQuery
- 🎯 **Modern Code** - PHP 8.1+ with typed properties, PSR-12 compliance

---

## 🎮 Use Cases

### 🛒 **E-commerce Stores**
- **Product Support**: Instant customer support on product pages
- **Order Assistance**: Help with checkout process and payment issues
- **Size Guidance**: Clothing and shoe size consultations
- **Stock Inquiries**: Real-time inventory questions

### 🏢 **Service Businesses**
- **Appointment Booking**: Quick scheduling via WhatsApp
- **Quote Requests**: Instant price estimates
- **Technical Support**: Real-time troubleshooting
- **Consultation Services**: Professional advice and guidance

### 🏪 **Local Businesses**
- **Store Hours**: Working hours with timezone support
- **Location Services**: Directions and store information
- **Delivery Inquiries**: Order tracking and delivery updates
- **Local Promotions**: Exclusive offers and discounts

### 🎓 **Educational Platforms**
- **Course Inquiries**: Program information and enrollment
- **Student Support**: Academic assistance and guidance
- **Technical Help**: Platform usage and troubleshooting
- **Admission Process**: Application and documentation support

---

## 🛠️ Installation

### 📦 **Method 1: Admin Panel Upload**
```bash
1. Download the latest release ZIP file
2. Go to Modules > Module Manager in PrestaShop admin
3. Click "Upload a module" and select the ZIP file
4. Click "Configure" after successful installation
```

### 💻 **Method 2: Manual Installation**
```bash
# Extract to modules directory
unzip whatsappwidget.zip -d /path/to/prestashop/modules/

# Set proper permissions
chmod -R 755 modules/whatsappwidget/
chown -R www-data:www-data modules/whatsappwidget/
```

### 🔧 **Method 3: Composer (Development)**
```bash
composer require luqeq/prestashop-whatsapp-widget
```

---

## ⚙️ Configuration

### 📱 **Basic Setup**
1. **Phone Number**: Enter your WhatsApp business number (with country code)
2. **Default Message**: Customize the initial message template
3. **Position**: Choose bottom-right or bottom-left placement
4. **Visibility**: Select pages where widget should appear

### 🎨 **Appearance Customization**
```css
/* Custom CSS examples */
#whatsapp-widget .whatsapp-button {
    background: #your-brand-color;
    border-radius: 50%; /* or 8px for rounded corners */
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
```

### ⏰ **Working Hours Setup**
- Configure business days and hours
- Automatic timezone detection from store settings
- Offline message customization
- Next available time display

### 🍪 **GDPR Compliance**
- Enable consent requirement
- Configure cookie names
- Customize consent messages
- Debug consent issues with console logging

### 🔗 **Hook Integration**
- **Primary Hook**: `displayAfterBodyOpeningTag` for optimal placement
- **Fallback Strategy**: If primary hook is disabled in theme, automatically falls back to `displayFooterAfter`
- **Theme Compatibility**: Ensures widget displays correctly across all PrestaShop themes
- **Note**: Some themes may have `displayAfterBodyOpeningTag` disabled; the fallback ensures consistent functionality

---

## 🔧 Advanced Features

### 🐛 **Debug Mode**
Enable detailed logging by adding `?ww_debug=1` to any page URL:
```javascript
// Console output examples
[WhatsApp Widget] Widget initialized
[WhatsApp Widget] Working hours check: true
[WhatsApp Widget] Consent status: granted
[WhatsApp Widget] Opening chat with message: "Hello from {product_name}"
```

### 📊 **Analytics Integration**
```javascript
// Google Analytics 4 / GTM integration
dataLayer.push({
    'event': 'whatsapp_click',
    'page_type': 'product',
    'product_id': '123',
    'product_name': 'Sample Product'
});
```

### 🎯 **Message Templates**
Supported tokens for dynamic content:
- `{product_name}` - Current product name
- `{product_price}` - Product price with currency
- `{product_url}` - Direct product link
- `{store_name}` - Your store name
- `{page_title}` - Current page title

### 🌐 **Multi-language Support**
The module automatically adapts to your store's language settings:
- Interface translations
- Message templates per language
- Timezone-aware working hours
- Localized date/time formats

---

## 📋 Requirements

| Component | Version | Notes |
|-----------|---------|-------|
| **PrestaShop** | 8.x - 9.x | Tested on latest versions |
| **PHP** | 8.1+ | Required for modern features |
| **MySQL** | 5.7+ / 8.0+ | Standard PrestaShop requirement |
| **Browser** | Modern browsers | Chrome 90+, Firefox 88+, Safari 14+ |

---

## 🎨 Customization Examples

### 🌈 **Brand Colors**
```css
/* Match your brand colors */
:root {
    --whatsapp-primary: #your-brand-color;
    --whatsapp-hover: #your-hover-color;
}
```

### 📱 **Mobile Optimization**
```css
/* Mobile-specific adjustments */
@media (max-width: 768px) {
    #whatsapp-widget {
        bottom: 80px; /* Above mobile navigation */
        right: 15px;
    }
}
```

### 🎭 **Animation Customization**
```css
/* Custom animations */
.whatsapp-button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.whatsapp-button:hover {
    transform: scale(1.1) rotate(5deg);
}
```

---

## 🚀 Performance

- **⚡ Fast Loading**: Assets load only when needed
- **📦 Small Footprint**: < 15KB total (CSS + JS)
- **🔄 Efficient Caching**: Browser and server-side optimization
- **📱 Mobile Optimized**: Minimal impact on mobile performance
- **🎯 Conditional Loading**: Loads only on configured pages

---

## 🛡️ Security

- ✅ **XSS Protection**: All user inputs sanitized
- ✅ **CSRF Tokens**: Form submissions protected
- ✅ **Input Validation**: Server-side validation for all data
- ✅ **Template Security**: Unknown tokens automatically removed
- ✅ **Permission Checks**: Admin-only configuration access

---

## 🔄 Changelog

### 🆕 **v2.0.0** - Latest Release
- ✨ **New**: Timezone support with DST handling
- ✨ **New**: Enhanced accessibility (ARIA, keyboard navigation)
- ✨ **New**: Toast notification system
- ✨ **New**: Admin preview functionality
- ✨ **New**: Debug mode with detailed logging
- 🔧 **Improved**: Template security and token sanitization
- 🔧 **Improved**: Popup blocker compatibility
- 🔧 **Improved**: GDPR consent management
- 🐛 **Fixed**: Working hours timezone issues
- 🐛 **Fixed**: Mobile device detection

---

## 🤝 Support

### 📞 **Getting Help**
- 📧 **Email**: support@example.com
- 💬 **WhatsApp**: +1234567890
- 🐛 **Issues**: [GitHub Issues](https://github.com/username/prestashop-whatsapp-widget/issues)
- 📖 **Documentation**: [Wiki](https://github.com/username/prestashop-whatsapp-widget/wiki)

### 🤝 **Contributing**
We welcome contributions! Please read our [Contributing Guide](CONTRIBUTING.md) for details.

### 📄 **License**
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<div align="center">

**Made with ❤️ for the PrestaShop community**

⭐ **Star this repo if it helped you!** ⭐

</div>