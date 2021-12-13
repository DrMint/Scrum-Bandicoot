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

    function createProject($slug) {
      $newProject = ["slug" => $slug, "members" => [], "backlogProduct" => [], "sprints" => []];
      array_push($this->data['projects'], $newProject);
      $this->save();
    }

    function projectAddUser($projectSlug, $userSlug) {
      $project = &$this->getProject($projectSlug);
      array_push($project['members'], $userSlug);
      $this->save();
    }

    function &getProjectBacklog($projectSlug) {
      $project = &$this->getProject($projectSlug);
      return $project['backlogProduct'];
    }

    function removeProject($projectSlug, $userSlug) {
      $project = &$this->getProject($projectSlug);
      $userIndex = array_search($userSlug, $project['members']);
      if ($userIndex){
        array_splice($project['members'], $userIndex, 1);
      }
      $this->save();
    }

    ### SPRINT ###

    function &getSprints($projectSlug) {
      $project = &$this->getProject($projectSlug);
      return $project['sprints'];
    }

    function &getSprint($projectSlug, $sprintIndex) {
      $project = &$this->getSprints($projectSlug)[$sprintIndex];
      return $project;
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

  }

?>