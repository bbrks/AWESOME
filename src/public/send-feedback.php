<?php

/**
 * Log the feedback information
 * The token can be traced to a student ID for feedback follow-up
 * The user agent can be used to debug front-end/visual bugs
 */

// We can send the user back to the page they were on once completed
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';

if (isset($_POST['feedbacktxt']) && isset($_POST['token'])) {

  require('../lib/sendMail.php');

  $feedbacktxt = $_POST['feedbacktxt'];
  $token = $_POST['token'];

  $ua = $_SERVER['HTTP_USER_AGENT'];
  $time = time();

  $body    = "Feedback:\r\n".$feedbacktxt."\r\n\r\nToken: ".$token."\r\nUser Agent: ".$ua."\r\nTimestamp: ".$time;
  $subject = '[AWESOME FEEDBACK]';

  $sendMail = sendMail('ben@bbrks.me', $subject, $body);
  if($sendMail === true) {
    echo 'Thank you for your feedback. Redirecting in 3 seconds.';
  } else {
    echo '<strong>Error:</strong> Feedback could not be sent!';
    echo 'Mailer Error: ' . $sendMail;
    die();
  }

} else {
  echo '<strong>Error:</strong> No feedback filled in! Redirecting in 3 seconds.';
}

echo '<meta http-equiv="refresh" content="3; url='.$ref.'" />';
