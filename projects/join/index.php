<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - Join a project</title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>

    <?php

      if (isset($_GET['slug'])) {
        $slug = filter_var($_GET['slug'], FILTER_SANITIZE_STRING);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

        if ($DB->projectExists($slug)) {
          $DB->projectAddUser($slug, $DB->getCurrentUser()['slug']);
          header('Location: /projects/?project=' . $slug);
        } else {
          echo "A problem occured while trying to join this project.";
        }
      }

    ?>

    <div class="container">

      <div id="projects">
        <div id="projectsHeader">
          <h2>Public projects</h2>
        </div>
        <div id="projectsList">
          <?php        
              foreach ($DB->getProjects() as $project) {
                if (!in_array($DB->getCurrentUser()['slug'], $project['members'])) {
                  echo '<div class="project">';
                      echo '<img src="/img/default-project.webp" alt="">';
                      echo '<h3>' . $project['slug'] . '</h3>';
                      echo '<a class="button outline" href="?slug=' . $project['slug'] . '">Join</a>';
                  echo '</div>';
                }
              }
            ?>
        </div>
      </div>

  </body>
</html>
