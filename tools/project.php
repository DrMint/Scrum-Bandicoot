<?php namespace project;  

  $rootFolder = $_SERVER["DOCUMENT_ROOT"] . '/data/projects/';

  class Project {
    public $slug;
    public $name;

    function __construct($slug = '') {
      if (exist($slug)) {
        $json = json_decode(file_get_contents(getInfoPath($slug)));
        foreach ($json as $key => $value) {
          $this->$key = $value;
        }
      }
      $this->slug = $slug;
    }

    function setName($name) {
      $this->name = $name;
    }

    function write() {
      
      if(!exist($this->slug)) {
        mkdir(getPathFolder($this->slug));
      }
      
      $filePath = getInfoPath($this->slug);

      // Remove attributes that should be serialized
      $slug = $this->slug;
      unset($this->slug);

      $file = fopen($filePath, 'w');
      fwrite($file, json_encode($this));
      fclose($file);

      // Add them back
      $this->slug = $slug;
    }

  }

  function getListSlug() {
    global $rootFolder;
    $items = glob($rootFolder . '*', GLOB_ONLYDIR);
    $result = array();
    foreach ($items as $item) {
      $item = substr($item, strlen($rootFolder));
      array_push($result, $item);
    }
    return $result;
  }

  function exist($slug) {
    return is_dir(getPathFolder($slug));
  }

  function getPathFolder($slug) {
    global $rootFolder;
    return $rootFolder . $slug;
  }

  function getInfoPath($slug) {
    return getPathFolder($slug) . '/' . 'info.json';
  }

 ?>
