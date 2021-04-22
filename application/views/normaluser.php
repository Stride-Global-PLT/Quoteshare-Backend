<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Users</h6>
      <a href="<?php echo base_url('adduser?type=1');?>" class="btn btn-primary btn-user " style="float:right;">Add new user</a>
      <span class="text-success update" style="float:right;">  <?php  echo $this->session->flashdata("update"); ?>
      <span class="text-success newuser" style="float:right;">  <?php  echo $this->session->flashdata("adduser"); ?>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Serial No</th>
              <th>Name</th>
              <th>User Type</th>
              <th>Email</th>
              <th>Bio</th>
              <th>User Name</th>
              <th>Profile</th>
              <th>Status</th>
              <th>Created_at</th>
              <th>Updated_at</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Serial No</th>
              <th>Name</th>
              <th>User Type</th>
              <th>Email</th>
              <th>Bio</th>
              <th>User Name</th>
              <th>Profile</th>
              <th>Status</th>
              <th>Created_at</th>
              <th>Updated_at</th>
              <th>Action</th>
            </tr>
          </tfoot>
        <tbody>
        
        <?php $a=1;
        foreach($users as $userData){
          $path=('./uploads/quotes/').$userData->picture;
    
          if(file_exists($path) && !empty($userData->picture)){
            $imagePath = ('./uploads/quotes/').$userData->picture;
          }else{
            $imagePath = ('./uploads/quotes/').'no-image.jpg';
          } ?>
          
      <tr>
                <td><?php echo $a++;?></td>
                <td><?php echo $userData->full_name;?></td>
                   
                    <td><?php if($userData->user_type==1 ){
                       echo 'Normal' ; 
                      }
                       elseif($userData->user_type==2){
                      echo 'Author';
                    } 
                    elseif($userData->user_type==3){
                      echo 'Book';
                    }
                    else{
                      echo 'Tag';
                    }
                    ?></td>
                    <td> <?php echo $userData->email;?></td>
                     <td><?php echo $userData->bio;?></td>
                    <td><?php echo $userData->user_name;?></td>
                    <!-- <td><img class="imgsize" alt  src="<?php //echo $imagePath; ?>"></td> -->


                    <!-- modal calling -->
<!--                     <div class="col-lg-6 d-none d-lg-block ">
 -->                      <td><img src="<?php echo $imagePath;?>" alt="img" data-toggle="modal" class="imgsize imageclick" data-src="<?php echo $imagePath;?>"></td>

<!--                     </div>
 -->                    <!-- calling end -->



                    <td><?php if($userData->is_active==1 ){ ?> <a  data-src="<?php echo $userData->user_id; ?>" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $userData->user_id; ?>" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a> <?php } ?></td>
                      <td><?php echo $userData->created_at;?></td>
                      <td><?php echo $userData->updated_at;?></td>
                      <td><a href="<?php echo base_url('edituser/'.urlencode(base64_encode($userData->user_id)));?>"><i class="fas fa-edit"></i></a>
                     </td>
                    </tr>
                      <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
</div>
<!-- Modal -->
<div class="modal fade imgmodal" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
    <div class="form-group">
       <img class="img-thumbnail popup" name="thumb" src="">
    </div>
  </div>
</div>
</div>

    <!-- Modal end -->





<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
 <script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script> 
<script type="text/javascript">
    $(document).ready(function(){
      
        setTimeout(function() {
            $('.update').fadeOut('slow');
        }, 2000);

        setTimeout(function() {
            $('.newuser').fadeOut('slow');
        }, 2000);
$(document).on('click', '.enable', function(){
    var user_id=$(this).attr('data-src');
    var status=$(this).attr('xyz');
  if(status==0){
      var answer = confirm('Are you sure you want to Block this user?');
    }else{
       var answer = confirm('Are you sure you want to Unblock this user?');
    }
if (answer)
{
  console.log('yes');
}
else
{
  console.log('cancel');
  return false();
}
      var it=$(this);
       $.ajax({
        url: '<?php echo base_url('blockuser');?>',
        type:"post",
        data: {userid:user_id,ustatus:status},
        success:function(xyz){

          if(xyz==1){
      it.parent().html('<a  data-src="'+user_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
          }
          else{
  //           it.parents("tr").animate({ backgroundColor: "#003" }, "slow")
  // .animate({ opacity: "hide" }, "slow");
            it.parent().html('<a  data-src="'+user_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
          }
  // hide.parents("tr").delay(5000).fadeOut();
  // hide.parents("tr").fadeOut(2000);
        }

  });
});
//Show modal with picture
$(document).on('click', '.imageclick', function(){
    var image=$(this).attr('data-src');
    //var status=$(this).attr('xyz');
    // alert(image);
      var it=$(this);
      // $(".popup").attr("src",+image+);
      //   $("#modal").show();

        $(".popup").attr("src",image);
        //$(".modal").show();
         $(".imgmodal").modal('show');
});
///
});
</script>