function saveTeam(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formTeam").serialize(),
    url: url+'&tipe=saveTeam',
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
    window.location = "pages.php?page=team";
}

function loadTable(){
  $.ajax({
    type:'POST',

    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelTeam);
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


function deleteTeam(id){
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
             url: url+'&tipe=deleteTeam',
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
function baruTeam(){

          $("#divForTeamname").attr("class","form-group label-floating ");
          $("#divForPassword").attr("class","form-group label-floating ");
          $("#divForEmail").attr("class","form-group label-floating ");
          $("#divForNama").attr("class","form-group label-floating ");
          $("#divForTelepon").attr("class","form-group label-floating ");
          $("#divForAlamat").attr("class","form-group label-floating ");
          $("#divForInstansi").attr("class","form-group label-floating ");
          $("#usernameTeam").val("");
          $("#passwordTeam").val("");
          $("#emailTeam").val("");
          $("#namaTeam").val("");
          $("#teleponTeam").val("");
          $("#alamatTeam").text("");
          $("#instansiTeam").val("");
          $("#statusTeam").val("1");
          $("#buttonSubmit").attr("onclick","saveTeam()");

}
function updateTeam(id){
  window.location = "pages.php?page=team&edit="+id;

}


function saveEditTeam(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formTeam").serialize()+"&idEdit="+idEdit,
    url: url+'&tipe=saveEditTeam',
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



function imageChanged(){
  var me= this;
  var filesSelected = document.getElementById("fileFotoTeam").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];

    var fileReader = new FileReader();

    fileReader.onload = function(fileLoadedEvent)
    {
      var textAreaFileContents = document.getElementById
      (
        "fotoTeam"
      );

      textAreaFileContents.value = fileLoadedEvent.target.result;
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
