<?php

  require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php");

  function verifyKey($username, $password) {
    $user = new User($username);
    return password_verify($password, $user->hash);
  }

  function generateHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
  }

 ?>
