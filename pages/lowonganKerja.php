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

    case 'saveLowongan':{

      if(empty($judulLowongan)){
          $err = "Isi judul lowongan";
      }elseif(empty($posisiLowongan)){
          $err = "Pilih Posisi Pekerjaan";
      }elseif(empty($spesifikasiPekerjaan)){
          $err = "Isi spesifikasi pekerjaan";
      }elseif(empty($jobDesc)){
          $err = "Isi deskripsi pekerjaan ";
      }elseif(empty($jenisKelamin)){
          $err = "Pilih jenis kelamin";
      }

      if(empty($err)){
          if(empty($batasTanggalLamaran)){
            $batasTanggalLamaran = "";
          }else{
            $batasTanggalLamaran = generateDate($batasTanggalLamaran);
          }
          $expodeSpesifikasi = explode("\n",$spesifikasiPekerjaan);
          for ($i=0; $i < sizeof($expodeSpesifikasi) ; $i++) {
            $listSpesifikasi .= "<li>".$expodeSpesifikasi[$i]."</li>";
          }
          $data = array(
                  'judul' => $judulLowongan,
                  'tanggal_buat' => date("Y-m-d"),
                  'batas_tanggal' => $batasTanggalLamaran,
                  'posisi' => $posisiLowongan,
                  'pendidikan' => implode(";",$pendidikanLowongan),
                  'salary' => $salaryMinimum."-".$salaryMaximum,
                  'jam_kerja' => $jamKerja,
                  'pengalaman' => $pengalamanKerjaMinimum."-".$pengalamanKerjaMaximum,
                  'deskripsi' =>  $jobDesc,
                  'spesifikasi' =>  $listSpesifikasi,
                  'usia' => $usiaMinimum."-".$usiaMaximum,
                  'gender' => $jenisKelamin,
          );
          $query = sqlInsert("lowongan_kerja",$data);
          sqlQuery($query);
          $cek = $query;
      }
      $content = array("judulLowongan" => $judulLowongan);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditLowongan':{
      if(empty($judulLowongan)){
          $err = "Isi judul lowongan";
      }elseif(empty($posisiLowongan)){
          $err = "Pilih Posisi Pekerjaan";
      }elseif(empty($spesifikasiPekerjaan)){
          $err = "Isi spesifikasi pekerjaan";
      }elseif(empty($jobDesc)){
          $err = "Isi deskripsi pekerjaan ";
      }elseif(empty($jenisKelamin)){
          $err = "Pilih jenis kelamin";
      }

      if(empty($err)){
        if(empty($batasTanggalLamaran)){
          $batasTanggalLamaran = "";
        }else{
          $batasTanggalLamaran = generateDate($batasTanggalLamaran);
        }
        $expodeSpesifikasi = explode("\n",$spesifikasiPekerjaan);
        for ($i=0; $i < sizeof($expodeSpesifikasi) ; $i++) {
          $listSpesifikasi .= "<li>".$expodeSpesifikasi[$i]."</li>";
        }
        $data = array(
                'judul' => $judulLowongan,
                'batas_tanggal' => $batasTanggalLamaran,
                'posisi' => $posisiLowongan,
                'pendidikan' => implode(";",$pendidikanLowongan),
                'salary' => $salaryMinimum."-".$salaryMaximum,
                'jam_kerja' => $jamKerja,
                'pengalaman' => $pengalamanKerjaMinimum."-".$pengalamanKerjaMaximum,
                'deskripsi' =>  $jobDesc,
                'spesifikasi' =>  $spesifikasiPekerjaan,
                'usia' => $usiaMinimum."-".$usiaMaximum,
                'gender' => $jenisKelamin,
          );
          $query = sqlUpdate("lowongan_kerja",$data,"id = '$idEdit'");
          sqlQuery($query);
          $cek = $query;


      }
      $content = array("judulLowongan" => $judulLowongan);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteLowongan':{
      $query = "delete from lowongan_kerja where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'downloadCV':{
      $getData = sqlArray(sqlQuery("select * from lamaran where id = '$id'"));
      $content = array('cv' => "http://pilar.web.id/cv/".$getData['cv']);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateLowongan':{
      $getData = sqlArray(sqlQuery("select * from lowongan_pekerjaan where id = '$id'"));
      $getPendidikan = sqlQuery('select * from ref_pendidikan');
      $explodePendidikan = explode(";",$getData['pendidikan']);
      while ($dataPendidikan = sqlArray($getPendidikan)) {
        //  if(in_array($dataPendidikan['id'],$explodePendidikan)){
              $selected = "selected";
          // }else{
          //     $selected = "";
          // }
          $isiPendidikan .= "<option value='".$dataPendidikan['id']."' $selected > ".$dataPendidikan['tingkat']."</option>";
      }
      $comboPendidikan = "
      <div class='col-lg-12 col-md-6 col-sm-3' id='divPendidikanLowongan'>
          <label class='control-label'>Pendidikan</label>
          <select class='selectpicker' name='pendidikanLowongan[]' id='pendidikanLowongan' data-style='select-with-transition' multiple title='Pilih Pendidikan' data-size='7'>
          <option disabled> Pilih Pendidikan</option>
          $isiPendidikan
      </select>
      </div>";
      $content = array("posisiLowongan" => $getData['posisi'],"pendidikanLowongan" => $comboPendidikan,
       "emailLowongan" => $getData['email'], "passwordLowongan" => $getRealPassword['password'], "namaLowongan" => $getData['nama'], "teleponLowongan" => $getData['telepon'], "alamatLowongan" => $getData['alamat'], "instansiLowongan" => $getData['instansi']);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from lowongan_kerja");
      while($dataLowongan = sqlArray($getData)){
        foreach ($dataLowongan as $key => $value) {
            $$key = $value;
        }
        $getPosisi = sqlArray(sqlQuery("select * from ref_posisi where id = '$posisi'"));
        $namaPosisi = $getPosisi['posisi'];
        $explodePendidikan = explode(';',$pendidikan);
        if(sizeof($explodePendidikan) != 0){
            for ($i=0; $i < sizeof($explodePendidikan) ; $i++) {
                $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='".$explodePendidikan[$i]."'"));
                $listPendidikan .= "- ".$getNamaPendidikan['tingkat']."<br>";
            }
        }else{
            $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='$pendidikan'"));
            $listPendidikan = "- ".$getNamaPendidikan['tingkat'];
        }

        $data .= "     <tr>
                          <td>$judul</td>
                          <td>$namaPosisi</td>
                          <td>$listPendidikan</td>
                          <td>$salary</td>
                          <td>$jam_kerja</td>
                          <td>$pengalaman Tahun</td>
                          <td class='text-right'>
                              <a onclick=listLamaran($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>confirmation_number</i></a>
                              <a onclick=updateLowongan($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>edit</i></a>
                              <a onclick=deleteLowongan($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Judul</th>
                  <th>Posisi</th>
                  <th>Pendidikan</th>
                  <th>Salary</th>
                  <th>Jam Kerja</th>
                  <th>Pengalaman</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelLowongan" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'loadLamaran':{
      $getData = sqlQuery("select * from lamaran where id_lowongan = '$idLamaran'");
      while($dataLamaran = sqlArray($getData)){
        foreach ($dataLamaran as $key => $value) {
            $$key = $value;
        }
        $getDataPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id = '$pendidikan'"));
        $pendidikan = $getDataPendidikan['tingkat'];

        $data .= "     <tr>
                          <td>$nama</td>
                          <td>$pendidikan</td>
                          <td>$email</td>
                          <td>$telepon</td>
                          <td><a onclick=downloadCV($id); class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>arrow_downward</i></a></td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama</th>
                  <th>Pendidikan</th>
                  <th>Email</th>
                  <th>Telepon</th>
                  <th>CV</th>

              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelLamaran" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=lowonganKerja";

        </script>
        <script src="js/lowonganKerja.js"></script>

        <?php
            if(!isset($_GET['edit']) && !isset($_GET['action'])){
                ?>
                  <div class="content">
                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-md-12">
                                      <div class="card">
                                          <div class="card-content">
                                              <ul class="nav nav-pills nav-pills-primary">
                                                  <li class="active">
                                                      <a href="#dataLowongan" id='data1' data-toggle="tab" aria-expanded="true" onclick="clearTemp();">Lowongan</a>
                                                  </li>
                                                  <li>
                                                      <a href="#lowonganBaru" id='data2' data-toggle="tab" aria-expanded="false" onclick="baruLowongan();">Baru</a>
                                                  </li>
                                              </ul>
                                              <div class="tab-content">
                                                  <div class="tab-pane active" id="dataLowongan">
                                                      <div class="col-md-12" id='tableLowongan'>
                                                        <div class="card">
                                                            <div class="card-header card-header-icon" data-background-color="purple">
                                                                <i class="material-icons">assignment</i>
                                                            </div>
                                                            <div class="card-content">
                                                                <h4 class="card-title">Data Lowongan</h4>
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
                                                            <!-- end content-->
                                                        </div>
                                                        <!--  end card  -->
                                                    </div>
                                                  </div>




                                                  <div class="tab-pane" id="lowonganBaru">
                                                    <form id='formLowongan'>
                                                    <div class="row">
                                                      <div class="col-lg-12 col-md-12 col-sm-12" >
                                                          <label class="control-label">Judul Lowongan</label>
                                                          <input type="text" id='judulLowongan' name='judulLowongan' class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-lg-3 col-md-6 col-sm-3" >
                                                          <label class="control-label">Posisi</label>
                                                          <?php
                                                            echo cmbQuery("posisiLowongan","1","select id,posisi from ref_posisi","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'","-- POSISI --")
                                                          ?>
                                                      </div>
                                                      <div class="col-lg-3 col-md-6 col-sm-3" >
                                                          <label class="control-label">Batas Tanggal Lamaran</label>
                                                          <input type="text" id='batasTanggalLamaran' name='batasTanggalLamaran' class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-6 col-sm-3" id='divPendidikanLowongan'>
                                                            <label class="control-label">Pendidikan</label>
                                                            <select class="selectpicker" name='pendidikanLowongan[]' id='pendidikanLowongan' data-style="select-with-transition" multiple title="Pilih Pendidikan" data-size="7">
                                                                <option disabled> Pilih Pendidikan</option>
                                                                <?php
                                                                    $getPendidikan = sqlQuery("select * from ref_pendidikan");
                                                                    while ($dataPendidikan = sqlArray($getPendidikan)) {
                                                                        echo "<option value='".$dataPendidikan['id']."'> ".$dataPendidikan['tingkat']."</option>";
                                                                    }
                                                                 ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-lg-3 col-md-6 col-sm-3" >
                                                          <label class="control-label">Jenis Kelamin</label>
                                                          <?php
                                                            $arrayJenisKelamin = array(
                                                                                      array('1','LAKI-LAKI'),
                                                                                      array('2','PEREMPUAN'),
                                                                                      array('3','LAKI-LAKI DAN PEREMPUAN'),
                                                                                    );
                                                            echo cmbArray("jenisKelamin","1",$arrayJenisKelamin,"-- JENIS KELAMIN --","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                                          ?>
                                                      </div>
                                                    </div>
                                                      <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                                <label class="control-label">Salary Minimum</label>
                                                                <input type="number" id='salaryMinimum' name='salaryMinimum' class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <div class="form-group label-floating" >
                                                                <center><h3> - </h3></center>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                                <label class="control-label">Salary Maximum</label>
                                                                <input type="number" id='salaryMaximum' name='salaryMaximum' class="form-control">
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                                <label class="control-label">Usia Minimum</label>
                                                                <input type="number" id='usiaMinimum' name='usiaMinimum' class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <div class="form-group label-floating" >
                                                                <center><h3> - </h3></center>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                                <label class="control-label">Usia Maximux</label>
                                                                <input type="number" id='usiaMaximum' name='usiaMaximum' class="form-control">
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-2">
                                                          <div class="radio">
                                                              <label>
                                                                  <input type="radio" value='PART TIME' id='partTime' name="jamKerja" checked> PART TIME
                                                              </label>
                                                          </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                          <div class="radio">
                                                              <label>
                                                                  <input type="radio" value='FULL TIME' id='fullTIme' name="jamKerja"> FULL TIME
                                                              </label>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                                <label class="control-label">Pengalaman Minimum</label>
                                                                <input type="number" id='pengalamanKerjaMinimum' name='pengalamanKerjaMinimum' class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <div class="form-group label-floating" >
                                                                <center><h3> - </h3></center>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                                <label class="control-label">Pengalaman Maximux</label>
                                                                <input type="number" id='pengalamanKerjaMaximum' name='pengalamanKerjaMaximum' class="form-control">
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group label-floating" id='divForSpesifikasi'>
                                                                <label class="control-label">Spesifikasi</label>
                                                                <textarea  id='spesifikasiLowongan' name='spesifikasiLowongan' class="form-control auto-resize"></textarea>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-12">
                                                          <label class="control-label">Deskripsi Pekerjaan</label>
                                                          <div class="card">
                                                              <div class="card-body no-padding">
                                                                  <div id="summernote">
                                                                    <h3> Deskripsi </h3>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-lg-12">
                                                          <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveLowongan();" data-dismiss="modal">Simpan</button>
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
            }elseif(isset($_GET['edit']) && !isset($_GET['action'])){
                $getData = sqlArray(sqlQuery("select * from lowongan_kerja where id = '".$_GET['edit']."'"));
                $arrayPendidikan = explode(";",$getData['pendidikan']);
                $explodeSalary = explode("-",$getData['salary']);
                $salaryMinimum = $explodeSalary[0];
                $salaryMaximum = $explodeSalary[1];
                $explodeUsia = explode("-",$getData['usia']);
                $usiaMinimum = $explodeUsia[0];
                $usiaMaximum = $explodeUsia[1];
                $explodePengalaman = explode("-",$getData['pengalaman']);
                $pengalamanMinimum = $explodePengalaman[0];
                $pengalamanMaximum = $explodePengalaman[1];
                if($getData['jam_kerja'] == 'FULL TIME'){
                    $fullTime = "checked";
                }else{
                    $partTime = "checked";
                }
                if(empty($getData['batas_tanggal'])){
                    $batasTanggalLamaran = "";
                }else{
                    $batasTanggalLamaran = generateDate($getData['batas_tanggal']);
                }
                $spesifikasiLowongan = str_replace('<li>',"",$getData['spesifikasi']);
                $spesifikasiLowongan = str_replace('</li>',"\n",$spesifikasiLowongan);

              ?>
              <div class="content">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-content">
                                          <ul class="nav nav-pills nav-pills-primary">
                                              <li>
                                                  <a href="pages.php?page=lowonganKerja">Lowongan</a>
                                              </li>
                                              <li class="active">
                                                  <a href="#lowonganBaru" id='data2' data-toggle="tab" aria-expanded="true" >Edit</a>
                                              </li>
                                          </ul>
                                          <div class="tab-content">
                                              <div class="tab-pane active" id="lowonganBaru">
                                                <form id='formLowongan'>
                                                <div class="row">
                                                  <div class="col-lg-12 col-md-12 col-sm-12" >
                                                      <label class="control-label">Judul Lowongan</label>
                                                      <input type="text" id='judulLowongan' name='judulLowongan' class="form-control" value="<?php echo $getData['judul'] ?>">
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-3 col-md-6 col-sm-3" >
                                                      <label class="control-label">Posisi</label>
                                                      <?php
                                                        echo cmbQuery("posisiLowongan",$getData['posisi'],"select id,posisi from ref_posisi","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'","-- POSISI --")
                                                      ?>
                                                  </div>
                                                  <div class="col-lg-3 col-md-6 col-sm-3" >
                                                      <label class="control-label">Batas Tanggal Lamaran</label>
                                                      <input type="text" id='batasTanggalLamaran' name='batasTanggalLamaran' value="<?php echo $batasTanggalLamaran ?>" class="form-control">
                                                  </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-6 col-sm-3" id='divPendidikanLowongan'>
                                                        <label class="control-label">Pendidikan</label>
                                                        <select class="selectpicker" name='pendidikanLowongan[]' id='pendidikanLowongan' data-style="select-with-transition" multiple title="Pilih Pendidikan" data-size="7">
                                                            <option disabled> Pilih Pendidikan</option>
                                                            <?php
                                                                $getPendidikan = sqlQuery("select * from ref_pendidikan");
                                                                while ($dataPendidikan = sqlArray($getPendidikan)) {
                                                                    if(in_array($dataPendidikan['id'],$arrayPendidikan)){
                                                                      echo "<option value='".$dataPendidikan['id']."' selected> ".$dataPendidikan['tingkat']."</option>";
                                                                    }else{
                                                                      echo "<option value='".$dataPendidikan['id']."'> ".$dataPendidikan['tingkat']."</option>";
                                                                    }

                                                                }
                                                             ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-lg-3 col-md-6 col-sm-3" >
                                                      <label class="control-label">Jenis Kelamin</label>
                                                      <?php
                                                        $arrayJenisKelamin = array(
                                                                                  array('1','LAKI-LAKI'),
                                                                                  array('2','PEREMPUAN'),
                                                                                  array('3','LAKI-LAKI DAN PEREMPUAN'),
                                                                                );
                                                        echo cmbArray("jenisKelamin",$getData['gender'],$arrayJenisKelamin,"-- JENIS KELAMIN --","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                                      ?>
                                                  </div>
                                                </div>
                                                  <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                            <label class="control-label">Salary Minimum</label>
                                                            <input type="text" id='salaryMinimum' name='salaryMinimum' value='<?php echo $salaryMinimum ?>' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group label-floating" >
                                                            <center><h3> - </h3></center>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                            <label class="control-label">Salary Maximum</label>
                                                            <input type="text" id='salaryMaximum' name='salaryMaximum' value='<?php echo $salaryMaximum ?>' class="form-control">
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                            <label class="control-label">Usia Minimum</label>
                                                            <input type="number" id='usiaMinimum' name='usiaMinimum' value="<?php echo $usiaMinimum ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group label-floating" >
                                                            <center><h3> - </h3></center>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                            <label class="control-label">Usia Maximux</label>
                                                            <input type="number" id='usiaMaximum' name='usiaMaximum' value="<?php echo $usiaMaximum ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-2">
                                                      <div class="radio">
                                                          <label>
                                                              <input type="radio" value='PART TIME' id='partTime' name="jamKerja" <?php echo $partTime ?> > PART TIME
                                                          </label>
                                                      </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                      <div class="radio">
                                                          <label>
                                                              <input type="radio" value='FULL TIME' id='fullTIme' name="jamKerja" <?php echo $fullTime ?>> FULL TIME
                                                          </label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="form-group label-floating" id='divForSalaryMinimum'>
                                                            <label class="control-label">Pengalaman Minimum</label>
                                                            <input type="number" id='pengalamanKerjaMinimum' name='pengalamanKerjaMinimum' value="<?php echo $pengalamanMinimum ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="form-group label-floating" >
                                                            <center><h3> - </h3></center>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group label-floating" id='divForSalaryMaximum'>
                                                            <label class="control-label">Pengalaman Maximux</label>
                                                            <input type="number" id='pengalamanKerjaMaximum' name='pengalamanKerjaMaximum' value="<?php echo $pengalamanMaximum ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group label-floating" id='divForSpesifikasi'>
                                                            <label class="control-label">Spesifikasi</label>
                                                            <textarea  id='spesifikasiLowongan' name='spesifikasiLowongan' class="form-control auto-resize"><?php echo $spesifikasiLowongan ?></textarea>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <label class="control-label">Deskripsi Pekerjaan</label>
                                                      <div class="card">
                                                          <div class="card-body no-padding">
                                                              <div id="summernote">
                                                              <?php echo $getData['deskripsi'] ?>
                                                              </div>
                                                          </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveEditLowongan(<?php echo $getData['id'] ?>);" data-dismiss="modal">Simpan</button>
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
              ?>
              <div class="content">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-content">
                                          <ul class="nav nav-pills nav-pills-primary">
                                              <li >
                                                  <a href="pages.php?page=lowonganKerja" id='data1' data-toggle="tab" aria-expanded="true" >Lowongan</a>
                                              </li>
                                              <li class="active">
                                                  <a  id='data2' data-toggle="tab" aria-expanded="false" >Lamaran</a>
                                              </li>
                                          </ul>
                                          <div class="tab-content">
                                              <div class="tab-pane active" id="dataLowongan">
                                                  <div class="col-md-12" id='tableLowongan'>
                                                    <div class="card">
                                                        <div class="card-header card-header-icon" data-background-color="purple">
                                                            <i class="material-icons">assignment</i>
                                                        </div>
                                                        <div class="card-content">
                                                            <h4 class="card-title">Data Lamaran</h4>
                                                            <div class="toolbar">
                                                                <!--        Here you can write extra buttons/actions for the toolbar              -->
                                                            </div>
                                                            <div class="material-datatables">
                                                                <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama</th>
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
                                                        <!-- end content-->
                                                    </div>
                                                    <!--  end card  -->
                                                </div>
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
                            <!-- <div id="LoadingImage"> -->
                              <img src="img/unnamed.gif" style="width: 30%; height: 30%; display: block; margin: auto;">
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
        </div>
<?php

     break;
     }

}

?>
