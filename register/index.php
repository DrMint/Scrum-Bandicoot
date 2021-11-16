<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <title>Scrum Bandicoot - Register</title>
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/login.css">
  </head>


  <body>

    <?php

    if (isset($_POST['submitButton'])) {

      $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

      require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/crypto.php");

      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }

      if (!exist($username)) {
        $user = new User($username);
        $user->setHash(generateHash($password));
        $user->write();
        $_SESSION['loginUsername'] = $username;
        header('Location: /templates');
      } else {
        unset($_SESSION['loginUsername']);
        echo '<style>body{animation: bw 1s;animation-fill-mode: forwards;}#container{animation: shake 0.2s;animation-iteration-count: 2;}</style>';
        $formMessage = "This username is already taken, please choose another one.";
      }

    }
    ?>

   <div id="container">
     <form method="POST" action="/register" id="myForm">
       <img src="/img/favicon.png" alt="">
       <h1>Scrum Bandicoot</h1>
       <input type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>" autocomplete="username" required>
       <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
       <button type="submit" name="submitButton" value="Submit">Create account</button>
     </form>
     <?php if (isset($formMessage)) "<p id='answer'>$formMessage</p>" ?>
     <p>Already have an account?<br>
     <a href="/login">Sign in</a></p>
   </div>

  </body>

 </html>
