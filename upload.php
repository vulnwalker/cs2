<?php
session_start();
if ($_FILES['file'] != '') {
copy($_FILES['file']['tmp_name'], "temp/".$_SESSION['username']."/".$_FILES['file']['name']) or die ('Proses upload Gagal: ');
}else {
die('Silahkan pilih file');
}
?>
