<style>
/*
 style for file upload
 
 */
 .file-upload .file-select{display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
 .file-upload .file-select .file-select-button{background:#dce4ec;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
 .file-upload .file-select .file-select-name{line-height:40px;display:inline-block;padding:0 10px;}
 .file-upload .file-select:hover{border-color:#34495e;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
 .file-upload .file-select:hover .file-select-button{background:#34495e;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
 .file-upload.active .file-select{border-color:#3fa46a;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
 .file-upload.active .file-select .file-select-button{background:#3fa46a;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
 .file-upload .file-select input[type=file]{z-index:100;cursor:pointer;position:absolute;height:100%;width:100%;top:0;left:0;opacity:0;filter:alpha(opacity=0);}
 .file-upload .file-select.file-select-disabled{opacity:0.65;}
 .file-upload .file-select.file-select-disabled:hover{cursor:default;display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;margin-top:5px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
 
 .file-upload .file-select {
height:50px;
color:  #6e707e;
 border-radius: 30px;
}
.file-upload .file-select .file-select-button{
    height:50px;
    line-height:50px;
}

.file-upload .file-select:hover{
    border-color: #4e73df;
}
.file-upload .file-select:hover .file-select-button {
    background: #4e73df;
}
    
    
</style>
  <div class="container">
<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <!-- Nested Row within Card Body -->
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block"><img src="<?php echo base_url().'uploads/apppic/'.'Quoteshare-logo.png'; ?>" style= "height: auto; width: 100%; margin-top: 50%;"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Add New Banner!</h1>
          </div>
          <form class="user" method="post"  enctype="multipart/form-data">
            <div class="form-group row">
           
              <div class="col-sm-12 mb-3 mb-sm-0">
              <label class="mdb-main-label">Banner Category</label>
              <select class="custom-select my-1 mr-sm-2 user_type " id="inlineFormCustomSelectPref" name="banner_category" value=<?php echo set_value('banner_category'); ?>>
                  <option value="" disabled selected>Please Choose Banner Catgory</option>
                  <option value="101"<?php echo set_select('banner_category','101', ( !empty($data) && $data == "101" ? TRUE : FALSE )); ?>>For You</option>
                   <option value="102"<?php echo set_select('banner_category','102', ( !empty($data) && $data == "102" ? TRUE : FALSE )); ?>>Latest</option>
                    <option value="103"<?php echo set_select('banner_category','103', ( !empty($data) && $data == "103" ? TRUE : FALSE )); ?>>Top</option>
                  <option value="1"<?php echo set_select('banner_category','1', ( !empty($data) && $data == "1" ? TRUE : FALSE )); ?>>Health</option>
                  <option value="2" <?php echo set_select('banner_category','2', ( !empty($data) && $data == "2" ? TRUE : FALSE )); ?>>Business</option>
                  <option value="3"<?php echo set_select('banner_category','3', ( !empty($data) && $data == "3" ? TRUE : FALSE )); ?>>Mindfulness</option>
                  <option value="4"<?php echo set_select('banner_category','4', ( !empty($data) && $data == "4" ? TRUE : FALSE )); ?>>Religion</option>
                  <option value="5" <?php echo set_select('banner_category','5', ( !empty($data) && $data == "5" ? TRUE : FALSE )); ?>>Fitness</option>
                  <option value="6"<?php echo set_select('banner_category','6', ( !empty($data) && $data == "6" ? TRUE : FALSE )); ?>>Money</option>
                   <option value="7"<?php echo set_select('banner_category','7', ( !empty($data) && $data == "7" ? TRUE : FALSE )); ?>>Love</option>
                  <option value="8" <?php echo set_select('banner_category','8', ( !empty($data) && $data == "8" ? TRUE : FALSE )); ?>>Relationship</option>
                  <option value="9"<?php echo set_select('banner_category','9', ( !empty($data) && $data == "9" ? TRUE : FALSE )); ?>>Investing</option>
                  <option value="10"<?php echo set_select('banner_category','10', ( !empty($data) && $data == "10" ? TRUE : FALSE )); ?>>Mindset</option>
              </select>
                  <span class="text-danger" ><?php echo form_error('banner_category'); ?></span>
                <!-- <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="picture"> -->
              </div>
             
            </div>
           
            
          
            <div class="form-group">
              <label class="mdb-main-label">Picture </label>
 <!-- <input type="file"  name="user_image" class="form-control form-control-user"  id="exampleLastName"> -->

            <div class="file-upload" >
           <div class="file-select" >
             <div class="file-select-button" id="fileName" >Choose File</div>
             <div class="file-select-name" id="noFile" >No file chosen...</div>
           <input type="file" name="user_image" id="chooseFile">
            </div>
          </div>
          <br>
          <span class="text-danger" ><?php echo form_error('user_image'); ?></span>



            <input type="hidden" name="user_Oldimage">
            <input type="hidden" name="user_id">
            
           <div class="form-group">
              <label class="mdb-main-label">Banner url </label>
          <input type="text"  name="url" class="form-control form-control-user"  id="exampleLastName" value="<?php echo set_value('url'); ?>">
            <span class="text-danger" >  <?php echo form_error('url'); ?></span>
            </div>
            
           <input type="submit" class="btn btn-primary btn-user btn-block" name="submit">
            <!-- <a href="" class="btn btn-primary btn-user btn-block">
              Update User Account
            </a> -->
         <!--    <hr> -->
            
          
          <hr>
          <div class="text-center">
            <?php if($_GET['type']  == 1) {
              $user='Normalusers';
            }elseif ($_GET['type']  == 2) {
             $user='Authors';
            }elseif ($_GET['type']  == 3) {
            $user='books';     
            }else{
              $user='users';
            }
                    ?>
            <a class="small" href="<?php echo base_url('banners'); ?>">Cancel</a>
         </form>
          </div>
          <!-- <div class="text-center">
            <a class="small" href="login.html">Already have an account? Login!</a>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script> 
<script type="text/javascript">
     $(document).ready(function(){
            
      var timer = null;
    $('.bio').keyup(function(){
           clearTimeout(timer); 
           timer = setTimeout(getlength, 10); 
    });
          function getlength()
          {
            var min=150;
            var bio= $('.bio').val().length;
            var type= $('.user_type').val();
              // alert(bio);
              // alert(type);

              if(type==3 && bio<=150){
              $('.bio').attr('minlength',"150");
              // $( ".minlength").show();
              // $( ".minlength").fadeOut(3000);
            
              }else{
                $('.bio').removeAttr('minlength',"150");
              }
      }
     /// for file upload
   });
   $('#chooseFile').bind('change', function () {
  var filename = $("#chooseFile").val();
  if (/^\s*$/.test(filename)) {
    $(".file-upload").removeClass('active');
    $("#noFile").text("No file chosen..."); 
  }
  else {
    $(".file-upload").addClass('active');
    $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
  }
});

        </script>