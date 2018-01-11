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

    case 'saveSlider':{
      if(empty($namaSlider)){
          $err = "Isi nama slider";
      }elseif(empty($statusKosong)){
          $err = "Pilih gambar";
      }
      $baseSlider = str_replace(' ', '+', $baseSlider);
      if(empty($err)){
           baseToImage($baseSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg");
          $data = array(
                  'nama' => $namaSlider,
                  'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg",
                  'status' =>  $statusPublish,
          );
          $query = sqlInsert("slider",$data);
          sqlQuery($query);
          $cek = $query;
      }
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditSlider':{
      if(empty($namaSlider)){
          $err = "Isi nama slider";
      }
      $baseSlider = str_replace(' ', '+', $baseSlider);
      if(empty($err)){
           baseToImage($baseSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg");
          $data = array(
                  'nama' => $namaSlider,
                  'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg",
                  'status' =>  $statusPublish,
          );
          $query = sqlUpdate("slider",$data,"id = '$idEdit'");
          sqlQuery($query);
          $cek = $query;
      }

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($slider_cb) ; $i++) {
        $query = "delete from slider where id = '".$slider_cb[$i]."'";
        sqlQuery($query);
      }
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $slider_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from slider");
      $nomor = 1;
      $nomorCB = 0;
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
        if($status == '1'){
          $statusPublish = "YA";
        }else{
          $statusPublish = "TIDAK";
        }
        $data .= "     <tr>
                          <td class='text-center'>$nomor</td>
                          <td class='text-center'><span class='checkbox'><label>".setCekBox($nomorCB,$id,'','slider')."&nbsp</label></span></td>
                          <td><img src='$gambar'  class='materialboxed' style='width:100px;height:100px;'></img></td>
                          <td>$nama</td>
                          <td>$statusPublish</td>
                      </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }
      $tabel = "<table id='datatables' class='cell-border table-striped ' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th width='2%'>No</th>
                  <th width='2%' class='text-center'><span class='checkbox'><label><input type='checkbox' name='slider_toogle' id='slider_toogle' onclick=checkSemua(100,'slider_cb','slider_toogle','slider_jmlcek')>&nbsp</label></span></th>
                  <th width='10%'>Gambar</th>
                  <th width='92%'>Nama</th>
                  <th width='2%'>Publish</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>
      <input type='hidden' name='slider_jmlcek' id='slider_jmlcek' value='0'>
      ";
      $content = array("tabelUser" => $tabel);
      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=slider";
        </script>
        <script src="js/slider.js"></script>
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                        <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                        <i class="material-icons visible-on-sidebar-mini">view_list</i>
                    </button>
                </div>
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Slider</a>
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
                                  <div class="col-md-12" id='tableUser'>
                                      <div style="float:right">
                                        <button class="btn btn-primary" onclick="Baru();">Baru</button> &nbsp
                                        <button class="btn btn-warning" onclick="Edit();">Edit</button> &nbsp
                                        <button class="btn btn-danger" onclick="Hapus();">Hapus</button> &nbsp
                                      </div>
                                      <div class="material-datatables">
                                        <form id='formSlider' name="formSlider" action="#">
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
                          <form id='formSlider'>
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
                                    <label class="control-label">Nama</label>
                                    <input type="text" id='namaSlider' name='namaSlider' class="form-control">
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="component">
                                <div class="overlay">
                                  <div class="overlay-inner">
                                  </div>
                                </div>
                                <img class="resize-image" id='gambarSlider'    alt="image for resizing">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-4 col-sm-4">
                              <span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-exists">Pilih Gambar</span>
                                <input type="hidden" id='statusKosong' name='statusKosong'>
                                <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                              </span>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <br><br>
                            </div>
                          </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="button" class="btn btn-primary"  onclick="saveSlider();" data-dismiss="modal">Simpan</button>
                                <button type="button" class="btn btn-danger"  onclick="Batal();" data-dismiss="modal">Batal</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from slider where id = '".$_GET['idEdit']."'"));
                  ?>
                  <script> resizeableImage($('#gambarSlider'));</script>
                  <div class="content" style="margin-top:20px;">
                    <div class="container-fluid">
                      <div class="card">
                        <div class="card-content">
                            <form id='formSlider'>
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
                                      <label class="control-label">Nama</label>
                                      <input type="text" id='namaSlider' name='namaSlider' class="form-control" value="<?php echo $getData['nama'] ?>">
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="component">
                                  <div class="overlay">
                                    <div class="overlay-inner">
                                    </div>
                                  </div>
                                  <img class="resize-image" id='gambarSlider' src="<?php echo $getData['gambar'] ?>" alt="image for resizing">
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-4 col-sm-4">
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-exists">Ganti</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong' value="1">
                                  <input type="hidden" id='statusEdit' name='statusEdit'>
                                  <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                </span>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <br><br>
                              </div>
                            </div>
                              <div class="row">
                                <div class="col-lg-12">
                                  <button type="button" class="btn btn-primary"  onclick="saveEditSlider(<?php echo $_GET['idEdit'] ?>);" data-dismiss="modal">Simpan</button>
                                  <button type="button" class="btn btn-danger"  onclick="Batal();" data-dismiss="modal">Batal</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
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
