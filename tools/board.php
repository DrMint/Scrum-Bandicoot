<?php

  require_once($_SERVER["DOCUMENT_ROOT"] . "/tools/task.php");

  $rootFolder = $_SERVER["DOCUMENT_ROOT"];

  class Board {
    public $slug;
    public $column;
    public $title;
    public $maxElem;
    public $task;


    function __construct($slug = '') {
      if (exist($slug)) {
        $json = json_decode(file_get_contents(getPathJSON($slug)));
        foreach ($json as $key => $value) {
          $this->$key = $value;
        }
      }
      $this->slug = $slug;
    }

    function getTasks($column) {
        global $rootFolder;
        $tasks = json_decode($rootFolder . "db.json");
        $results = array();
        foreach ($tasks as $task) {
            array_push($results,  $tasks['title']);
        }

        return $results;

    }


    function showTask($taskObject, $type="") {
        $baseUrl = $_SERVER["PHP_SELF"]."?shift&id=".$taskObject["id"]."&type=";
        $editUrl = $_SERVER["PHP_SELF"] . "?edit&id=".$taskObject["id"]."&type=". $type;
        $deleteUrl = $_SERVER["PHP_SELF"] . "?delete&id=".$taskObject["id"];
        $out = '<span class="board">'.$taskObject["task"].'
            <hr>
            <span>
                <a href="'.$baseUrl.'backlog">B</a> |
                <a href="'.$baseUrl.'pending">P</a> |
                <a href="'.$baseUrl.'progress">IP</a> |
                <a href="'.$baseUrl.'completed">C</a> |
            </span>
            <a href="'.$editUrl.'">Edit</a> | <a href="'.$deleteUrl.'">Delete</a>
            </span>';
        return $out;
    }

    function saveTask() {

    }

    function editTask() {
        $activeId = "";
        $activeTask = "";

        if(isset($_GET['edit'])){
            $id = isset($_GET['id']) ? $_GET['id'] : NULL;
            $activeId = $id;
            $type = isset($_GET['type']) ? $_GET['type'] : NULL;
            if($id){
                $taskObject = json_decode(file_get_contents($rootFolder . "db.json"));
            }
        }

    }
    

  }
?>