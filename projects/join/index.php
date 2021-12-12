<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - Home</title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>

    <div class="container">
      <?php

        foreach ($DB->getProjects() as $project) {
          if (!in_array($DB->getCurrentUser()['slug'], $project['members'])) {
            echo $project['slug'] . '<br>';
          }
        }
        
       ?>

  </body>
</html>
