<?php 
session_start();
include "../include/config.php";

  
    $user= @$_POST['var_usn'];
    $pass= @$_POST['var_pwd'];
    // $login= @$_POST['login'];
    // $user= stripslashes($user);
    // $pass= stripslashes($pass);
    // $user = mysql_real_escape_string($user);
    // $pass = mysql_real_escape_string($pass);
    // $user = htmlspecialchars($user);
    // $pass = htmlspecialchars($pass);

$data =mysql_fetch_array(mysql_query("SELECT * from users where username='$user' and  password = '".sha1(md5($pass))."' and jenis_user = '2' "));
// $datas =  $data['username'];
 



    	if( $user =="" || $pass == ""){
    	$arr = array('response' => 'kosong');
   	      echo json_encode($arr);

    	}elseif(mysql_num_rows(mysql_query("SELECT * from users where username='$user' and  password = '".sha1(md5($pass))."' and jenis_user = '2' "))!=0){
           $_SESSION["username"] = $user;
           $_SESSION["status"] = "login";
           $arr = array('response' => 'ok',
           							'username' => $user
            );
   				 echo json_encode($arr);
      }else{
    	$arr = array('response' => 'tidak');
       		echo json_encode($arr);
    	}
    
 //echo $datas;
    
    ?>