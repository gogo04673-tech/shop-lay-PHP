<?php

require_once 'connect.php';

require_once 'functions.php';

$page = isset($_GET['page'] ? $_GET['page'] : 'home' ;

switch($page) {
  case 'signup': 
    require 'signup.ph';
    break;
  case 'login':
    require 'signin.php';
    break;
  default: 
    require 'home.php';
    break;
}
