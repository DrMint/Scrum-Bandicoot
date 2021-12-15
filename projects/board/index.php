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


        if (isset($_GET['action'])) {
          $action = filter_var($_GET['action'], FILTER_SANITIZE_STRING);
          
          if ($action === 'left') {
            $columnIndex = filter_var($_GET['column'], FILTER_SANITIZE_STRING);

            if (isset($_GET['task'])) {
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->moveTask($slug, $sprint, $columnIndex, $task, $columnIndex - 1);
            } else {
              $DB->moveColumn($slug, $sprint, $columnIndex, $columnIndex - 1);
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'right') {
            $columnIndex = filter_var($_GET['column'], FILTER_SANITIZE_STRING);

            if (isset($_GET['task'])) {
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->moveTask($slug, $sprint, $columnIndex, $task, $columnIndex + 1);
            } else {
              $DB->moveColumn($slug, $sprint, $columnIndex, $columnIndex + 1);
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'delete') {
            
            $columnIndex = filter_var($_GET['column'], FILTER_SANITIZE_STRING);
            if (isset($_GET['task'])) {
              $task = filter_var($_GET['task'], FILTER_SANITIZE_STRING);
              $DB->deleteTask($slug, $sprint, $columnIndex, $task);
            } else {
              $DB->deleteColumn($slug, $sprint, $columnIndex);
            }
            header('Location: ?project=' . $slug . "&sprint=" . $sprint);

          } elseif ($action == 'edit') {
            $columnIndex = filter_var($_GET['column'], FILTER_SANITIZE_STRING);

            if (isset($_GET['task'])) {
              $taskIndex = filter_var($_GET['task'], FILTER_SANITIZE_STRING);

              if (isset($_POST['submitButton'])) {
                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                $assignees = $_POST['assignees'];
                if (is_null($assignees)) $assignees = [];
                $DB->setTaskTitle($slug, $sprint, $columnIndex, $taskIndex, $title);
                $DB->setTaskDescription($slug, $sprint, $columnIndex, $taskIndex, $description);
                $DB->setTaskAssignees($slug, $sprint, $columnIndex, $taskIndex, $assignees);
                header('Location: ?project=' . $slug . "&sprint=" . $sprint);
              } else {
                $task = $DB->getTask($slug, $sprint, $columnIndex, $taskIndex);
                $members = $DB->getProject($slug)['members'];
                displayFormEdit($slug, $sprint, $columnIndex, $taskIndex, $task, $members);
              }

            } else {

              if (isset($_POST['submitButton'])) {
                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $DB->setColumnTitle($slug, $sprint, $columnIndex, $title);
                header('Location: ?project=' . $slug . "&sprint=" . $sprint);
              } else {
                $column = $DB->getColumn($slug, $sprint, $columnIndex);
                $members = $DB->getProject($slug)['members'];
                displayFormEditColumn($slug, $sprint, $columnIndex, $column);
              }

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
              
              if (isset($_POST['submitButton'])) {
                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $DB->createColumn($slug, $sprint, $title);
                header('Location: ?project=' . $slug . "&sprint=" . $sprint);
              } else {
                displayFormCreationColumn($slug, $sprint);
              }

            }

          }
        }
        
        echo '<div id="boardHeader">';
        echo '<a class="button outline" href="/projects?project=' . $_GET["project"] . '">Return to project</a>';
        if ($_GET["sprint"] > 0) echo '<a class="button outline" href="?project=' . $_GET["project"] . '&sprint=' . $_GET["sprint"] . '&action=create' . '">Create column</a>';
        echo '</div>';

        echo '<div class="board-container">';
          displayColumns($slug, $sprint, $DB->getColumns($slug, $sprint));
        echo '</div>';
     
      
      ?>

  </body>
</html>
