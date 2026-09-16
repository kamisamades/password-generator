<?php

function generatePassword($length, $includeUppercase, $includeLowercase, $includeNumbers, $includeSymbols, $includeWords = false, $wordsCount = 0, $passwordPattern = '') {
    $random = '';
    $characters = '';
    $words = '';
    $password = '';
    if ($includeUppercase) {
        $characters .= APP_PASSWORD_UPPERCASE;
    }
    if ($includeLowercase) {
        $characters .= APP_PASSWORD_LOWERCASE;
    }
    if ($includeNumbers) {
        $characters .= APP_PASSWORD_NUMBERS;
    }
    if ($includeSymbols) {
        $characters .= APP_PASSWORD_SYMBOLS;
    }
    if ($includeWords && $wordsCount > 0) {
        $words = generateWords($wordsCount);
    }

    if (empty($characters) && !$includeWords) {
        return '';
    }

    for ($i = 0; $i < $length; $i++) {
        $random .= $characters[random_int(0, strlen($characters) - 1)];
    }
    $password = str_replace(['{words}','{random}'], [$words, $random], $passwordPattern);
    return $password;
}

function generateWords($count,$glue='-') {
    $words = []; $wordList = []; $bannedWords = [];
    if (file_exists(APP_PASSWORD_WORDS_FILE)) {
        $wordList = file(APP_PASSWORD_WORDS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    if (file_exists(APP_PASSWORD_WORDS_BANNED_FILE)) {
        $bannedWords = file(APP_PASSWORD_WORDS_BANNED_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    if ($wordList === false) {
        return '';
    }
    if ($bannedWords === false) {
        $bannedWords = [];
    }
    $wordList = array_diff($wordList, $bannedWords);
    $wordList = array_values($wordList); // Reindex the array after filtering

    if (!empty($wordList) && $count > 0) {
        $count = min($count, count($wordList)); // Limit the count to the number of available words
        for ($i = 0; $i < $count; $i++) {
            $randomIndex = random_int(0, count($wordList) - 1);
            $words[] = utf8_encode($wordList[$randomIndex]);
            unset($wordList[$randomIndex]); // Remove the selected word to avoid duplicates
            $wordList = array_values($wordList); // Reindex the array after unsetting
        }
    }
    return implode($glue, $words);
}