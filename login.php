
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IMS | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>IMS</b>-V2</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Semangat Bekerja</p>

    <form action="cek_login.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        <span class="fa fa-user form-control-feedback" ></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group">
        <span class="pull-right"><button class="btn btn-primary btn-flat" type='submit'  name="login"><i class="fa fa-sign-in"></i> Login</button></span>
        <span class="clearfix"></span>
      </div>


    </form>
    <?php if(isset($_GET['status']) AND isset($_GET['error'])){ ?>
    <div class="callout callout-danger">
      <h4>Error Status : <?php echo $_GET['status']; ?></h4>
      <p><?php echo $_GET['error']; ?></p>
    </div>
  <?php } ?>
    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="js/bootstrap.min.js"></script>

</body>
</html>
