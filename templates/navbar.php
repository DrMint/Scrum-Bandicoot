<link rel="stylesheet" href="/css/navbar.css">

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php") ?>

<?php
  // Authentification check
  if (session_status() == PHP_SESSION_NONE) session_start();
  if (!isset($_SESSION['loginUsername'])) {
    header('Location: /');
  }
 ?>

 <?php
    function isActive($url) {
      return $url === dirname($_SERVER["PHP_SELF"]);
    }
  ?>

<div id="navbar">
  <h1>Srum Bandicoot</h1>
  <div id="logout">
    <p>Welcome <?php echo getCurrentUser()->slug; ?></p>
    <a class="button invert outline" href="/logout">Logout</a>
  </div>
</div>
