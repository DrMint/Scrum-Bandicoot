<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/board.css">
    <link rel="stylesheet" href="/css/home.css">
    <title>Scrum Bandicoot - <?php echo $_GET["project"] ?></title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/board.php"); ?>

    <?php

      
      if (!isset($_GET['project']) or !isset($_GET['sprint'])) {
        header('Location: /' . $slug);
      }
      
    
    ?>

        
      <?php

        $slug = filter_var($_GET['project'], FILTER_SANITIZE_STRING);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

        $sprint = filter_var($_GET['sprint'], FILTER_SANITIZE_STRING);
        
        echo '<div id="boardHeader">';
        echo "<a class='button outline' href='/projects?project=" . $_GET["project"] . "' >Return to project</a>";
        echo '</div>';

        echo '<div class="board-container">';
          displayColumns($slug, $sprint, $DB->getColumns($slug, $sprint));
        echo '</div>';

        if (isset($_GET['action'])) {
          $action = filter_var($_GET['action'], FILTER_SANITIZE_STRING);
          
          if ($action === 'left') {

            if (isset($_GET['task'])) {
              $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->moveTask($slug, $sprint, $column, $task, $column - 1);
            } else {
              # TODO for column
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'right') {

            if (isset($_GET['task'])) {
              $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->moveTask($slug, $sprint, $column, $task, $column + 1);
            } else {
              # TODO for column
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'delete') {

            if (isset($_GET['task'])) {
              $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->deleteTask($slug, $sprint, $column, $task);
            } else {
              # TODO for column
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'edit') {

            if (isset($_GET['task'])) {
              $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);

              if (isset($_POST['submitButton'])) {
                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                $assignees = $_POST['assignees'];
                $DB->setTaskTitle($slug, $sprint, $column, $task, $title);
                $DB->setTaskDescription($slug, $sprint, $column, $task, $description);
                $DB->setTaskAssignees($slug, $sprint, $column, $task, $assignees);
                header('Location: ?project=' . $slug . "&sprint=" . $sprint);
              } else {
                $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
                $taskIndex = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
                $task = $DB->getTask($slug, $sprint, $column, $taskIndex);
                $members = $DB->getProject($slug)['members'];
                displayFormEdit($slug, $sprint, $column, $taskIndex, $task, $members);
              }

            } else {
              # TODO for column
            }

          } elseif ($action == 'view') {

            $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
            $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
            $task = $DB->getTask($slug, $sprint, $column, $task);
            displayFormView($slug, $sprint, $task);


          } elseif ($action == 'create') {

            if (isset($_GET['column'])) {
              $column = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
              
              if (isset($_POST['submitButton'])) {
                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $DB->createTask($slug, $sprint, $column, $title);
                header('Location: ?project=' . $slug . "&sprint=" . $sprint);
              } else {
                displayFormCreation($slug, $sprint, $column );
              }

            } else {
              # TODO for column
            }

          }
        }
      
      
      ?>

  </body>
</html>
