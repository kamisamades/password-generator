<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// Initialize the trace array for debugging purposes
$GLOBALS['trace'] = [];

$formSent =         isset($_POST['form-sent']) && $_POST['form-sent'] === 'yes';
$length =           isset($_POST['length']) ? (int)$_POST['length'] : APP_PASSWORD_DEFAULT_LENGTH;
$includeUppercase = isset($_POST['include-uppercase']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_UPPERCASE);
$includeLowercase = isset($_POST['include-lowercase']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_LOWERCASE);
$includeNumbers =   isset($_POST['include-numbers']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_NUMBERS);
$includeSymbols =   isset($_POST['include-symbols']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_SYMBOLS);
$includeWords =     isset($_POST['include-words']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_WORDS);
$includeRandom =    isset($_POST['include-random']) && $formSent ? true : ($formSent ? false : APP_PASSWORD_INCLUDE_RANDOM);
$passwordsCount =   isset($_POST['passwords']) && is_numeric($_POST['passwords']) && $formSent ? (int)$_POST['passwords'] : ($formSent ? 1 : APP_PASSWORD_DEFAULT_COUNT);
$wordsCount =       isset($_POST['words']) && is_numeric($_POST['words']) && $includeWords && $formSent ? (int)$_POST['words'] : ($formSent ? 0 : APP_PASSWORD_WORDS_NUMBER_DEFAULT);
$passwordPattern =  isset($_POST['password-pattern']) && $formSent ? $_POST['password-pattern'] : APP_PASSWORD_PATTERN;

// For debugging purposes, store the form data in the trace array
$GLOBALS['trace'][] = [
    'formSent' => $formSent,
    'length' => $length,
    'includeUppercase' => $includeUppercase,
    'includeLowercase' => $includeLowercase,
    'includeNumbers' => $includeNumbers,
    'includeSymbols' => $includeSymbols,
    'includeWords' => $includeWords,
    'includeRandom' => $includeRandom,
    'passwordsCount' => $passwordsCount,
    'wordsCount' => $wordsCount,
    'passwordPattern' => $passwordPattern
];

