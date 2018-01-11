<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
session_start();

// function setCekBox($cb, $KeyValueStr, $isi){
//   $hsl = '';
//   $Prefix = "produk";
//   /*if($KeyValueStr!=''){*/
//     $hsl = "<input type='checkbox' $isi id='".$Prefix."cb$cb' name='".$Prefix."cb[]'
//         value='".$KeyValueStr."' onchange = thisChecked('".$Prefix."cb$cb','produkJmlcek'); >";
//   /*}*/
//   return $hsl;
// }

function unlinkDir($dir)
{
    $dirs = array($dir);
    $files = array() ;
    for($i=0;;$i++)
    {
        if(isset($dirs[$i]))
            $dir =  $dirs[$i];
        else
            break ;

        if($openDir = opendir($dir))
        {
            while($readDir = @readdir($openDir))
            {
                if($readDir != "." && $readDir != "..")
                {

                    if(is_dir($dir."/".$readDir))
                    {
                        $dirs[] = $dir."/".$readDir ;
                    }
                    else
                    {

                        $files[] = $dir."/".$readDir ;
                    }
                }
            }

        }

    }



    foreach($files as $file)
    {
        unlink($file) ;

    }
    $dirs = array_reverse($dirs) ;
    foreach($dirs as $dir)
    {
        rmdir($dir) ;
    }

}
function createDescFile($fileName,$descSreenShot) {
  $fileDesc = fopen( "temp/".$_SESSION['username']."/$fileName".".desc", 'wb' );
  fwrite( $fileDesc, $descSreenShot );
  fclose( $fileDesc );
}
if(!empty($tipe)){
  // include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}


switch($tipe){
    case 'showGambarProduk':{
      $getNamaImage = sqlArray(sqlQuery("SELECT * from produk where id = '$idproduk' "));
      $decodedJSON = json_decode($getNamaImage[screen_shot]);
      for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
          $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
          $jsonScreenshot[] = array(
                    'name' => $explodeNamaGambar[3],
                    'type' => 'image/jpeg',
                    'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
          );
          if ($number == "") {
            $listImage .="
                <div class='item active'>
                  <img src='".$decodedJSON[$i]->fileName."' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                  <h5>".$decodedJSON[$i]->desc."</h5>
                </div>

          ";
          }else{
            $listImage .="
                <div class='item'>
                  <img src='".$decodedJSON[$i]->fileName."' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                  <h5>".$decodedJSON[$i]->desc."</h5>
                </div>

          ";
          }
        $number = "1";

      }
      $imagesProduks = "
        <!-- Carousel Card -->
        <div class='card card-raised card-carousel'>
          <div id='carousel-example-generic' class='carousel slide' data-ride='carousel'>
            <div class='carousel slide' data-ride='carousel'>

              <!-- Indicators -->

              <!-- <ol class='carousel-indicators'>
                <li data-target='#carousel-example-generic' data-slide-to='0' class='active'></li>
                <li data-target='#carousel-example-generic' data-slide-to='1'></li>
                <li data-target='#carousel-example-generic' data-slide-to='2'></li>
              </ol> -->

              <!-- Wrapper for slides -->
              <div class='carousel-inner'>

                ".$listImage."
                <!-- <div class='item'>
                  <img src='images/produk/ATISISBADA/b3c18adb84f2548b04467090a673c529.jpg' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                </div>
                <div class='item'>
                  <img src='images/produk/ATISISBADA/e8c6d95650a17cd8530834a8ce5ab45a.jpg' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                </div> -->

              </div>

              <!-- Controls -->
              <a class='left carousel-control' href='#carousel-example-generic' data-slide='prev' style='background: linear-gradient(to right, #0a0a0a45 , #0a0a0a00);'>
                <i class='material-icons'><!-- keyboard_arrow_left --></i>
              </a>
              <a class='right carousel-control' href='#carousel-example-generic' data-slide='next' style='background: linear-gradient(to right, #08080800 , #0a0a0a45);'>
                <i class='material-icons'><!-- keyboard_arrow_right --></i>
              </a>
            </div>
          </div>
        </div>
        <!-- End Carousel Card -->
      ";
      $content = array("imagesProduks" => $imagesProduks);

      echo generateAPI($cek,$err,$content);
      break;
}

    case 'saveProduk':{
      if(empty($namaProduk)){
          $err = "Isi nama produk";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($statusKosong)){
          $err = "Pilih gambar Produk";
      }
      if(empty($err)){

        $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
        $imageTitle = baseToImage($baseGambarProduk,"images/produk/$namaProduk/title.jpg");
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
          $err = "Isi nama produk";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }
      if(empty($err)){
        $getOldProduk = sqlArray(sqlQuery("select * from produk where id = '$idEdit'"));
        // $files = glob("images/produk/".$getOldProduk['nama_produk']."/*"); // get all file names
        //   foreach($files as $file){ // iterate files
        //     if(is_file($file))
        //       unlink($file); // delete file
        //   }
        unlinkDir("images/produk/".$getOldProduk['nama_produk']);
        $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
        $imageTitle = baseToImage($baseGambarProduk,"images/produk/$namaProduk/title.jpg");
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
      $getData = sqlArray(sqlQuery("select * from produk where id = '$id'"));
      unlinkDir("images/produk/".$getData['nama_produk']);
      $query = "delete from produk where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'removeTemp':{
      unlink('temp/'.$_SESSION['username']."/".$id);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateProduk':{
      clearDirectory("temp/".$_SESSION['username']);
      $getData = sqlArray(sqlQuery("select * from produk where id = '$id'"));
      $decodedJSON = json_decode($getData['screen_shot']);
      for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
          $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
          copy($decodedJSON[$i]->fileName,"temp/".$_SESSION['username']."/".$explodeNamaGambar[3]);
          createDescFile($explodeNamaGambar[3],$decodedJSON[$i]->desc);
          $jsonScreenshot[] = array(
                    'name' => $explodeNamaGambar[3],
                    'type' => 'image/jpeg',
                    'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
          );;
      }



      $type = pathinfo($getData['image_title'], PATHINFO_EXTENSION);
      $data = file_get_contents($getData['image_title']);
      //$baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
      $content = array("namaProduk" => $getData['nama_produk']
                      ,"statusPublish" => $getData['status']
                      , "deskripsi" => $getData['deskripsi']
                      , "baseOfFile" => $baseOfFile
                      ,"screenShot" => json_encode($jsonScreenshot));
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deskripsiScreenShot':{
      $descScreenShot = file_get_contents("temp/".$_SESSION['username']."/$namaFile".".desc");
      if($descScreenShot){

      }else{
          $descScreenShot = "";
      }
      $content = array(
                        'srcImage' => "temp/".$_SESSION['username']."/$namaFile",
                        'descScreenShot' =>$descScreenShot
                      );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveDescSreenshot':{
      $fileDesc = fopen( "temp/".$_SESSION['username']."/$namaFile".".desc", 'wb' );
      fwrite( $fileDesc, $descSreenShot );
      fclose( $fileDesc );

      $content = array(
                        'srcImage' => "temp/".$_SESSION['username']."/$namaFile",
                        'descScreenShot' => file_get_contents($namaFile.".desc")
                      );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($produk_cb) ; $i++) {
        $query = "delete from produk where id = '".$produk_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $produk_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from produk");
      $nomor = 1;
      $nomorCB = 0;
      while($dataProduk = sqlArray($getData)){
        foreach ($dataProduk as $key => $value) {
            $$key = $value;
        }

        if($status == "1"){
            $status = "YA";
        }else{
            $status = "TIDAK";
        }
        $data .= "     <tr>
                          <td class='text-center'>$nomor</td>
                          <td class='text-center'><span class='checkbox'><label>".setCekBox($nomorCB,$id,'','produk')."&nbsp</label></span></td>
                          <td>$nama_produk</td>
                          <td><img src='$image_title'  class='materialboxed' style='width:100px;height:100px;'></img> </td>
                          <td>$status</td>
                          <td>
                            <!-- <input type='button' class='waves-effect waves-light btn btn-primary' value='Show'> -->
                            <input type='button' style='border-radius: unset;' class='btn btn-raised btn-round btn-primary' data-toggle='modal' data-target='#noticeModal' onclick=showGambarProduk($id); value='SHOW'>

                            <!-- modal image -->
                            <div class='modal fade' id='noticeModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                              <div class='modal-dialog modal-notice'>
                                  <div class='modal-content'>
                                      <div class='modal-header'>
                                          <button type='button' class='close' data-dismiss='modal' aria-hidden='true'> <!-- <i class='material-icons'>clear</i> --></button>
                                          <!-- <h5 class='modal-title' id='myModalLabel'>
                                            How Do You Become an Affiliate?
                                          </h5> -->
                                      </div>
                                      <div class='modal-body'>
                                          <div class='instruction'>
                                              <div class='row'>
                                                  <div class='col-md-12'>

                                                    <div id='tempatGambar'></div>

                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- end modal image -->

                          </td>
                      </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th style='border: 1px solid silver; height: 54px!important; background-color: #1b5a8d; color: white;'>No</th>
                  <th class='text-center' style='border: 1px solid silver; background-color: #1b5a8d; height: 54px!important; color: white;'><span class='checkbox'><label><input type='checkbox' name='produk_toogle' id='produk_toogle' onclick=checkSemua(100,'produk_cb','produk_toogle','produk_jmlcek')>&nbsp</label></span></th>
                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white; height: 54px!important;'>Produk</th>
                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white; height: 54px!important;'>Gambar</th>
                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white; height: 54px!important;'>Publish</th>
                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white; height: 54px!important;'>Screen Shot</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>
      <input type='hidden' name='produk_jmlcek' id='produk_jmlcek' value='0'>";
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


        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                        <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                        <i class="material-icons visible-on-sidebar-mini">view_list</i>
                    </button>
                </div>
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Produk</a>
                </div>
            </div>
        </nav>
        <?php
          if(!isset($_GET['action'])){
            ?>
              <div class="content" style="margin-top: 20px;">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                            <div class="card">
                                <div class="card-content">
                                  <div class="col-md-12" id='tabelProduk'>
                                    <div style="float:right">
                                      <button class="btn btn-primary" onclick="Baru();">Baru</button> &nbsp
                                      <button class="btn btn-warning" onclick="Edit();">Edit</button> &nbsp
                                      <button class="btn btn-danger" onclick="Hapus();">Hapus</button> &nbsp
                                    </div>
                                    <div class="material-datatables">
                                    <form id='formProduk' name="formProduk" action="#">
                                      <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%; border: 1px solid silver;">
                                          <thead>
                                              <tr>
                                                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white;'>Judul</th>
                                                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white;'>Posisi</th>
                                                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white;'>Tanggal</th>
                                                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white;'>Penulis</th>
                                                  <th style='border: 1px solid silver; background-color: #1b5a8d; color: white;'>Status</th>
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

              <?php
          }else{
              if($_GET['action'] == 'baru'){
                clearDirectory("temp/".$_SESSION['username']);
                ?>
                <div class="content" style="margin-top:20px;">
                  <div class="container-fluid">
                    <div class="card">
                      <div class="card-content">
                          <form id='formProduk'>
                            <div class="row">
                              <div class="col-lg-3 col-md-6 col-sm-3">
                                <label class="control-label">Publish</label>
                                  <?php
                                    $arrayStatus = array(
                                              array('1','YA'),
                                              array('2','TIDAK'),
                                    );
                                    echo cmbArray("statusPublish","1",$arrayStatus,"STATUS","class='form-control' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                  ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="component">
                                  <div class="overlay">
                                    <div class="overlay-inner">
                                    </div>
                                  </div>
                                  <img class="resize-image" id='gambarProduk' alt="image for resizing">
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
                                <form method="#" action="#">
                                  <div class="form-group label-floating">
                                      <label class="control-label">Nama Produk</label>
                                      <input type="text" id="namaProduk" name="namaProduk" class="form-control">
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                Screen Shot
                                  <form action="upload.php" id='dropzone'  >
                                  </form>
                                  <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                              </div>
                            </div>
                            <div class="row">
                              <div class="card">
                                  <div class="card-body no-padding">
                                      <div id="summernote">
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="button" class="btn btn-primary"  onclick="saveProduk();" data-dismiss="modal">Simpan</button>
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

                  clearDirectory("temp/".$_SESSION['username']);
                  $getData = sqlArray(sqlQuery("select * from produk where id = '".$_GET['idEdit']."' "));
                  $decodedJSON = json_decode($getData['screen_shot']);
                  for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
                      $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
                      copy($decodedJSON[$i]->fileName,"temp/".$_SESSION['username']."/".$explodeNamaGambar[3]);
                      createDescFile($explodeNamaGambar[3],$decodedJSON[$i]->desc);
                      $jsonScreenshot[] = array(
                                'name' => $explodeNamaGambar[3],
                                'type' => 'image/jpeg',
                                'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
                      );;
                  }



                  $type = pathinfo($getData['image_title'], PATHINFO_EXTENSION);
                  $data = file_get_contents($getData['image_title']);
                  ?>
                  <div class="content" style="margin-top:20px;">
                  <div class="container-fluid">
                    <div class="card">
                      <div class="card-content">
                          <form id='formProduk'>
                            <div class="row">
                              <div class="col-lg-3 col-md-6 col-sm-3">
                                <label class="control-label">Publish</label>
                                  <?php
                                    $arrayStatus = array(
                                              array('1','YA'),
                                              array('2','TIDAK'),
                                    );
                                    echo cmbArray("statusPublish",$getData['status'],$arrayStatus,"-- STATUS --","class='form-control' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                  ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="component">
                                  <div class="overlay">
                                    <div class="overlay-inner">
                                    </div>
                                  </div>
                                  <img class="resize-image" id='gambarProduk' src ='<?php echo $getData['image_title'] ?>' alt="image for resizing">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4 col-sm-4">
                                <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-exists">Pilih Gambar</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong' value="1">
                                  <input type="hidden" id='statusEdit' name='statusEdit' >
                                  <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                </span>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form method="#" action="#">
                                  <div class="form-group label-floating">
                                      <label class="control-label">Nama Produk</label>
                                      <input type="text" id="namaProduk" name="namaProduk" class="form-control" value="<?php echo $getData['nama_produk'] ?>">
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                Screen Shot
                                  <form action="upload.php" id='dropzone'  >
                                  </form>
                                  <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                              </div>
                            </div>
                            <div class="row">
                              <div class="card">
                                  <div class="card-body no-padding">
                                      <div id="summernote">
                                        <?php echo $getData['deskripsi'] ?>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="button" class="btn btn-primary"  onclick="saveEditProduk(<?php echo $_GET['idEdit'] ?>);" data-dismiss="modal">Simpan</button>
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

        <div class="modal fade" id="formDeskripsiScreenShot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Screen Shot</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                  <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                              <div class="fileinput-new thumbnail">
                                                  <img  src="assets/img/image_placeholder.jpg" id='tempScreenShot' alt="...">
                                              </div>
                                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                          </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating" id='divForDesc'>
                                    <label class="control-label">Deskripsi Srenshot</label>
                                    <textarea id='descSreenShot' class="form-control" style="height:100px;"></textarea>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id='buttonSubmitScreenShot' data-dismiss="modal">Simpan</button>
                        <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Close</button>
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
