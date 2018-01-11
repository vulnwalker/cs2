<?php include "include/config.php";
  $page = @$_GET['page'];
session_start();
switch($page){
  case 'informasi':{
    include 'pages/informasi.php';
    break;
  }
  case 'produk':{
    include 'pages/produk.php';
    break;
  }
  case 'acara':{
    include 'pages/acara.php';
    break;
  }
  case 'slider':{
    include 'pages/slider.php';
    break;
  }
  case 'setting':{
    include 'pages/setting.php';
    break;
  }
  case 'profile':{
    include 'pages/profile.php';
    break;
  }
  case 'chating':{
    include 'pages/chating.php';
    break;
  }
  case 'userManagement':{
    include 'pages/userManagement.php';
    break;
  }
  case 'lowonganKerja':{
    include 'pages/lowonganKerja.php';
    break;
  }
  case 'team':{
    include 'pages/team.php';
    break;
  }

  default:{

  }

}

?>
