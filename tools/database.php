<?php

  $dbPath = $_SERVER['DOCUMENT_ROOT'] . '/db.json';

  class Database {
    private $data;

    function __construct() {
      global $dbPath;
      $this->data = json_decode(file_get_contents($dbPath), true);
    }

    ### GLOBAL ###

    function save() {
      global $dbPath;
      $dbFile = fopen($dbPath, 'w');
      fwrite($dbFile, json_encode($this->data, JSON_PRETTY_PRINT));
      fclose($dbFile);
    }

    ### USER ###

    function &getUsers() {
      return $this->data['users'];
    }

    function &getUser($slug) {
      $users = &$this->getUsers();
      foreach ($users as $user) {
        if ($user['slug'] === $slug) {
          return $user;
        }
      }
    }

    function &getCurrentUser() {
      return $this->getUser($_SESSION['loginUsername']);
    }

    function userExists($slug) {
      return !is_null($this->getUser($slug));
    }

    function verifyCredentials($slug, $password) {
      if (!$this->userExists($slug)) return FALSE;
      return password_verify($password, $this->getUser($slug)['hash']);
    }

    function createUser($slug, $password) {
      $newUser = ["slug" => $slug, "hash" => password_hash($password, PASSWORD_DEFAULT)];
      array_push($this->data['users'], $newUser);
      $this->save();
    }

    ### PROJECT ###

    function &getProjects() {
      return $this->data['projects'];
    }

    function &getProject($slug) {
      $projects = &$this->getProjects();
      foreach ($projects as &$project) {
        if ($project['slug'] === $slug) {
          return $project;
        }
      }
    }

    function projectExists($slug) {
      return !is_null($this->getProject($slug));
    }

    function userInProject($projectSlug, $userSlug) {
      return in_array($userSlug, $this->getProject($projectSlug)['members']);
    }

    function createProject($slug) {
      $backlogColumn = ['title' => 'Backlog Sprint', 'locked' => TRUE, 'maxItem' => -1, 'tasks' => []];
      $backlogSprint = ['beginning' => -1, 'ending' => -1, 'columns' => [$backlogColumn]];
      $newProject = ['slug' => $slug, 'members' => [], 'sprints' => [$backlogSprint]];
      array_push($this->data['projects'], $newProject);
      $this->save();
    }

    function projectAddUser($projectSlug, $userSlug) {
      if ($this->projectExists($projectSlug) and !$this->userInProject($projectSlug, $userSlug)) {
        $project = &$this->getProject($projectSlug);
        array_push($project['members'], $userSlug);
        $this->save();
      }
    }

    function projectRemoveUser($projectSlug, $userSlug) {
      var_dump($userIndex);
      if ($this->projectExists($projectSlug) and $this->userInProject($projectSlug, $userSlug)) {
        $project = &$this->getProject($projectSlug);
        $userIndex = array_search($userSlug, $project['members']);
        array_splice($project['members'], $userIndex, 1);
        $this->save();
      }
    }

    ### SPRINT ###

    function &getSprints($projectSlug) {
      $project = &$this->getProject($projectSlug);
      return $project['sprints'];
    }

    function &getSprint($projectSlug, $sprintIndex) {
      $sprint = &$this->getSprints($projectSlug)[$sprintIndex];
      return $sprint;
    }

    function addSprint($projectSlug, $beginning, $ending) {
      $project = &$this->getProject($projectSlug); 
      $newSprint = ['beginning' => $beginning, 'ending' => $ending, 'columns' => $this->getDefaultSprintColumns()];
      array_push($project['sprints'], $newSprint);
      $this->save();
    }

    function editSprint($projectSlug, $sprintIndex, $newBeginning, $newEnding) {
      $sprint = &$this->getSprint($projectSlug, $sprintIndex);
      $sprint['beginning'] = $newBeginning;
      $sprint['ending'] = $newEnding;
      $this->save();
    }

    function cancelSprint($projectSlug, $sprintIndex) {
      foreach ($this->getColumns($projectSlug, $sprintIndex) as $columnIndex => $column) {
        foreach ($this->getTasks($projectSlug, $sprintIndex, $columnIndex) as $task) {
          $this->insertTask($projectSlug, 0, 0, $task);
        }
      }
      $sprints = &$this->getSprints($projectSlug);
      array_splice($sprints, $sprintIndex, 1);
      $this->save();
    }

    function getDefaultSprintColumns() {
      $backlog = ['title' => 'Backlog Sprint', 'maxItem' => -1, 'locked' => TRUE, 'tasks' => []];
      $todo = ["title" => "To do", "maxItem" => -1, "locked" => FALSE, "tasks" => []];
      $doing = ["title" => "Doing", "maxItem" => -1, "locked" => FALSE, "tasks" => []];
      $review = ["title" => "Review", "maxItem" => 4, "locked" => FALSE, "tasks" => []];
      $done = ["title" => "Done", "maxItem" => -1, "locked" => TRUE, "tasks" => []];
      return [$backlog, $todo, $doing, $review, $done];
    }

    ### BOARD ###

    function &getColumns($projectSlug, $sprintIndex) {
      $sprint = &$this->getSprint($projectSlug, $sprintIndex);
      return $sprint['columns'];
    }

    function &getColumn($projectSlug, $sprintIndex, $columnIndex) {
      $columns = &$this->getColumns($projectSlug, $sprintIndex);
      return $columns[$columnIndex];
    }

    function createColumn($projectSlug, $sprintIndex, $title) {
      $column = &$this->getColumns($projectSlug, $sprintIndex);
      $newColumn = ["title" => $title, "locked" => false, "maxItem" => -1, "tasks" => []];
      array_push($column, $newColumn);
    }

    function setColumnTitle($projectSlug, $sprintIndex, $columnIndex, $newTitle) {
      $column = &$this->getColumn($projectSlug, $sprintIndex, $columnIndex);
      $column["title"] = $newTitle;
    }

    function setColumnLock($projectSlug, $sprintIndex, $columnIndex, $taskIndex, bool $lock) {
      $column = &$this->getColumn($projectSlug, $sprintIndex, $columnIndex);
      $column["locked"] = $lock;
    }

    function setMaxItem($projectSlug, $sprintIndex, $columnIndex, int $maxItem) {
      $column = &$this->getColumn($projectSlug, $sprintIndex, $columnIndex);
      $column["maxItem"] = $maxItem;
    }

    function deleteColumn($projectSlug, $sprintIndex, $columnIndex, $taskIndex) {
      $column = &$this->getTask($projectSlug, $sprintIndex, $columnIndex);
      if (!$column["locked"]){
        array_splice($column, $columnIndex, 1);
        $column = NULL;
      }
      else{echo "Can't delete this column";}
    }

    ### TASKS ###

    function &getTasks($projectSlug, $sprintIndex, $columnIndex) {
      $column = &$this->getColumn($projectSlug, $sprintIndex, $columnIndex);
      return $column['tasks'];
    }

    function &getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex) {
      $tasks = &$this->getTasks($projectSlug, $sprintIndex, $columnIndex);
      return $tasks[$taskIndex];
    }

    function createTask($projectSlug, $sprintIndex, $columnIndex, $title) {
      $tasks = &$this->getTasks($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $newTask = ["title" => $title, "description" => "", "assignees" => []];
      array_push($tasks, $newTask);
      $this->save();
    }

    function moveTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $newColumnIndex) {
      $task = $this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $this->insertTask($projectSlug, $sprintIndex, $newColumnIndex, $task);
      $this->deleteTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $this->save();
    }

    function insertTask($projectSlug, $sprintIndex, $columnIndex, $task) {
      $tasks = &$this->getTasks($projectSlug, $sprintIndex, $columnIndex);
      array_unshift($tasks, $task);
      $this->save();
    }

    function setTaskTitle($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $newTitle) {
      $task = &$this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $task["title"] = $newTitle;
      $this->save();
    }

    function setTaskDescription($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $newDescription) {
      $task = &$this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $task["description"] = $newDescription;
      $this->save();
    }

    function addTaskAssignee($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $newAssignee) {
      $task = &$this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      if (!in_array($newAssignee, $task["assignees"])) {
        array_push($task["assignees"], $newAssignee);
        $this->save();
      }      
    }

    function removeTaskAssignee($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $assignee) {
      $task = &$this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $assigneeIndex = array_search($assignee, $task["assignees"]);
      if (in_array($assignee, $task["assignees"])) {
        array_splice($task["assignees"], array_search($assignee, $task["assignees"]), 1);
        $this->save();
      }
    }

    function setTaskAssignees($projectSlug, $sprintIndex, $columnIndex, $taskIndex, $assignees) {
      $task = &$this->getTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex);
      $task['assignees'] = $assignees;
      $this->save();
    }

    function deleteTask($projectSlug, $sprintIndex, $columnIndex, $taskIndex) {
      $tasks = &$this->getTasks($projectSlug, $sprintIndex, $columnIndex);
      array_splice($tasks, $taskIndex, 1);
      $this->save();
    }

    function deleteTasks($projectSlug, $sprintIndex, $columnIndex, $taskIndices) {
      $tasks = &$this->getTasks($projectSlug, $sprintIndex, $columnIndex);
      $newTasks = [];
      foreach ($tasks as $taskIndex=> $task) {
        if (!in_array($taskIndex, $taskIndices)) {
          array_push($newTasks, $task);
        }
      }
      $tasks = $newTasks;
      $this->save();
    }

  }

?>