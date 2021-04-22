<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Login</title>

  <!-- Custom fonts for this template-->
  <!-- <link href="<?php //echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->

  <!-- Custom styles for this template-->
  <!-- <link href="<?php //echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet"> -->

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block "><img src="<?php echo base_url('admin/').$user->picture;?>" alt="img" data-toggle="modal" data-target="#myModal" style="max-width:90%;" ></div>
              <div class="col-lg-6">
                <div class="p-5">
                   <div class="text-right">
                     <span class="text-success text-right updatemsg">
                    <?php  echo $this->session->flashdata("update"); ?> </span>
                  </div>

                
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Admin Profile</h1>
                  </div>
                  <form  method="post" class="adminlogin">
                
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="fullname" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Admin Name..." value="<?php echo  set_value('fullname',$user->full_name);?>">
                      <span class="text-danger" >
                   
                      <span class="text-danger" >
                      <?php echo form_error('fullname');?></span>
                    </div>
                    <div class="form-group">
                      <input type="text" name="username" class="form-control form-control-user" id="exampleInputPassword" placeholder="User Name" value="<?php echo  set_value('username',$user->user_name); ?>" >
                      <span class="text-danger" >
                    
                      <?php echo form_error('username');?></span>
                    </div>
                    <span class="text-danger " >
                    <?php  echo $this->session->flashdata("username"); ?> </span>
                    <!-- <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div> -->
                    <input type="submit" name="submit" value="Change Profile"  class="btn btn-primary btn-user btn-block">
                   
                    <!-- <hr> -->
                    
                   
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?php echo base_url('users');?>">Cancel</a>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        
<form class="form-horizontal" method="post" action="<?php echo base_url('updatepicture');?>" enctype="multipart/form-data">


	<input type="hidden" name="oldimage" value="<?php echo $user->picture;?>">
    <div class="form-group">
       <img class="img-thumbnail" name="thumb" src="<?php echo base_url('admin/').$user->picture;?>">
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Update Image</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="newimage">
        </div>
    </div>
 
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Update</button>
        </div>
    </div>
</form>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>
  </div>


  
</body>

</html>

<!-- <script type="text/javascript">
  $(document).ready(function(){
  $(function() {
        $('#exampleInputPassword').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
            }
        });
});
});
</script>
 -->