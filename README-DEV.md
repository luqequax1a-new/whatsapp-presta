# WhatsApp Widget Module - Developer Guide

## 🛠️ Development Setup

### Prerequisites
- PHP 7.4+ or 8.x
- PrestaShop 8.x development environment
- Composer (optional, for future dependencies)
- Git
- Node.js (for future build tools)

### Local Development

1. **Clone the repository**
   ```bash
   git clone https://github.com/luqequax1a-new/whatsapp-presta.git
   cd whatsapp-presta
   ```

2. **Install in PrestaShop**
   ```bash
   # Copy module to PrestaShop modules directory
   cp -r whatsappwidget /path/to/prestashop/modules/
   
   # Or create symlink for development
   ln -s $(pwd)/whatsappwidget /path/to/prestashop/modules/whatsappwidget
   ```

3. **Enable Developer Mode**
   ```php
   // In config/defines.inc.php
   define('_PS_MODE_DEV_', true);
   ```

## 📁 Project Structure

```
whatsappwidget/
├── whatsappwidget.php          # Main module class
├── config.xml                  # Module configuration
├── README.md                   # User documentation
├── views/
│   ├── css/
│   │   ├── admin.css          # Admin panel styles
│   │   ├── widget.css         # Frontend widget styles
│   │   └── widget.min.css     # Minified frontend styles
│   ├── js/
│   │   └── admin.js           # Admin panel JavaScript
│   └── templates/
│       ├── admin/
│       │   └── configure.tpl   # Admin configuration template
│       └── hook/
│           ├── widget.tpl      # Frontend widget template
│           └── custom-hook-example.tpl  # Custom hook examples
└── translations/               # Future: Translation files
    └── en.php
```

## 🔧 Development Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Use PrestaShop coding conventions
- Comment complex logic
- Use meaningful variable names

### CSS/JS Guidelines
- Use BEM methodology for CSS classes
- Prefix all CSS classes with `whatsapp-widget-`
- Minify CSS/JS for production
- Use modern ES6+ JavaScript

### Security Best Practices
- Validate all user inputs
- Escape output data
- Use PrestaShop's built-in security functions
- Never expose sensitive configuration

## 🧪 Testing

### Manual Testing Checklist
- [ ] Module installation/uninstallation
- [ ] Admin panel configuration
- [ ] Widget display on different page types
- [ ] Mobile responsiveness
- [ ] Cross-browser compatibility
- [ ] Performance impact

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🚀 Build Process

### CSS Minification
```bash
# Using online tools or build scripts
# Input: views/css/widget.css
# Output: views/css/widget.min.css
```

### Future Build Tools
```bash
# Package.json for future use
npm install
npm run build
npm run watch
```

## 📊 Performance Considerations

### Optimization Strategies
- Conditional loading based on page type
- Minified CSS/JS files
- Efficient hook usage
- Minimal DOM manipulation
- CSS-only animations where possible

### Performance Metrics
- Widget load time: < 100ms
- CSS file size: < 10KB
- JavaScript execution: < 50ms
- Memory usage: < 1MB

## 🔌 Hook System

### Available Hooks
- `actionFrontControllerSetMedia` - Load CSS/JS
- `displayFooter` - Footer display
- `displayHeader` - Header display
- `displayCustomWhatsAppWidget` - Custom positioning

### Adding New Hooks
```php
// In whatsappwidget.php
public function hookDisplayNewPosition($params)
{
    if (!$this->shouldDisplayWidget()) {
        return '';
    }
    
    return $this->displayWidget($params);
}
```

## 🌐 Internationalization (i18n)

### Adding Translations
```php
// In translations/en.php
$_MODULE['<{whatsappwidget}prestashop>whatsappwidget_config_title'] = 'WhatsApp Widget Configuration';

// Usage in templates
{l s='Configuration' mod='whatsappwidget'}
```

### Supported Languages (Future)
- English (en)
- Turkish (tr)
- Spanish (es)
- French (fr)
- German (de)

## 🐛 Debugging

### Debug Mode
```php
// Enable debug in module
define('WHATSAPP_WIDGET_DEBUG', true);

// Debug output
if (defined('WHATSAPP_WIDGET_DEBUG') && WHATSAPP_WIDGET_DEBUG) {
    error_log('WhatsApp Widget: ' . $message);
}
```

### Common Issues
1. **Widget not displaying**: Check page type configuration
2. **Styling issues**: Verify CSS loading and conflicts
3. **JavaScript errors**: Check browser console
4. **Performance issues**: Review hook usage and file sizes

## 🤝 Contributing

### Development Workflow
1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Commit Message Format
```
type(scope): description

feat(widget): add new animation effects
fix(admin): resolve configuration save issue
docs(readme): update installation guide
style(css): improve mobile responsiveness
refactor(hooks): optimize hook performance
test(unit): add widget display tests
```

### Pull Request Guidelines
- Include description of changes
- Add screenshots for UI changes
- Update documentation if needed
- Ensure all tests pass
- Follow code style guidelines

## 📈 Future Roadmap

### Version 2.0 Features
- [ ] WhatsApp Business API integration
- [ ] Advanced analytics dashboard
- [ ] A/B testing capabilities
- [ ] Multi-language support
- [ ] Custom message templates
- [ ] Integration with CRM systems

### Version 2.1 Features
- [ ] AI-powered message suggestions
- [ ] Advanced targeting rules
- [ ] Performance monitoring
- [ ] API for third-party integrations

## 📞 Developer Support

- **Issues**: [GitHub Issues](https://github.com/luqequax1a-new/whatsapp-presta/issues)
- **Discussions**: [GitHub Discussions](https://github.com/luqequax1a-new/whatsapp-presta/discussions)
- **Documentation**: [Wiki](https://github.com/luqequax1a-new/whatsapp-presta/wiki)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

**Happy Coding! 🚀**