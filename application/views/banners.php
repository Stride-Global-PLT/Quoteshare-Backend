<style type="text/css">
  img.expand { width: 10em; }
</style>
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Banner</h6>
      <a href="<?php echo base_url('AddnewBanner');?>" class="btn btn-primary btn-user " style="float:right;">Add New Banner</a>
      <span class="text-success update" style="float:right;">  <?php  echo $this->session->flashdata("update"); ?>
      <span class="text-success newuser" style="float:right;">  <?php  echo $this->session->flashdata("adduser"); ?>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Serial No</th>
              <th>Category</th>
              <th>Banner</th>
               <th>Banner Url</th>
             <!--  <th>Email</th> -->
              <th>Is Featured</th>
              <th>Status</th>
              <th>Created_on</th>
              <th>Updated_on</th>
             <!--  <th>Created_at</th>
              <th>Updated_at</th> -->
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
            <th>Serial No</th>
              <th>Category</th>
              <th>Banner</th>
               <th>Banner Url</th>
             <!--  <th>Email</th> -->
              <th>Is Featured</th>
              <th>Status</th>
              <th>Created_on</th>
              <th>Updated_on</th>
             <!--  <th>Created_at</th>
              <th>Updated_at</th> -->
              <th>Action</th>
            </tr>
          </tfoot>
        <tbody>
        
        <?php $a=1;
        foreach($banner as $userData){
          $path=('./uploads/banner/').$userData->banner_image;
    
          if(file_exists($path) && !empty($userData->banner_image)){
            $imagePath = ('./uploads/banner/').$userData->banner_image;
          }else{
            $imagePath = ('./uploads/quotes/').'no-image.jpg';
          } ?>
          
      <tr>
                <td><?php echo $a++;?></td>
                <td><?php echo $userData->banner_categoryname;?></td>
                   
                  

                    <!-- modal calling -->
<!--                     <div class="col-lg-6 d-none d-lg-block ">
                                                                                        //imgsize imageclick
 -->                      <td><img src="<?php echo $imagePath;?>" alt="img" data-toggle="modal" class="expand" data-src="<?php echo $imagePath;?>"></td>
                            <td><a class="text-decoration-none" href="<?php echo $userData->banner_url;?>" target="_blank"><?php echo $userData->banner_url;?></a></td>
<!--                     </div>
 -->                    <!-- calling end -->

                    <td><?php if($userData->is_feature==1 ){ ?> <a  data-src="<?php echo $userData->banner_id; ?>" category_id="<?php echo $userData->banner_categoryid; ?>" xyz="0" is_active="<?php echo $userData->status; ?>" class="btn btn-success feature" href="javascript:void(0)">Is_feature</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $userData->banner_id; ?>" category_id="<?php echo $userData->banner_categoryid; ?>" xyz="1" is_active="<?php echo $userData->status; ?>" class="btn btn-danger feature" href="javascript:void(0)">feature</a> <?php } ?></td>



                    <td><?php if($userData->status==1 ){ ?> <a  data-src="<?php echo $userData->banner_id; ?>" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $userData->banner_id; ?>" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a> <?php } ?></td>
                      <td><?php echo $userData->created_at;?></td>
                      <td><?php echo $userData->updated_at;?></td>
                      <td><a href="<?php echo base_url('edit_banner/'.$userData->banner_id);?>"><i class="fas fa-edit text-success"></i></a>
                       <!--  <a href="<?php //echo base_url('edituser/'.urlencode(base64_encode($userData->banner_id)));?>"><i class="fas fa-edit text-success"></i></a> -->
                       <!--  <a href="<?php //echo base_url('edituser/'.urlencode(base64_encode($userData->banner_id)));?>"><i class="fa fa-trash text-danger"></i></a> -->
                           <a href="#" class="trashbanner" data_src="<?php echo $userData->banner_id; ?>"><i class="fa fa-trash text-danger"></i></a>
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
    var banner_id=$(this).attr('data-src');
    var status=$(this).attr('xyz');
  if(status==0){
      var answer = confirm('Are you sure you want to Block this Banner?');
    }else{
       var answer = confirm('Are you sure you want to Unblock this Banner?');
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
        url: '<?php echo base_url('blockBanner');?>',
        type:"post",
        data: {bannerid:banner_id,ustatus:status},
        success:function(xyz){
          if(xyz==1){
      it.parent().html('<a  data-src="'+banner_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>'); 
      location.reload(true);

          }
          else{
  //           it.parents("tr").animate({ backgroundColor: "#003" }, "slow")
  // .animate({ opacity: "hide" }, "slow");
            it.parent().html('<a  data-src="'+banner_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
            location.reload(true);
          }
//   // hide.parents("tr").delay(5000).fadeOut();
//   // hide.parents("tr").fadeOut(2000);
        }

  });
});
//is feature
$(document).on('click', '.feature', function(){
    var banner_id=$(this).attr('data-src');
    var category_id=$(this).attr('category_id');
    var status=$(this).attr('xyz');
    var active=$(this).attr('is_active');
if(active==0){
  alert('Please first unblock the banner');
  return false;
}

  if(status==0){
      var answer = confirm('Are you sure you want to remove from Is_feature?');
    }else{
       var answer = confirm('Are you sure you want to add this Is_feature?');
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
        url: '<?php echo base_url('is_featured');?>',
        type:"post",
        data: {bannerid:banner_id,cate_id:category_id,ustatus:status},
        success:function(response){
          //alert(xyz.banner_id);
         var returnedData = JSON.parse(response);
          //alert(returnedData.banner_id);

          if(returnedData.status==1){
      it.parent().html('<a  data-src="'+banner_id+'" is_active="'+returnedData.status+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Is_feature</a>'); 
        location.reload(true); 
          }
          else{
  //           it.parents("tr").animate({ backgroundColor: "#003" }, "slow")
  // .animate({ opacity: "hide" }, "slow");
            it.parent().html('<a  data-src="'+banner_id+'" is_active="'+returnedData.status+'"  xyz="1" class="btn btn-danger enable" href="javascript:void(0)">feature</a>');
             location.reload(true); 
          }
//   // hide.parents("tr").delay(5000).fadeOut();
//   // hide.parents("tr").fadeOut(2000);
        }

  });
});
// 


//Show modal with picture
$(document).on('click', '.expand', function(){
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
//End Modal

// This is the delete ajax function 
$(document).on('click', '.trashbanner', function(){
    var banner_id=$(this).attr('data_src');
      var it=$(this);
    if(banner_id){
      var answer = confirm('Are you sure you want to delete the banner?');
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
        url: '<?php echo base_url('adminpanel/deleteBanner');?>',
        type:"post",
        data: {bannerid:banner_id},
        success:function(xyz){
          if(xyz==1){
         it.parents('tr').hide(); 
         //location.reload(true); 
          }
        }
  });      
});
// 
});
</script>