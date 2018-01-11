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
        $data = array(
                'judul' => $judulInformasi,
                'isi_informasi' => $isiInformasi,
                'posisi' => $posisiInformasi,
                'status' => $statusPublish,
                'tanggal_create' =>  date("Y-m-d"),
                'jam_create' =>  date("H:i"),
                'tanggal_update' =>  date("Y-m-d"),
                'jam_update' =>  date("H:i"),
                'penulis' => "VulnWalker"
        );
        $query = sqlInsert("informasi",$data);
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("judulInformasi" => $judulInformasi);

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
        $data = array(
                'judul' => $judulInformasi,
                'isi_informasi' => $isiInformasi,
                'posisi' => $posisiInformasi,
                'status' => $statusPublish,
                'tanggal_create' =>  date("Y-m-d"),
                'jam_create' =>  date("H:i"),
                'tanggal_update' =>  date("Y-m-d"),
                'jam_update' =>  date("H:i"),
                'penulis' => "VulnWalker"
        );
        $query = sqlUpdate("informasi",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("judulInformasi" => $judulInformasi);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteInformasi':{
      $query = "delete from informasi where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateInformasi':{
      $getData = sqlArray(sqlQuery("select * from informasi where id = '$id'"));
      $content = array("judulInformasi" => $getData['judul'],"statusPublish" => $getData['status'], "isiInformasi" => $getData['isi_informasi'], "posisi" => $getData['posisi']);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from informasi");
      while($dataInformasi = sqlArray($getData)){
        foreach ($dataInformasi as $key => $value) {
            $$key = $value;
        }
        if($posisi == "1"){
            $posisi = "Kiri";
        }else{
            $posisi = "Kanan";
        }
        if($status == "1"){
            $status = "PUBLISH";
        }else{
            $status = "NON PUBLISH";
        }
        $data .= "     <tr>
                          <td>$judul</td>
                          <td>$posisi</td>
                          <td>".generateDate($tanggal_update)." $jam_update</td>
                          <td>$penulis</td>
                          <td>$status</td>
                          <td class='text-right'>
                              <a onclick=updateInformasi($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteInformasi($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Judul</th>
                  <th>Posisi</th>
                  <th>Tanggal</th>
                  <th>Penulis</th>
                  <th>Status</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
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

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Start Modal -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <button class="btn btn-primary btn-raised btn-round" data-toggle="modal" onclick="baruInformasi();">
                                            Informasi Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->



                    <div class="col-md-12" id='tableInformasi'>
                        <div class="card">
                            <div class="card-header card-header-icon" data-background-color="purple">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Data informasi</h4>
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

        <div class="modal fade" id="formInformasiBaru" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Informasi</h4>
                    </div>
                    <div class="modal-body">
                        <form id='formInformasi'>
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

                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">Judul Informasi</label>
                                    <input type="text" id='judulInformasi' class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Input -->



                        <!-- Start Checkbox and Radio Buttons -->
                        <div class="row">
                          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="radio">
                                    <label class="control-label">Posisi Informasi</label>
                                </div>
                          </div>
                          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                              <div class="radio">
                                  <label>
                                      <input type="radio" value='1' id='kiri' name="posisiInformasi" checked> Kiri
                                  </label>
                              </div>
                          </div>
                          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                              <div class="radio">
                                  <label>
                                      <input type="radio" value='2' id='kanan' name="posisiInformasi"> Kanan
                                  </label>
                              </div>
                          </div>
                        </div>
                        <!-- End Checkbox and Radio Buttons -->

                        <!-- BEGIN SUMMERNOTE -->
                        <div class="card">
                            <div class="card-body no-padding">
                                <div id="summernote">
                                </div>
                            </div><!--end .card-body -->
                        </div><!--end .card -->
                        <!-- END SUMMERNOTE -->

                    </div>
                  </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-simple" id='buttonSubmit' onclick="saveInformasi();" data-dismiss="modal">Simpan</button>
                        <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php

     break;
     }

}

?>
