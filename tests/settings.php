<?php 


if (file_exists("../core/auth/.env")) {
    $set = parse_ini_file("../core/auth/.env");
}

echo "<pre>";

print_r([
    'production' => PRODUCTION ?? 'undefined',
    'app_name' => APP_NAME ?? 'undefined',
    'site_title' => SITE_TITLE ?? 'undefined',
    'appRoot' => APP_ROOT,
    'webRoot' => WEB_ROOT,
    'views' => VIEWS_DIRECTORY,
    'init' => INIT_APPLICATION,
    'site_url' => SITE_URL ?? 'undefined',
    'session_name' => SESSION_NAME ?? 'undefined',
    'session_life' => MAX_SESSION_LIFE ?? 'undefined',
    'cookie_secure' => COOKIE_SECURE  ?? 'undefined',
    'cookie_samesite' => COOKIE_SAMESITE ?? 'undefined',
    'default_mai_service' => DEFAULT_MAIL_SERVICE ?? 'undefined',
    'test' => $set['TEST_ME'] ?? 'none',
    'another' => $set['NONONOO'] ?? 'none'
]);

echo "</pre>";
