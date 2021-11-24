<link rel="stylesheet" href="/css/navbar.css">

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php") ?>

<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Authentification check
  if (session_status() == PHP_SESSION_NONE) session_start();
  if (!isset($_SESSION['loginUsername'])) {
    header('Location: /login');
  }
 ?>

 <?php
    function isActive($url) {
      return $url === dirname($_SERVER["PHP_SELF"]);
    }
  ?>

<div id="navbar">
  <h1>Scrum Bandicoot</h1>
  <div id="logout">
    <p>Welcome <?php echo getCurrentUser()->slug; ?></p>
    <a class="button invert outline" href="/logout">Logout</a>
  </div>
</div>
