<?php
error_reporting(0);
$db_host='localhost';
$db_user='root';
$db_password='anjing';
$db_name='pilar_web';

mysql_connect($db_host,$db_user,$db_password);
mysql_select_db($db_name);

function setCekBox($cb, $KeyValueStr, $isi, $Prefix){
  $hsl = '';
  // $Prefix = "userManagement";
  /*if($KeyValueStr!=''){*/
    $hsl = "<input type='checkbox' $isi id='".$Prefix."_cb$cb' name='".$Prefix."_cb[]'
        value='".$KeyValueStr."' onchange = thisChecked('".$Prefix."_cb$cb','".$Prefix."_jmlcek'); >";
  /*}*/
  return $hsl;
}

function sqlInsert($table, $data){
	    if (is_array($data)) {
	        $key   = array_keys($data);
	        $kolom = implode(',', $key);
	        $v     = array();
	        for ($i = 0; $i < count($data); $i++) {
	            array_push($v, "'" . $data[$key[$i]] . "'");
	        }
	        $values = implode(',', $v);
	        $query  = "INSERT INTO $table ($kolom) VALUES ($values)";
	    } else {
	        $query = "INSERT INTO $table $data";
	    }
		  return $query;

	}
function sqlUpdate($table, $data, $where){
    if (is_array($data)) {
        // ini buat array
        $key   = array_keys($data);
        $kolom = implode(',', $key);
        $v     = array();
        for ($i = 0; $i < count($data); $i++) {
            array_push($v, $key[$i] . " = '" . $data[$key[$i]] . "'");
        }
        $values = implode(',', $v);
        $query  = "UPDATE $table SET $values WHERE $where";
    } else {
        $query = "UPDATE $table SET $data WHERE $where";
    }

   return $query;
}

function sqlQuery($query){
		return mysql_query($query);
}

function sqlArray($sqlQuery){
	return mysql_fetch_array($sqlQuery);
}

function sqlNumRow($sqlQuery){
	return mysql_num_rows($sqlQuery);
}


function cmbQuery($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='') {
    global $Ref;
    $Input = "<option value='$vAtas'>$Atas</option>";
    $Query = mysql_query($query);
    while ($Hasil = mysql_fetch_row($Query)) {
        $Sel = $Hasil[0] == $value ? "selected" : "";
        $Input .= "<option $Sel value='{$Hasil[0]}'>{$Hasil[1]}";
    }
    $Input = "<select $param name='$name' id='$name'>$Input</select>";
    return $Input;
}

function cmbArray($name='txtField',$value='',$arrList = '',$default='Pilih', $param='') {
 	$isi = $value;
	$Input = "<option value=''>$default</option>";
	for($i=0;$i<count($arrList);$i++) {
		$Sel = $isi==$arrList[$i][0]?" selected ":"";
		$Input .= "<option $Sel value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
	}
	$Input  = "<select $param name='$name'  id='$name' >$Input</select>";
	return $Input;
}
function cmbArrayEmpty($name='txtField',$value='',$arrList = '',$default='Pilih', $param='') {
 	$isi = $value;
	for($i=0;$i<count($arrList);$i++) {
		$Sel = $isi==$arrList[$i][0]?" selected ":"";
		$Input .= "<option $Sel value='{$arrList[$i][0]}'>{$arrList[$i][1]}</option>";
	}
	$Input  = "<select $param name='$name'  id='$name' >$Input</select>";
	return $Input;
}

function generateAPI($cek,$err,$content){
		$api = array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
		return json_encode($api);
}

function generateDate($tanggal){
		$tanggal = explode("-",$tanggal);
		return $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
}

function clearDirectory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
	 if (!$dir_handle)
	      return false;
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != "..") {
	            if (!is_dir($dirname."/".$file))
	                 unlink($dirname."/".$file);
	            else
	                 delete_directory($dirname.'/'.$file);
	       }
	 }
	 closedir($dir_handle);
	 return true;
}

function getImage($dirname,$namaProduk) {
   mkdir($namaProduk);
	 $dir_handle = opendir($dirname);
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) != 'desc') {
                  $newFile = $namaProduk."/".md5($file).".jpg";
									copy($dirname."/".$file,$newFile);
									if(file_get_contents($dirname."/".$file.".desc")){
	                  $deskripsiScreenShot = file_get_contents($dirname."/".$file.".desc");
	                }else{
	                   $deskripsiScreenShot = "";
	                }
	                $arrFile[] = array('fileName' => $newFile, 'desc' => $deskripsiScreenShot);
	       }
	 }
	 closedir($dir_handle);
   return json_encode($arrFile);
}

function baseToImage($base64_string, $output_file) {

		$ifp = fopen( $output_file, 'wb' );
		$data = explode( ',', $base64_string );

		fwrite( $ifp, base64_decode( $data[ 1 ] ) );

		fclose( $ifp );

		return str_replace("../",$output_file);
}

function limitChar($karakter, $panjang)
{
    if (strlen($karakter) <= $panjang) {
        return $karakter;
    } else {
        $string = substr($karakter, 0, $panjang);
        return $string . '...';
    }
}


function imageToBase($path){
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  return $base64;
}

?>
