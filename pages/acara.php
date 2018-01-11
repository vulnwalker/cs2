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

function getKordinat($alamat){
  $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($alamat)."&country:ID&key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU");
  curl_setopt($curl,CURLOPT_POST, sizeof($arrayData));
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
  curl_setopt($curl,CURLOPT_POSTFIELDS, $arrayData);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $result = json_decode(curl_exec($curl));
  $resultJSON = $result->results;
  $kordinatX = $resultJSON[0]->geometry->location->lat;
  $kordinatY = $resultJSON[0]->geometry->location->lng;
  return $kordinatX.",".$kordinatY;
}

switch($tipe){

    case 'saveAcara':{
      if(empty($namaAcara)){
          $err = "Isi Nama Acara";
      }elseif(empty($tanggalAcara)){
          $err = "Isi tanggal acara";
      }elseif(empty($waktuAcara)){
          $err = "Isi waktu acara";
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
                'nama_acara' => $namaAcara,
                'tanggal' => generateDate($tanggalAcara),
                'jam' => $waktuAcara,
                'lokasi' => $lokasi,
                'deskripsi' =>  $deskripsiAcara,
                'koordinat' => $kordinatLocation
        );
        $query = sqlInsert("acara",$data);
        sqlQuery($query);
        $cek = $query;
      }


      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditAcara':{
      if(empty($namaAcara)){
          $err = "Isi Nama Acara";
      }elseif(empty($tanggalAcara)){
          $err = "Isi tanggal acara";
      }elseif(empty($waktuAcara)){
          $err = "Isi waktu acara";
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
                'nama_acara' => $namaAcara,
                'tanggal' => generateDate($tanggalAcara),
                'jam' => $waktuAcara,
                'lokasi' => $lokasi,
                'deskripsi' =>  $deskripsiAcara,
                'koordinat' => $kordinatLocation
        );
        $query = sqlUpdate("acara",$data,"id='$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("location" => $kordinatLocation);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteAcara':{
      $query = "delete from acara where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'saveKonfirmasi':{
      $dataUpdate = array(
            'status' => $statusConfirmasi,
            'jumlah_orang' => $jumlahOrang,
      );
      $query = sqlUpdate("reservasi_acara",$dataUpdate,"id = '$id'");
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'confirmAcara':{
      $getData = sqlArray(sqlQuery("select * from reservasi_acara where id = '$id'"));
      $arrayStatus = array(
                        array('1','TERKONFIRMASI'),
                        array('2','TOLAK'),
                    );
      if(empty($getData['status'])){
        $status = "1";
      }else{
        $status = $getData['status'];
      }
      $comboStatus = cmbArray("statusConfirmasi",$status,$arrayStatus,"-- STATUS --","  data-style='btn btn-primary btn-round' title='Single Select' data-size='7'");
      $content = array(
                        'jumlahOrang' => $getData['jumlah_orang'],
                        'comboStatus' => $comboStatus,
                      );

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

    case 'updateAcara':{
      $getData = sqlArray(sqlQuery("select * from acara where id = '$id'"));
      $explodeLocation = explode(',',$getData['koordinat']);
      $lat = $explodeLocation[0];
      $lng = $explodeLocation[1];
      $content = array("namaAcara" => $getData['nama_acara'],
      "tanggalAcara" => generateDate($getData['tanggal']),
      "waktuAcara" => $getData['jam'],
       "kapasitasAcara" => $getData['kapasitas'],
       "lokasi" => $getData['lokasi'],
       "deskripsiAcara" => $getData['deskripsi'],
       "kordinatLocation" => "(".$getData['koordinat'].")",
       "lat" => $lat,
       "lng" => $lng,
    );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
        $getData = sqlQuery("select * from acara");
        while($dataAcara = sqlArray($getData)){
          foreach ($dataAcara as $key => $value) {
              $$key = $value;
          }

          $data .= "     <tr>
                            <td>$nama_acara</td>
                            <td>$lokasi</td>
                            <td>".generateDate($tanggal)." $jam</td>
                            <td>$jumlahRegister</td>
                            <td class='text-right'>
                                <a onclick=listKonfirmasi($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>confirmation_number</i></a>
                                <a onclick=updateAcara($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>edit</i></a>
                                <a onclick=deleteAcara($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                            </td>
                        </tr>
                      ";
        }

        $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
            <thead>
                <tr>
                    <th>Nama Acara</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Pendaftar</th>
                    <th class='disabled-sorting text-right'>Actions</th>
                </tr>
            </thead>
            <tbody>
              $data
            </tbody>
        </table>";
        $content = array("tabelAcara" => $tabel);


      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'loadKonfirmasi':{
      $getData = sqlQuery("select * from reservasi_acara where id_acara = '$idAcara'");
      while($dataAcara = sqlArray($getData)){
        foreach ($dataAcara as $key => $value) {
            $$key = $value;
        }
        if(empty($status)){
            $status = "PENDING";
        }elseif($status == '1'){
            $status = "TERKONFIMASI";
        }else{
            $status = "DITOLAK";
        }

        $data .= "<tr>
                          <td>$nama_peserta</td>
                          <td>$email</td>
                          <td>$instansi</td>
                          <td>$jumlah_orang</td>
                          <td>$status</td>
                          <td class='text-right'>
                              <a onclick=confirmAcara($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>edit</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Instansi</th>
                  <th>Jumlah Orang</th>
                  <th>Status</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelAcara" => $tabel);


      echo generateAPI($cek,$err,$content);
    break;
    }



     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=acara";

        </script>
        

        <script src="js/acara.js"></script>

        <?php
            if(!isset($_GET['action'])){
                ?>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Start Modal -->

                            <div class="col-md-12">
                              <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Acara
                                            </h4>
                                        </div>
                                        <div class="card-content">
                                            <ul class="nav nav-pills nav-pills-primary">
                                              <li class="active" >
                                                  <a href="pages.php?page=acara" >Acara</a>
                                              </li>
                                              <li >
                                                  <a href="pages.php?page=acara&action=new" >Baru</a>
                                              </li>
                                              <!-- <li>
                                                  <a href="pages.php?page=acara&action=confirm">Konfirmasi</a>
                                              </li> -->
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="dataAcara">
                                                  <div class="col-md-12" id='tableAcara'>
                                                      <div class="card">
                                                          <div class="card-header card-header-icon" data-background-color="purple">
                                                              <i class="material-icons">assignment</i>
                                                          </div>
                                                          <div class="card-content">
                                                              <h4 class="card-title">Data acara</h4>
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
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }else{
                if($_GET['action']=='new'){
                  ?>
                  <style>
                      #map {
                      height: 100%;
                      }

                      .controls {
                      margin-top: 10px;
                      border: 1px solid transparent;
                      border-radius: 2px 0 0 2px;
                      box-sizing: border-box;
                      -moz-box-sizing: border-box;
                      height: 32px;
                      outline: none;
                      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                      }

                      #pac-input {
                      background-color: #fff;
                      font-family: Roboto;
                      font-size: 15px;
                      font-weight: 300;
                      margin-left: 12px;
                      padding: 0 11px 0 13px;
                      text-overflow: ellipsis;
                      width: 300px;
                      }

                      #pac-input:focus {
                      border-color: #4d90fe;
                      }

                      .pac-container {
                      font-family: Roboto;
                      }

                      #type-selector {
                      color: #fff;
                      background-color: #4d90fe;
                      padding: 5px 11px 0px 11px;
                      }

                      #type-selector label {
                      font-family: Roboto;
                      font-size: 13px;
                      font-weight: 300;
                      }
                  </style>
                  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU&libraries=places&callback=initMap"
                  async ></script>
                          <script>
                          var markers = [];
                          var map;
                          function initMap() {
                          var origin = {lat: -6.9066217615554235, lng: 107.6347303390503};

                           map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 18,
                            center: origin
                          });
                          var clickHandler = new ClickEventHandler(map, origin);

                          var input = /** @type {!HTMLInputElement} */(
                            document.getElementById('pac-input'));

                          var types = document.getElementById('type-selector');
                          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                          map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

                          var autocomplete = new google.maps.places.Autocomplete(input);
                          autocomplete.bindTo('bounds', map);

                          var infowindow = new google.maps.InfoWindow();
                          var marker = new google.maps.Marker({
                          map: map,
                          anchorPoint: new google.maps.Point(0, -29)
                          });
                          google.maps.event.addListener(map, "click", function (e) {
                            //lat and lng is available in e object
                            var latLng = e.latLng;

                            getAlamat(latLng);
                            deleteMarkers();
                            addMarker(latLng);
                          });

                          autocomplete.addListener('place_changed', function() {
                          infowindow.close();
                          marker.setVisible(false);
                          var place = autocomplete.getPlace();
                          if (!place.geometry) {
                            // User entered the name of a Place that was not suggested and
                            // pressed the Enter key, or the Place Details request failed.
                            window.alert("No details available for input: '" + place.name + "'");
                            return;
                          }

                          // If the place has a geometry, then present it on a map.
                          if (place.geometry.viewport) {
                            map.fitBounds(place.geometry.viewport);
                          } else {
                            map.setCenter(place.geometry.location);
                            map.setZoom(17);  // Why 17? Because it looks good.
                          }
                          marker.setIcon(/** @type {google.maps.Icon} */({
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(35, 35)
                          }));
                          marker.setPosition(place.geometry.location);
                          marker.setVisible(true);

                          var address = '';
                          if (place.address_components) {
                            address = [
                              (place.address_components[0] && place.address_components[0].short_name || ''),
                              (place.address_components[1] && place.address_components[1].short_name || ''),
                              (place.address_components[2] && place.address_components[2].short_name || '')
                            ].join(' ');
                          }

                          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                          infowindow.open(map, marker);
                          });

                          // Sets a listener on a radio button to change the filter type on Places
                          // Autocomplete.
                          function setupClickListener(id, types) {
                          var radioButton = document.getElementById(id);
                          radioButton.addEventListener('click', function() {
                            autocomplete.setTypes(types);
                          });
                          }

                          setupClickListener('changetype-all', []);
                          setupClickListener('changetype-address', ['address']);
                          setupClickListener('changetype-establishment', ['establishment']);
                          setupClickListener('changetype-geocode', ['geocode']);
                          }

                          function deleteMarkers() {
                          clearMarkers();
                          markers = [];
                          }
                          function setMapOnAll(map) {
                          for (var i = 0; i < markers.length; i++) {
                           markers[i].setMap(map);
                          }
                          }

                          // Removes the markers from the map, but keeps them in the array.
                          function clearMarkers() {
                          setMapOnAll(null);
                          }

                          function addMarker(location) {
                            var marker = new google.maps.Marker({
                            position: location,
                            map: map,
                            title: 'Lokasi'
                          });
                          markers.push(marker);
                          map.setCenter(marker.getPosition())
                          }


                          var ClickEventHandler = function(map, origin) {
                          this.origin = origin;
                          this.map = map;
                          this.directionsService = new google.maps.DirectionsService;
                          this.directionsDisplay = new google.maps.DirectionsRenderer;
                          this.directionsDisplay.setMap(map);
                          this.placesService = new google.maps.places.PlacesService(map);
                          this.infowindow = new google.maps.InfoWindow;
                          this.infowindowContent = document.getElementById('infowindow-content');
                          this.infowindow.setContent(this.infowindowContent);

                          // Listen for clicks on the map.
                          this.map.addListener('click', this.handleClick.bind(this));
                          };

                          ClickEventHandler.prototype.handleClick = function(event) {
                          console.log('You clicked on: ' + event.latLng);
                          // If the event has a placeId, use it.
                          if (event.placeId) {
                          console.log('You clicked on place:' + event.placeId);

                          // Calling e.stop() on the event prevents the default info window from
                          // showing.
                          // If you call stop here when there is no placeId you will prevent some
                          // other map click event handlers from receiving the event.
                          event.stop();
                          this.calculateAndDisplayRoute(event.placeId);
                          this.getPlaceInformation(event.placeId);
                          }
                          };

                          ClickEventHandler.prototype.calculateAndDisplayRoute = function(placeId) {
                          var me = this;
                          this.directionsService.route({
                          origin: this.origin,
                          destination: {placeId: placeId},
                          travelMode: 'WALKING'
                          }, function(response, status) {
                          if (status === 'OK') {
                          me.directionsDisplay.setDirections(response);
                          } else {
                          window.alert('Directions request failed due to ' + status);
                          }
                          });
                          };

                          ClickEventHandler.prototype.getPlaceInformation = function(placeId) {
                          var me = this;
                          this.placesService.getDetails({placeId: placeId}, function(place, status) {
                          if (status === 'OK') {
                          me.infowindow.close();
                          me.infowindow.setPosition(place.geometry.location);
                          me.infowindowContent.children['place-icon'].src = place.icon;
                          me.infowindowContent.children['place-name'].textContent = place.name;
                          me.infowindowContent.children['place-id'].textContent = place.place_id;
                          me.infowindowContent.children['place-address'].textContent =
                            place.formatted_address;
                          me.infowindow.open(me.map);
                          }
                          });
                          };
                          </script>
                  <div class="content">
                      <div class="container-fluid">
                          <div class="row">
                              <!-- Start Modal -->

                              <div class="col-md-12">
                                <div class="card">
                                          <div class="card-header">
                                              <h4 class="card-title">Acara
                                              </h4>
                                          </div>
                                          <div class="card-content">
                                              <ul class="nav nav-pills nav-pills-primary">
                                                  <li >
                                                      <a href="pages.php?page=acara"  >Acara</a>
                                                  </li>
                                                  <li class="active">
                                                      <a href="pages.php?page=acara&action=new"  >Baru</a>
                                                  </li>
                                                  <!-- <li>
                                                      <a href="pages.php?page=acara&action=confirm">Konfirmasi</a>
                                                  </li> -->
                                              </ul>
                                              <div class="tab-content">
                                                <div class="tab-pane active" id="acaraBaru">
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group label-floating" >
                                                            <label class="control-label">Acara</label>
                                                            <input type="text" id='namaAcara' name='namaAcara' class="form-control">
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-6 col-sm-4">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Tanggal Acara</label>
                                                              <input type="text" id='tanggalAcara' class="form-control datepicker ">
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6">
                                                          <div class="form-group label-floating">
                                                              <label class="control-label">Waktu Acara</label>
                                                              <input type="text" id='waktuAcara' class="form-control timepicker ">
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                      <div class="form-group label-floating">
                                                          <label class="control-label">Lokasi</label>
                                                          <textarea id="lokasi" class="form-control"></textarea>
                                                          <input type="hidden" id='kordinatX' class="">
                                                          <input type="hidden" id='kordinatY' class="">
                                                          <input type="hidden" id='tempKordinat' class="">
                                                      </div>
                                                    </div>
                                                  </div>

                                                    <div class="card">
                                                      Deskripsi Acara
                                                        <div class="card-body no-padding">
                                                            <div id="summernote">
                                                            </div>
                                                        </div><!--end .card-body -->
                                                    </div>

                                                  <div class="row">
                                                    <input id="pac-input" class="controls" type="text"
                                                    placeholder="Enter a location">
                                                    <div id="type-selector" class="controls">
                                                    <input type="radio" name="type" id="changetype-all" checked="checked">
                                                    </div>
                                                    <div id="map"></div>

                                                      </div>
                                                  <div class="row">
                                                      <div class="col-md-12 col-sm-12">
                                                          <div class="form-group label-floating">
                                                              <input type='button' id='submitAcara' value='SIMPAN' class='btn btn-primary' onclick="saveAcara();" >
                                                          </div>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                              </div>
                                          </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="content" hidden="hidden">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <legend>Sliders</legend>
                                                    <div id="sliderRegular" class="slider"></div>
                                                    <div id="sliderDouble" class="slider slider-info"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card -->
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <?php
                }elseif($_GET['action'] == 'edit'){
                    $getDataEdit = sqlArray(sqlQuery("select * from acara where id = '".$_GET['id']."'"));
                    $explodeKoordinat = explode(",",$getDataEdit['koordinat']);
                    ?>
                    <style>
                        #map {
                        height: 100%;
                        }

                        .controls {
                        margin-top: 10px;
                        border: 1px solid transparent;
                        border-radius: 2px 0 0 2px;
                        box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        height: 32px;
                        outline: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                        }

                        #pac-input {
                        background-color: #fff;
                        font-family: Roboto;
                        font-size: 15px;
                        font-weight: 300;
                        margin-left: 12px;
                        padding: 0 11px 0 13px;
                        text-overflow: ellipsis;
                        width: 300px;
                        }

                        #pac-input:focus {
                        border-color: #4d90fe;
                        }

                        .pac-container {
                        font-family: Roboto;
                        }

                        #type-selector {
                        color: #fff;
                        background-color: #4d90fe;
                        padding: 5px 11px 0px 11px;
                        }

                        #type-selector label {
                        font-family: Roboto;
                        font-size: 13px;
                        font-weight: 300;
                        }
                    </style>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU&libraries=places&callback=initMap"
                    async ></script>
                            <script>
                            var markers = [];
                            var map;
                            function initMap() {
                            var origin = {lat: <?php echo $explodeKoordinat[0] ?>, lng: <?php echo $explodeKoordinat[1] ?>};
                             map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 18,
                              center: origin
                            });
                            var clickHandler = new ClickEventHandler(map, origin);

                            var input = /** @type {!HTMLInputElement} */(
                              document.getElementById('pac-input'));

                            var types = document.getElementById('type-selector');
                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

                            var autocomplete = new google.maps.places.Autocomplete(input);
                            autocomplete.bindTo('bounds', map);

                            var infowindow = new google.maps.InfoWindow();
                            var marker = new google.maps.Marker({
                            map: map,
                            anchorPoint: new google.maps.Point(0, -29)
                            });
                            google.maps.event.addListener(map, "click", function (e) {
                              //lat and lng is available in e object
                              var latLng = e.latLng;

                              getAlamat(latLng);
                              deleteMarkers();
                              addMarker(latLng);
                            });

                            autocomplete.addListener('place_changed', function() {
                            infowindow.close();
                            marker.setVisible(false);
                            var place = autocomplete.getPlace();
                            if (!place.geometry) {
                              // User entered the name of a Place that was not suggested and
                              // pressed the Enter key, or the Place Details request failed.
                              window.alert("No details available for input: '" + place.name + "'");
                              return;
                            }

                            // If the place has a geometry, then present it on a map.
                            if (place.geometry.viewport) {
                              map.fitBounds(place.geometry.viewport);
                            } else {
                              map.setCenter(place.geometry.location);
                              map.setZoom(17);  // Why 17? Because it looks good.
                            }
                            marker.setIcon(/** @type {google.maps.Icon} */({
                              url: place.icon,
                              size: new google.maps.Size(71, 71),
                              origin: new google.maps.Point(0, 0),
                              anchor: new google.maps.Point(17, 34),
                              scaledSize: new google.maps.Size(35, 35)
                            }));
                            marker.setPosition(place.geometry.location);
                            marker.setVisible(true);

                            var address = '';
                            if (place.address_components) {
                              address = [
                                (place.address_components[0] && place.address_components[0].short_name || ''),
                                (place.address_components[1] && place.address_components[1].short_name || ''),
                                (place.address_components[2] && place.address_components[2].short_name || '')
                              ].join(' ');
                            }

                            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                            infowindow.open(map, marker);
                            });

                            // Sets a listener on a radio button to change the filter type on Places
                            // Autocomplete.
                            function setupClickListener(id, types) {
                            var radioButton = document.getElementById(id);
                            radioButton.addEventListener('click', function() {
                              autocomplete.setTypes(types);
                            });
                            }

                            setupClickListener('changetype-all', []);
                            setupClickListener('changetype-address', ['address']);
                            setupClickListener('changetype-establishment', ['establishment']);
                            setupClickListener('changetype-geocode', ['geocode']);
                            }




                            function deleteMarkers() {
                            clearMarkers();
                            markers = [];
                            }
                            function setMapOnAll(map) {
                            for (var i = 0; i < markers.length; i++) {
                             markers[i].setMap(map);
                            }
                            }

                            // Removes the markers from the map, but keeps them in the array.
                            function clearMarkers() {
                            setMapOnAll(null);
                            }

                            function addMarker(location) {
                              var marker = new google.maps.Marker({
                              position: location,
                              map: map,
                              title: 'Lokasi'
                            });
                            markers.push(marker);
                            map.setCenter(marker.getPosition())
                            }


                            var ClickEventHandler = function(map, origin) {
                            this.origin = origin;
                            this.map = map;
                            // this.directionsService = new google.maps.DirectionsService;
                            // this.directionsDisplay = new google.maps.DirectionsRenderer;
                            // this.directionsDisplay.setMap(map);
                            this.placesService = new google.maps.places.PlacesService(map);
                            this.infowindow = new google.maps.InfoWindow;
                            this.infowindowContent = document.getElementById('infowindow-content');
                            this.infowindow.setContent(this.infowindowContent);
                            deleteMarkers();
                            var lastLocation = new google.maps.LatLng(<?php echo $explodeKoordinat[0] ?>,<?php echo $explodeKoordinat[1] ?>);
                            addMarker(lastLocation);

                            // Listen for clicks on the map.
                            this.map.addListener('click', this.handleClick.bind(this));
                            };

                            ClickEventHandler.prototype.handleClick = function(event) {
                            console.log('You clicked on: ' + event.latLng);
                            // If the event has a placeId, use it.
                            if (event.placeId) {
                            console.log('You clicked on place:' + event.placeId);

                            // Calling e.stop() on the event prevents the default info window from
                            // showing.
                            // If you call stop here when there is no placeId you will prevent some
                            // other map click event handlers from receiving the event.
                            event.stop();
                            this.calculateAndDisplayRoute(event.placeId);
                            this.getPlaceInformation(event.placeId);
                            }
                            };

                            ClickEventHandler.prototype.calculateAndDisplayRoute = function(placeId) {
                            var me = this;
                            this.directionsService.route({
                            origin: this.origin,
                            destination: {placeId: placeId},
                            travelMode: 'WALKING'
                            }, function(response, status) {
                            if (status === 'OK') {
                            me.directionsDisplay.setDirections(response);
                            } else {
                            window.alert('Directions request failed due to ' + status);
                            }
                            });
                            };

                            ClickEventHandler.prototype.getPlaceInformation = function(placeId) {
                            var me = this;
                            this.placesService.getDetails({placeId: placeId}, function(place, status) {
                            if (status === 'OK') {
                            me.infowindow.close();
                            me.infowindow.setPosition(place.geometry.location);
                            me.infowindowContent.children['place-icon'].src = place.icon;
                            me.infowindowContent.children['place-name'].textContent = place.name;
                            me.infowindowContent.children['place-id'].textContent = place.place_id;
                            me.infowindowContent.children['place-address'].textContent =
                              place.formatted_address;
                            me.infowindow.open(me.map);
                            }
                            });
                            };
                            </script>

                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Start Modal -->

                                <div class="col-md-12">
                                  <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Acara
                                                </h4>
                                            </div>
                                            <div class="card-content">
                                                <ul class="nav nav-pills nav-pills-primary">
                                                    <li >
                                                        <a href="pages.php?page=acara"  >Acara</a>
                                                    </li>
                                                    <li class="active">
                                                        <a >Edit</a>
                                                    </li>
                                                    <!-- <li>
                                                        <a href="pages.php?page=acara&action=confirm">Konfirmasi</a>
                                                    </li> -->
                                                </ul>
                                                <div class="tab-content">
                                                  <div class="tab-pane active" id="acaraBaru">
                                                    <div class="row">
                                                      <div class="col-lg-12">
                                                          <div class="form-group label-floating" >
                                                              <label class="control-label">Acara</label>
                                                              <input type="text" id='namaAcara' name='namaAcara' class="form-control" value='<?php echo $getDataEdit['nama_acara'] ?>'>
                                                          </div>
                                                      </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-4">
                                                            <div class="form-group label-floating">
                                                                <label class="control-label">Tanggal Acara</label>
                                                                <input type="text" id='tanggalAcara' class="form-control datepicker " value='<?php echo generateDate($getDataEdit['tanggal']) ?>'>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="form-group label-floating">
                                                                <label class="control-label">Waktu Acara</label>
                                                                <input type="text" id='waktuAcara' class="form-control timepicker " value='<?php echo $getDataEdit['jam'] ?>'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-12 col-sm-12">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label">Lokasi</label>
                                                            <textarea id="lokasi" class="form-control"><?php echo $getDataEdit['lokasi'] ?></textarea>
                                                            <input type="hidden" id='kordinatX' class="" value='<?php echo $explodeKoordinat[0] ?>'>
                                                            <input type="hidden" id='kordinatY' class="" value='<?php echo $explodeKoordinat[1] ?>'>
                                                            <input type="hidden" id='tempKordinat' class="">
                                                        </div>
                                                      </div>
                                                    </div>

                                                      <div class="card">
                                                        Deskripsi Acara
                                                          <div class="card-body no-padding">
                                                              <div id="summernote">
                                                                <?php echo $getDataEdit['deskripsi'] ?>
                                                              </div>
                                                          </div><!--end .card-body -->
                                                      </div>

                                                    <div class="row">
                                                      <input id="pac-input" class="controls" type="text"
                                                      placeholder="Enter a location">
                                                      <div id="type-selector" class="controls">
                                                      <input type="radio" name="type" id="changetype-all" checked="checked">
                                                      </div>
                                                      <div id="map"></div>

                                                        </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="form-group label-floating">
                                                                <input type='button' id='submitAcara' value='SIMPAN' class='btn btn-primary' onclick="saveEditAcara(<?php echo $getDataEdit['id'] ?>);" >
                                                            </div>
                                                        </div>
                                                    </div>
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
                }elseif($_GET['action'] == 'confirm'){
                    ?>
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Start Modal -->

                                <div class="col-md-12">
                                  <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Acara
                                                </h4>
                                            </div>
                                            <div class="card-content">
                                                <ul class="nav nav-pills nav-pills-primary">
                                                  <li >
                                                      <a href="pages.php?page=acara" >Acara</a>
                                                  </li>
                                                  <li >
                                                      <a href="pages.php?page=acara&action=new" >Baru</a>
                                                  </li>
                                                  <li class="active" >
                                                      <a  href="#">Konfirmasi</a>
                                                  </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="dataAcara">
                                                      <div class="col-md-12" id='tableAcara'>
                                                          <div class="card">
                                                              <div class="card-header card-header-icon" data-background-color="purple">
                                                                  <i class="material-icons">assignment</i>
                                                              </div>
                                                              <div class="card-content">
                                                                  <h4 class="card-title">Konfirmasi Acara</h4>
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
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
         ?>


         <div class="modal fade" id="formKonfirmasiAcara" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                             <i class="material-icons">clear</i>
                         </button>
                         <h4 class="modal-title">Konfirmasi</h4>
                     </div>
                     <div class="modal-body">

                         <div class="row">
                             <div class="col-md-12 col-sm-12">
                                 <div class="form-group label-floating" id='divForDesc'>
                                     <label class="control-label">Status</label>
                                     <span id='spanComboStatus'></span>
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-12 col-sm-12">
                                 <div class="form-group label-floating is-focused" id='divForDesc'>
                                     <label class="control-label">Jumlah Orang</label>
                                     <input type='text' id='jumlahOrang' class="form-control">
                                 </div>
                             </div>
                         </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-simple" id='buttonSubmitKonfirmasi' data-dismiss="modal">Simpan</button>
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
