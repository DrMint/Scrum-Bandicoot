<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - <?php echo $_GET["project"] ?></title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>
    <?php if(isset($_GET["leave"])){
            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            $DB->removeProject($slug,$_SESSION["loginUsername"]);
            header('Location: /');
          }
    ?>

    <div class="container">
      <h2><?php echo $_GET["project"] ?></h2>
      <h3>Backlog Product</h3>
      <?php

        $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);

        foreach ($DB->getProjectBacklog($slug) as $task) {
          echo $task['title'] . '<br>'; 
        }


        echo "<a class='delete button' href='?leave=true&project=" . $_GET["project"] . "' >Leave</a>";

      ?>
    </div>

  </body>
</html>
