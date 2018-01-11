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


switch($tipe){

    case 'saveUser':{
      if(empty($statusUser)){
          $err = "Pilih status user";
      }elseif(empty($usernameUser)){
          $err = "Isi username";
      }elseif(empty($emailUser)){
          $err = "Isi email";
      }elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }

      if(empty($err)){
          $data = array(
                  'email' => $emailUser,
                  'username' => $usernameUser,
                  'password' => sha1(md5($passwordUser)),
                  'nama' => $namaUser,
                  'telepon' => $teleponUser,
                  'alamat' =>  $alamatUser,
                  'instansi' =>  $instansiUser,
                  'jenis_user' =>  $statusUser,
          );
          $query = sqlInsert("users",$data);
          sqlQuery($query);
          $cek = $query;

          $dataHash = array(
              'hash' => sha1(md5($passwordUser)),
              'password' => $passwordUser,
          );
          if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUser'")) == 0){
              sqlQuery(sqlInsert("wordlist",$dataHash));
          }

      }
      $content = array("judulUser" => $judulUser);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditUser':{
      if(empty($statusUser)){
          $err = "Pilih status user";
      }elseif(empty($usernameUser)){
          $err = "Isi username";
      }elseif(empty($emailUser)){
          $err = "Isi email";
      }elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }

      if(empty($err)){
        $data = array(
                'email' => $emailUser,
                'username' => $usernameUser,
                'password' => sha1(md5($passwordUser)),
                'nama' => $namaUser,
                'telepon' => $teleponUser,
                'alamat' =>  $alamatUser,
                'instansi' =>  $instansiUser,
                'jenis_user' =>  $statusUser,
        );
        $query = sqlUpdate("users",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;

        $dataHash = array(
            'hash' => sha1(md5($passwordUser)),
            'password' => $passwordUser,
        );
        if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUser'")) == 0){
            sqlQuery(sqlInsert("wordlist",$dataHash));
        }
      }
      $content = array("judulUser" => $judulUser);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($userManagement_cb) ; $i++) {
        $query = "delete from users where id = '".$userManagement_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $userManagement_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from users");
      $nomor = 1;
      $nomorCB = 0;
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
        if($jenis_user == '1'){
            $jenisUser = "MEMBER";
        }else{
            $jenisUser = "ADMIN";
        }
        $data .= "     <tr>
                          <td class='text-center'>$nomor</td>
                          <td class='text-center;width:20px;'><span class='checkbox'><label>".setCekBox($nomorCB,$id,'','userManagement')."&nbsp</label></span></td>
                          <td>$nama</td>
                          <td>$username</td>
                          <td>$email</td>
                          <td>$instansi</td>
                          <td>$telepon</td>
                          <td>$jenisUser</td>
                       </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }
      $tabelBody = "
      <table class='table table-striped no-margin table-hover' id='tabelBody'>
        <thead>
          <tr>
            <th class='text-center'>No</th>
            <th class='text-center;width:20px;'><input type='checkbox' name='userManagement_toogle' id='userManagement_toogle' onclick=checkSemua(100,'userManagement_cb','userManagement_toogle','userManagement_jmlcek')></th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Instansi</th>
            <th>Telepon</th>
            <th>Kategori</th>
          </tr>
        </thead>
        <tbody>
          $data
        </tbody>
      <table class='table table-striped no-margin table-hover' id='tabelBody'>
      ";


      $tabelFooter = "
        <ul class='pagination pagination-info'>
            <li class='active'>
                <a href='javascript:void(0);'>Prev</a>
            </li>
            <li>
                <a href='javascript:void(0);'>1</a>
            </li>
            <li>
                <a href='javascript:void(0);'>2</a>
            </li>
            <li >
                <a href='javascript:void(0);'>3</a>
            </li>
            <li>
                <a href='javascript:void(0);'>Next </a>
            </li>
        </ul>
      <input type='hidden' name='userManagement_jmlcek' id='userManagement_jmlcek' value='0'>";
      $content = array("tabelBody" => $tabelBody, 'tabelFooter' => $tabelFooter);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'setMenuEdit':{
      if($statusMenu == 'index'){
        $header = "
          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>

                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Baru();>
                      <i class='fa fa-plus'></i>
                      baru
                  </button>
                </div>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Edit();>
                    <i class='fa fa-magic'></i>
                    edit
                  </button>
                </div>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Hapus();>
                    <i class='fa fa-close'></i>
                    hapus
                  </button>
                </div>
              </div>
            </li>
            <li class='dropdown'>
              <div class='navbar-search' role='search' >
                <div class='form-group'>
                  <input type='text' class='form-control' name='headerSearch' placeholder='Enter your keyword'>
                </div>
                <button type='submit' class='btn btn-icon-toggle ink-reaction'>
                  <i class='fa fa-search'></i>
                </button>
              </div>
            </li>
          </ul>
          ";
      }else{
        $header = "
          <ul class='header-nav header-nav-options'>

          </ul>
          ";
      }

      $content = array("header" => $header);
      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=userManagement";
        </script>
        <script src="js/jquery.js"></script>
        <script src="js/userManagement.js"></script>
        <?php
          if(!isset($_GET['action'])){
            ?>
            <script type="text/javascript">
              $(document).ready(function() {
                  loadTable();
                  setMenuEdit('index');
              });
            </script>
            <div id="content">
      				<section>
      					<div class="section-body contain-lg">
      						<div class="row">
      							<div class="col-lg-12">
      								<div class="card">
      									<div class="card-body no-padding">
      										<div class="table-responsive no-margin">
                            <form id='formUserManagement' name="formUserManagement" action="#">
        											<table class="table table-striped no-margin table-hover" id='tabelBody'>

        											</table>
        											<div class="col-lg-12" style="text-align: right;" id='tabelFooter'>
        												<ul class="pagination pagination-info">
        		                        <li class="active">
        		                            <a href="javascript:void(0);"> prev</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">1</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">2</a>
        		                        </li>
        		                        <li >
        		                            <a href="javascript:void(0);">3</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">4</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">5</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">next </a>
        		                        </li>
        		                    </ul>
        											</div>
                            </form>
      										</div>
      									</div>
      								</div>
      							</div>
      						</div>
      					</div>
      				</section>
      			</div>

            <?php
          }else{
              if($_GET['action'] == 'baru'){
                ?>
                <script type="text/javascript">
                  $(document).ready(function() {
                      setMenuEdit('baru');
                  });
                </script>
                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
          						<div class="row">
          							<div class="col-md-12">
          								<div class="card">
          									<div class="card-body">
          										<form class="form" id ='formUser'>
                                <div class="form-group floating-label">
                                  <?php
                                    $arrayStatus = array(
                                              array('1','MEMBER'),
                                              array('2','ADMIN'),
                                    );
                                    echo cmbArrayEmpty("statusUser","",$arrayStatus,"-- TYPE USER --","class='form-control' ")
                                  ?>
          												<label for="statusUser">TYPE USER</label>
          											</div>
          											<div class="form-group floating-label">
          												<input type="text" class="form-control" id="usernameUser" name='usernameUser'>
          												<label for="usernameUser">Username</label>
          											</div>
          											<div class="form-group floating-label">
          												<input type="password" class="form-control" id="passwordUser" name='passwordUser'>
          												<label for="passwordUser">Password</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="email" id='emailUser' name='emailUser' class="form-control">
        												  <label for="emailUser">Email</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="text" id='namaUser' name='namaUser' class="form-control">
        												  <label for="namaUser">Nama Lengkap</label>
          											</div>
                                <div class="form-group">
          												<input type="number" id='teleponUser' name='teleponUser' class="form-control">
          												<label for="teleponUser">Telepon</label>
          											</div>
                                <div class="form-group floating-label">
          												<textarea name="alamatUser" id="alamatUser" class="form-control" rows="3" placeholder=""></textarea>
          												<label for="alamatUser">Alamat</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="text" id='instansiUser' name='instansiUser' class="form-control">
        												  <label for="instansiUser">Instansi</label>
          											</div>
          										</form>
          									</div>
          									<div class="card-actionbar">
          										<div class="card-actionbar-row">
          											<button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveUser();">Simpan</button>
          											<button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
          										</div>
          									</div>
          								</div>
          							</div>
          						</div>
          					</div>
          				</section>
          			</div>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from users where id = '".$_GET['idEdit']."'"));
                  $getRealPassword = sqlArray(sqlQuery("select * from wordlist where hash = '".$getData['password']."'"));
                  ?>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        setMenuEdit('baru');
                    });
                  </script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
            						<div class="row">
            							<div class="col-md-12">
            								<div class="card">
            									<div class="card-body">
            										<form class="form" id ='formUser'>
                                  <div class="form-group floating-label">
                                    <?php
                                      $arrayStatus = array(
                                                array('1','MEMBER'),
                                                array('2','ADMIN'),
                                      );
                                      echo cmbArrayEmpty("statusUser",$getData['jenis_user'],$arrayStatus,"-- TYPE USER --","class='form-control' ")
                                    ?>
            												<label for="statusUser">TYPE USER</label>
            											</div>
            											<div class="form-group floating-label">
            												<input type="text" class="form-control" id="usernameUser" name='usernameUser' value="<?php echo $getData['username'] ?>">
            												<label for="usernameUser">Username</label>
            											</div>
            											<div class="form-group floating-label">
            												<input type="password" class="form-control" id="passwordUser" name='passwordUser' value="<?php echo $getRealPassword['password'] ?>">
            												<label for="passwordUser">Password</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="email" id='emailUser' name='emailUser' class="form-control" value="<?php echo $getData['email'] ?>">
          												  <label for="emailUser">Email</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="text" id='namaUser' name='namaUser' class="form-control" value="<?php echo $getData['nama'] ?>" >
          												  <label for="namaUser">Nama Lengkap</label>
            											</div>
                                  <div class="form-group">
            												<input type="number" id='teleponUser' name='teleponUser' class="form-control" value="<?php echo $getData['telepon'] ?>">
            												<label for="teleponUser">Telepon</label>
            											</div>
                                  <div class="form-group floating-label">
            												<textarea name="alamatUser" id="alamatUser" class="form-control" rows="3" placeholder=""><?php echo $getData['alamat'] ?></textarea>
            												<label for="alamatUser">Alamat</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="text" id='instansiUser' name='instansiUser' class="form-control" value="<?php echo $getData['instansi'] ?>">
          												  <label for="instansiUser">Instansi</label>
            											</div>
            										</form>
            									</div>
            									<div class="card-actionbar">
            										<div class="card-actionbar-row">
            											<button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditUser(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
            											<button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
            										</div>
            									</div>
            								</div>
            							</div>
            						</div>
            					</div>
            				</section>
            			</div>
                  <?php
              }
          }
         ?>



<?php

     break;
     }

}

?>
