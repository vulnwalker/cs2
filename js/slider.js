function saveSlider(){
      $("#LoadingImage").attr('style','display:block');
      $.ajax({
        type:'POST',
        data : {
                namaSlider : $("#namaSlider").val(),
                statusPublish : $("#statusPublish").val(),
                baseSlider : crop,
                statusKosong : $("#statusKosong").val(),
                },
        url: url+'&tipe=saveSlider',
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
function saveEditSlider(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
            namaSlider : $("#namaSlider").val(),
            statusPublish : $("#statusPublish").val(),
            baseSlider : crop,
            statusKosong : $("#statusKosong").val(),
            idEdit : idEdit
            },
    url: url+'&tipe=saveEditSlider',
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


      $("#gambarSlider").attr('src',fileLoadedEvent.target.result);
      resizeableImage($('#gambarSlider'));
      $('.component').show();
      $("#statusKosong").val('1');
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
function refreshList(){
    window.location = "pages.php?page=slider" ;
}
function loadTable(){
  $.ajax({
    type:'POST',

    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelUser);
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
function Baru(){
  window.location = "pages.php?page=slider&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=slider" ;
}
function Edit(){
  var errMsg = getJumlahChecked("slider");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formSlider").serialize(),
      url: url+'&tipe=Edit',
        success: function(data) {
        var resp = eval('(' + data + ')');
          if(resp.err==''){
            window.location = "pages.php?page=slider&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("slider");
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
             data : $("#formSlider").serialize(),
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
