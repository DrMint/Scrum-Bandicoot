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
    
    <?php 
          function displayFormEdit($project, $sprint, $columnIndex, $taskIndex, $task, $members) {
            echo '<div class="popup-form">';
                echo '<a href="/projects/sprints/?projects=' . $_GET["project"] . 'method="post" >';
                echo '<div class="background"></div></a>';
                echo '<form action="/projects/sprints/?action=edit&project=' . $_GET["project"] . 'method="post" >';
                    echo '<h2>Edit a sprint</h2>';
                    echo '<input type="date" name="beginning" min="' . date('Y-m-d', time()) . '"value="' . date('Y-m-d', time()) . '">';
                    echo '<input type="date" name="ending" min="' . date('Y-m-d', time()) . '"value="' . date('Y-m-d', time()) . '">';
                    echo '<button type="submit" class="button outline" name="submitButton" value="Submit">Confirm</button>';
                echo '</form>';
            echo '</div>';
        }

          if(isset($_GET["cancel"])){
            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            $sprint = filter_var($_GET["sprint"], FILTER_SANITIZE_STRING);
            $DB->cancelSprint($slug, $sprint);
            header('Location: ?project=' . $_GET["project"]);
          }
          elseif(isset($_GET["edit"])){
            if(isset($_POST["submitButton"])){
              $beginning = filter_var($_POST["beginning"], FILTER_SANITIZE_STRING);
              $ending = filter_var($_POST["ending"], FILTER_SANITIZE_STRING);
              $beginning = convertDateToSecond($beginning);
              $ending = convertDateToSecond($ending);

            }

            header('Location: ?project=' . $_GET["project"]);
          }
    ?>

    <div class="container">

      <div id="projectHeader">
        <h1><?php echo $_GET["project"] ?></h1>
        <?php echo "<a class='button outline' href='/projects?project=" . $_GET["project"] . "' >Return to project</a>" ?>
      </div>


      <div id="projects">

        <div id="projectsHeader">
          <h2>My Sprints</h2>
          <a class="button outline" href="/projects/sprints/?project=<?php echo $_GET["project"] ?>">Add sprint</a>
        </div>

        <div id="projectsList">
          <?php

            $slug = filter_var($_GET["project"], FILTER_SANITIZE_STRING);
            
            foreach ($DB->getSprints($slug) as $sprintIndex => $sprint) {
              if ($sprintIndex > 0) {
                echo '<div class="project sprint manage" href="/projects/board?project=' . $slug . '&sprint=' . $sprintIndex . '">';
                  echo '<img src="/img/default-project.webp" alt="">';
                  echo '<h3>' . 'Sprint ' . $sprintIndex . '</h3>';
                  if ($sprint['beginning'] > time()) {
                    echo '<p>Start: ' . date('Y-m-d', $sprint['beginning']) . ' (in ' . secondsToHumanReadable($sprint['beginning'] - time()) .')</p>';
                  } else {
                    echo '<p>Start: ' . date('Y-m-d', $sprint['beginning']) . ' (' . secondsToHumanReadable(time() - $sprint['beginning']) .' ago)</p>';
                  }
                  echo '<p>End: ' . date('Y-m-d', $sprint['ending']) . ' (' . secondsToHumanReadable($sprint['ending'] - $sprint['beginning']) . ' later)</p>';
                  echo '<a class="button outline" href="?edit&project=' . $_GET["project"] . '&sprint=' . $sprintIndex . '">Edit</a>';
                  echo '<a class="button outline" href="?cancel&project=' . $_GET["project"] . '&sprint=' . $sprintIndex . '">Cancel</a>';
                echo '</div>';
              }
            }
            ?>
        </div>
      </div>
      

    </div>

  </body>
</html>
