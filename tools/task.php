<?php

  require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/users.php");

  $rootFolder = $_SERVER["DOCUMENT_ROOT"] . '/data/projects/' . $_GET["project"] . '/' ;

  class Task {
    public $slug;
    public $title;
    public $description;
    public $assignees;
    public $deadline;


    function __construct($slug = '') {
        if (exist($slug)) {
          $json = json_decode(file_get_contents(getPathJSON($slug)));
          foreach ($json as $key => $value) {
            $this->$key = $value;
          }
        }
        $this->slug = $slug;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setAssignees($assignees) {
        $this->assignees = $assignees;
    }

    function setDeadline($deadline) {
        $this->deadline = $deadline;
    }

  }








?>