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

    case 'saveTeam':{
      if(empty($namaLengkapTeam)){
          $err = "Isi nama lengkap ";
      }elseif(empty($tempatLahir)){
          $err = "Isi tempat lahir";
      }elseif(empty($tanggalLahir)){
          $err = "Isi tanggal lahir";
      }elseif(empty($posisiTeam)){
          $err = "Pilih posisi ";
      }elseif(empty($tentangTeam)){
          $err = "Isi deskripsi diri ";
      }

      if(empty($err)){
          $arraySosmed = array(
                'facebook'=> $facebookTeam,
                'twiter'=> $twiterTeam,
                'instagram'=> $instagramTeam,
                'line'=> $lineTeam,
                'bbm'=> $bbmTeam,
                'whatsapp'=> $waTeam,
          );
          baseToImage($fotoTeam,"images/team/$namaLengkapTeam.jpg");
          $data = array(
                  'nama' => $namaLengkapTeam,
                  'tempat_lahir' => $tempatLahir,
                  'tanggal_lahir' => generateDate($tanggalLahir),
                  'posisi' => $posisiTeam,
                  'foto' => "images/team/$namaLengkapTeam.jpg",
                  'tentang' =>  $tentangTeam,
                  'sosial_media' =>  json_encode($arraySosmed),
          );
          $query = sqlInsert("team",$data);
          sqlQuery($query);
          $cek = $query;



      }
      $content = array("judulTeam" => $judulTeam);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditTeam':{
      if(empty($namaLengkapTeam)){
          $err = "Isi nama lengkap ";
      }elseif(empty($tempatLahir)){
          $err = "Isi tempat lahir";
      }elseif(empty($tanggalLahir)){
          $err = "Isi tanggal lahir";
      }elseif(empty($posisiTeam)){
          $err = "Pilih posisi ";
      }elseif(empty($tentangTeam)){
          $err = "Isi deskripsi diri ";
      }

      if(empty($err)){
          $arraySosmed = array(
                'facebook'=> $facebookTeam,
                'twiter'=> $twiterTeam,
                'instagram'=> $instagramTeam,
                'line'=> $lineTeam,
                'bbm'=> $bbmTeam,
                'whatsapp'=> $waTeam,
          );
          baseToImage($fotoTeam,"images/team/$namaLengkapTeam.jpg");
          $data = array(
                  'nama' => $namaLengkapTeam,
                  'tempat_lahir' => $tempatLahir,
                  'tanggal_lahir' => generateDate($tanggalLahir),
                  'posisi' => $posisiTeam,
                  'foto' => "images/team/$namaLengkapTeam.jpg",
                  'tentang' =>  $tentangTeam,
                  'sosial_media' =>  json_encode($arraySosmed),
          );
          $query = sqlUpdate("team",$data,"id = '$idEdit'");
          sqlQuery($query);
          $cek = $query;



      }
      $content = array("judulTeam" => $judulTeam);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteTeam':{
      $query = "delete from team where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateTeam':{
      $getData = sqlArray(sqlQuery("select * from team where id = '$id'"));
      $getRealPassword = sqlArray(sqlQuery("select * from wordlist where hash = '".$getData['password']."'"));
      $arrayStatus = array(
                array('1','MEMBER'),
                array('2','ADMIN'),
      );
      $content = array("usernameTeam" => $getData['username'],"statusTeam" => cmbArray("statusTeam",$getData['jenis_user'],$arrayStatus,"-- TYPE USER --","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'"),
       "emailTeam" => $getData['email'], "passwordTeam" => $getRealPassword['password'], "namaTeam" => $getData['nama'], "teleponTeam" => $getData['telepon'], "alamatTeam" => $getData['alamat'], "instansiTeam" => $getData['instansi']);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from team");
      while($dataTeam = sqlArray($getData)){
        foreach ($dataTeam as $key => $value) {
            $$key = $value;
        }
        if($jenis_user == '1'){
            $jenisTeam = "MEMBER";
        }else{
            $jenisTeam = "ADMIN";
        }
        $getJabatan = sqlArray(sqlQuery("select * from ref_posisi where id = '$posisi'"));
        $jabatan = $getJabatan['posisi'];
        $data .= "     <tr>
                          <td>$nama</td>
                          <td>".generateDate($tanggal_lahir)."</td>
                          <td>$tempat_lahir</td>
                          <td>$jabatan</td>
                          <td class='text-right'>
                              <a onclick=updateTeam($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteTeam($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama</th>
                  <th>Tanggal Lahir</th>
                  <th>Tempat Lahir</th>
                  <th>Jabatan</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelTeam" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=team";

        </script>
        <script src="js/team.js"></script>

        <?php
          if(!isset($_GET['edit'])){
            ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                                <div class="card">
                                    <div class="card-content">
                                        <ul class="nav nav-pills nav-pills-primary">
                                            <li class="active">
                                                <a href="#dataInfo" id='data1' data-toggle="tab" aria-expanded="true" onclick="clearTemp();">Team</a>
                                            </li>
                                            <li>
                                                <a href="#userBaru" id='data2' data-toggle="tab" aria-expanded="false" onclick="baruTeam();">Baru</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="dataInfo">
                                                <div class="col-md-12" id='tableTeam'>
                                                  <div class="card">
                                                      <div class="card-header card-header-icon" data-background-color="purple">
                                                          <i class="material-icons">assignment</i>
                                                      </div>
                                                      <div class="card-content">
                                                          <h4 class="card-title">Data Team</h4>
                                                          <div class="toolbar">
                                                              <!--        Here you can write extra buttons/actions for the toolbar              -->
                                                          </div>
                                                          <div class="material-datatables">
                                                              <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                                                  <thead>
                                                                      <tr>
                                                                          <th>Judul</th>
                                                                          <th>Posisi</th>
                                                                          <th>Tanggal</th>
                                                                          <th>Penulis</th>
                                                                          <th>Status</th>
                                                                          <th class="disabled-sorting text-right">Actions</th>
                                                                      </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                  </tbody>
                                                              </table>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                            </div>



                                        <div class="tab-pane" id="userBaru">
                                          <form id='formTeam'>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" >
                                                      <label class="control-label">Nama Lengkap</label>
                                                      <input type="text" id='namaLengkapTeam' name='namaLengkapTeam' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" >
                                                      <label class="control-label">Tempat Lahir</label>
                                                      <input type="text" id='tempatLahir' name='tempatLahir' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" >
                                                      <label class="control-label">Tanggal Lahir</label>
                                                      <input type="text" id='tanggalLahir' name='tanggalLahir' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-3 col-md-6 col-sm-3">
                                                  <label class="control-label">Jabatan</label>
                                                  <?php
                                                    echo cmbQuery("posisiTeam","","select * from ref_posisi","class='selectpicker' data-style='btn btn-primary btn-round'  data-size='7'","-- JABATAN --")
                                                  ?>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4 col-sm-4">
                                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail">
                                                        <img  src="assets/img/image_placeholder.jpg" id='tempImageProduk' alt="...">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                    <div>
                                                        <span class="btn btn-rose btn-round btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="hidden" id='fotoTeam' name='fotoTeam'>
                                                            <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='fileFotoTeam' name="fileFotoTeam">
                                                        </span>
                                                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                                    </div>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" >
                                                      <label class="control-label">Tentang</label>
                                                      <textarea id='tentangTeam' name='tentangTeam' class="form-control" style="height:100px;"></textarea>
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <?php
                                                  $dataSosmed = json_decode($getData['media_sosial']);
                                                  $facebookTeam = $dataSosmed->facebook;
                                                  $twiterTeam = $dataSosmed->twiter;
                                                  $instagramTeam = $dataSosmed->instagram;
                                                  $lineTeam = $dataSosmed->line;
                                                  $waTeam = $dataSosmed->whatsapp;
                                                  $bbmTeam = $dataSosmed->bbm;
                                               ?>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Facebook</label>
                                                      <input type="text" id='facebookTeam' name='facebookTeam' class='form-control' value='<?php echo $facebookTeam ?>' >
                                                  </div>
                                              </div>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Twiter</label>
                                                      <input type="text" id='twiterTeam' name='twiterTeam' class='form-control' value='<?php echo $twiterTeam ?>' >
                                                  </div>
                                              </div>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Instagram</label>
                                                      <input type="text" id='instagramTeam' name='instagramTeam' class='form-control' value='<?php echo $instagramTeam ?>' >
                                                  </div>
                                              </div>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Line</label>
                                                      <input type="text" id='lineTeam' name='lineTeam' class='form-control' value='<?php echo $lineTeam ?>' >
                                                  </div>
                                              </div>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Whats App</label>
                                                      <input type="text" id='waTeam' name='waTeam' class='form-control' value='<?php echo $waTeam ?>' >
                                                  </div>
                                              </div>
                                              <div class="col-md-2 col-sm-2">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">BBM</label>
                                                      <input type="text" id='bbmTeam' name='bbmTeam' class='form-control' value='<?php echo $bbmTeam ?>' >
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveTeam();" data-dismiss="modal">Simpan</button>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
            <?php
          }else{
              $getDataTeam = sqlArray(sqlQuery("select * from team where id = '".$_GET['edit']."'"));
              $type = pathinfo($getDataTeam['foto'], PATHINFO_EXTENSION);
              $data = file_get_contents($getDataTeam['foto']);
              $baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
              ?>
              <div class="content">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-content">
                                          <ul class="nav nav-pills nav-pills-primary">
                                              <li >
                                                  <a href="pages.php?page=team" >Team</a>
                                              </li>
                                              <li class="active">
                                                  <a href="#userBaru" id='data2' data-toggle="tab" aria-expanded="false" >Edit</a>
                                              </li>
                                          </ul>
                                          <div class="tab-content">



                                            <div class="tab-pane active" id="userBaru">
                                              <form id='formTeam'>
                                                <div class="row">
                                                  <div class="col-lg-12">
                                                      <div class="form-group label-floating" >
                                                          <label class="control-label">Nama Lengkap</label>
                                                          <input type="text" id='namaLengkapTeam' name='namaLengkapTeam' value='<?php echo $getDataTeam['nama'] ?>' class="form-control">
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-12">
                                                      <div class="form-group label-floating" >
                                                          <label class="control-label">Tempat Lahir</label>
                                                          <input type="text" id='tempatLahir' name='tempatLahir' class="form-control" value='<?php echo $getDataTeam['tempat_lahir'] ?>'>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-12">
                                                      <div class="form-group label-floating" >
                                                          <label class="control-label">Tanggal Lahir</label>
                                                          <input type="text" id='tanggalLahir' name='tanggalLahir' class="form-control" value='<?php echo generateDate($getDataTeam['tanggal_lahir']) ?>'>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-3 col-md-6 col-sm-3">
                                                      <label class="control-label">Jabatan</label>
                                                      <?php
                                                        echo cmbQuery("posisiTeam",$getDataTeam['posisi'],"select * from ref_posisi","class='selectpicker' data-style='btn btn-primary btn-round'  data-size='7'","-- JABATAN --")
                                                      ?>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-md-4 col-sm-4">
                                                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail">
                                                            <img  src="<?php echo $getDataTeam['foto'] ?>" id='tempImageProduk' alt="...">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                        <div>
                                                            <span class="btn btn-rose btn-round btn-file">
                                                                <span class="fileinput-new">Select image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input type="hidden" id='fotoTeam' name='fotoTeam' value='<?php echo $baseOfFile ?>'>
                                                                <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='fileFotoTeam' name="fileFotoTeam">
                                                            </span>
                                                            <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-12">
                                                      <div class="form-group label-floating" >
                                                          <label class="control-label">Tentang</label>
                                                          <textarea id='tentangTeam' name='tentangTeam' class="form-control" style="height:100px;"><?php echo $getDataTeam['tentang'] ?></textarea>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <?php
                                                      $dataSosmed = json_decode($getDataTeam['sosial_media']);
                                                      $facebookTeam = $dataSosmed->facebook;
                                                      $twiterTeam = $dataSosmed->twiter;
                                                      $instagramTeam = $dataSosmed->instagram;
                                                      $lineTeam = $dataSosmed->line;
                                                      $waTeam = $dataSosmed->whatsapp;
                                                      $bbmTeam = $dataSosmed->bbm;
                                                   ?>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Facebook</label>
                                                          <input type="text" id='facebookTeam' name='facebookTeam' class='form-control' value='<?php echo $facebookTeam ?>' >
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Twiter</label>
                                                          <input type="text" id='twiterTeam' name='twiterTeam' class='form-control' value='<?php echo $twiterTeam ?>' >
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Instagram</label>
                                                          <input type="text" id='instagramTeam' name='instagramTeam' class='form-control' value='<?php echo $instagramTeam ?>' >
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Line</label>
                                                          <input type="text" id='lineTeam' name='lineTeam' class='form-control' value='<?php echo $lineTeam ?>' >
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Whats App</label>
                                                          <input type="text" id='waTeam' name='waTeam' class='form-control' value='<?php echo $waTeam ?>' >
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 col-sm-2">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">BBM</label>
                                                          <input type="text" id='bbmTeam' name='bbmTeam' class='form-control' value='<?php echo $bbmTeam ?>' >
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-12">
                                                    <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveEditTeam(<?php echo $getDataTeam['id'] ?>);" data-dismiss="modal">Simpan</button>
                                                  </div>
                                                </div>
                                              </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
              <?php
          }
         ?>



        <div class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="LoadingImage" style="display: none;">
              <div class="modal-dialog modal-notice">
                  <div class="modal-content" style="background-color: transparent; border: unset; box-shadow: unset;">
                      <div class="modal-body">
                            <img src="img/unnamed.gif" style="width: 30%; height: 30%; display: block; margin: auto;">
                      </div>
                  </div>
              </div>
        </div>
<?php

     break;
     }

}

?>
