<?php

/**
 * Log the feedback information
 * The token can be traced to a student ID for feedback follow-up
 * The user agent can be used to debug front-end/visual bugs
 */

// We can send the user back to the page they were on once completed
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';

if (isset($_POST['feedbacktxt']) && isset($_POST['token'])) {

  ini_set('SMTP', 'smtphost.aber.ac.uk');
  ini_set('sendmail_from', Config::MAIL_FROM_ADDR);

  $feedbacktxt = $_POST['feedbacktxt'];
  $token = $_POST['token'];

  $ua = $_SERVER['HTTP_USER_AGENT'];
  $time = time();

  $msg = "Feedback:\r\n".$feedbacktxt."\r\n\r\nToken: ".$token."\r\nUser Agent: ".$ua."\r\nTimestamp: ".$time;

  mail('ben@bbrks.me', '[AWESOME FEEDBACK]', $msg);

  echo 'Thank you for your feedback. Redirecting in 3 seconds.';

} else {
  echo '<strong>Error:</strong> No feedback was sent! Redirecting in 3 seconds.';
}

echo '<meta http-equiv="refresh" content="3; url='.$ref.'" />';
