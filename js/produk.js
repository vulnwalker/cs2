function showGambarProduk(id){
  $.ajax({
    type:'POST',
    data : {
        idproduk : id,
    },
    url: url+'&tipe=showGambarProduk',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
         $("#tempatGambar").html(resp.content.imagesProduks);
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
function saveProduk(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    // data : $("#formProduk").serialize()+"&deskripsiProduk="+$("#summernote").code()+"&baseGambarProduk="+crop,
    data : {
          namaProduk : $("#namaProduk").val(),
          statusKosong : $("#statusKosong").val(),
          baseGambarProduk : crop,
          statusPublish : $("#statusPublish").val(),
          deskripsiProduk : $("#summernote").code(),
    },
    url: url+'&tipe=saveProduk',
      success: function(data) {
      $("#LoadingImage").hide();
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
    window.location= 'pages.php?page=produk';
}

function loadTable(){
  $.ajax({
    type:'POST',

    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelProduk);
          $('#datatables').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_ &nbsp",
                searchPlaceholder: "Cari data",
            },
            "oLanguage": {
              "sLengthMenu": "Data perhalaman &nbsp _MENU_ ",
            },
            "bSortable": false,
            "ordering": false,
            "dom": '<"top"fl>rt<"bottom"ip><"clear">'
          });
          $('.dataTables_filter').addClass('pull-left');

        }else{
          alert(resp.err);
        }
      }
  });
}


function deleteProduk(id){
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
             url: url+'&tipe=deleteProduk',
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

function removeTemp(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=removeTemp',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){

        }else{
          alert(resp.err);
        }
      }
  });
}
function deskripsiScreenShot(namaFile){
  $.ajax({
    type:'POST',
    data : {namaFile : namaFile},
    url: url+'&tipe=deskripsiScreenShot',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#descSreenShot").text("");
          $("#tempScreenShot").attr('src',resp.content.srcImage);
          $("#descSreenShot").val(resp.content.descScreenShot);
          $("#divForDesc").attr("class","form-group label-floating is-focused");
          $("#buttonSubmitScreenShot").attr("onclick","saveDescSreenshot('"+namaFile+"')");
          $("#formDeskripsiScreenShot").modal();
        }else{
          alert(resp.err);
        }
      }
  });
}
function saveDescSreenshot(namaFile){
  $.ajax({
    type:'POST',
    data : {
              namaFile : namaFile,
              descSreenShot : $("#descSreenShot").val()
            },
    url: url+'&tipe=saveDescSreenshot',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
        }else{
          alert(resp.err);
        }
      }
  });
}
function clearTemp(){
  $("#data2").text("Baru");
  $("#data2").click();
}
function updateProduk(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateProduk',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          // $("#data2").text("Edit");
          // $("#data2").click();
          // $("#namaProduk").val(resp.content.namaProduk);
          // $("#statusPublish").val(resp.content.statusPublish);
          // $("#summernote").code(resp.content.deskripsi);
          // $("#buttonSubmit").attr("onclick","saveEditProduk("+id+")");
          // $("#tempImageProduk").attr("src",resp.content.baseOfFile);
          // $("#gambarProduk").val(resp.content.baseOfFile);
          // Dropzone.autoDiscover = false;
          //   var myDropzone = new Dropzone("#dropzone", {
          //       url: "upload.php",
          //       maxFileSize: 50,
          //       acceptedFiles: ".jpeg,.jpg,.png,.gif",
          //       addRemoveLinks: true,
          //       init: function() {
          //           this.on("complete", function(file) {
          //               $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' onclick=removeTemp('"+file.name+"');>REMOVE</span></div>");
          //
          //           });
          //       }
          //
          //   });
          // $("#dropzone").attr('class','dropzone dz-clickable');
          // var existingFiles = JSON.parse(resp.content.screenShot);
          // for (i = 0; i < existingFiles.length; i++) {
          //     myDropzone.emit("addedfile", existingFiles[i]);
          //     myDropzone.emit("thumbnail", existingFiles[i], existingFiles[i].imageLocation);
          //     myDropzone.emit("complete", existingFiles[i]);
          // }
          //
          // $("#isiProduk").val(resp.content.isiProduk);
            window.location = "pages.php?page=produk&edit="+id;
        }else{
          alert(resp.err);
        }
      }
  });

}


function saveEditProduk(idEdit){
  $.ajax({
    type:'POST',
    data : {
        statusPublish : $("#statusPublish").val(),
        namaProduk : $("#namaProduk").val(),
        deskripsiProduk : $("#summernote").code(),
        idEdit : idEdit,
        statusKosong : $("#statusKosong").val(),
        baseGambarProduk : crop,
    },
    url: url+'&tipe=saveEditProduk',
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

function imageChanged(){
  var me= this;
  var filesSelected = document.getElementById("imageProduk").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];

    var fileReader = new FileReader();

    fileReader.onload = function(fileLoadedEvent)
    {
      // var textAreaFileContents = document.getElementById
      // (
      //   "gambarProduk"
      // );
      //
      // textAreaFileContents.value = fileLoadedEvent.target.result;


      $("#gambarProduk").attr('src',fileLoadedEvent.target.result);
      resizeableImage($('#gambarProduk'));
      $('.component').show();
      $("#statusKosong").val('1');
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}

function Baru(){
  window.location = "pages.php?page=produk&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=produk" ;
}
function Edit(){
  var errMsg = getJumlahChecked("produk");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formProduk").serialize(),
      url: url+'&tipe=Edit',
        success: function(data) {
        var resp = eval('(' + data + ')');
          if(resp.err==''){
            window.location = "pages.php?page=produk&action=edit&idEdit="+resp.content.idEdit;
          }else{
             errorAlert(resp.err);
          }
        }
    });
  }else{
      errorAlert(errMsg);
  }
}
function Hapus(){
  var errMsg = getJumlahChecked("produk");
  if(errMsg == '' || errMsg=='Pilih hanya satu data'){
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
             data : $("#formProduk").serialize(),
             url: url+'&tipe=Hapus',
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

  }else{
      errorAlert(errMsg);
  }
}
