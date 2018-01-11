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
        $dataSlider = array(
                'option_value' => $sliderBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataSlider,"option_name = 'background_slider'"));
        $dataTentang = array(
                'option_value' => $tentangBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataTentang,"option_name = 'background_tentang'"));
        $dataLowongan = array(
                'option_value' => $lowonganBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataLowongan,"option_name = 'background_lowongan'"));
        $dataPopularTitleColor = array(
                'option_value' => $popularTitleColor
        );
        sqlQuery(sqlUpdate("general_setting",$dataPopularTitleColor,"option_name = 'title_popular_color'"));
        $dataPopularDeskripsiColor = array(
                'option_value' => $popularDeskripsiColor
        );
        sqlQuery(sqlUpdate("general_setting",$dataPopularDeskripsiColor,"option_name = 'deskripsi_popular_color'"));
        $dataEffectSlider = array(
                'option_value' => $effectSlider
        );
        sqlQuery(sqlUpdate("general_setting",$dataEffectSlider,"option_name = 'effect_slider'"));

        $dataKontak = array(
                                'nama_perusahaan' => $namaPerusahaan,
                                'alamat' => $alamatPerusahaan,
                                'telepon' => $teleponPerusahaan,
                                'email' => $emailPerusahaan,
                                'tentang' => $tentang,
                                'media_sosial' => json_encode(array(
                                                                'facebook' => $facebookPerusahaan,
                                                                'twiter' => $twiterPerusahaan,
                                                                'instagram' => $instagramPerusahaan,
                                                                'googlePlus' => $googlePlus,
                                                                'linkedIn' => $linkedInPerusahaan,
                                                                'whatsapp' => $waPerusahaan,
                                                              )),
                            );
        sqlQuery(sqlUpdate("kontak_web",$dataKontak,"1=1"));


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
        $getDataKontak = sqlArray(sqlQuery("select * from kontak_web"));
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
                                                             <label class="control-label">Background Informasi</label>
                                                             <?php
                                                               $getInformasiBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'informasi_background'"));
                                                             ?>
                                                             <input type="color" id='informasiBackground' class='form-control' value='<?php echo $getInformasiBackground['option_value'] ?>' >
                                                           </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <div class="form-group label-floating">
                                                              <label class="control-label">Background Produk</label>
                                                              <?php
                                                                $getProdukBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'produk_background'"));
                                                              ?>
                                                              <input type="color" id='produkBackground' class='form-control' value='<?php echo $getProdukBackground['option_value'] ?>' >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <div class="form-group label-floating">
                                                              <label class="control-label">Background Acara</label>
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
                                                           <label class="control-label">Background Slider</label>
                                                           <?php
                                                             $getSliderBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_slider'"));
                                                           ?>
                                                           <input type="color" id='sliderBackground' class='form-control' value='<?php echo $getSliderBackground['option_value'] ?>' >
                                                         </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Background Tentang</label>
                                                            <?php
                                                              $getTentangBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_tentang'"));
                                                            ?>
                                                            <input type="color" id='tentangBackground' class='form-control' value='<?php echo $getTentangBackground['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Background Lowongan</label>
                                                            <?php
                                                              $getLowonganBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_lowongan'"));
                                                            ?>
                                                            <input type="color" id='lowonganBackground' class='form-control' value='<?php echo $getLowonganBackground['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                  </div>
                                                    <div class="row">
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Warna Title Popular</label>
                                                            <?php
                                                              $getPopularTitleColor = sqlArray(sqlQuery("select * from general_setting where option_name = 'title_popular_color'"));
                                                            ?>
                                                            <input type="color" id='popularTitleColor' class='form-control' value='<?php echo $getPopularTitleColor['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                            <label class="control-label">Warna Deskripsi Popular</label>
                                                            <?php
                                                              $getPopularDeskripsiColor = sqlArray(sqlQuery("select * from general_setting where option_name = 'deskripsi_popular_color'"));
                                                            ?>
                                                            <input type="color" id='popularDeskripsiColor' class='form-control' value='<?php echo $getPopularDeskripsiColor['option_value'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                         <div class="form-group label-floating">
                                                           <label class="control-label">Slider Effect</label>
                                                           <?php
                                                             $arrayEffect = array(
                                                                       array('1','PARTICKELS'),
                                                                       array('2','SQUARE'),
                                                                       array('3','SNOW'),
                                                                       array('4','STARS'),
                                                                       array('5','BOKEH'),
                                                             );
                                                             $getEffectSlider = sqlArray(sqlQuery("select * from general_setting where option_name = 'effect_slider' "));
                                                             echo cmbArray("effectSlider",$getEffectSlider['option_value'],$arrayEffect,"- EFFECT SLIDER -","class='form-control'")
                                                           ?>
                                                         </div>
                                                      </div>

                                                  </div>
                                                    <div class="row">
                                                      <div class="col-md-3 col-sm-3">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Nama Perusahaan</label>
                                                              <input type="text" id='namaPerusahaan' class='form-control' value='<?php echo $getDataKontak['nama_perusahaan'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-5 col-sm-5">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Alamat</label>
                                                              <input type="text" id='alamatPerusahaan' class='form-control' value='<?php echo $getDataKontak['alamat'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Email</label>
                                                              <input type="text" id='emailPerusahaan' class='form-control' value='<?php echo $getDataKontak['email'] ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-2 col-sm-2">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Telepon</label>
                                                              <input type="text" id='teleponPerusahaan' class='form-control' value='<?php echo $getDataKontak['telepon'] ?>' >
                                                          </div>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <?php
                                                          $dataSosmed = json_decode($getDataKontak['media_sosial']);
                                                          $facebookPerusahaan = $dataSosmed->facebook;
                                                          $twiterPerusahaan = $dataSosmed->twiter;
                                                          $instagramPerusahaan = $dataSosmed->instagram;
                                                          $googlePlus = $dataSosmed->googlePlus;
                                                          $waPerusahaan = $dataSosmed->whatsapp;
                                                          $linkedInPerusahaan = $dataSosmed->linkedIn;
                                                       ?>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Facebook</label>
                                                              <input type="text" id='facebookPerusahaan' class='form-control' value='<?php echo $facebookPerusahaan ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Twiter</label>
                                                              <input type="text" id='twiterPerusahaan' class='form-control' value='<?php echo $twiterPerusahaan ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Instagram</label>
                                                              <input type="text" id='instagramPerusahaan' class='form-control' value='<?php echo $instagramPerusahaan ?>' >
                                                          </div>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Google +</label>
                                                              <input type="text" id='googlePlus' class='form-control' value='<?php echo $googlePlus ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Whats App</label>
                                                              <input type="text" id='waPerusahaan' class='form-control' value='<?php echo $waPerusahaan ?>' >
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Linked In</label>
                                                              <input type="text" id='linkedInPerusahaan' class='form-control' value='<?php echo $linkedInPerusahaan ?>' >
                                                          </div>
                                                      </div>
                                                    </div>

                                                    <div class="row">
                                                      <div class="col-md-12 col-sm-12">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Tentang</label>
                                                              <textarea  id='tentang' class='form-control' ><?php echo $getDataKontak['tentang'] ?></textarea>
                                                          </div>
                                                      </div>

                                                    </div>
                                                      <div class="row">
                                                          <div class="col-md-4 col-sm-4">
                                                              <div class="form-group label-floating">
                                                                  <input type='button' id='submitSetting' value='SIMPAN' class='btn btn-primary' onclick="saveSetting();" >
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
