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

    <?php
      if (isset($_POST['submitButton'])) {

        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $slug = strtolower($name);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
        
        require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/project.php");

        if (!project\exist($slug)) {
          $project = new project\Project($slug);
          $project->setName($name);
          $project->write();
          header('Location: /');
        } else {

        }


      }
     ?>

    <div class="container">
      <form method="POST" action="/projects/create" id="myForm">
        <h2>Create a new project</h2>
        <input type="text" name="name" placeholder="Project's name">
        <button type="submit" name="submitButton" value="Submit">Create project</button>
      </form>
    </div>

  </body>
</html>