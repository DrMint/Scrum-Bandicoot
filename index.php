<?php
  // Authentification check
  if (session_status() == PHP_SESSION_NONE) session_start();
  if (!isset($_SESSION['loginUsername'])) {
    header('Location: /login');
  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <title>Scrum Bandicoot</title>
  </head>
  <body>

    <h1>Hello <?php echo $_SESSION['loginUsername']; ?></h1>

  </body>
</html>
