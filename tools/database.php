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

    function getUsers() {
      return $this->data['users'];
    }

    function getUser($slug) {
      foreach ($this->getUsers() as $user) {
        if ($user['slug'] === $slug) {
          return $user;
        }
      }
    }

    function getCurrentUser() {
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
      var_dump($project);
      echo "<br>";
      var_dump($this->getProject($projectSlug));
      $this->save();
    }

  }

?>