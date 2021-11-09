<?php

  $rootFolder = $_SERVER["DOCUMENT_ROOT"] . '/users/';

  class User {
    public $slug;
    public $hash;

    function __construct($slug = '') {
      if (exist($slug)) {
        $json = json_decode(file_get_contents(getPathJSON($slug)));
        foreach ($json as $key => $value) {
          $this->$key = $value;
        }
      }
      $this->slug = $slug;
    }

    function setHash($hash) {
      $this->hash = $hash;
    }

    function write() {
      $filePath = getPathJSON($this->slug);

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
    $items = scandir($rootFolder);
    $result = array();
    foreach ($items as $item) {
      if (substr($item, -5, 5) == '.json') {
        array_push($result, substr($item, 0, -5));
      }
    }
    return $result;
  }

  function exist($slug) {
    return file_exists(getPathJSON($slug));
  }

  function getPathJSON($slug) {
    global $rootFolder;
    return $rootFolder . $slug . '.json';
  }

  function getCurrentUser() {
    return new User($_SESSION['loginUsername']);
  }


 ?>
