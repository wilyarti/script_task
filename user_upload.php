#!/usr/bin/php
<?php
/*
    Copyright Wilyarti Howard - 2020
*/
$help = "
User Upload (c) Wilyarti Howard 2020
You will need to specify some of the following command line directives :
    - --file [csv file name] – this is the name of the CSV to be parsed
    - --create_table – this will cause the MySQL users table to be built (and no further
    - action will be taken)
    - --dry_run – this will be used with the --file directive in case we want to run the
    script but not insert into the DB. All other functions will be executed, but the
    database won't be altered
    - -u – MySQL username
    - -p – MySQL password
    - -h – MySQL host
    - --help – which will output the above list of directives with details.\n";

/**
 * Program functions
 * Below are the main functions for the program.
 * They are executed based on the command line directives.
 */
// Create our user table
function createTable($username, $password, $host, $database, $table, $DRY_RUN)
{
    // Create connection
    $conn = new mysqli($host, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    // If in dry run mode exit now as connection works
    if ($DRY_RUN) {
        echo "Connected to database successfully.\n";
        exit(0);
    }
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully.\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
        exit(1);
    }
    $conn->close();
    $conn = new mysqli($host, $username, $password, $database);
    $sql = "CREATE TABLE IF NOT EXISTS $table" .
        "(
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            surname VARCHAR(50) NOT NULL,
            email VARCHAR(50) UNIQUE,
            created TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully.\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
        exit(1);
    }
    $conn->close();
    exit();
}

// Upload user data to table
function uploadUsers($username, $password, $host, $database, $table, $file, $DRY_RUN)
{
    // Regex pattern
    $pattern = "/[^\wàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð,.\'-]/";

    // MySQL connection
    $conn = new mysqli($host, $username, $password, $database);
    // Ignore first line
    fgets($file);
    $errCount = 0;
    $count = 0;
    while (!feof($file)) {
        $line = fgets($file);
        $values = explode(",", $line);
        // Verify if lines are set. Remove whitespace
        if (isset($values[0]) && isset($values[1]) && isset($values[2])) {
            $name =  $conn->real_escape_string(preg_replace($pattern, '', $values[0])); // Remove illegal characters from names
            $surname = $conn->real_escape_string(preg_replace($pattern, '', $values[1])); // As above
            $email = filter_var($values[2], FILTER_SANITIZE_EMAIL);
            // Cleanup names
            $name = strtolower($name);
            $name = ucfirst($name);
            $surname = strtolower($surname);
            $surname = ucfirst($surname);
            $email = strtolower($email);
            // Validate e-mail
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailEscaped = $conn->real_escape_string($email);
                $sql = "INSERT INTO $table " .
                    "(name, surname, email) values ('$name', '$surname', '$emailEscaped')";
                if ($DRY_RUN) {
                    $count++;
                    continue;
                }
                if ($conn->query($sql) === TRUE) {
                    echo " + $email\n";
                    $count++;
                } else {
                    echo "Error adding $email: " . $conn->error . "\n";
                    $errCount++;
                }
            } else {
                echo("Invalid email, skipping '$email'\n");
                $errCount++;
            }
            // Remove incorrect characters from names.
        }
    }
    $conn->close();
    echo "Added $count emails\n$errCount error(s)\n";
    if ($DRY_RUN) {
        echo "Ran in dry run mode. No modifications made to the database.\n";
    }
    exit();
}

// Print our help
function printHelp($help)
{
    echo $help;
    exit();
}

/**
 * Program variables and flags.
 */
// Program variables
$username = "";
$password = "";
$host = "";
$database = "";
$table = "";
$fileName = "";

// Program flags
$DRY_RUN = false;
/**
 * The program flow below is based on command line directives.
 * Command line directives are checked for validity or an error is thrown.
 */
// Setup our argv directives
$optionsText = "";
$optionsText .= "u:";  // MySQL username
$optionsText .= "p:";  // MySQL password
$optionsText .= "h:";  // MySQL host
$optionsText .= "d:";  // MySQL database name
$optionsText .= "t:";  // MySQL table name

$longOptionsText = array(
    "file:",    // File to be processed
    "create_table::",    // Create table for our users
    "dry_run::",    // Don't actual add users, just simulate
    "help",    // Print help screen
);

$options = getopt($optionsText, $longOptionsText);
// Go through all our command switches first
// Eliminate the directives that modify program flow first
// Then setup flags
// Help menu
if (isset($options['help'])) {
    printHelp($help);
}
// Dry run flag
if (isset($options['dry_run'])) {
    $DRY_RUN = true;
}
// Run through our directives to make sure there are no
// missing directives or missing files
if (isset($options['u']) &&
    isset($options['p']) &&
    isset($options['h']) &&
    isset($options['d']) &&
    isset($options['t'])) {
    // Store our variables
    $username = $options['u'];
    $password = $options['p'];
    $host = $options['h'];
    $database = $options['d'];
    $table = $options['t'];

    // Are we just setting up the table?
    if (isset($options['create_table'])) {
        createTable($username, $password, $host, $database, $table, $DRY_RUN);
    }
    // Run through our user import
    if (isset($options['file'])) {
        $fileName = $options['file'];
        // Check if file can be opened, exit if unable
        $file = fopen($fileName, 'r')
        or exit(1);
        uploadUsers($username, $password, $host, $database, $table, $file, $DRY_RUN);
    } else {
        echo "ERROR: Please make sure file values are set (ie):
    user_upload.php -u user -p password -h localhost --file ourusers.csv\n";
        exit(1);
    }
} else {
    echo "ERROR: Please make sure user, password, hostname and database name values are set (ie):
        user_upload.php -u user -p password -h localhost --file ourusers.csv\n";
    exit(1);
}
