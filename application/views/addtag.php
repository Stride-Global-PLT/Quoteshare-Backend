

  <div class="container">

<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <!-- Nested Row within Card Body -->
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Add New Tag</h1>
          </div>
          <form class="user" method="post"  enctype="multipart/form-data">
         
            <div class="form-group">
            <label class="mdb-main-label">Tag</label>
              <input type="text" name="tag" class="form-control form-control-user" id="exampleInputEmail" placeholder="Please enter the tag">
              <span class="text-danger"><?php echo form_error('tag'); ?></span>
              <span class="text-success"><?php  echo $this->session->flashdata("tag"); ?></span>
            </div>
            
           
           <input type="submit" class="btn btn-primary btn-user btn-block" name="submit">
            <!-- <a href="" class="btn btn-primary btn-user btn-block">
              Update User Account
            </a> -->
            <hr>
            
          </form>
          <hr>
          <div class="text-center">
            <a class="small" href="<?php echo base_url('tags'); ?>">Cancel</a>
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
