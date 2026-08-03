<?php

if (!function_exists('get_custom_option_display')) {
    function get_custom_option_display(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            if (isset($value['value'])) {
                return $value['value'];
            }

            return implode(', ', array_map(function ($v) {
                return is_array($v) && isset($v['value']) ? $v['value'] : (string) $v;
            }, $value));
        }

        return (string) $value;
    }
}

if (!function_exists('is_custom_option_file')) {
    function is_custom_option_file(mixed $value): bool
    {
        if (is_string($value)) {
            return str_starts_with($value, 'temp-uploads/');
        }

        if (is_array($value) && isset($value['value']) && is_string($value['value'])) {
            return str_starts_with($value['value'], 'temp-uploads/');
        }

        return false;
    }
}

if (!function_exists('get_custom_option_file_path')) {
    function get_custom_option_file_path(mixed $value): string
    {
        if (is_string($value) && str_starts_with($value, 'temp-uploads/')) {
            return $value;
        }

        if (is_array($value) && isset($value['value']) && is_string($value['value']) && str_starts_with($value['value'], 'temp-uploads/')) {
            return $value['value'];
        }

        return '';
    }
}
