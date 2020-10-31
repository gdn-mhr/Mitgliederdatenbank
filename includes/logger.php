<?php

// Include config file
require_once "template_config.php";

function logger($level, $message)
{
  $link = getLink();
  $upd =
    "INSERT INTO log (level, user, message) VALUES (" .
    "'" .
    mysqli_real_escape_string($link, strip_tags(trim($level))) .
    "'," .
    "'" .
    mysqli_real_escape_string(
      $link,
      strip_tags(
        trim(isset($_SESSION['username']) ? $_SESSION['username'] : "anonymous")
      )
    ) .
    "'," .
    "'" .
    mysqli_real_escape_string($link, strip_tags(trim($message))) .
    "'" .
    ");";
  mysqli_query($link, $upd);
}

function send_mail($content)
{

  $mail = getMail();

  //Header
  $mail->setFrom(getFromMail(), getFromName());
  $mail->addAddress(getToMail(), getToName());
  // Content
  $mail->isHTML(true); // Set email format to HTML
  $mail->Subject = 'Neue Aktivität';
  $mail->Body =
    '<p><img style="display: block; margin-left: auto; margin-right: auto;" src="includes/template_logo.png" alt="Logo" width="300" height="auto" /></p><p>&nbsp;</p><p>Hallo,</p><p>in der Mitgliederdatenbank gab es folgende wichtige &Auml;nderung:</p><p>' .
    $content .
    '</p><p>von: ' .
    (isset($_SESSION['username'])
      ? $_SESSION['username']
      : "anonymous") .
        '<br />am: ' .
        date("d.m.Y H:i:s") .
        '</p><p>' . getOrg() . '</p><p>&nbsp;</p><p style="font-size:10px;">Diese Mail enth&auml;lt eventuell sch&uuml;tzenswerte pers&ouml;nliche Daten Dritter. Sie erhalten diese, da sie in der Benachrichtigungsliste f&uuml;r wichtige Ereignisse stehen. Sollten sie diese Mail irrt&uuml;mlich erhalten haben oder keine weiteren Mails erhalten wollen, kontaktieren Sie bitte ' .
        getContact() .
        '. Vielen Dank.</p>';
  $mail->AltBody = 'Hallo, \n in der Mitgliederdatenbank gab es folgende wichtige &Auml;nderung: \n' .
  $content .
  '\n von: ' .
  isset($_SESSION['username'])
    ? $_SESSION['username']
    : "anonymous" .
      '\n am: ' .
      date("d.m.Y H:i:s") .
      '\n ' . getOrg() . ' \n\n Diese Mail enth&auml;lt eventuell sch&uuml;tzenswerte pers&ouml;nliche Daten Dritter. Sie erhalten diese, da sie in der Benachrichtigungsliste f&uuml;r wichtige Ereignisse stehen. Sollten sie diese Mail irrt&uuml;mlich erhalten haben oder keine weiteren Mails erhalten wollen, kontaktieren Sie bitte ' .
      getContact() .
      '. Vielen Dank.';

  $mail->send();
}

function log_login()
{
  $user = $_SESSION['username'];
  $content = "User '" . $user . "' has successfully logged in.";
  logger("DEBUG", $content);
}

function log_failed_login($user)
{
  logger("WARN", "Failed attempt to log in with username '" . $user . "'.");
}

function log_changed($item)
{
  logger("DEBUG", "Entry '" . $item . "' has been updated.");
}

function log_changed_archive($item)
{
  logger("DEBUG", "Archived entry '" . $item . "' has been updated.");
}

function log_create($item)
{
    $content = "Entry '" . $item . "' has been created.";
  logger("DEBUG", $content);
  send_mail($content);
}

function log_delete($item)
{
    $content = "Entry '" . $item . "' has been deleted.";
  logger("DEBUG", $content);
  send_mail($content);
}

function log_delete_archive($item)
{
    $content = "Archived entry '" . $item . "' has been deleted.";
    logger("DEBUG", $content);
    send_mail($content);
}

function log_archive($item)
{
    $content = "Entry '" . $item . "' has been archived.";
    logger("DEBUG", $content);
    send_mail($content);
}

function log_unarchive($item)
{
    $content = "Entry '" . $item . "' has been restored.";
    logger("DEBUG", $content);
    send_mail($content);
}

function log_column_create($item)
{
  logger("DEBUG", "Column '" . $item . "' has been created.");
}

function log_column_update($item, $new)
{
  logger("DEBUG", "Column '" . $item . "' has been updated to '" . $new . "'.");
}

function log_column_delete($item)
{
  logger("DEBUG", "Column '" . $item . "' has been deleted.");
}

function log_column_update_archive($item, $new)
{
  logger(
    "DEBUG",
    "Archived column '" . $item . "' has been updated to '" . $new . "'."
  );
}

function log_column_delete_archive($item)
{
  logger("DEBUG", "Archived column '" . $item . "' has been deleted.");
}

function log_view_create($item)
{
  logger("DEBUG", "View '" . $item . "' has been created.");
}

function log_view_update($item)
{
  logger("DEBUG", "View '" . $item . "' has been updated.");
}

function log_view_delete($item)
{
  logger("DEBUG", "View '" . $item . "' has been deleted.");
}

function log_view_create_archive($item)
{
  logger("DEBUG", "Archived view '" . $item . "' has been created.");
}

function log_view_update_archive($item)
{
  logger("DEBUG", "Archived view '" . $item . "' has been updated.");
}

function log_view_delete_archive($item)
{
  logger("DEBUG", "Archived view '" . $item . "' has been deleted.");
}

?>
