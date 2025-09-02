<?php

declare(strict_types=1);

namespace WhatsAppWidget\Util;

use Context;
use Product;
use Tools;
use Currency;

/**
 * Template utility class for message token processing
 */
class Template
{
    private const MAX_MESSAGE_LENGTH = 1000;
    
    /**
     * Available tokens for general pages
     */
    private const GENERAL_TOKENS = [
        '{page_url}',
        '{shop_name}',
        '{currency}'
    ];
    
    /**
     * Available tokens for product pages
     */
    private const PRODUCT_TOKENS = [
        '{product_name}',
        '{product_ref}',
        '{price}',
        '{product_url}'
    ];

    /**
     * Process message template with tokens and remove unknown tokens
     */
    public static function processTemplate(string $template, array $context = []): string
    {
        $message = $template;
        $contextObj = Context::getContext();
        
        // General tokens
        $message = str_replace('{page_url}', $context['page_url'] ?? self::getCurrentUrl(), $message);
        $message = str_replace('{shop_name}', $contextObj->shop->name ?? '', $message);
        $message = str_replace('{currency}', $contextObj->currency->iso_code ?? 'TRY', $message);
        
        // Product-specific tokens
        if (isset($context['product']) && $context['product'] instanceof Product) {
            $product = $context['product'];
            $message = str_replace('{product_name}', $product->name[$contextObj->language->id] ?? '', $message);
            $message = str_replace('{product_ref}', $product->reference ?? '', $message);
            
            // Format price
            $price = $product->getPrice(true, null, 6, null, false, true);
            $formattedPrice = Tools::displayPrice($price, $contextObj->currency);
            $message = str_replace('{price}', $formattedPrice, $message);
            
            // Product URL
            $productUrl = $contextObj->link->getProductLink($product);
            $message = str_replace('{product_url}', $productUrl, $message);
        } else {
            // Remove product tokens if no product context
            foreach (self::PRODUCT_TOKENS as $token) {
                $message = str_replace($token, '', $message);
            }
        }
        
        // Remove any remaining unknown tokens for security
        $message = self::removeUnknownTokens($message, isset($context['product']));
        
        // Clean and limit message
        $message = self::sanitizeMessage($message);
        
        return $message;
    }

    /**
     * Sanitize message for WhatsApp URL
     */
    public static function sanitizeMessage(string $message): string
    {
        // Remove HTML tags
        $message = strip_tags($message);
        
        // Normalize whitespace
        $message = preg_replace('/\s+/', ' ', $message);
        
        // Trim
        $message = trim($message);
        
        // Limit length
        if (strlen($message) > self::MAX_MESSAGE_LENGTH) {
            $message = substr($message, 0, self::MAX_MESSAGE_LENGTH - 3) . '...';
        }
        
        return $message;
    }

    /**
     * Validate message template
     */
    public static function validateTemplate(string $template, bool $isProductTemplate = false): array
    {
        if (empty(trim($template))) {
            return ['valid' => false, 'error' => 'Message template cannot be empty'];
        }
        
        if (strlen($template) > self::MAX_MESSAGE_LENGTH) {
            return ['valid' => false, 'error' => 'Message template is too long (max ' . self::MAX_MESSAGE_LENGTH . ' characters)'];
        }
        
        // Check for valid tokens
        $allowedTokens = self::GENERAL_TOKENS;
        if ($isProductTemplate) {
            $allowedTokens = array_merge($allowedTokens, self::PRODUCT_TOKENS);
        }
        
        // Find all tokens in template
        preg_match_all('/\{[^}]+\}/', $template, $matches);
        $usedTokens = $matches[0] ?? [];
        
        foreach ($usedTokens as $token) {
            if (!in_array($token, $allowedTokens)) {
                return ['valid' => false, 'error' => 'Invalid token: ' . $token];
            }
        }
        
        return ['valid' => true];
    }

    /**
     * Get current page URL
     */
    private static function getCurrentUrl(): string
    {
        $context = Context::getContext();
        return $context->link->getPageLink('index', true);
    }

    /**
     * Get available tokens for template help
     */
    public static function getAvailableTokens(bool $isProductTemplate = false): array
    {
        $tokens = self::GENERAL_TOKENS;
        if ($isProductTemplate) {
            $tokens = array_merge($tokens, self::PRODUCT_TOKENS);
        }
        return $tokens;
    }

    /**
     * Remove unknown tokens from message for security
     */
    public static function removeUnknownTokens(string $message, bool $hasProductContext = false): string
    {
        $allowedTokens = self::GENERAL_TOKENS;
        if ($hasProductContext) {
            $allowedTokens = array_merge($allowedTokens, self::PRODUCT_TOKENS);
        }
        
        // Find all tokens in message
        preg_match_all('/\{[^}]+\}/', $message, $matches);
        $foundTokens = $matches[0] ?? [];
        
        // Remove unknown tokens
        foreach ($foundTokens as $token) {
            if (!in_array($token, $allowedTokens)) {
                $message = str_replace($token, '', $message);
            }
        }
        
        return $message;
    }
    
    /**
     * URL encode message for WhatsApp
     */
    public static function encodeForWhatsApp(string $message): string
    {
        return rawurlencode($message);
    }
}