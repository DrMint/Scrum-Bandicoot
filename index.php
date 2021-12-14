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
      <div id="projects">
        <div id="projectsHeader">
          <h2>My projects</h2>
          <a class="button outline" href="?action=new">Create a project</a>
        </div>
        <div id="projectsList">
          <?php

              function displayFormNew() {
                echo '<div class="popup-form">';
                  echo '<a href="/">';
                  echo '<div class="background"></div></a>';
                  echo '<form action="/?action=new" method="post">';
                    echo '<h2>Create a project</h2>';
                    echo '<input class="title" type="text" name="name" placeholder="Project\'s name">';
                    echo '<button type="submit" class="button outline" name="submitButton" value="Submit">Confirm</button>';
                  echo '</form>';
                echo '</div>';
              }

              foreach ($DB->getProjects() as $project) {
                if (in_array($DB->getCurrentUser()['slug'], $project['members'])) {
                  echo '<a class="project" href="/projects/?project=' . $project['slug'] . '">';
                      echo '<img src="/img/default-project.webp" alt="">';
                      echo '<h3>' . $project['slug'] . '</h3>';
                  echo '</a>';
                }
              }

              if (isset($_GET['action'])) {
                $action = filter_var($_GET['action'], FILTER_SANITIZE_STRING);
                if ($action === 'new') {
                  if (isset($_POST['submitButton'])) {
                    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
                    $slug = strtolower($name);
                    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
                    if (!$DB->projectExists($slug)) {
                      $DB->createProject($slug);
                      $DB->projectAddUser($slug, $DB->getCurrentUser()['slug']);
                      header('Location: /projects/?project=' . $slug);
                      exit();
                    }
                    header('Location: /');
                  } else {
                    displayFormNew();
                  }
                } elseif ($action === 'join') {
                  $slug = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
                  $slug = strtolower($slug);
                  $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

                  if ($DB->projectExists($slug)) {
                    $DB->projectAddUser($slug, $DB->getCurrentUser()['slug']);
                    header('Location: /');
                  }
                }
              }

            ?>
        </div>
      </div>
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
                        echo '<a class="button outline" href="?action=join&name=' . $project['slug'] . '">Join</a>';
                    echo '</div>';
                  }
                }
              ?>
          </div>
        </div>
    </div>
  </body>
</html>
