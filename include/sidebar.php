<?php
  $getDataUser = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."'"));
 ?>
 <!-- BEGIN MENUBAR-->
 <div id="menubar" class="menubar-inverse ">
   <div class="menubar-fixed-panel">
     <div>
       <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
         <i class="fa fa-bars"></i>
       </a>
     </div>
     <div class="expanded">
       <a href="html/dashboards/dashboard.html">
         <span class="text-lg text-bold text-primary ">MATERIAL&nbsp;ADMIN</span>
       </a>
     </div>
   </div>
   <div class="menubar-scroll-panel">

     <!-- BEGIN MAIN MENU -->
     <ul id="main-menu" class="gui-controls">
       <?php
                    $page = @$_GET['page'];
                    if ($page == "informasi") {
                        $informasiActive = "active";
                    }elseif ($page == "produk") {
                        $produkActive = "active";
                    }elseif ($page == "acara") {
                        $acaraActive = "active";
                    }elseif ($page == "slider") {
                        $sliderActive = "active";
                    }elseif ($page == "setting") {
                        $settingActive = "active";
                    }elseif ($page == "chating") {
                        $chatingActive = "active";
                    }elseif ($page == "lowonganKerja") {
                        $lowonganKerjaActive = "active";
                    }elseif ($page == "team") {
                        $teamActivce = "active";
                    }else{
                        $userManagement = "active";
                    }

                        echo "
                                <li>
                                  <a href='?page=userManagement' class='$userManagement'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>User Management</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=produk' class='$produkActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Produk</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=informasi' class='$informasiActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Informasi</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=acara' class='$acaraActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Acara</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=acara' class='$sliderActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Slider</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=lowonganKerja' class='$lowonganKerjaActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Lowongan Kerja</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=team' class='$teamActivce'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Team</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=setting' class='$settingActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Setting</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=chating' class='$chatingActive'>
                                    <div class='gui-icon'><i class='md md-web'></i></div>
                                    <span class='title'>Live Chat</span>
                                  </a>
                                </li>



                              ";

                     ?>
     </ul>
     <div class="menubar-foot-panel">
       <small class="no-linebreak hidden-folded">
         <span class="opacity-75">Copyright &copy; 2014</span> <strong>CodeCovers</strong>
       </small>
     </div>
   </div><!--end .menubar-scroll-panel-->
 </div><!--end #menubar-->
 <!-- END MENUBAR -->
