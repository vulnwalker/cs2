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
          $err = "Isi Nama Slider";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarSlider)){
          $err = "Pilih gambar Slider";
      }
      if(empty($err)){
        $imageTitle = baseToImage($gambarSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d")).md5(date("H:i:s")).".jpg");
        $data = array(
                'nama' => $namaSlider,
                'status' => $statusPublish,
                'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d")).md5(date("H:i:s")).".jpg",
        );
        $query = sqlInsert("slider",$data);
        sqlQuery($query);
        $cek = $query;

      }
      $content = array("judulSlider" => $judulSlider);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditSlider':{
      if(empty($namaSlider)){
          $err = "Isi Nama Slider";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarSlider)){
          $err = "Pilih gambar Slider";
      }
      if(empty($err)){
        $imageTitle = baseToImage($gambarSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d")).md5(date("H:i:s")).".jpg");
        $data = array(
                'nama' => $namaSlider,
                'status' => $statusPublish,
                'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d")).md5(date("H:i:s")).".jpg",
        );
        $query = sqlUpdate("slider",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("judulSlider" => $judulSlider);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteSlider':{
      $query = "delete from slider where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateSlider':{
      $getData = sqlArray(sqlQuery("select * from slider where id = '$id'"));
      $type = pathinfo($getData['gambar'], PATHINFO_EXTENSION);
			$data = file_get_contents($getData['gambar']);
			$baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
      $content = array("namaSlider" => $getData['nama'],"statusPublish" => $getData['status'], "gambarSlider" => $getData['gambar'], "baseImage" => $baseOfFile);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from slider");
      while($dataSlider = sqlArray($getData)){
        foreach ($dataSlider as $key => $value) {
            $$key = $value;
        }

        if($status == "1"){
            $status = "PUBLISH";
        }else{
            $status = "NON PUBLISH";
        }
        $data .= "     <tr>
                          <td>$nama</td>
                          <td><img src='$gambar'  class='materialboxed' style='width:100px;height:100px;'></img> </td>
                          <td>$status</td>
                          <td class='text-right'>
                              <a onclick=updateSlider($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteSlider($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama</th>
                  <th>Gambar</th>
                  <th>Status</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelSlider" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=slider";

        </script>
        <script src="js/dropzone/dropzone.js"></script>
        <script src="js/slider.js"></script>
        <link rel="stylesheet" href="js/dropzone/dropzone.css">


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Start Modal -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <button class="btn btn-primary btn-raised btn-round" data-toggle="modal" onclick="baruSlider();">
                                            Slider Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->



                    <div class="col-md-12" id='tableSlider'>
                        <div class="card">
                            <div class="card-header card-header-icon" data-background-color="purple">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Data slider</h4>
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
                    <!-- end col-md-12 -->
                </div>
                <!-- end row -->
            </div>
        </div>



  <!-- Popup Area -->

        <div class="modal fade" id="formSliderBaru" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Slider</h4>
                    </div>
                    <div class="modal-body">

                        <!-- Start Customisable -->
                        <div class="row">
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-lg-6 col-md-4 col-sm-3">
                                    <?php
                                        $arrayStatus = array(
                                                  array('1','PUBLISH'),
                                                  array('2','NON PUBLISH'),
                                        );
                                        echo cmbArray("statusPublish","1",$arrayStatus,"STATUS","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                     ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                        <!-- End Customisable -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                  <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                              <div class="fileinput-new thumbnail">
                                                  <img  src="assets/img/image_placeholder.jpg" id='tempImage' alt="...">
                                              </div>
                                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                              <div>
                                                  <span class="btn btn-rose btn-round btn-file">
                                                      <span class="fileinput-new">Select image</span>
                                                      <span class="fileinput-exists">Change</span>
                                                      <input type="file" onchange="imageChanged();" id='imageSlider' name="imageSlider">
                                                  </span>
                                                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                              </div>
                                              <input type="hidden" id='gambarSlider' >
                                          </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">Nama Slider</label>
                                    <input type="text" id='namaSlider' class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Input -->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-simple" id='buttonSubmit' onclick="saveSlider();" data-dismiss="modal">Simpan</button>
                        <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    clearDirectory("temp");
     break;
     }

}


?>
