<?php

declare(strict_types=1);

namespace WhatsAppWidget\Util;

use DateTime;
use DateTimeZone;

/**
 * Working hours utility class
 */
class WorkingHours
{
    /**
     * Check if current time is within working hours
     */
    public static function isWorkingTime(array $config): bool
    {
        if (!isset($config['enabled']) || !$config['enabled']) {
            return true; // Always working if not configured
        }
        
        $timezone = $config['timezone'] ?? 'Europe/Istanbul';
        $now = new DateTime('now', new DateTimeZone($timezone));
        
        $currentDay = strtolower($now->format('l')); // monday, tuesday, etc.
        $currentTime = $now->format('H:i');
        
        // Check if today is a working day
        $workingDays = $config['working_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        if (!in_array($currentDay, $workingDays)) {
            return false;
        }
        
        // Check working hours
        $startTime = $config['start_time'] ?? '09:00';
        $endTime = $config['end_time'] ?? '18:00';
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    /**
     * Get next working time message
     */
    public static function getNextWorkingTimeMessage(array $config): string
    {
        if (!isset($config['enabled']) || !$config['enabled']) {
            return '';
        }
        
        $timezone = $config['timezone'] ?? 'Europe/Istanbul';
        $now = new DateTime('now', new DateTimeZone($timezone));
        
        $workingDays = $config['working_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $startTime = $config['start_time'] ?? '09:00';
        $endTime = $config['end_time'] ?? '18:00';
        
        // Find next working day
        for ($i = 1; $i <= 7; $i++) {
            $nextDay = clone $now;
            $nextDay->modify("+{$i} day");
            $dayName = strtolower($nextDay->format('l'));
            
            if (in_array($dayName, $workingDays)) {
                $dayDisplay = ucfirst($dayName);
                return "We'll be available on {$dayDisplay} from {$startTime} to {$endTime}";
            }
        }
        
        return "We'll be back soon!";
    }

    /**
     * Validate working hours configuration
     */
    public static function validateConfig(array $config): array
    {
        $errors = [];
        
        if (isset($config['start_time']) && !self::isValidTime($config['start_time'])) {
            $errors[] = 'Invalid start time format. Use HH:MM format.';
        }
        
        if (isset($config['end_time']) && !self::isValidTime($config['end_time'])) {
            $errors[] = 'Invalid end time format. Use HH:MM format.';
        }
        
        if (isset($config['start_time'], $config['end_time'])) {
            if ($config['start_time'] >= $config['end_time']) {
                $errors[] = 'Start time must be before end time.';
            }
        }
        
        if (isset($config['working_days']) && !is_array($config['working_days'])) {
            $errors[] = 'Working days must be an array.';
        }
        
        return $errors;
    }

    /**
     * Validate time format (HH:MM)
     */
    private static function isValidTime(string $time): bool
    {
        return (bool) preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
    }

    /**
     * Get default working hours configuration
     */
    public static function getDefaultConfig(): array
    {
        return [
            'enabled' => false,
            'timezone' => 'Europe/Istanbul',
            'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'start_time' => '09:00',
            'end_time' => '18:00',
            'offline_message' => 'We are currently offline. Please leave a message and we will get back to you soon!'
        ];
    }
}