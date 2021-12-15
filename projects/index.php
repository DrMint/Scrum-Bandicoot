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
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/time.php") ?>
    
    <?php if(isset($_GET["leave"])){
            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            $DB->projectRemoveUser($slug, $DB->getCurrentUser()['slug']);
            header('Location: /');
          }
    ?>

    <div class="container">

      <div id="projectHeader">
        <h1><?php echo $_GET["project"] ?></h1>
        <?php echo "<a class='button outline' href='?leave&project=" . $_GET["project"] . "' >Leave project</a>" ?>
      </div>


      <div id="projects">

        <div id="projectsHeader">
          <h2>My Sprints</h2>
          <a class="button outline" href="/projects/sprints/?project=<?php echo $_GET["project"] ?>">Manage sprints</a>
        </div>

        <div id="projectsList">
          <?php

            

            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            
            foreach ($DB->getSprints($slug) as $sprintIndex => $sprint) {
              echo '<a class="project sprint" href="/projects/board?project=' . $slug . '&sprint=' . $sprintIndex . '">';
                echo '<img src="/img/default-project.webp" alt="">';
                if ($sprintIndex === 0) {
                  echo '<h3>Backlog Product</h3>';
                } else {
                  echo '<h3>' . 'Sprint ' . $sprintIndex . '</h3>';
                  if ($sprint['beginning'] > time()) {
                    echo '<p>Starts in ' . secondsToHumanReadable($sprint['beginning'] - time()) . '</p>';
                  } else {
                    echo '<p>Ends in ' . secondsToHumanReadable($sprint['ending'] - time()) . '</p>';
                  }
                }
              echo '</a>';
            }
            ?>
        </div>

        <div id="projectsHeader">
          <h2>My Tasks</h2>
        </div>

        <div id="taskList">
          <?php

            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            $sprints = $DB->getSprints($slug);
            
            foreach($sprints as $sprintIndex => $sprint) {
              foreach($DB->getColumns($slug, $sprintIndex) as $columnIndex => $column)
              foreach($DB->getTasks($slug, $sprintIndex, $columnIndex) as $task) {
                if (in_array($DB->getCurrentUser()['slug'], $task['assignees'])) {
                  echo '<a class="task" href="/projects/board?project=' . $slug . '&sprint=' . $sprintIndex . '">';
                  echo '<h3>' . $task['title'] . '</h3>';
                  if ($sprintIndex > 0) {
                    echo  '<p>in Sprint ' . $sprintIndex . '</p>';
                  } else {
                    echo  '<p>in Backlog Product</p>';
                  }
                  echo '</a>';
                }
              }
            }

          ?>
        </div>

      </div>
      

    </div>

  </body>
</html>
