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

  case 'saveInformasi':{
    if(empty($judulInformasi)){
        $err = "Isi Judul";
    }elseif(empty($statusPublish)){
        $err = "Pilih status publish";
    }

    if(empty($err)){
      $getDataUser = sqlArray(sqlQuery("select * from users where username ='".$_SESSION['username']."'"));
      $data = array(
              'judul' => $judulInformasi,
              'isi_informasi' => $isiInformasi,
              'status' => $statusPublish,
              'tanggal_create' =>  date("Y-m-d"),
              'jam_create' =>  date("H:i"),
              'tanggal_update' =>  date("Y-m-d"),
              'jam_update' =>  date("H:i"),
              'penulis' => $getDataUser['id']
      );
      $query = sqlInsert("informasi",$data);
      sqlQuery($query);
      $cek = $query;
    }

    echo generateAPI($cek,$err,$content);
  break;
  }

    case 'saveEditInformasi':{
      if(empty($judulInformasi)){
          $err = "Isi Judul";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }

      if(empty($err)){
        $getDataUser = sqlArray(sqlQuery("select * from users where username ='".$_SESSION['username']."'"));
        $data = array(
                'judul' => $judulInformasi,
                'isi_informasi' => $isiInformasi,
                'status' => $statusPublish,
                'tanggal_update' =>  date("Y-m-d"),
                'jam_update' =>  date("H:i"),
                'penulis' => $getDataUser['id']
        );
        $query = sqlUpdate("informasi",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($informasi_cb) ; $i++) {
        $query = "delete from informasi where id = '".$informasi_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $informasi_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from informasi order by tanggal_create desc,tanggal_create desc");
      $nomor = 1;
      $nomorCB = 0;
      while($dataInformasi = sqlArray($getData)){
        foreach ($dataInformasi as $key => $value) {
            $$key = $value;
        }
        if($status == "1"){
            $status = "YA";
        }else{
            $status = "TIDAK";
        }
        $getNamaPenulis = sqlArray(sqlQuery("select * from users where id = '$penulis'"));
        $data .= "     <tr>
                          <td class='text-center'>$nomor</td>
                          <td class='text-center'><span class='checkbox'><label>".setCekBox($nomorCB,$id,'','informasi')."&nbsp</label></span></td>
                          <td>".limitChar($judul,75)."</td>
                          <td>".generateDate($tanggal_create)." $jam_create</td>
                          <td>".$getNamaPenulis['nama']."</td>
                          <td>$status</td>
                      </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }
      $tabel = "<table id='datatables' class='cell-border table-striped ' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>No</th>
                  <th class='text-center'><span class='checkbox'><label><input type='checkbox' name='informasi_toogle' id='informasi_toogle' onclick=checkSemua(100,'informasi_cb','informasi_toogle','informasi_jmlcek')>&nbsp</label></span></th>
                  <th>Judul</th>
                  <th>Tanggal</th>
                  <th>Penulis</th>
                  <th>Publish</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>
      <input type='hidden' name='informasi_jmlcek' id='informasi_jmlcek' value='0'>
      ";
      $content = array("tabelInformasi" => $tabel);
      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=informasi";

        </script>
        <script src="js/informasi.js"></script>
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                        <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                        <i class="material-icons visible-on-sidebar-mini">view_list</i>
                    </button>
                </div>
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Informasi</a>
                </div>
            </div>
        </nav>
        <?php
          if(!isset($_GET['action'])){
            ?>

            <div class="content" style="margin-top:20px;">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="card">
                              <div class="card-content">
                                  <div class="col-md-12" id='tableInformasi'>
                                      <div style="float:right">
                                        <button class="btn btn-primary" onclick="Baru();">Baru</button> &nbsp
                                        <button class="btn btn-warning" onclick="Edit();">Edit</button> &nbsp
                                        <button class="btn btn-danger" onclick="Hapus();">Hapus</button> &nbsp
                                      </div>
                                      <div class="material-datatables">
                                        <form id='forminformasi' name="forminformasi" action="#">
                                          <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                              <thead>
                                                  <tr>
                                                      <th>Judul</th>
                                                      <th>Posisi</th>

                                                      <th class="disabled-sorting text-right">Actions</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                          </table>
                                        </form>
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
          }else{
              if($_GET['action'] == 'baru'){
                ?>
                <div class="content" style="margin-top:20px;">
                  <div class="container-fluid">
                    <div class="card">
                      <div class="card-content">
                          <form id='formInformasi'>
                            <div class="row">
                              <div class="col-lg-3 col-md-6 col-sm-3">
                                  <div class="form-group label-floating" >
                                    <label class="control-label">Publish</label>
                                    <?php
                                      $arrayStatus = array(
                                                array('1','YA'),
                                                array('2','TIDAK'),
                                      );
                                      echo cmbArrayEmpty("statusPublish","",$arrayStatus,"-- PUBLISH --","class='form-control' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                    ?>
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                  <div class="form-group label-floating">
                                      <label class="control-label">Judul Informasi</label>
                                      <input type="text" id='judulInformasi' class="form-control">
                                  </div>
                              </div>
                            </div>
                            <div class="card">
                                <div class="card-body no-padding">
                                    <textarea id="isiInformasi">
                                    </textarea>
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="button" class="btn btn-primary"  onclick="saveInformasi();" data-dismiss="modal">Simpan</button>
                                <button type="button" class="btn btn-danger"  onclick="Batal();" data-dismiss="modal">Batal</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <script>
                    $('#isiInformasi').froalaEditor();
                  </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from informasi where id = '".$_GET['idEdit']."'"));
                  ?>
                  <div class="content" style="margin-top:20px;">
                    <div class="container-fluid">
                      <div class="card">
                        <div class="card-content">
                            <form id='formInformasi'>
                              <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-3">
                                    <div class="form-group label-floating" >
                                      <label class="control-label">Publish</label>
                                      <?php
                                        $arrayStatus = array(
                                                  array('1','YA'),
                                                  array('2','TIDAK'),
                                        );
                                        echo cmbArrayEmpty("statusPublish",$getData['status'],$arrayStatus,"-- PUBLISH --","class='form-control' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                      ?>
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Judul Informasi</label>
                                        <input type="text" id='judulInformasi' class="form-control" value="<?php echo $getData['judul'] ?>">
                                    </div>
                                </div>
                              </div>
                              <div class="card">
                                  <div class="card-body no-padding">
                                      <textarea id="isiInformasi">
                                        <?php echo $getData['isi_informasi'] ?>
                                      </textarea>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-lg-12">
                                  <button type="button" class="btn btn-primary"  onclick="saveEditInformasi(<?php echo $_GET['idEdit'] ?>);" data-dismiss="modal">Simpan</button>
                                  <button type="button" class="btn btn-danger"  onclick="Batal();" data-dismiss="modal">Batal</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                      $('#isiInformasi').froalaEditor();
                    </script>
                  <?php
              }
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
