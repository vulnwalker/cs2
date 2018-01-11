function saveAcara(){
  $.ajax({
    type:'POST',
    data : {
              namaAcara : $("#namaAcara").val(),
              tanggalAcara : $("#tanggalAcara").val(),
              waktuAcara : $("#waktuAcara").val(),
              lokasi : $("#lokasi").val(),
              deskripsiAcara : $("#summernote").code(),
              kordinatX : $("#kordinatX").val(),
              kordinatY : $("#kordinatY").val(),
            },
    url: url+'&tipe=saveAcara',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}

function refreshList(){
    window.location = "pages.php?page=acara";
}

function loadTable(){
  $.ajax({
    type:'POST',
    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelAcara);
          $('#datatables').DataTable({
              "pagingType": "full_numbers",
              "lengthMenu": [
                  [10, 25, 50, -1],
                  [10, 25, 50, "All"]
              ],
              responsive: true,
              language: {
                  search: "_INPUT_",
                  searchPlaceholder: "Search records",
              }
          });
        }else{
          alert(resp.err);
        }
      }
  });
}

function loadKonfirmasi(idAcara){
  $.ajax({
    type:'POST',
    data: {
            idAcara : idAcara
          },
    url: url+'&tipe=loadKonfirmasi',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelAcara);
          $('#datatables').DataTable({
              "pagingType": "full_numbers",
              "lengthMenu": [
                  [10, 25, 50, -1],
                  [10, 25, 50, "All"]
              ],
              responsive: true,
              language: {
                  search: "_INPUT_",
                  searchPlaceholder: "Search records",
              }
          });
        }else{
          alert(resp.err);
        }
      }
  });
}



function deleteAcara(id){
  swal({
      title: "Yakin Hapus Data",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Ya',
      cancelButtonText: "Tidak"
   }).then(
         function () {
           $.ajax({
             type:'POST',
             data : {id:id},
             url: url+'&tipe=deleteAcara',
               success: function(data) {
               var resp = eval('(' + data + ')');
                 if(resp.err==''){
                   suksesAlert("Data Terhapus");
                 }else{
                   errorAlert(resp.err);
                 }
               }
           });
         },
         function () { return false; });
}

function baruAcara(){

          $("#formAcaraBaru").modal();
          $("#judulAcara").val("");

          $("#buttonSubmit").attr("onclick","saveAcara()");

}
function updateAcara(id){
  // $.ajax({
  //   type:'POST',
  //   data : {id : id},
  //   url: url+'&tipe=updateAcara',
  //     success: function(data) {
  //     var resp = eval('(' + data + ')');
  //       if(resp.err==''){
  //           $("#data2").text("Edit");
  //           $("#data2").click();
  //           $("#submitAcara").attr("onclick","saveEditAcara("+id+")");
  //           $("#namaAcara").val(resp.content.namaAcara);
  //           $("#tanggalAcara").val(resp.content.tanggalAcara);
  //           $("#waktuAcara").val(resp.content.waktuAcara);
  //           //$("#lokasi").val(resp.content.lokasi);
  //           $("#kapasitasAcara").val(resp.content.kapasitasAcara);
  //           $("#summernote").code(resp.content.deskripsiAcara);
  //           getAlamat(resp.content.kordinatLocation);
  //           deleteMarkers();
  //           var lastLocation = new google.maps.LatLng(resp.content.lat,resp.content.lng);
  //           addMarker(lastLocation);
  //       }else{
  //         alert(resp.err);
  //       }
  //     }
  // });
  // getAlamat(<?php echo $getDataEdit['koordinat'] ?>);
  // deleteMarkers();
  // var lastLocation = new google.maps.LatLng(<?php echo $explodeKoordinat[0] ?>,<?php echo $explodeKoordinat[1] ?>);
  // addMarker(lastLocation);
window.location = "pages.php?page=acara&action=edit&id="+id;
}
function confirmAcara(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=confirmAcara',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
            $("#formKonfirmasiAcara").modal();
            if(document.getElementById('statusConfirmasi')){
            }else{
                $("#spanComboStatus").html(resp.content.comboStatus);
                $("#statusConfirmasi").attr('class','selectpicker');
                $("#statusConfirmasi").selectpicker('refresh');
            }
            $("#jumlahOrang").val(resp.content.jumlahOrang);
            $("#buttonSubmitKonfirmasi").attr('onclick',"saveKonfirmasi("+id+")");
        }else{
          alert(resp.err);
        }
      }
  });
}
function listKonfirmasi(id){
  window.location = "pages.php?page=acara&action=confirm&idAcara="+id;
}


function saveEditAcara(idEdit){
  $.ajax({
    type:'POST',
    data : {
              namaAcara : $("#namaAcara").val(),
              tanggalAcara : $("#tanggalAcara").val(),
              waktuAcara : $("#waktuAcara").val(),
              lokasi : $("#lokasi").val(),
              deskripsiAcara : $("#summernote").code(),
              kordinatX : $("#kordinatX").val(),
              kordinatY : $("#kordinatY").val(),
              idEdit : idEdit,
            },
    url: url+'&tipe=saveEditAcara',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
         suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
function saveKonfirmasi(id){
  $.ajax({
    type:'POST',
    data : {
              statusConfirmasi : $("#statusConfirmasi").val(),
              jumlahOrang : $("#jumlahOrang").val(),
              id : id,
            },
    url: url+'&tipe=saveKonfirmasi',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}



function activeAction(){
  $('.timepicker').pickatime({
      default: 'now', // Set default time: 'now', '1:30AM', '16:30'
      fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
      twelvehour: false, // Use AM/PM or 24-hour format
      donetext: 'OK', // text for done-button
      cleartext: 'Clear', // text for clear-button
      canceltext: 'Cancel', // Text for cancel-button
      autoclose: false, // automatic close timepicker
      ampmclickable: true, // make AM PM clickable
      aftershow: function(){} //Function for after opening timepicker
    });
    $('.datepicker').pickadate({
      selectMonths: true,
      selectYears: 15,
      today: 'Today',
      clear: 'Clear',
      close: 'Ok',
      format: 'dd-mm-yyyy',
      closeOnSelect: false
      });

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

}

function clearTemp(){
  $("#data2").text("Baru");
  $("#data2").click();
}

function getAlamat(koordinat){
   $("#tempKordinat").val(koordinat);
  //  alert($("#tempKordinat").val());
  $.ajax({
    type:'POST',
    data : {
              koordinat : $("#tempKordinat").val()
            },
    url: url+'&tipe=generateLocation',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){

             $("#lokasi").val(resp.content.lokasi);
            $("#kordinatX").val(resp.content.lat);
            $("#kordinatY").val(resp.content.lang);
        }else{
          alert(resp.err);
        }
      }
  });
}
