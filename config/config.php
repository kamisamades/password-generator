<?php

define('APP_NAME', 'Password Generator');
define('APP_VERSION', '1.0.6');
define('APP_AUTHOR', 'Maurice LECON');
define('APP_DESCRIPTION', 'A simple password generator application.');
define('APP_LICENSE', 'MIT License');
define('APP_LICENSE_URL', 'https://opensource.org/licenses/MIT');
define('APP_GITHUB_URL', '');
define('APP_RELEASE_DATE', '2026-09-16');
define('APP_AUTHOR_URL', 'https://lebrun.dev');

define('APP_PASSWORD_MIN_LENGTH', 5);
define('APP_PASSWORD_MAX_LENGTH', 128);
define('APP_PASSWORD_DEFAULT_LENGTH', 12);

define('APP_PASSWORD_INCLUDE_UPPERCASE', true);
define('APP_PASSWORD_INCLUDE_LOWERCASE', true);
define('APP_PASSWORD_INCLUDE_NUMBERS', true);
define('APP_PASSWORD_INCLUDE_SYMBOLS', false);

define('APP_PASSWORD_DEFAULT_COUNT', 1);
define('APP_PASSWORD_MAX_COUNT', 100);

define('APP_PASSWORD_INCLUDE_WORDS', true);
define('APP_PASSWORD_INCLUDE_RANDOM', true);

define('APP_PASSWORD_WORDS_NUMBER_DEFAULT', 2);
define('APP_PASSWORD_WORDS_FILE', __DIR__ . '/../datas/liste-mots-francais.txt');
define('APP_PASSWORD_WORDS_BANNED_FILE', __DIR__ . '/../datas/motdepasses-a-eviter.txt');
define('APP_PASSWORD_PATTERN', '{words}-{random}');
define('APP_PASSWORD_SYMBOLS', '!@#$%^&*()_+-=[]{}|;:,.<>?');
define('APP_PASSWORD_UPPERCASE', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('APP_PASSWORD_LOWERCASE', 'abcdefghijklmnopqrstuvwxyz');
define('APP_PASSWORD_NUMBERS', '0123456789');

define('DEBUG_MODE', false); // Set to true to enable debug mode