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
          <a class="button outline" href="/projects/create">Create a project</a>
          <a id="joinProject" class="button outline" href="/projects/join">Join a project</a>
        </div>
        <div id="projectsList">
          <?php
              require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/project.php");
              
              foreach (project\getListSlug() as $slug) {
                $project = new project\Project($slug);
                if (in_array(getCurrentUser()->slug, $project->members)) {
                  echo '<a class="project" href="/projects/?name=' . $project->name . '">';
                      echo '<img src="/img/default-project.webp" alt="">';
                      echo "<h3>$project->name</h3>";
                  echo '</a>';
                }
              }
            ?>
        </div>
        
      </div>
    </div>

  </body>
</html>
