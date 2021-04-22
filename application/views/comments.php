

<div class="row">
  <div class="col-md-2">
    S No.
  </div>
  <div class="col-md-2">
    User
  </div>
  <div class="col-md-4">
    Comment
  </div>
  <div class="col-md-2">
    Time
  </div>
  <div class="col-md-2">
   Action
  </div>
</div>

<?php
$a =1;
 foreach ($comments as $value){?>


  
<div class="row hiderow<?= $value->comment_id ?>">
  <span><?php echo $a++;?></span>
  <div class="col-md-2">
 
  </div>
  <div class="col-md-2">
    <span><?php echo $value->full_name;?> </span>
  </div>
  <div class="col-md-4">
    <span><?php echo $value->comment;?> </span>
  </div>
  <div class="col-md-2">
 <span><?php echo $value->created_at;?> </span>  </div>
  <div class="col-md-2">
   <?php if($value->status==1 ){ ?> <a  data-src="<?php echo $value->comment_id; ?>" xyz="0" post_id="<?php echo $value->feed_id;?>" class="btn btn-success hidecomment" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $value->comment_id; ?>" xyz="1"  post_id="<?php echo $value->feed_id;?>" class="btn btn-danger hidecomment" href="javascript:void(0)">Inactive</a> <?php } ?>

  </div>
</div>
<hr class="hrclass<?= $value->comment_id ?>">
<?php }?>


<!-- <script src="<?php //echo base_url('assets/jquery.min.js'); ?>"></script> --> 
<script type="text/javascript">
    $(document).ready(function(){
    $(document).on('click', '.hidecomment', function(){
    var commentid=$(this).attr('data-src');
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
        data: {comment_id:commentid,cstatus:status},
        success:function(xyz){
          if(xyz==1){
      it.parent().html('<a  data-src="'+user_id+'" xyz="0" class="btn btn-success show" href="javascript:void(0)">Active</a>');
          }
          else{
  //           it.parents("div").animate({ backgroundColor: "#003" }, "slow")
  // .animate({ opacity: "hide" }, "slow");
            it.parent().html('<a  data-src="'+user_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
          }
  // hide.parents("tr").delay(5000).fadeOut();
  // hide.parents("tr").fadeOut(2000);
        }

  });
});

// //show all comment on count click function 
//  $(document).on('click', '.comment', function(){
//     var feedid=$(this).attr('data-src');
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
// //



});
</script>