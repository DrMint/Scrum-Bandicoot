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
      <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/project.php");

        foreach (project\getListSlug() as $slug) {
          $project = new project\Project($slug);
          echo $project->name;
        }
        
       ?>

<div class="board-column">
    <h3>Backlog</h3>
    <div class="board-form">
      <input value="<?php echo get_active_value("backlog", $activeTask);?>" type="text" name="backlog" style="height: 30px; width: 70%" autocomplete="off"/>
      <button type="submit" name="save-backlog">Save</button>
    </div>
    <div class="board-items">
      <?php foreach (get_tasks('backlog') as $task):?>
          <?php echo show_tile($task,'backlog');?>
      <?php endforeach;?>
    </div>
  </div>
    </div>

  </body>
</html>
