<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";

if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}



switch($tipe){

    case 'saveProfile':{

      if(empty($err)){

        $dataUsers = array(
                                'email' => $emailUsers,
                                'nama' => $namaLengkap,
                                'telepon' => $teleponUsers,
                                'alamat' => $alamatUsers,
                                'instansi' => $instansiUsers,
                                'password' => sha1(md5($passwordUsers)),
                            );
        sqlQuery(sqlUpdate("users",$dataUsers,"username = '".$_SESSION['username']."'"));
        $cek = sqlUpdate("users",$dataUsers,"username = '".$_SESSION['username']."'");
        $dataHash = array(
            'hash' => sha1(md5($passwordUsers)),
            'password' => $passwordUsers,
        );
        if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUsers'")) == 0){
            sqlQuery(sqlInsert("wordlist",$dataHash));
        }

      }


      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditProfile':{
      if(empty($namaProfile)){
          $err = "Isi Nama Profile";
      }elseif(empty($tanggalProfile)){
          $err = "Isi tanggal setting";
      }elseif(empty($waktuProfile)){
          $err = "Isi waktu setting";
      }elseif(empty($lokasi)){
          $err = "Isi lokasi";
      }

      if(empty($err)){
        if($kordinatX == ''){
            $kordinatLocation = getKordinat($lokasi);
        }else{
            $kordinatLocation = $kordinatX.",".$kordinatY;
        }
        $data = array(
                'nama_setting' => $namaProfile,
                'tanggal' => generateDate($tanggalProfile),
                'jam' => $waktuProfile,
                'kapasitas' => $kapasitasProfile,
                'lokasi' => $lokasi,
                'deskripsi' =>  $deskripsiProfile,
                'koordinat' => $kordinatLocation
        );
        $query = sqlUpdate("setting",$data,"id='$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("location" => $kordinatLocation);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteProfile':{
      $query = "delete from setting where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'generateLocation':{
      $explodeKordinat = explode(',',$koordinat);
      $kordinatX = str_replace("(","",$explodeKordinat[0]);
      $kordinatY = str_replace(')','',$explodeKordinat[1]);
      $kordinatY = str_replace(' ','',$kordinatY);
      $curl = curl_init();
			curl_setopt($curl,CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$kordinatX.",".$kordinatY."&key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU");
			curl_setopt($curl,CURLOPT_POST, sizeof($arrayData));
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
			curl_setopt($curl,CURLOPT_POSTFIELDS, $arrayData);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $result = json_decode(curl_exec($curl));
      $resultJSON = $result->results;
      $lokasi = $resultJSON[0]->formatted_address;





      $content = array('lat' => str_replace("(","",$explodeKordinat[0]),'lang' => str_replace(')','',$explodeKordinat[1]), 'lokasi' => $lokasi );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateProfile':{
      $getData = sqlArray(sqlQuery("select * from setting where id = '$id'"));
      $explodeLocation = explode(',',$getData['koordinat']);
      $lat = $explodeLocation[0];
      $lng = $explodeLocation[1];
      $content = array("namaProfile" => $getData['nama_setting'],
      "tanggalProfile" => generateDate($getData['tanggal']),
      "waktuProfile" => $getData['jam'],
       "kapasitasProfile" => $getData['kapasitas'],
       "lokasi" => $getData['lokasi'],
       "deskripsiProfile" => $getData['deskripsi'],
       "kordinatLocation" => "(".$getData['koordinat'].")",
       "lat" => $lat,
       "lng" => $lng,
    );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from setting");
      while($dataProfile = sqlArray($getData)){
        foreach ($dataProfile as $key => $value) {
            $$key = $value;
        }

        $data .= "     <tr>
                          <td>$nama_setting</td>
                          <td>$lokasi</td>
                          <td>".generateDate($tanggal)." $jam</td>
                          <td>$kapasitas</td>
                          <td class='text-right'>
                              <a onclick=updateProfile($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteProfile($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama Profile</th>
                  <th>Lokasi</th>
                  <th>Tanggal</th>
                  <th>Kapasitas</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelProfile" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        $getData = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."'"));
        $getPasswordUser = sqlArray(sqlQuery("select * from wordlist where hash = '".$getData['password']."'"));
        $passwordUsers = $getPasswordUser['password'];
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=profile";

        </script>

        <script src="js/profile.js"></script>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Start Modal -->

                    <div class="col-md-12">
                      <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Profile
                                    </h4>
                                </div>

                                        <div class="tab-pane active" id="dataProfile">
                                          <div class="col-md-12" id='tableProfile'>
                                              <div class="card">
                                                  <div class="card-content">
                                                    <div class="row">
                                                      <div class="col-md-6 col-sm-6">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Nama Lengkap</label>
                                                              <input type="text" id='namaLengkap' name='namaLengkap' class='form-control' value='<?php echo $getData['nama'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Email</label>
                                                              <input type="text" id='emailUsers' name='emailUsers' class='form-control' value='<?php echo $getData['email'] ?>' >
                                                          </div>
                                                      </div>

                                                    </div>
                                                    <div class="row">
                                                       <div class="col-md-2 col-sm-2">
                                                           <div class="form-group label-floating">
                                                               <label class="control-label">Telepon</label>
                                                               <input type="text" id='teleponUsers' class='form-control' value='<?php echo $getData['telepon'] ?>' >
                                                           </div>
                                                       </div>
                                                       <div class="col-md-10 col-sm-10">
                                                           <div class="form-group label-floating">
                                                               <label class="control-label">Alamat</label>
                                                               <input type="text" id='alamatUsers' class='form-control' value='<?php echo $getData['alamat'] ?>' >
                                                           </div>
                                                       </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-6 col-sm-6">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Instansi</label>
                                                              <input type="text" id='instansiUsers' class='form-control' value='<?php echo $getData['instansi'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Password</label>
                                                              <input type="password" id='passwordUsers' class='form-control' value='<?php echo $passwordUsers ?>' >
                                                          </div>
                                                      </div>

                                                    </div>
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <input type='button' id='submitProfile' value='SIMPAN' class='btn btn-primary' onclick="saveProfile();" >
                                                              </div>
                                                          </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
<?php

     break;
     }

}

?>
