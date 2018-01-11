function getJumlahChecked(prefix){
  var jmldata= document.getElementById( prefix+'_jmlcek' ).value;
  for(var i=0; i < jmldata; i++){
    var box = document.getElementById( prefix+'_cb' + i);
    if( box.checked){
      break;
    }
  }
  var err = "";
  if(jmldata == 0){
      err = "Pilih data";
  }else if(jmldata > 1){
      err = "Pilih hanya satu data";
  }
  return err;
}


function checkSemua(n,fldName,elHeaderChecked,elJmlCek) {

   if (!fldName) {
     fldName = 'cb';
   }
   if (!elHeaderChecked) {
     elHeaderChecked = 'toggle';
   }
   var c = document.getElementById(elHeaderChecked).checked;
   var n2 = 0;
   for (i=0; i < n ; i++) {
    cb = document.getElementById(fldName+i);
    if (cb) {
      cb.checked = c;

      //  thisChecked($("#"+fldName+i).val(),fldName+i);
      n2++;
    }
   }
   if (c) {
    document.getElementById(elJmlCek).value = n2;
   } else {
    document.getElementById(elJmlCek).value = 0;
   }
}


function thisChecked(idCheckbox,elJmlCek){
    var c = document.getElementById(idCheckbox).checked;
    var jumlahCheck = parseInt($("#"+elJmlCek).val());
    if(c){
        document.getElementById(elJmlCek).value = jumlahCheck + 1;
    }else{
        document.getElementById(elJmlCek).value = jumlahCheck - 1;
    }
}


  function suksesAlert(pesan){
        swal({
        title: pesan,
        type: "success"
        }).then(function() {
            refreshList();
        });
    }

    function errorAlert(pesan){
        swal({
        title: pesan,
        type: "warning"
        }).then(function() {
        });
    }
