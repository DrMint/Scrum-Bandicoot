<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - <?php echo $_GET["name"] ?></title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>

    <div class="container">
      <h2><?php echo $_GET["name"] ?></h2>
      <h3>Backlog Product</h3>
    </div>

  </body>
</html>
