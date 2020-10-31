<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
//some MYSQL-Servers have an offset when retrieving entries, if you archive an entry and the first column disappears, try changing this value to 1
$OFFSET_DATABASE = 0;
/* Database credentials. Assuming you are running MySQL
 server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'database');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$SIMPLESAMLPHP_PATH = '/path/to/SimpleSAMLphp';
$SAML_AUTHSOURCE = 'my-sp';
$LOGIN_URL = 'https://PATH/user_login.php';

define('CONTACT', 'CONTACT NAME');
define('FROM_MAIL', 'FROM MAIL');
define('FROM_NAME', 'FROM NAME');
define('TO_MAIL', 'TO MAIL');
define('TO_NAME', 'TO NAME');
define('ORG', 'ORGANISATION');

//SMTP
define('SMTP_HOST', 'example.com');
define('SMTP_PORT', 25);
define('SMTP_USER', 'log@exampole.com');
define('SMTP_PASSWORD', 'password');

define('NAME_SCHEME', [1]);

// Check connection
if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

function getLink()
{
  /* Attempt to connect to MySQL database */
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection
  if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
  }
  return $link;
}

function getMail()
{
    

    $mail = new PHPMailer();

    // Settings
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';

    $mail->Host       = SMTP_HOST; // SMTP server example
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = SMTP_PORT;                    // set the SMTP port for the mail server
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Username   = SMTP_USER; // SMTP account username example
    $mail->Password   = SMTP_PASSWORD;        // SMTP account password example
    
    return $mail;
}

function getContact() {
    return CONTACT;
}

function getFromMail() {
    return FROM_MAIL;
}

function getFromName() {
    return FROM_NAME;
}

function getToMail() {
    return TO_MAIL;
}

function getToName() {
    return TO_NAME;
}

function getOrg() {
    return ORG;
}
?>
