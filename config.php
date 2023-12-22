//salam
//1402/10/01

<?php
// Database configuration
define('DB_SERVER', 'localhost'); // Database server, usually 'localhost'
define('DB_USERNAME', 'root'); // Database username
define('DB_PASSWORD', ''); // Database password
define('DB_NAME', 'task'); // Database name

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
