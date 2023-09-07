<?php
session_start();
if(!isset($_SESSION['username'])){
  header("location:login.php");
  exit();
}else{
include_once 'config/config.php';
include "inc/fungsi.php";
$_SESSION['page'] = empty($_GET['page']) ? "" : $_GET['page'];
$_SESSION['page_level'] = getPageLevel();
$_SESSION['page_id'] = getIdMenu($_SESSION['page']);
$_SESSION['publisher'] = 0;
$_SESSION['p-a'] = array('0','1');
$_SESSION['authors'] = 1;
$_SESSION['view'] = 1;
if(isset($_GET['Notif']) && $_GET['Notif'] == "ReadNotif"){
  ReadNotif($_SESSION['page']);
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IMS | ISMA</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="css/datepicker3.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/_all-skins.min.css">
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <link rel="stylesheet" href="lib/select2-bootstrap/dist/select2.min.css">
  <link rel="stylesheet" href="lib/select2-bootstrap/dist/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/main.css">
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCryjVwD49CIhNml46CTmZDdbc4HPsulYo"></script> -->
 
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  
</head>

<body class="hold-transition skin-purple sidebar-mini">
<input type='hidden' id='PageLevel' value="<?php echo $_SESSION['page_level'] ?>" />
<input type='hidden' id='PageIdUser' value="<?php echo $_SESSION['Id'] ?>" />
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IMS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IMS </b> SDM</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning " id='CountNotif'></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id='NotifHeder'></li>
              <li id='ListNotif'></li>
              <li class='footer' id='FooterNotif'><a href='index.php?page=Notifikasi'>Lihat Semua</a></li>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/avatar.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nama_user']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="img/avatar.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nama_user']; ?>
                  <small><?php echo $_SESSION['Jabatan']; ?></small>
                </p>
              </li>
              <li class="user-footer">
               
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-danger btn-flat"><i class="fa fa-sign-out"></i> Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="img/avatar.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['nama_user']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php include_once 'menu.php'; ?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <?php include_once 'konten.php'; ?>

    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2020 <a href="#">INTAN SEJHATERA UTAMA</a>. </strong> All rights
    reserved.
  </footer>
</div>

<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/chart/Chart.min.js" type="text/javascript"></script>
<script src="lib/select2-bootstrap/dist/select2.full.js"></script>
<script src="js/fastclick.js"></script>
<script src="js/pdfobject.min.js"></script>
<script src="js/adminlte.min.js"></script>
<script src="js/main.js"></script>

<?php
  if($page != null){
    $page = str_replace("../", "", addslashes($page));
    $files = "inc/".$page."/main.js";
    if(file_exists($files)){
      echo "<script src='".$files."'></script>";
    }
  }else{
    echo "<script src='inc/home.js'></script>";
  }
?>


<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
    StopLoad();
  })
</script>

</body>
</html>
<?php } ?>