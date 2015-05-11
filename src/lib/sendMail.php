<?php

/**
 * A wrapper function for PHPMailer to easily send emails
 */
function sendMail($toAddr, $subject, $body) {

  set_include_path(dirname(dirname(__FILE__)));

  require_once('config/config.php');
  require_once('lib/class.phpmailer.php');
  require_once('lib/class.smtp.php');

  $mail = new PHPMailer;

  // $mail->SMTPDebug = 3; // Verbose debugging output

  $mail->isSMTP();
  $mail->Host = Config::SMTP_HOST;
  $mail->SMTPAuth = true;
  $mail->Username = Config::SMTP_USERNAME;
  $mail->Password = Config::SMTP_PASSWORD;
  $mail->SMTPSecure = Config::SMTP_SECURE;
  $mail->Port = Config::SMTP_PORT;

  $mail->From = Config::MAIL_FROM_ADDR;
  $mail->FromName = Config::MAIL_FROM_NAME;

  $mail->addAddress($toAddr);
  $mail->Subject = $subject;
  $mail->Body    = $body;

  if(!$mail->send()) {
    return $mail->ErrorInfo;
  } else {
    return true;
  }

}
