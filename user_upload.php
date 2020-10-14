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
    function createTable() {
        echo "Creating table...\n";
        exit();
    }
    // Upload user data to table
    function uploadUsers($username, $password, $host, $file) {
        echo "Uploading users\n";
    }
    // Print our help
    function printHelp($help) {
        echo $help;
    }

    /**
     * Program variables and flags.
     */
    // Program variables
    $username = "";
    $password = "";
    $host = "";
    $fileName = "";

    // Program flags
    $DRY_RUN = false;
    /**
     * The program flow below is based on command line directives.
     * Command line directives are checked for validity or an error is thrown.
     */
    // Setup our argv directives
    $optionsText  = "";
    $optionsText .= "u:";  // MySQL username
    $optionsText .= "p:";  // MySQL password
    $optionsText .= "h:";  // MySQL host

    $longOptionsText  = array(
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
    if (isset($options['create_table'])) {
        createTable();
    }
    // Dry run flag
    if (isset($options['dry_run'])) {
        $DRY_RUN = true;
    }
    // Run through our directives to make sure there are no
    // missing directives or missing files
    if (isset($options['u']) && isset($options['p']) && isset($options['h']) && isset($options['file']) ) {
        // Store our variables
        $username = $options['u'];
        $password = $options['p'];
        $host = $options['h'];
        $fileName = $options['file'];
        // Check if file can be opened, exit if unable
        $file = fopen($fileName, 'r')
        or exit(1);

        uploadUsers($username, $password, $host, $file);

    } else {
        echo "ERROR: Please make sure user, password and hostname values are set (ie):
        user_upload.php -u user -p password -h localhost --file ourusers.csv\n";
        exit(1);
    }
