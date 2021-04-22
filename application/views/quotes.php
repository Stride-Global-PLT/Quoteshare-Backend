<style type="text/css">
  .modal-autoheight .modal-body {
  position: relative;
  overflow-y: auto;
  min-height: 100px !important;
  max-height: 600px !important;
}
</style>
    <div class="container-fluid">
    <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Quotes</h6>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th>Serial No</th>
                      <th>Category</th>
                      <th>User Name</th>
                      <th>Quote Image</th>
                      <th>Quote</th>
                      <th>Caption</th>
                      <th>Author</th>
                      <th>Book</th>
                      <th>Comments</th>
                      <th>Created_at</th>
                      <th>Updated_at</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                  <tr>
                      <th>Serial No</th>
                      <th>Category</th>
                      <th>User Name</th>
                      <th>Quote Image</th>
                      <th>Quote</th>
                      <th>Caption</th>
                      <th>Author</th>
                      <th>Book</th>
                       <th>Comments</th>
                      <th>Created_at</th>
                      <th>Updated_at</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  <?php $a=1;
                    foreach($quotes as $userData){
                      //COde for show default image
                      $path=('./uploads/quotes/').$userData->image;
    
                     if(file_exists($path) && !empty($userData->image)){
                        //echo "hai";
                         $imagePath = ('./uploads/quotes/').$userData->image;
                      }else{
                           $imagePath = ('./uploads/quotes/').'no-image.jpg';
                      }
                  ?>
                    <tr>
                    <td><?php echo $a++;?></td>
                    <td><?php echo $userData->CategoryName;?></td>
                    <!-- It will show the user name of feed user -->
                     <td><?php echo $userData->user_name;?></td>


                    <!--  <td><img class="imgsize" alt src="<?php echo $imagePath; ?>"></td> -->
                    <td><img src="<?php echo $imagePath;?>" alt="img" data-toggle="modal" class="imgsize imageclick" data-src="<?php echo $imagePath;?>"></td>
                    <td><?php echo $userData->quote;?></td>
                    <td><?php echo $userData->caption;?></td>
                    <td><?php echo $userData->author_name;?></td>
                    <td><?php echo $userData->booker_name;?></td>

                   <!--  <td><a href="<?php //echo base_url()?>" data-toggle="modal" data-target="#myModal" style="max-width:90%; height: 80%;" > <?php //echo $userData->totalComments;?></a></td> -->
 <!-- <td><a href="<?php //echo base_url('edituser/'.urlencode(base64_encode($userData->user_id)));?>"><i class="fas fa-edit"></i></a>
                     </td>  -->

                     <!--  <td><a href="<?php //echo base_url('showcomments/'.$userData->feed_id); ?>"><?php //echo $userData->totalComments?> </a> </td> -->

                      <td> <a href="<?php echo base_url('allcomment/').$userData->feed_id;?>" class="comment" id="comment<?= $userData->feed_id; ?>" data-src="<?php echo $userData->feed_id;?>"> <?php echo $userData->totalComments;?> </a>  </td>

                      <td><?php echo $userData->created_at;?></td>
                      <td><?php echo $userData->updated_at;?></td>
                       <td><?php if($userData->status==1 ) { ?> <a  data-src="<?php echo $userData->feed_id; ?>" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $userData->feed_id; ?>" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a> <?php } ?></td>
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


<script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script> 
<script type="text/javascript">
    $(document).ready(function(){

    $(document).on('click', '.enable', function(){
    var feed_id=$(this).attr('data-src');
    var status=$(this).attr('xyz');
    //alert(commentid);
if(status==0){
      var answer = confirm('Are you sure you want to Block this Feed?');
    }else{
       var answer = confirm('Are you sure you want to Unblock this Feed?');
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
//This function is used to block the feed
var it=$(this);
    $.ajax({
      url: '<?php echo base_url('blockfeed');?>',
      type:"post",
      data: {feed_id:feed_id,ustatus:status},
      success:function(xyz){
        if(xyz==1){
          it.parent().html('<a  data-src="'+feed_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
        }
        else{
            it.parent().html('<a  data-src="'+feed_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
        }
  
    }
  });
    });
//End function
  //     var it=$(this);
  //      $.ajax({
  //       url: '<?php //echo base_url('commentblock');?>',
  //       type:"post",
  //       data: {comment_id:commentid,cstatus:status},
  //       success:function(xyz){
  //         if(xyz==1){
  //     it.parent().html('<a  data-src="'+user_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
  //         }
  //         else{
  //           it.parents("tr").animate({ backgroundColor: "#003" }, "slow")
  // .animate({ opacity: "hide" }, "slow");
  //           // it.parent().html('<a  data-src="'+user_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
  //         }
  // // hide.parents("tr").delay(5000).fadeOut();
  // // hide.parents("tr").fadeOut(2000);
  //       }

  // });


// });

//show all comment on count click function 
//  $(document).on('click', '.comment', function(){
//     var feedid=$(this).attr('data-src');
//     //alert(feedid)
//     var it=$(this);
//        $.ajax({
//         url: '<?php //echo base_url('showcomments');?>',
//         type:"post",
//         data: {feed_id:feedid},
//         success:function(res)
//         {
          
//           $('#feedcomment').html(res);
//           $('#commentModal').modal('show');

//         }
// });
// });
//

//function to block the comment

// $(document).on('click', '.hidecomment', function(){
//     var commentid=$(this).attr('data-src');
//     var status=$(this).attr('xyz');
//     var post_id=$(this).attr('post_id');
// var answer = confirm('Are you sure you want to delete this?');
// if (answer)
// {
//   console.log('yes');
// }
// else
// {
//   console.log('cancel');
//   return false;
// }



//This function is used to block the comment
  //     var it=$(this);
  //      $.ajax({
  //       url: '<?php //echo base_url('commentblock');?>',
  //       type:"post",
  //       data: {comment_id:commentid,cstatus:status},
  //       success:function(xyz){
  //         if(xyz==1){
  //           it.parent().html('<a  data-src="'+user_id+'" xyz="0" class="btn btn-success hidecomment" href="javascript:void(0)">Active</a>');
  //         }
  //         else{
  //         // chnage moda cntent 
  //           $.ajax({
  //             url: '<?php //echo base_url('showcomments');?>',
  //             type:"post",
  //             data: {feed_id:post_id},
  //             success:function(res)
  //             {
  //               $('#feedcomment').html(res);
  //               // chnage comment count
  //               $.ajax({
  //                 url: '<?php //echo base_url('countcomments');?>',
  //                 type:"post",
  //                 data: {feed_id:post_id},
  //                 success:function(res)
  //                 {
  //                   $('#comment'+post_id).html(res);

  //                 }
  //               });
  //            //Change count ajax end  
  //             }
  //           });
  //         }
  //       }
  // });
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
// });
</script>