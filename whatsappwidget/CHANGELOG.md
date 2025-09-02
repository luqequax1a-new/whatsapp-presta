# Changelog

All notable changes to the WhatsApp Widget module will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-15

### Added
- Initial release of WhatsApp Widget module
- Floating WhatsApp button with customizable positioning
- Smart device detection (mobile vs desktop URL generation)
- Message templates with token replacement system
- Product page integration with dynamic product information
- Working hours control with offline state management
- GDPR-compliant consent management system
- Conditional asset loading for performance optimization
- Multi-page visibility controls (Home, Category, Product, CMS, Cart)
- Device-specific visibility settings (Desktop/Mobile)
- Customizable appearance options:
  - Custom theme colors
  - Multiple button sizes (Small, Medium, Large)
  - Border radius options
  - Dark mode support
- Advanced security features:
  - XSS protection with input validation
  - CSRF token protection
  - Rate limiting for configuration updates
  - Secure output escaping
- Accessibility features:
  - Full keyboard navigation support
  - Screen reader compatibility
  - ARIA labels and roles
  - High contrast mode support
  - Reduced motion preferences
- Performance optimizations:
  - Deferred JavaScript loading
  - Conditional CSS/JS injection
  - Minimal asset footprint (~15KB total)
  - Browser cache optimization
- Modern admin interface with:
  - Responsive design
  - Real-time validation
  - Intuitive configuration sections
  - Help tooltips and examples
- Google Analytics 4 integration:
  - Custom event tracking
  - Configurable event names
  - DataLayer push support
- PSR-4 autoloading with Composer
- PHP 8.1+ compatibility with typed properties
- PrestaShop 8.x and 9.x compatibility
- Comprehensive documentation and examples

### Technical Implementation
- **Main Module Class**: `whatsappwidget.php` with modern PHP practices
- **Security Layer**: `src/Security/Security.php` and `src/Security/Validator.php`
- **Utility Classes**:
  - `src/Util/Phone.php` - E.164 phone number validation
  - `src/Util/Template.php` - Message template processing
  - `src/Util/WorkingHours.php` - Business hours logic
- **Frontend Assets**:
  - `views/js/front.js` - Vanilla JavaScript (no dependencies)
  - `views/css/front.css` - Modern CSS with responsive design
- **Templates**:
  - `views/templates/admin/configure.tpl` - Admin configuration interface
  - `views/templates/hook/widget.tpl` - Frontend widget display
- **Hooks Integration**:
  - `hookHeader` - Conditional asset loading
  - `hookDisplayAfterBodyOpeningTag` - Widget rendering
  - `hookDisplayProductButtons` - Product page integration

### Security
- All user inputs validated and sanitized
- XSS protection on all outputs
- CSRF protection on form submissions
- Rate limiting to prevent abuse
- Secure random token generation
- Input length restrictions
- SQL injection prevention

### Performance
- Assets only load when widget is visible
- Deferred JavaScript execution
- Optimized CSS with critical path inlining
- Minimal DOM manipulation
- Efficient event handling
- Browser cache friendly headers

### Accessibility
- WCAG 2.1 AA compliance
- Keyboard navigation support
- Screen reader compatibility
- Focus management
- High contrast mode support
- Reduced motion preferences
- Semantic HTML structure

---

## Future Releases

### Planned for v1.1.0
- Multi-language support (i18n)
- Multi-store compatibility
- Additional message tokens
- Widget animation options
- Advanced analytics integration
- Custom CSS injection
- Widget scheduling
- A/B testing support

### Planned for v1.2.0
- WhatsApp Business API integration
- Chat history logging
- Automated responses
- Customer segmentation
- Advanced reporting dashboard
- Integration with CRM systems

---

## Support

For support and feature requests, please contact the module developer or check the documentation.

## License

This project is licensed under the Academic Free License (AFL 3.0).