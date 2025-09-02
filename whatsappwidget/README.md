# WhatsApp Widget for PrestaShop 8.x

A modern, customizable WhatsApp widget module for PrestaShop 8.x with advanced admin panel and performance optimization.

## Features

### 🚀 Core Features
- **Floating & Inline Widget Styles**: Choose between floating action button or inline widget
- **Custom Hook Support**: Use `{hook h='displayCustomWhatsAppWidget'}` anywhere in your theme
- **Product URL Integration**: Automatically includes product URL in WhatsApp messages on product pages
- **Multi-Hook Support**: Display widget in header, footer, sidebar, or custom positions
- **Page-Specific Visibility**: Control widget visibility on product, category, home, or all pages

### 🎨 Design & Customization
- **Material Design Admin Panel**: Modern, responsive admin interface
- **Color Customization**: Choose custom colors for the widget
- **Size Options**: Small, medium, or large widget sizes
- **Position Control**: Bottom-right, bottom-left, top-right, or top-left positioning
- **Responsive Design**: Mobile-optimized with touch-friendly interactions

### ⚡ Performance Optimization
- **Conditional Asset Loading**: CSS/JS only loaded when widget is displayed
- **Minified CSS**: Optimized file sizes for faster loading
- **Cache-Friendly**: Efficient caching strategies
- **Lazy Loading**: Assets loaded only when needed

### 🔧 Technical Features
- **PrestaShop 8.x Compatible**: Built specifically for PrestaShop 8.x
- **Security Focused**: SQL injection protection and secure data handling
- **Cross-Browser Compatible**: Works on all modern browsers
- **Accessibility Support**: WCAG compliant with keyboard navigation
- **Dark Mode Support**: Automatic dark mode detection

## Installation

1. **Upload Module**:
   - Upload the `whatsappwidget` folder to `/modules/` directory
   - Or use PrestaShop's module installer

2. **Install Module**:
   - Go to Modules > Module Manager
   - Find "WhatsApp Widget" and click Install

3. **Configure Module**:
   - Click Configure after installation
   - Set your WhatsApp phone number (with country code)
   - Customize appearance and behavior

## Configuration

### Basic Settings
- **Enable Widget**: Turn the widget on/off
- **Phone Number**: Your WhatsApp number with country code (e.g., +905551234567)
- **Default Message**: Message template sent via WhatsApp

### Display Settings
- **Widget Style**: Floating (fixed position) or Inline (in content)
- **Display Hook**: Choose where to show the widget
- **Position**: Corner positioning for floating widgets

### Appearance
- **Color**: Custom background color
- **Size**: Small, medium, or large

### Page Visibility
- **Product Pages**: Show on product detail pages
- **Category Pages**: Show on category listing pages
- **Home Page**: Show on homepage
- **All Pages**: Override and show everywhere

## Custom Hook Usage

You can display the widget anywhere in your theme using the custom hook:

```smarty
{* In your theme template files *}
{hook h='displayCustomWhatsAppWidget'}
```

### Examples:

**In header.tpl:**
```smarty
<header>
    <!-- Your header content -->
    {hook h='displayCustomWhatsAppWidget'}
</header>
```

**In product.tpl:**
```smarty
<div class="product-actions">
    {hook h='displayCustomWhatsAppWidget'}
    <!-- Other product actions -->
</div>
```

**Conditional display:**
```smarty
{if $page.page_name == 'product'}
    {hook h='displayCustomWhatsAppWidget'}
{/if}
```

## File Structure

```
whatsappwidget/
├── whatsappwidget.php          # Main module file
├── config.xml                  # Module configuration
├── README.md                   # Documentation
├── views/
│   ├── templates/
│   │   ├── admin/
│   │   │   └── configure.tpl   # Admin panel template
│   │   └── hook/
│   │       ├── widget.tpl      # Widget template
│   │       └── custom-hook-example.tpl
│   └── css/
│       ├── admin.css           # Admin panel styles
│       ├── widget.css          # Widget styles
│       └── widget.min.css      # Minified widget styles
└── views/js/
    └── admin.js                # Admin panel JavaScript
```

## Hooks Used

- `displayFooter` - Footer area
- `displayHeader` - Header area
- `displayTop` - Top of page
- `displayLeftColumn` - Left sidebar
- `displayRightColumn` - Right sidebar
- `displayHome` - Homepage specific
- `displayProductButtons` - Product page buttons area
- `displayShoppingCartFooter` - Cart footer
- `displayCustomWhatsAppWidget` - Custom position (created by module)
- `actionFrontControllerSetMedia` - Asset loading

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Notes

- CSS/JS assets are only loaded when the widget is enabled and should be displayed
- Minified CSS reduces file size by ~60%
- Widget uses efficient CSS animations with hardware acceleration
- Responsive images and SVG icons for crisp display on all devices

## Security Features

- SQL injection protection using `pSQL()`
- XSS prevention with proper escaping
- Secure configuration handling
- Validation of all user inputs

## Troubleshooting

### Widget Not Showing
1. Check if module is enabled in configuration
2. Verify page visibility settings
3. Ensure correct hook is selected
4. Check if phone number is configured

### Styling Issues
1. Clear PrestaShop cache
2. Check for CSS conflicts
3. Verify widget.css is loading
4. Test with default theme

### Performance Issues
1. Enable CSS minification
2. Check server cache settings
3. Optimize images if using custom icons
4. Monitor asset loading in browser dev tools

## Changelog

### Version 1.0.0
- Initial release
- Material Design admin panel
- Custom hook support
- Performance optimization
- Mobile responsive design
- Accessibility features

## License

MIT License - Feel free to modify and distribute.

## Support

For support and customization requests, please contact the module developer.

---

**Developed for PrestaShop 8.x** | **Performance Optimized** | **Mobile Ready**