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
      $slug = strtolower($username);
      $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
      
      $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }

      require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/database.php");
      $DB = new Database();

      if (!$DB->userExists($slug)) {
        $DB->createUser($slug, $password);
        $_SESSION['loginUsername'] = $slug;
        header('Location: /');
      } else {
        unset($_SESSION['loginUsername']);
        echo '<style>body{animation: bw 1s;animation-fill-mode: forwards;}#container{animation: shake 0.2s;animation-iteration-count: 2;}</style>';
        $formMessage = "This username is already taken, please choose another one.";
      }

    }
    ?>

   <div id="container">
     <form method="POST" action="/register" id="myForm">
       <h1>Scrum Bandicoot</h1>
       <input type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>" autocomplete="username" required>
       <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
       <button type="submit" name="submitButton" value="Submit">Create account</button>
     </form>

     <?php if (isset($formMessage)) echo "<p id='answer'>$formMessage</p>" ?>
     <p>Already have an account?<br>
     <a href="/login">Sign in</a></p>
   </div>

  </body>

</html>