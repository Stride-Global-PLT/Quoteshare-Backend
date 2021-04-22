
    <div class="container-fluid">
    <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Authors</h6>
              <a href="<?php echo base_url('adduser?type=2');?>" class="btn btn-primary btn-user " style="float:right;">
                  Add new user
                </a>
                <span class="text-success update" style="float:right;">  <?php  echo $this->session->flashdata("update"); ?></span>
              <span class="text-success newuser" style="float:right;">  <?php  echo $this->session->flashdata("adduser"); ?></span>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th>Serial No</th>
                      <th>Name</th>
                      <th>User Type</th>
                      <th>Bio</th>
                      <th>User Name</th>
                      <!-- <th>User_name</th>
                      <th>Email</th>
                      <th>Password</th> -->
                      <th>Picture</th>
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
                  <th>Bio</th>
                  <th>User Name</th>
                      <!-- <th>User_name</th>
                      <th>Email</th>
                      <th>Password</th> -->
                      <th>Picture</th>
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
                        //echo "hai";
                         $imagePath = ('./uploads/quotes/').$userData->picture;
                      }else{
                           $imagePath = ('./uploads/quotes/').'no-image.jpg';
                      }

                    ?>
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
                     <td><?php echo $userData->bio;?></td>
                    <td><?php echo $userData->user_name;?></td>

                    <td><img src="<?php echo $imagePath;?>" alt="img" data-toggle="modal"  style="max-width:90%;" class="imgsize imageclick" data-src="<?php echo $imagePath;?>"></td>
                  <!--   <td><img class="imgsize" alt src="<?php //echo $imagePath; ?>"></td> -->
                  


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
    <!-- Modal end -->

</div>
</div>
</div>
 <script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script> 
<script type="text/javascript">
$(document).ready(function(){

$(document).on('click', '.enable', function(){
    var user_id=$(this).attr('data-src');
    var status=$(this).attr('xyz');
if(status==0){
      var answer = confirm('Are you sure you want to Block this Author?');
    }else{
       var answer = confirm('Are you sure you want to Unblock this Author?');
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
            it.parent().html('<a  data-src="'+user_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
          // it.parents("tr").delay(2000).fadeOut();
          //   it.parents("tr").fadeOut(2000);

          }
  // hide.parents("tr").delay(5000).fadeOut();
  // hide.parents("tr").fadeOut(2000);
        }

  });


});

//On image click

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


  //       url: '<?php //echo base_url('blockuser');?>',
  //       type:"post",
  //       data: {userid:user_id,ustatus:status},
  //       success:function(xyz){
  //         if(xyz==1){
  //  it.parent().html('<a  data-src="'+user_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
  //         }
  //         else{
  //           it.parent().html('<a  data-src="'+user_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
  //         // it.parents("tr").delay(2000).fadeOut();
  //         //   it.parents("tr").fadeOut(2000);

  //         }
  // // hide.parents("tr").delay(5000).fadeOut();
  // // hide.parents("tr").fadeOut(2000);
  //       }



});
//


});
</script>