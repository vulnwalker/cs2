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

    case 'saveProduk':{
      if(empty($namaProduk)){
          $err = "Isi Judul";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarProduk)){
          $err = "Pilih gambar Produk";
      }
      if(empty($err)){

        // $listImage = getImage("../temp","../images/produk/$namaProduk");
        // $imageTitle = baseToImage($gambarProduk,"../images/produk/$namaProduk/title.jpg");
        $listImage = getImage("temp","images/produk/$namaProduk");
        $imageTitle = baseToImage($gambarProduk,"images/produk/$namaProduk/title.jpg");
        $data = array(
                'nama_produk' => $namaProduk,
                'status' => $statusPublish,
                'tanggal' =>  date("Y-m-d"),
                'image_title' => "images/produk/$namaProduk/title.jpg",
                'deskripsi' => $deskripsiProduk,
                'screen_shot' => $listImage,
        );
        $query = sqlInsert("produk",$data);
        sqlQuery($query);
        $cek = $query;

      }
      $content = array("judulProduk" => $judulProduk);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditProduk':{
      if(empty($namaProduk)){
          $err = "Isi Judul";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarProduk)){
          $err = "Pilih gambar Produk";
      }
      if(empty($err)){
        $files = glob('images/produk/$namaProduk/*'); // get all file names
          foreach($files as $file){ // iterate files
            if(is_file($file))
              unlink($file); // delete file
          }
        $listImage = getImage("temp","images/produk/$namaProduk");
        $imageTitle = baseToImage($gambarProduk,"images/produk/$namaProduk/title.jpg");
        $data = array(
                'nama_produk' => $namaProduk,
                'status' => $statusPublish,
                'tanggal' =>  date("Y-m-d"),
                'image_title' => "images/produk/$namaProduk/title.jpg",
                'deskripsi' => $deskripsiProduk,
                'screen_shot' => $listImage,
        );
        $query = sqlUpdate("produk",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("judulProduk" => $judulProduk);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteProduk':{
      $query = "delete from produk where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'removeTemp':{
      unlink('temp/'.$id);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateProduk':{
      clearDirectory("temp");
      $getData = sqlArray(sqlQuery("select * from produk where id = '$id'"));
      $decodedJSON = json_decode($getData['screen_shot']);
      for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
          $explodeNamaGambar = explode('/',$decodedJSON[$i]);
          copy($decodedJSON[$i],"temp/".$explodeNamaGambar[3]);
          $jsonScreenshot[] = array(
                    'name' => $explodeNamaGambar[3],
                    'type' => 'image/jpeg',
                    'imageLocation' => "temp/".$explodeNamaGambar[3],
          );;
      }



      $type = pathinfo($getData['image_title'], PATHINFO_EXTENSION);
			$data = file_get_contents($getData['image_title']);
			$baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
      $content = array("namaProduk" => $getData['nama_produk']
                      ,"statusPublish" => $getData['status']
                      , "deskripsi" => $getData['deskripsi']
                      , "baseOfFile" => $baseOfFile
                      ,"screenShot" => json_encode($jsonScreenshot));
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from produk");
      while($dataProduk = sqlArray($getData)){
        foreach ($dataProduk as $key => $value) {
            $$key = $value;
        }

        if($status == "1"){
            $status = "PUBLISH";
        }else{
            $status = "NON PUBLISH";
        }
        $data .= "     <tr>
                          <td>$nama_produk</td>
                          <td><img src='$image_title'  class='materialboxed' style='width:100px;height:100px;'></img> </td>
                          <td>".generateDate($tanggal)."</td>
                          <td>$status</td>
                          <td><input type='button'  class='waves-effect waves-light btn' value='Show'></td>
                          <td class='text-right'>
                              <a onclick=updateProduk($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteProduk($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Produk</th>
                  <th>Gambar</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Screen Shot</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelProduk" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=produk";

        </script>
        <script src="js/dropzone/dropzone.js"></script>
        <script src="js/produk.js"></script>
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
                                        <button class="btn btn-primary btn-raised btn-round" data-toggle="modal" onclick="baruProduk();">
                                            Produk Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->



                    <div class="col-md-12" id='tableProduk'>
                        <div class="card">
                            <div class="card-header card-header-icon" data-background-color="purple">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Data produk</h4>
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

        <div class="modal fade" id="formProdukBaru" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Produk</h4>
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
                                                  <img  src="assets/img/image_placeholder.jpg" id='tempImageProduk' alt="...">
                                              </div>
                                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                              <div>
                                                  <span class="btn btn-rose btn-round btn-file">
                                                      <span class="fileinput-new">Select image</span>
                                                      <span class="fileinput-exists">Change</span>
                                                      <input type="file" onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                                  </span>
                                                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                              </div>
                                              <input type="hidden" id='gambarProduk' >
                                          </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">Nama Produk</label>
                                    <input type="text" id='namaProduk' class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Input -->



                        <!-- Start Checkbox and Radio Buttons -->
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            Screen Shot

                              <form action="upload.php" id='dropzone'  >
                                <!-- <div class="dz-default dz-message" ><span>Drop files here to upload</span>
                                </div> -->
                              </form>
                              <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                          </div>
                        </div>

                        <div class="card">
                            <div class="card-body no-padding">
                                <div id="summernote">
                                </div>
                            </div><!--end .card-body -->
                        </div><!--end .card -->
                        <!-- End Checkbox and Radio Buttons -->

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-simple" id='buttonSubmit' onclick="saveProduk();" data-dismiss="modal">Simpan</button>
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
