function saveLowongan(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formLowongan").serialize()+"&jobDesc="+$("#summernote").code()+"&spesifikasiPekerjaan="+$("#spesifikasiLowongan").val(),
    url: url+'&tipe=saveLowongan',
      success: function(data) {
      var resp = eval('(' + data + ')');
      $("#LoadingImage").hide();
        if(resp.err==''){
           suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}

function refreshList(){
    window.location = "pages.php?page=lowonganKerja";
}

function loadTable(){
  $.ajax({
    type:'POST',
    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelLowongan);
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

function loadLamaran(idLamaran){
  $.ajax({
    type:'POST',
    data:{idLamaran : idLamaran},
    url: url+'&tipe=loadLamaran',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelLamaran);
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
function downloadCV(id){
  $.ajax({
    type:'POST',
    data:{id : id},
    url: url+'&tipe=downloadCV',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          var win = window.open(resp.content.cv, '_blank');
          win.focus();
        }else{
          alert(resp.err);
        }
      }
  });
}


function deleteLowongan(id){
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
             url: url+'&tipe=deleteLowongan',
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
function clearTemp(){
  $("#data2").text("Baru");
  $("#data2").click();
}
function baruLowongan(){

          $("#divForLowonganname").attr("class","form-group label-floating ");
          $("#divForPassword").attr("class","form-group label-floating ");
          $("#divForEmail").attr("class","form-group label-floating ");
          $("#divForNama").attr("class","form-group label-floating ");
          $("#divForTelepon").attr("class","form-group label-floating ");
          $("#divForAlamat").attr("class","form-group label-floating ");
          $("#divForInstansi").attr("class","form-group label-floating ");
          $("#usernameLowongan").val("");
          $("#passwordLowongan").val("");
          $("#emailLowongan").val("");
          $("#namaLowongan").val("");
          $("#teleponLowongan").val("");
          $("#alamatLowongan").text("");
          $("#instansiLowongan").val("");
          $("#statusLowongan").val("1");
          $("#buttonSubmit").attr("onclick","saveLowongan()");

}
function updateLowongan(id){

  window.location = "pages.php?page=lowonganKerja&edit="+id;
}
function listLamaran(id){

  window.location = "pages.php?page=lowonganKerja&action=confirm&idLowongan="+id;
}


function saveEditLowongan(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formLowongan").serialize()+"&jobDesc="+$("#summernote").code()+"&idEdit="+idEdit+"&spesifikasiPekerjaan="+$("#spesifikasiLowongan").val(),
    url: url+'&tipe=saveEditLowongan',
      success: function(data) {
      var resp = eval('(' + data + ')');
        $("#LoadingImage").hide();
        if(resp.err==''){
          suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}


function base64Encode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}

function base64Decode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}
