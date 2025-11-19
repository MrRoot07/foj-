<?php
/**
 * Lightweight i18n Bootstrap
 * Handles language detection, loading, and translation functions
 */

if (session_id() == '') {
    session_start();
}

// Supported languages
define('SUPPORTED_LANGUAGES', ['en', 'ar']);
define('DEFAULT_LANGUAGE', 'en');

// Get language from query parameter or session
$lang = DEFAULT_LANGUAGE;
if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
} elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], SUPPORTED_LANGUAGES)) {
    $lang = $_SESSION['lang'];
} else {
    $_SESSION['lang'] = $lang;
}

// Load language file
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';
$translations = [];

if (file_exists($lang_file)) {
    $translations = require $lang_file;
} else {
    // Fallback to English
    $fallback_file = __DIR__ . '/../lang/' . DEFAULT_LANGUAGE . '.php';
    if (file_exists($fallback_file)) {
        $translations = require $fallback_file;
    }
}

// Store current language in global variable
$GLOBALS['current_lang'] = $lang;

/**
 * Translate a key with optional variable replacement
 * 
 * @param string $key Translation key
 * @param array $vars Variables to replace in format ['var1' => 'value1', ...]
 * @return string Translated string or key if not found
 */
function __t($key, $vars = []) {
    global $translations;
    
    if (isset($translations[$key])) {
        $text = $translations[$key];
        
        // Replace variables in format {var}
        if (!empty($vars)) {
            foreach ($vars as $var => $value) {
                $text = str_replace('{' . $var . '}', $value, $text);
            }
        }
        
        return $text;
    }
    
    // Fallback to English if key not found
    if ($GLOBALS['current_lang'] !== DEFAULT_LANGUAGE) {
        $fallback_file = __DIR__ . '/../lang/' . DEFAULT_LANGUAGE . '.php';
        if (file_exists($fallback_file)) {
            $fallback_translations = require $fallback_file;
            if (isset($fallback_translations[$key])) {
                $text = $fallback_translations[$key];
                if (!empty($vars)) {
                    foreach ($vars as $var => $value) {
                        $text = str_replace('{' . $var . '}', $value, $text);
                    }
                }
                return $text;
            }
        }
    }
    
    return $key; // Return key if translation not found
}

/**
 * Echo a translated string
 * 
 * @param string $key Translation key
 * @param array $vars Variables to replace
 */
function __e($key, $vars = []) {
    echo __t($key, $vars);
}

/**
 * Get current language code
 * 
 * @return string Language code (en, ar, etc.)
 */
function get_current_lang() {
    return $GLOBALS['current_lang'] ?? DEFAULT_LANGUAGE;
}

/**
 * Check if current language is RTL
 * 
 * @return bool True if RTL language
 */
function is_rtl() {
    return get_current_lang() === 'ar';
}

/**
 * Get language switcher URL preserving current page
 * 
 * @param string $target_lang Target language code
 * @return string URL with language parameter
 */
function get_lang_url($target_lang) {
    $current_url = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($current_url);
    $path = $parsed_url['path'] ?? '/';
    $query = [];
    
    // Parse existing query parameters
    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query);
    }
    
    // Update or add lang parameter
    $query['lang'] = $target_lang;
    
    return $path . '?' . http_build_query($query);
}

