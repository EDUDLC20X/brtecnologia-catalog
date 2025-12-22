<?php
/**
 * OpenSSL Stub for environments where OpenSSL extension is not available
 * This provides basic fallbacks for Laravel's encryption functions
 */

if (!extension_loaded('openssl')) {
    // Define stub functions for openssl
    if (!function_exists('openssl_cipher_iv_length')) {
        function openssl_cipher_iv_length($cipher_algo) {
            $ciphers = [
                'aes-128-cbc' => 16,
                'aes-256-cbc' => 16,
                'aes-256-gcm' => 12,
            ];
            
            $cipher_lower = strtolower($cipher_algo);
            return $ciphers[$cipher_lower] ?? 0;
        }
    }
    
    if (!function_exists('openssl_random_pseudo_bytes')) {
        function openssl_random_pseudo_bytes($length, &$strong = false) {
            $strong = true;
            if (function_exists('random_bytes')) {
                return random_bytes($length);
            }
            return bin2hex(random_bytes($length / 2));
        }
    }
    
    if (!function_exists('openssl_encrypt')) {
        function openssl_encrypt($data, $cipher_algo, $key, $options = 0, $iv = '', &$tag = null, $aad = '', $tag_length = 16) {
            throw new Exception('OpenSSL extension not available. Please install php_openssl.dll or use sodium encryption.');
        }
    }
    
    if (!function_exists('openssl_decrypt')) {
        function openssl_decrypt($data, $cipher_algo, $key, $options = 0, $iv = '', $tag = '', $aad = '') {
            throw new Exception('OpenSSL extension not available. Please install php_openssl.dll or use sodium encryption.');
        }
    }
}
?>