?>
<html lang="fr">
<head>
    <title><?php echo APP_NAME.' '.APP_VERSION; ?></title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="apple-touch-icon" sizes="500x500" href="./assets/password-generator-transparent.png">
    <link rel="icon" type="image/png" sizes="500x500" href="./assets/password-generator-transparent.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-4.0.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="./assets/scripts.js" type="text/javascript"></script>
    <script type="text/javascript">
        // Initialize the password pattern on page load
        $( document ).ready(function() {
            // Add event listeners to update the password pattern when options change
            $('#include-random, #include-words').on('change', updatePasswordPattern);
            $('#display-pattern-btn').on('click', displayPattern);
            $('#password-generator').on('submit', function(e) {
                let allow = allowGenerate();
                if (!allow) {
                    e.preventDefault(); // Prevent form submission if validation fails
                    displayMessages(allow);
                    return false; // Stop further execution
                }
            });
            updatePasswordPattern();
        });
    </script>
    </head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div id="logo"></div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h1><?php echo APP_NAME; ?></h1>
                    <p><?php echo APP_DESCRIPTION; ?></p>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 text-center">
                    <nav id="navigation" class="navbar navbar-expand-lg align-middle">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="./">🔐 Generate Password</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h2>Generate a Secure Password</h2>
                    <form name="password-generator" id="password-generator" method="post">
                        <input type="hidden" name="form-sent" value="yes">
                        <div class="form-group mb-3">
                            <label for="passwords">How Many Passwords:</label>
                            <input type="number" id="passwords" name="passwords" class="form-control center" min="1" max="<?php echo APP_PASSWORD_MAX_COUNT; ?>" value="<?php echo $passwordsCount; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="include-random"><input type="checkbox" id="include-random" name="include-random" class="form-check-input" <?php echo $includeRandom ? 'checked' : ''; ?>> Include Random Characters</label>
                            <label for="include-words"><input type="checkbox" id="include-words" name="include-words" class="form-check-input" <?php echo $includeWords ? 'checked' : ''; ?>> Include Words</label>
                        </div>
                        <div class="form-group mb-3 random-block">
                            <label for="length">Password Length:</label>
                            <input type="number" id="length" name="length" class="form-control center" min="<?php echo APP_PASSWORD_MIN_LENGTH; ?>" max="<?php echo APP_PASSWORD_MAX_LENGTH; ?>" value="<?php echo $length; ?>" required>
                        </div>
                        <div class="form-group mb-3 random-block">
                            <label for="include-uppercase"><input type="checkbox" id="include-uppercase" name="include-uppercase" class="form-check-input" <?php echo $includeUppercase ? 'checked' : ''; ?>> Include Uppercase Letters</label>
                            <label for="include-lowercase"><input type="checkbox" id="include-lowercase" name="include-lowercase" class="form-check-input" <?php echo $includeLowercase ? 'checked' : ''; ?>> Include Lowercase Letters</label>
                            <label for="include-numbers"><input type="checkbox" id="include-numbers" name="include-numbers" class="form-check-input" <?php echo $includeNumbers ? 'checked' : ''; ?>> Include Numbers</label>
                            <label for="include-symbols"><input type="checkbox" id="include-symbols" name="include-symbols" class="form-check-input" <?php echo $includeSymbols ? 'checked' : ''; ?>> Include Symbols</label>
                        </div>
                        <div class="form-group mb-3 words-block">
                            <label for="words">How Many Words:</label>
                            <input type="number" id="words" name="words" class="form-control center" min="0" max="10" value="<?php echo $wordsCount; ?>">
                        </div>
                        <div id="password-pattern-container" class="form-group mb-3 invisible">
                            <label for="password-pattern">Password pattern:</label>
                            <input type="text" id="password-pattern" name="password-pattern" class="form-control center" value="<?php echo $passwordPattern; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <button type="button" id="display-pattern-btn" class="btn btn-secondary">Show Password Pattern</button>
                            <button type="submit" id="generate-btn" class="btn btn-primary">Generate</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div id="password-pattern-explanation" class="alert alert-info<?php echo ($formSent ? ' invisible' : ''); ?>" role="alert">
                        <h4 class="alert-heading">Password Pattern Explanation</h4>
                        <p>The password pattern allows you to customize the structure of the generated password. You can use the following placeholders:</p>
                        <ul>
                            <li><code>{words}</code>: This placeholder will be replaced with the specified number of random words from the word list.</li>
                            <li><code>{random}</code>: This placeholder will be replaced with a random string of characters based on your selected options (uppercase, lowercase, numbers, symbols).</li>
                        </ul>
                        <p>For example, if you set the pattern to <code>{words}-{random}</code>, the generated password will consist of random words followed by a random string of characters, separated by a hyphen.</p>
                        <p>You can customize the pattern to create passwords that meet your specific requirements.</p>
                    </div>
                    <div id="password-hint" class="alert alert-warning invisible" role="alert">
                        <h4 class="alert-heading">Password Generation Hint</h4>
                        <p>To generate a password, please select at least one of the following options:</p>
                        <ul>
                            <li><strong>Include Random Characters</strong>: This option will generate a password with random characters based on your selected criteria (uppercase, lowercase, numbers, symbols).</li>
                            <li><strong>Include Words</strong>: This option will generate a password using random words from the word list.</li>
                        </ul>
                        <p>You can also customize the password pattern to create passwords that meet your specific requirements.</p>
                    </div>
                    <div id="generated-passwords">
                    <?php
                    try {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            /*
                            $length =           isset($_POST['length']) ? (int)$_POST['length'] : APP_PASSWORD_DEFAULT_LENGTH;
                            $includeUppercase = isset($_POST['include-uppercase']) && $formSent ? true : APP_PASSWORD_INCLUDE_UPPERCASE;
                            $includeLowercase = isset($_POST['include-lowercase']) && $formSent ? true : APP_PASSWORD_INCLUDE_LOWERCASE;
                            $includeNumbers = isset($_POST['include-numbers']) && $formSent ? true : APP_PASSWORD_INCLUDE_NUMBERS;
                            $includeSymbols = isset($_POST['include-symbols']) && $formSent ? true : APP_PASSWORD_INCLUDE_SYMBOLS;
                            $includeWords = isset($_POST['include-words']) && $formSent ? true : APP_PASSWORD_INCLUDE_WORDS;
                            $includeRandom = isset($_POST['include-random']) && $formSent ? true : APP_PASSWORD_INCLUDE_RANDOM;
                            $passwordsCount = isset($_POST['passwords']) && is_numeric($_POST['passwords']) ? (int)$_POST['passwords'] : 1;
                            $wordsCount = isset($_POST['words']) && is_numeric($_POST['words']) && $includeWords ? (int)$_POST['words'] : 0;
                            $passwordPattern = isset($_POST['password-pattern']) ? $_POST['password-pattern'] : APP_PASSWORD_PATTERN;
                            */
                            if (!$includeRandom && $includeWords) {
                                $length = 0;
                                $passwordPattern = '{words}';
                            } elseif ($length < APP_PASSWORD_MIN_LENGTH) {
                                $length = APP_PASSWORD_MIN_LENGTH;
                            } elseif ($length > APP_PASSWORD_MAX_LENGTH) {
                                $length = APP_PASSWORD_MAX_LENGTH;
                            }
                            if ($passwordsCount < 1) {
                                $passwordsCount = 1;
                            } elseif ($passwordsCount > APP_PASSWORD_MAX_COUNT) {
                                $passwordsCount = APP_PASSWORD_MAX_COUNT;
                            }
                            $passwords = [];
                            for ($i = 0; $i < $passwordsCount; $i++) {
                                $password = generatePassword($length, $includeUppercase, $includeLowercase, $includeNumbers, $includeSymbols, $includeWords, $wordsCount, $passwordPattern);
                                $passwords[] = $password;
                            }
                            if (count($passwords) === 1) {
                                echo '<div class="alert alert-success" role="alert">Generated Password: <strong>' . htmlspecialchars($passwords[0]) . '</strong></div>';
                            }
                            else {
                                echo '<div class="alert alert-success" role="alert">Generated Passwords:';
                                foreach ($passwords as $password) {
                                    echo '<br /><strong>' . htmlspecialchars($password) . '</strong>';
                                }
                                echo '</div>';
                            }
                        } else {
                            $length = APP_PASSWORD_MIN_LENGTH;
                            $includeUppercase = true;
                            $includeLowercase = true;
                            $includeNumbers = true;
                            $includeSymbols = false;
                            $includeWords = false;
                            $wordsCount = 0;
                            $passwordPattern = '{random}';

                            //echo '<div class="alert alert-info" role="alert">Please fill out the form to generate a password.</div>';
                        }
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (DEBUG_MODE) {
            echo '<div class="container"><h3>Debug Trace</h3><pre>';
            print_r($GLOBALS['trace']);
            echo '</pre></div>';
        }
        ?>
    </main>
    <footer>
        <p>
            &copy; <?php echo date('Y'); ?> <a href="<?php echo APP_AUTHOR_URL; ?>" target="_blank"><?php echo APP_AUTHOR; ?></a> | Version: <?php echo APP_VERSION; ?> | <a href="<?php echo APP_GITHUB_URL; ?>" target="_blank">GitHub Repo</a> | License: <a href="<?php echo APP_LICENSE_URL; ?>" target="_blank"><?php echo APP_LICENSE; ?></a> | Release Date: <?php echo APP_RELEASE_DATE; ?>
        </p>
    </footer>
</body>
</html>