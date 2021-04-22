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
          <div class="col-lg-5 d-none d-lg-block"><img src="<?php echo base_url().'uploads/quotes/'.$userDetail->picture; ?>" class="responsive"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Edit User Account!</h1>
              </div>
              <form class="user" method="post"  enctype="multipart/form-data">
                <div class="form-group row">
               
                  <div class="col-sm-6 mb-3 mb-sm-0">
                  <label class="mdb-main-label">User Type</label>
                  <select class="custom-select my-1 mr-sm-2 usertype" id="inlineFormCustomSelectPref" name="user_type">
                        <option value="" disabled selected>Please Choose User Type</option>
                        <option value="1" <?php if($userDetail->user_type=='1') echo 'selected';?> >Normal</option>
                        <option value="2" <?php if($userDetail->user_type=='2') echo 'selected';?>>Author</option>
                        <option value="3" <?php if($userDetail->user_type=='3') echo 'selected';?>>Book</option>
                       <!--  <option value="4" <?php //if($userDetail->user_type=='4') echo 'selected';?>>Tag</option> -->
                    </select>
                 <span class="text-danger"> <?php echo form_error('user_type'); ?></span>
 
                    <!-- <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="picture"> -->
                  </div>
                  <div class="col-sm-6">
                  <label class="mdb-main-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control form-control-user" id="exampleLastName" placeholder="Full Name" value="<?php echo $userDetail->full_name; ?>">
                   <span class="text-danger"> <?php echo form_error('full_name'); ?></span>
                  </div>
                </div>
                <div class="form-group">
                <label class="mdb-main-label">Bio</label>
                <textarea name="bio" class="form-control bio" aria-label="With textarea"><?php echo $userDetail->bio; ?><?php echo set_value('bio'); ?> </textarea>
                  <span class="text-danger"><?php echo form_error('bio'); ?></span>
                  <span class="text-danger minlength" style="display: none;">minimum length of book Bio should be 150</span>
                  <?php  echo $this->session->flashdata("error"); ?>
                </div>
                <div class="form-group">
                  <label class="mdb-main-label">Picture </label>

<!-- New Input file -->
  <div class="file-upload" >
           <div class="file-select" >
             <div class="file-select-button" id="fileName" >Choose File</div>
             <div class="file-select-name" id="noFile" >No file chosen...</div>
           <input type="file" name="user_image" id="chooseFile">

            </div>
          </div>
          <br>
<!--  -->
<!-- 
     <input type="file"  name="user_image" class="form-control form-control-user"  id="exampleLastName"> -->
                </div>
                <input type="hidden" name="user_Oldimage" value="<?php echo $userDetail->picture; ?>">
                <input type="hidden" name="user_id" value="<?php echo $userDetail->user_id; ?>">
               
               <input type="submit" class="btn btn-primary btn-user btn-block" name="submit">
                <!-- <a href="" class="btn btn-primary btn-user btn-block">
                  Update User Account
                </a> -->
               <!--  <hr> -->
          <?php if($userDetail->user_type  == 1) {
              $user='Normalusers';
            }elseif ($userDetail->user_type == 2) {
             $user='Authors';
            }elseif ($userDetail->user_type  == 3) {
            $user='books';     
            }else{
              $user='users';
            }
                ?>
              <!--</form>-->
              <hr>
              <div class="text-center">
                <a class="small" href="<?php echo base_url($user); ?>">Cancel</a>
              </div>
              </form>
              <!-- <div class="text-center">
                <a class="small" href="login.html">Already have an account? Login!</a>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
 <!--  <script src="<?php //echo base_url('assets/jquery.min.js'); ?>"></script> -->
 
 <!-- <script type="text/javascript">
 $(document).ready(function(){
  $('.bio').on('keyup', function() {
   //alert( this.value );
    var type=$('.usertype').val();
    var bio=$('.bio').val().length;
    var bio1=jQuery('.bio').val().length;
   
    var timer = null;
    $('.usertype').change(function(){
           clearTimeout(timer); 
           timer = setTimeout(getlength, 1500); 
           
    });
    
    function getlength() 
{
  var min=150;
    var bio=$('.bio').val().length;
    var type=$('.usertype').val();
    if(type==3 && bio<=150){
      $('.bio').attr('minlength',"140")
    $( ".minlength").show();
    $( ".minlength").fadeOut(3000);
    return false;
    }
   // alert('Minlength 150');
}


});

//  });
   
   </script> -->