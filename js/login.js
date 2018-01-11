function check_login()
{
	//Mengambil value dari input username & Password
	var username = $('#username').val();
	var password = $('#password').val();
	//Ubah alamat url berikut, sesuaikan dengan alamat script pada komputer anda
 //    var full = window.location.host
	// //window.location.host is subdomain.domain.com
	// var parts = full.split('.')
	// var sub = '';
 //    var domain = '';
 //    var type = '';
    var url_login = '/pages/proses.php';
    var url_admin = 'pages.php?page=userManagement';
	//Ubah tulisan pada button saat click login
	//$('#btnLogin').attr('text','Silahkan tunggu ...');
	document.getElementById('submit').textContent = 'Silahkan tunggu ...';
	//Gunakan jquery AJAX
	$.ajax({
		url		: url_login,
		//mengirimkan username dan password ke script login.php
		data	: 'var_usn='+username+'&var_pwd='+password, 
		//Method pengiriman
		type	: 'POST',
		//Data yang akan diambil dari script pemroses
		dataType: 'html',
		//Respon jika data berhasil dikirim
		success	: function(pesan){
			var obj = JSON.parse(pesan);
			if(obj.response=='ok'){
				
				swal({
				  position: 'top-right',
				  type: 'success',
				  title: 'Berhasil Login',
				  showConfirmButton: true,
				  timer: 5000
				});
				// alert("Berhasil Login");
				window.location = url_admin;
				
			}
			else{

				document.getElementById('submit').textContent = 'LOGIN';
				swal({
		            position: 'top-right',
		            type: 'warning',
		            title: 'Gagal Login',
		            showConfirmButton: true,
		            timer: 5000
		          });
				// alert("Gagal Login");
			}
		},
	});
}
