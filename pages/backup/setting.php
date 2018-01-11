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

    case 'saveSetting':{

      if(empty($err)){
        $dataInformasiTitle = array(
                'option_value' => $informasiTitle.";".$informasiType.";".$informasiPosisi
        );
        sqlQuery(sqlUpdate("general_setting",$dataInformasiTitle,"option_name = 'informasi_title'"));
        $dataProdukTitle = array(
                'option_value' => $produkTitle.";".$produkType.";".$produkPosisi
        );
        sqlQuery(sqlUpdate("general_setting",$dataProdukTitle,"option_name = 'produk_title'"));
        $dataAcaraTitle = array(
                'option_value' => $acaraTitle.";".$acaraType.";".$acaraPosisi
        );
        sqlQuery(sqlUpdate("general_setting",$dataAcaraTitle,"option_name = 'acara_title'"));
        $dataInformasiBackground = array(
                'option_value' => $informasiBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataInformasiBackground,"option_name = 'informasi_background'"));
        $dataProdukBackground = array(
                'option_value' => $produkBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataProdukBackground,"option_name = 'produk_background'"));
        $dataAcaraBackground = array(
                'option_value' => $acaraBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataAcaraBackground,"option_name = 'acara_background'"));


      }


      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditSetting':{
      if(empty($namaSetting)){
          $err = "Isi Nama Setting";
      }elseif(empty($tanggalSetting)){
          $err = "Isi tanggal setting";
      }elseif(empty($waktuSetting)){
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
                'nama_setting' => $namaSetting,
                'tanggal' => generateDate($tanggalSetting),
                'jam' => $waktuSetting,
                'kapasitas' => $kapasitasSetting,
                'lokasi' => $lokasi,
                'deskripsi' =>  $deskripsiSetting,
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

    case 'deleteSetting':{
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

    case 'updateSetting':{
      $getData = sqlArray(sqlQuery("select * from setting where id = '$id'"));
      $explodeLocation = explode(',',$getData['koordinat']);
      $lat = $explodeLocation[0];
      $lng = $explodeLocation[1];
      $content = array("namaSetting" => $getData['nama_setting'],
      "tanggalSetting" => generateDate($getData['tanggal']),
      "waktuSetting" => $getData['jam'],
       "kapasitasSetting" => $getData['kapasitas'],
       "lokasi" => $getData['lokasi'],
       "deskripsiSetting" => $getData['deskripsi'],
       "kordinatLocation" => "(".$getData['koordinat'].")",
       "lat" => $lat,
       "lng" => $lng,
    );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from setting");
      while($dataSetting = sqlArray($getData)){
        foreach ($dataSetting as $key => $value) {
            $$key = $value;
        }

        $data .= "     <tr>
                          <td>$nama_setting</td>
                          <td>$lokasi</td>
                          <td>".generateDate($tanggal)." $jam</td>
                          <td>$kapasitas</td>
                          <td class='text-right'>
                              <a onclick=updateSetting($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteSetting($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama Setting</th>
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
      $content = array("tabelSetting" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=setting";

        </script>

        <script src="js/setting.js"></script>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Start Modal -->

                    <div class="col-md-12">
                      <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Setting
                                    </h4>
                                </div>

                                        <div class="tab-pane active" id="dataSetting">
                                          <div class="col-md-12" id='tableSetting'>
                                              <div class="card">
                                                  <div class="card-content">
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <label class="control-label">Title Informasi</label>
                                                                  <?php
                                                                    $getDataInformasiTitle = sqlArray(sqlQuery("select * from general_setting where option_name = 'informasi_title'"));
                                                                    $explodeInformasiTitle = explode(';',$getDataInformasiTitle['option_value']);
                                                                    $informasiTitle = $explodeInformasiTitle[0];
                                                                    $informasiType = $explodeInformasiTitle[1];
                                                                    $informasiPosisi = $explodeInformasiTitle[2];
                                                                  ?>
                                                                  <input type="text" id='informasiTitle' class='form-control' value='<?php echo $informasiTitle ?>' >
                                                              </div>
                                                          </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">POSISI</label>
                                                              <?php
                                                                  $arrayPosisi = array(
                                                                            array('LEFT','LEFT'),
                                                                            array('CENTER','CENTER'),
                                                                            array('RIGHT','RIGHT'),
                                                                  );
                                                                  echo cmbArray("informasiPosisi",$informasiPosisi,$arrayPosisi,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                               ?>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">TYPE</label>
                                                              <?php
                                                                  $arrayStatus = array(
                                                                            array('TEXT','TEXT'),
                                                                            array('IMAGE','IMAGE'),
                                                                  );
                                                                  echo cmbArray("informasiType",$informasiType,$arrayStatus,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                               ?>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Informasi Background</label>
                                                            <?php
                                                              $getInformasiBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'informasi_background'"));
                                                            ?>
                                                            <input type="color" id='informasiBackground' class='form-control' value='<?php echo $getInformasiBackground['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                    </div>
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <label class="control-label">Title Produk</label>
                                                                  <?php
                                                                    $getDataProdukTitle = sqlArray(sqlQuery("select * from general_setting where option_name = 'produk_title'"));
                                                                    $explodeProdukTitle = explode(';',$getDataProdukTitle['option_value']);
                                                                    $produkTitle = $explodeProdukTitle[0];
                                                                    $produkType = $explodeProdukTitle[1];
                                                                    $produkPosisi = $explodeProdukTitle[2];
                                                                  ?>
                                                                  <input type="text" id='produkTitle' class='form-control' value='<?php echo $produkTitle ?>' >
                                                              </div>
                                                          </div>
                                                        <div class="col-md-2 col-sm-2">
                                                            <div class="form-group label-floating">
                                                              <label class="control-label">POSISI</label>
                                                                <?php
                                                                    $arrayPosisi = array(
                                                                              array('LEFT','LEFT'),
                                                                              array('CENTER','CENTER'),
                                                                              array('RIGHT','RIGHT'),
                                                                    );
                                                                    echo cmbArray("produkPosisi",$produkPosisi,$arrayPosisi,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                                 ?>
                                                            </div>
                                                        </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">TYPE</label>
                                                              <?php
                                                                  $arrayStatus = array(
                                                                            array('TEXT','TEXT'),
                                                                            array('IMAGE','IMAGE'),
                                                                  );
                                                                  echo cmbArray("produkType",$produkType,$arrayStatus,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                               ?>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Produk Background</label>
                                                            <?php
                                                              $getProdukBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'produk_background'"));
                                                            ?>
                                                            <input type="color" id='produkBackground' class='form-control' value='<?php echo $getProdukBackground['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                    </div>
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <label class="control-label">Title Acara</label>
                                                                  <?php
                                                                    $getDataAcaraTitle = sqlArray(sqlQuery("select * from general_setting where option_name = 'acara_title'"));
                                                                    $explodeAcaraTitle = explode(';',$getDataAcaraTitle['option_value']);
                                                                    $acaraTitle = $explodeAcaraTitle[0];
                                                                    $acaraType = $explodeAcaraTitle[1];
                                                                    $acaraPosisi = $explodeAcaraTitle[2];
                                                                  ?>
                                                                  <input type="text" id='acaraTitle' class='form-control' value='<?php echo $acaraTitle ?>' >
                                                              </div>
                                                          </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">POSISI</label>
                                                              <?php
                                                                  $arrayPosisi = array(
                                                                            array('LEFT','LEFT'),
                                                                            array('CENTER','CENTER'),
                                                                            array('RIGHT','RIGHT'),
                                                                  );
                                                                  echo cmbArray("acaraPosisi",$acaraPosisi,$arrayPosisi,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                               ?>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">TYPE</label>
                                                              <?php
                                                                  $arrayStatus = array(
                                                                            array('TEXT','TEXT'),
                                                                            array('IMAGE','IMAGE'),
                                                                  );
                                                                  echo cmbArray("acaraType",$acaraType,$arrayStatus,"- TYPE -","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='4'")
                                                               ?>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Acara Background</label>
                                                            <?php
                                                              $getAcaraBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'acara_background'"));
                                                            ?>
                                                            <input type="color" id='acaraBackground' class='form-control' value='<?php echo $getAcaraBackground['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                    </div>
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <input type='button' id='submitSetting' value='SIMPAN' class='waves-effect waves-light btn' onclick="saveSetting();" >
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
