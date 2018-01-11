function saveUser(){
  // $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formUser").serialize(),
    url: url+'&tipe=saveUser',
      success: function(data) {
      // $("#LoadingImage").hide();
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
function saveEditUser(idEdit){
  // $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formUser").serialize()+"&idEdit="+idEdit,
    url: url+'&tipe=saveEditUser',
      success: function(data) {
      // $("#LoadingImage").hide();
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
    window.location = "pages.php?page=userManagement" ;
}
function loadTable(){
  $.ajax({
    type:'POST',
    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#tabelBody").html(resp.content.tabelBody);
          $("#tabelFooter").html(resp.content.tabelFooter);


        }else{
          alert(resp.err);
        }
      }
  });
}
function setMenuEdit(statusMenu){
  $.ajax({
    type:'POST',
    data : {statusMenu : statusMenu},
    url: url+'&tipe=setMenuEdit',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#actionArea").html(resp.content.header);


        }else{
          alert(resp.err);
        }
      }
  });
}
function Baru(){
  window.location = "pages.php?page=userManagement&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=userManagement" ;
}
function Edit(){
  var errMsg = getJumlahChecked("userManagement");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formUserManagement").serialize(),
      url: url+'&tipe=Edit',
        success: function(data) {
        var resp = eval('(' + data + ')');
          if(resp.err==''){
            window.location = "pages.php?page=userManagement&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("userManagement");
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
             data : $("#formUserManagement").serialize(),
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
