<?php

declare(strict_types=1);

namespace WhatsAppWidget\Security;

/**
 * Security class for WhatsApp Widget module
 * Provides CSRF protection and security utilities
 */
class Security
{
    /**
     * CSRF token session key
     */
    private const CSRF_TOKEN_KEY = 'whatsapp_widget_csrf_token';
    
    /**
     * Token lifetime in seconds (30 minutes)
     */
    private const TOKEN_LIFETIME = 1800;
    
    /**
     * Generate CSRF token
     *
     * @return string Generated token
     */
    public static function generateCSRFToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $timestamp = time();
        
        $_SESSION[self::CSRF_TOKEN_KEY] = [
            'token' => $token,
            'timestamp' => $timestamp
        ];
        
        return $token;
    }
    
    /**
     * Validate CSRF token
     *
     * @param string $token Token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateCSRFToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::CSRF_TOKEN_KEY])) {
            return false;
        }
        
        $sessionData = $_SESSION[self::CSRF_TOKEN_KEY];
        
        // Check if token has expired
        if (time() - $sessionData['timestamp'] > self::TOKEN_LIFETIME) {
            unset($_SESSION[self::CSRF_TOKEN_KEY]);
            return false;
        }
        
        // Use hash_equals to prevent timing attacks
        $isValid = hash_equals($sessionData['token'], $token);
        
        if ($isValid) {
            // Regenerate token after successful validation
            unset($_SESSION[self::CSRF_TOKEN_KEY]);
        }
        
        return $isValid;
    }
    
    /**
     * Get current CSRF token
     *
     * @return string|null Current token or null if not set
     */
    public static function getCurrentCSRFToken(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::CSRF_TOKEN_KEY])) {
            return null;
        }
        
        $sessionData = $_SESSION[self::CSRF_TOKEN_KEY];
        
        // Check if token has expired
        if (time() - $sessionData['timestamp'] > self::TOKEN_LIFETIME) {
            unset($_SESSION[self::CSRF_TOKEN_KEY]);
            return null;
        }
        
        return $sessionData['token'];
    }
    
    /**
     * Validate admin access
     *
     * @return bool True if user has admin access
     */
    public static function validateAdminAccess(): bool
    {
        // Check if user is logged in as admin
        if (!isset($_COOKIE['psAdmin']) && !isset($_SESSION['psAdmin'])) {
            return false;
        }
        
        // Additional checks can be added here
        return true;
    }
    
    /**
     * Sanitize configuration value
     *
     * @param mixed $value Value to sanitize
     * @param string $type Type of value (string, int, bool, array)
     * @return mixed Sanitized value
     */
    public static function sanitizeConfigValue($value, string $type)
    {
        switch ($type) {
            case 'string':
                return is_string($value) ? trim($value) : '';
                
            case 'int':
                return is_numeric($value) ? (int)$value : 0;
                
            case 'bool':
                return (bool)$value;
                
            case 'array':
                return is_array($value) ? $value : [];
                
            case 'json':
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    return is_array($decoded) ? $decoded : [];
                }
                return is_array($value) ? $value : [];
                
            default:
                return $value;
        }
    }
    
    /**
     * Rate limiting for form submissions
     *
     * @param string $identifier Unique identifier (IP, user ID, etc.)
     * @param int $maxAttempts Maximum attempts allowed
     * @param int $timeWindow Time window in seconds
     * @return bool True if within limits, false if rate limited
     */
    public static function checkRateLimit(string $identifier, int $maxAttempts = 5, int $timeWindow = 300): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $key = 'rate_limit_' . md5($identifier);
        $now = time();
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        
        // Clean old attempts
        $_SESSION[$key] = array_filter($_SESSION[$key], function($timestamp) use ($now, $timeWindow) {
            return ($now - $timestamp) < $timeWindow;
        });
        
        // Check if limit exceeded
        if (count($_SESSION[$key]) >= $maxAttempts) {
            return false;
        }
        
        // Add current attempt
        $_SESSION[$key][] = $now;
        
        return true;
    }
    
    /**
     * Generate secure random string
     *
     * @param int $length Length of the string
     * @return string Random string
     */
    public static function generateRandomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Hash sensitive data
     *
     * @param string $data Data to hash
     * @param string $salt Optional salt
     * @return string Hashed data
     */
    public static function hashData(string $data, string $salt = ''): string
    {
        return hash('sha256', $data . $salt);
    }
    
    /**
     * Validate referrer to prevent CSRF
     *
     * @param string $expectedDomain Expected domain
     * @return bool True if referrer is valid
     */
    public static function validateReferrer(string $expectedDomain): bool
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            return false;
        }
        
        $referrerHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        return $referrerHost === $expectedDomain;
    }
    
    /**
     * Log security event
     *
     * @param string $event Event description
     * @param array $context Additional context
     * @return void
     */
    public static function logSecurityEvent(string $event, array $context = []): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'context' => $context
        ];
        
        // In a real implementation, this would write to a secure log file
        // For now, we'll use error_log
        error_log('WhatsApp Widget Security Event: ' . json_encode($logData));
    }
    
    /**
     * Validate file upload security
     *
     * @param array $file $_FILES array element
     * @param array $allowedTypes Allowed MIME types
     * @param int $maxSize Maximum file size in bytes
     * @return array{valid: bool, error?: string}
     */
    public static function validateFileUpload(array $file, array $allowedTypes = [], int $maxSize = 1048576): array
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'Invalid file upload'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'File too large'];
        }
        
        if (!empty($allowedTypes)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes, true)) {
                return ['valid' => false, 'error' => 'Invalid file type'];
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * Clean temporary files and sessions
     *
     * @return void
     */
    public static function cleanup(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            return;
        }
        
        // Clean expired CSRF tokens
        if (isset($_SESSION[self::CSRF_TOKEN_KEY])) {
            $sessionData = $_SESSION[self::CSRF_TOKEN_KEY];
            if (time() - $sessionData['timestamp'] > self::TOKEN_LIFETIME) {
                unset($_SESSION[self::CSRF_TOKEN_KEY]);
            }
        }
        
        // Clean old rate limit data
        $now = time();
        foreach ($_SESSION as $key => $value) {
            if (str_starts_with($key, 'rate_limit_') && is_array($value)) {
                $_SESSION[$key] = array_filter($value, function($timestamp) use ($now) {
                    return ($now - $timestamp) < 300; // 5 minutes
                });
                
                if (empty($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                }
            }
        }
    }
}