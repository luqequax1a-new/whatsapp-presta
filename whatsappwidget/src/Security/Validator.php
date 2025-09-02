<?php

declare(strict_types=1);

namespace WhatsAppWidget\Security;

/**
 * Security validator class for WhatsApp Widget module
 * Provides validation and sanitization methods for user inputs
 */
class Validator
{
    /**
     * Maximum allowed length for message templates
     */
    public const MAX_MESSAGE_LENGTH = 1000;
    
    /**
     * Maximum allowed length for phone numbers
     */
    public const MAX_PHONE_LENGTH = 20;
    
    /**
     * Maximum allowed length for cookie names
     */
    public const MAX_COOKIE_NAME_LENGTH = 100;
    
    /**
     * Maximum allowed length for event names
     */
    public const MAX_EVENT_NAME_LENGTH = 50;
    
    /**
     * Allowed HTML tags for message templates
     */
    public const ALLOWED_HTML_TAGS = '<br><strong><em><u>';
    
    /**
     * Validate and sanitize phone number
     *
     * @param string $phone Phone number to validate
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validatePhone(string $phone): array
    {
        // Remove all whitespace and special characters except + and digits
        $sanitized = preg_replace('/[^+\d]/', '', $phone);
        
        if (empty($sanitized)) {
            return [
                'valid' => false,
                'sanitized' => '',
                'error' => 'Phone number is required'
            ];
        }
        
        if (strlen($sanitized) > self::MAX_PHONE_LENGTH) {
            return [
                'valid' => false,
                'sanitized' => $sanitized,
                'error' => 'Phone number is too long'
            ];
        }
        
        // E.164 format validation
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $sanitized)) {
            return [
                'valid' => false,
                'sanitized' => $sanitized,
                'error' => 'Phone number must be in E.164 format (e.g., +905551112233)'
            ];
        }
        
        return [
            'valid' => true,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Validate and sanitize message template
     *
     * @param string $message Message template to validate
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validateMessage(string $message): array
    {
        if (empty($message)) {
            return [
                'valid' => false,
                'sanitized' => '',
                'error' => 'Message template is required'
            ];
        }
        
        if (strlen($message) > self::MAX_MESSAGE_LENGTH) {
            return [
                'valid' => false,
                'sanitized' => $message,
                'error' => 'Message template is too long (max ' . self::MAX_MESSAGE_LENGTH . ' characters)'
            ];
        }
        
        // Strip dangerous HTML tags but allow basic formatting
        $sanitized = strip_tags($message, self::ALLOWED_HTML_TAGS);
        
        // Remove potential XSS vectors
        $sanitized = self::removeXSSVectors($sanitized);
        
        return [
            'valid' => true,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Validate hex color
     *
     * @param string $color Color value to validate
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validateColor(string $color): array
    {
        $sanitized = trim($color);
        
        if (empty($sanitized)) {
            $sanitized = '#25D366'; // Default WhatsApp green
        }
        
        // Ensure it starts with #
        if (!str_starts_with($sanitized, '#')) {
            $sanitized = '#' . $sanitized;
        }
        
        // Validate hex color format
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $sanitized)) {
            return [
                'valid' => false,
                'sanitized' => '#25D366',
                'error' => 'Invalid color format. Use hex format like #25D366'
            ];
        }
        
        return [
            'valid' => true,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Validate time format (HH:MM)
     *
     * @param string $time Time value to validate
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validateTime(string $time): array
    {
        $sanitized = trim($time);
        
        if (empty($sanitized)) {
            return [
                'valid' => false,
                'sanitized' => '',
                'error' => 'Time is required'
            ];
        }
        
        // Validate HH:MM format
        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $sanitized)) {
            return [
                'valid' => false,
                'sanitized' => $sanitized,
                'error' => 'Invalid time format. Use HH:MM format (e.g., 09:00)'
            ];
        }
        
        return [
            'valid' => true,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Validate cookie names list
     *
     * @param string $cookies Comma-separated cookie names
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validateCookieNames(string $cookies): array
    {
        $sanitized = trim($cookies);
        
        if (empty($sanitized)) {
            return [
                'valid' => true,
                'sanitized' => ''
            ];
        }
        
        // Split by comma and validate each cookie name
        $cookieNames = array_map('trim', explode(',', $sanitized));
        $validNames = [];
        
        foreach ($cookieNames as $name) {
            if (empty($name)) {
                continue;
            }
            
            if (strlen($name) > self::MAX_COOKIE_NAME_LENGTH) {
                return [
                    'valid' => false,
                    'sanitized' => $sanitized,
                    'error' => 'Cookie name "' . $name . '" is too long'
                ];
            }
            
            // Validate cookie name format (alphanumeric, underscore, hyphen)
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
                return [
                    'valid' => false,
                    'sanitized' => $sanitized,
                    'error' => 'Invalid cookie name "' . $name . '". Use only letters, numbers, underscore, and hyphen'
                ];
            }
            
            $validNames[] = $name;
        }
        
        return [
            'valid' => true,
            'sanitized' => implode(',', $validNames)
        ];
    }
    
    /**
     * Validate event name for dataLayer
     *
     * @param string $eventName Event name to validate
     * @return array{valid: bool, sanitized: string, error?: string}
     */
    public static function validateEventName(string $eventName): array
    {
        $sanitized = trim($eventName);
        
        if (empty($sanitized)) {
            $sanitized = 'whatsapp_click'; // Default event name
        }
        
        if (strlen($sanitized) > self::MAX_EVENT_NAME_LENGTH) {
            return [
                'valid' => false,
                'sanitized' => $sanitized,
                'error' => 'Event name is too long (max ' . self::MAX_EVENT_NAME_LENGTH . ' characters)'
            ];
        }
        
        // Validate event name format (alphanumeric, underscore)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $sanitized)) {
            return [
                'valid' => false,
                'sanitized' => $sanitized,
                'error' => 'Invalid event name. Use only letters, numbers, and underscore'
            ];
        }
        
