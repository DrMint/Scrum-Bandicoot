<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - Create project</title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>

    <?php
      if (isset($_POST['submitButton'])) {

        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $slug = strtolower($name);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

        if (!$DB->projectExists($slug)) {
          $DB->createProject($slug);
          $DB->projectAddUser($slug, $DB->getCurrentUser()['slug']);
          header('Location: /projects/?project=' . $slug);
        } else {
          echo "There is already a project with this name. Please choose another name.";
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
