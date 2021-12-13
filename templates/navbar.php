<link rel="stylesheet" href="/css/navbar.css">

<?php

  require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/database.php");
  $DB = new Database();

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
  <a id="home" href="/"><h1>Scrum Bandicoot</h1></a>
  <div id="logout">
    <p>Welcome <?php echo $DB->getCurrentUser()['slug']; ?></p>
    <a class="button invert outline" href="/logout">Logout</a>
  </div>
</div>
