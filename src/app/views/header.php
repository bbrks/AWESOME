<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php isset($title) ? $this->title($title) : $this->title(); ?></title>

  <link rel="stylesheet" type="text/css" href="/css/style.css" />

</head>
<body>

<?php
  if (isset($error)) {
    echo '<span class="error">'.$error.'</span>';
  }
?>

<h1>AWESOME</h1>
