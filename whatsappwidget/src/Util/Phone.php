<?php

declare(strict_types=1);

namespace WhatsAppWidget\Util;

/**
 * Phone utility class for E.164 format validation and processing
 */
class Phone
{
    /**
     * Validate phone number in E.164 format
     */
    public static function validateE164(string $phone): bool
    {
        // E.164 format: +[1-15 digits]
        return (bool) preg_match('/^\+[1-9]\d{1,14}$/', $phone);
    }

    /**
     * Clean phone number to digits only
     */
    public static function cleanPhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Format phone number to E.164 if possible
     */
    public static function formatToE164(string $phone): string
    {
        $cleaned = self::cleanPhone($phone);
        
        // If already starts with country code, add +
        if (strlen($cleaned) >= 10 && strlen($cleaned) <= 15) {
            return '+' . $cleaned;
        }
        
        return $phone; // Return original if can't format
    }

    /**
     * Get phone number for WhatsApp URL (digits only)
     */
    public static function getWhatsAppPhone(string $phone): string
    {
        if (self::validateE164($phone)) {
            return substr($phone, 1); // Remove + for WhatsApp URL
        }
        
        return self::cleanPhone($phone);
    }

    /**
     * Validate and sanitize phone input
     */
    public static function sanitizePhone(string $phone): array
    {
        $phone = trim($phone);
        
        if (empty($phone)) {
            return ['valid' => false, 'error' => 'Phone number is required'];
        }
        
        if (!self::validateE164($phone)) {
            return ['valid' => false, 'error' => 'Phone number must be in E.164 format (e.g., +905551112233)'];
        }
        
        return ['valid' => true, 'phone' => $phone];
    }
}