
<!-- <link href="<?php //echo base_url('assets/imagethumbnail.css'); ?>" rel="stylesheet">
 -->    <div class="container-fluid">
    <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Comments</h6>
             <!--  <a href="<?php //echo base_url('adduser?type=3');?>" class="btn btn-primary btn-user " style="float:right;">
                  Add new user
                </a> -->
                <span class="text-success update" style="float:right;">  <?php  echo $this->session->flashdata("update"); ?>
              <span class="text-success newuser" style="float:right;">  <?php  echo $this->session->flashdata("adduser"); ?>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th>Serial No</th>
                      <th>Comment By</th>
                      <th>Comment</th>
                      <th>Time</th>
                     <!--  <th>Action</th> -->
                      <!-- <th>User_name</th>
                      <th>Email</th>
                      <th>Password</th> -->
                      <!-- <th>Picture</th>
                      <th>Status</th>
                      <th>Created_at</th>
                      <th>Updated_at</th> -->
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                  <tr>
                   <th>Serial No</th>
                      <th>Comment By</th>
                      <th>Comment</th>
                      <th>Time</th>
                 <!--  <th>Bio</th>
                  <th>User Name</th> -->
                      <!-- <th>User_name</th>
                      <th>Email</th>
                      <th>Password</th> -->
                     <!--  <th>Picture</th>
                      <th>Status</th>
                      <th>Created_at</th>
                      <th>Updated_at</th> -->
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  <?php $a=1;
                    foreach($comments as $userData){ 
                  ?>
                    <tr>
                    <td><?php echo $a++;?></td>
                    <td><?php echo $userData->full_name;?></td>
                   
               
                     <td><?php echo $userData->comment;?></td>
                    <td><?php echo $userData->created_at;?></td>
                 


                    <td><?php if($userData->status==1 ){ ?> <a  data-src="<?php echo $userData->comment_id; ?>" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $userData->comment_id; ?>" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a> <?php } ?></td>
                    </tr>
                      <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
</div>
 <script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script> 
<script type="text/javascript">

$(document).ready(function(){
$(document).on('click', '.enable', function(){
    var commentId=$(this).attr('data-src');
    var status=$(this).attr('xyz');
if(status==0){
      var answer = confirm('Are you sure you want to Block this Comment?');
    }else{
       var answer = confirm('Are you sure you want to Unblock this Comment?');
    }
if (answer)
{
  console.log('yes');
}
else
{
  console.log('cancel');
  return false;
}
      var it=$(this);
       $.ajax({
        url: '<?php echo base_url('commentblock');?>',
        type:"post",
        data: {comment_id:commentId,ustatus:status},
        success:function(res){
          if(res==1){
   it.parent().html('<a  data-src="'+commentId+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
          }
          else{     
            it.parent().html('<a  data-src="'+commentId+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');

          }
  
        }

  });
});
});
</script>