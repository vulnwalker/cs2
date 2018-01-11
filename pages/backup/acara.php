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
                'kapasitas' => $kapasitasAcara,
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
                'kapasitas' => $kapasitasAcara,
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
                          <td>$kapasitas</td>
                          <td class='text-right'>
                              <a onclick=updateAcara($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
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
                  <th>Kapasitas</th>
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
        <style>


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
                                    <ul class="nav nav-pills nav-pills-warning">
                                        <li class="active">
                                            <a href="#dataAcara" id='data1' data-toggle="tab" aria-expanded="true" onclick="clearTemp();">Acara</a>
                                        </li>
                                        <li class="">
                                            <a href="#acaraBaru" id='data2' data-toggle="tab" aria-expanded="false" onclick="activeAction();">Baru</a>
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



                                        <div class="tab-pane" id="acaraBaru">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Nama Acara</label>
                                                      <input type="text" id='namaAcara' >
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-4 col-sm-4">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Tanggal Acara</label>
                                                      <input type="text" id='tanggalAcara' class="datepicker ">
                                                  </div>
                                              </div>
                                              <div class="col-md-4 col-sm-4">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Waktu Acara</label>
                                                      <input type="text" id='waktuAcara' class="timepicker ">
                                                  </div>
                                              </div>
                                              <div class="col-md-4 col-sm-4">
                                                  <div class="form-group label-floating">
                                                      <label class="control-label">Kapasitas</label>
                                                      <input type="text" id='kapasitasAcara' >
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                                <div class="input-field col s12">
                                                  <textarea id="lokasi" class="materialize-textarea"></textarea>
                                                  <label for="lokasi">Lokasi</label>
                                                  <input type="hidden" id='kordinatX' class="">
                                                  <input type="hidden" id='kordinatY' class="">
                                                  <input type="hidden" id='tempKordinat' class="">
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
                                                      <input type='button' id='submitAcara' value='SIMPAN' class='waves-effect waves-light btn' onclick="saveAcara();" >
                                                  </div>
                                              </div>
                                          </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                    </div>
                    <!-- End Modal -->




                    <!-- end col-md-12 -->
                </div>
                <!-- end row -->
            </div>
        </div>



<?php

     break;
     }

}

?>
