<?php
error_reporting(0);
$dirname = "kszxpo";
$dir_handle = opendir($dirname);
while($file = readdir($dir_handle)) {
      if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) != 'desc') {
               $newFile = $namaProduk."/".md5($file).".jpg";
              //  copy($dirname."/".$file,$newFile);
               if(file_get_contents($dirname."/".$file.".desc")){
                 $deskripsiScreenShot = file_get_contents($dirname."/".$file.".desc");
               }else{
                  $deskripsiScreenShot = "";
               }
               $arrFile[] = array('fileName' => $newFile, 'desc' => $deskripsiScreenShot);
      }
}
echo json_encode($arrFile);

 ?>