        return [
            'valid' => true,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Validate array of allowed values
     *
     * @param array $values Values to validate
     * @param array $allowedValues Allowed values
     * @return array{valid: bool, sanitized: array, error?: string}
     */
    public static function validateAllowedValues(array $values, array $allowedValues): array
    {
        $sanitized = [];
        
        foreach ($values as $value) {
            if (in_array($value, $allowedValues, true)) {
                $sanitized[] = $value;
            }
        }
        
        return [
            'valid' => true,
            'sanitized' => array_unique($sanitized)
        ];
    }
    
    /**
     * Remove potential XSS vectors from string
     *
     * @param string $input Input string
     * @return string Sanitized string
     */
    private static function removeXSSVectors(string $input): string
    {
        // Remove javascript: protocol
        $input = preg_replace('/javascript:/i', '', $input);
        
        // Remove data: protocol
        $input = preg_replace('/data:/i', '', $input);
        
        // Remove vbscript: protocol
        $input = preg_replace('/vbscript:/i', '', $input);
        
        // Remove onload, onclick, etc. event handlers
        $input = preg_replace('/on\w+\s*=/i', '', $input);
        
        // Remove script tags
        $input = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $input);
        
        // Remove style tags
        $input = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $input);
        
        return $input;
    }
    
    /**
     * Sanitize output for HTML display
     *
     * @param string $output Output string
     * @return string Sanitized output
     */
    public static function sanitizeOutput(string $output): string
    {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Sanitize URL for href attributes
     *
     * @param string $url URL to sanitize
     * @return string Sanitized URL
     */
    public static function sanitizeUrl(string $url): string
    {
        // Only allow http, https, and wa.me protocols
        if (!preg_match('/^(https?:\/\/|wa\.me\/)/i', $url)) {
            return '';
        }
        
        return htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}