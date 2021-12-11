<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/master.css">
    <link rel="stylesheet" href="/css/board.css">
    <title>Scrum Bandicoot</title>
  </head>
  <body>

    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar.php") ?>

    <div class="container">
      <?php
        require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/board.php");
      ?>

      <div class="board-column">
        <h3>Backlog</h3>
        <div class="board-form">
          <input value="" type="text" name="backlog" style="height: 30px; width: 70%" autocomplete="off"/>
          <button type="submit" name="save-backlog">Save</button>
        </div>
      </div>

      <div class="board-column">
        <h3>In Progress</h3>
        <div class="board-form">
          <input value="" type="text" name="progress" style="height: 30px; width: 70%" autocomplete="off"/>
          <button type="submit" name="save-progress">Save</button>
        </div>
      </div>

      <div class="board-column">
        <h3>Pending</h3>
        <div class="board-form">
          <input value="" type="text" name="pending" style="height: 30px; width: 70%" autocomplete="off"/>
          <button type="submit" name="save-pending">Save</button>
        </div>
      </div>

      <div class="board-column">
        <h3>Completed</h3>
        <div class="board-form">
          <input value="" type="text" name="completed" style="height: 30px; width: 70%" autocomplete="off"/>
          <button type="submit" name="save-completed">Save</button>
        </div>
      </div>

    </div>
  </body>
</html>
