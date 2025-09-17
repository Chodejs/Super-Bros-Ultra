<?php
// settings_loader.php
// This helper script reads the site-wide settings from the JSON file.

$settings_file_path = __DIR__ . '/settings.json';
$settings = [];

// Default values to prevent errors if the settings file is missing or malformed
$defaults = [
    'company_name' => 'Super Brothers LLC',
    'phone' => '123-456-7890',
    'email' => 'contact@example.com',
    'address' => '123 Main St, Anytown, USA',
    'service_areas' => 'Serving the greater metropolitan area.',
    'facebook_url' => '#',
    'instagram_url' => '#',
    'tiktok_url' => '#'
];

if (file_exists($settings_file_path)) {
    $settings_json = file_get_contents($settings_file_path);
    $settings = json_decode($settings_json, true);
    // Ensure all keys are present by merging with defaults
    $settings = array_merge($defaults, $settings);
} else {
    // If the file doesn't exist, use the defaults
    $settings = $defaults;
}
?>
