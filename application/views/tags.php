

<div class="container-fluid">
<div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m m-0 font-weight-bold text-primary">Dashboard/Tags</h6>
              <a href="<?php echo base_url('AddTag');?>" class="btn btn-primary btn-user " style="float:right;">
                  Add new Tag
                </a>
                <span class="text-success" style="float:right;">  <?php  echo $this->session->flashdata("update"); ?>
              <span class="text-success" style="float:right;">  <?php  echo $this->session->flashdata("adduser"); ?>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th>Serial No</th>
                      <th>Tag Name</th>
                      <th>Status</th>
                      <th>Created_at</th>
                      <th>updated_at</th>
                      <th>Action</th>
            
                      
                    </tr>
                  </thead>
                  <tfoot>
                  <tr>
                  <th>Serial No</th>
                      <th>Tag Name</th>
                      <th>Status</th>
                      <th>Created_at</th>
                      <th>updated_at</th>
                      <th>Action</th>
                    
                    </tr>
                  </tfoot>
                  <tbody>
                  <?php $a=1;
                  foreach($tag as $tags){ ?>
                    <tr>
                    <td><?php echo $a++;?></td>
                    <td><?php echo $tags->tag;?></td>
                      <td><?php if($tags->is_active==1 ){ ?> <a  data-src="<?php echo $tags->tag_id; ?>" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>
        <?php } 

        else { ?>
        <a  data-src="<?php echo $tags->is_active; ?>" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a> <?php } ?></td>
                      <td><?php echo $tags->created_at;?></td>
                      <td><?php echo $tags->updated_at;?></td>
                      <td><a href="<?php echo base_url('edittag/'.$tags->tag_id);?>"><i class="fas fa-edit"></i></a>
                     </td>
                    </tr>
                      <?php }?>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
</div>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
 <script src="<?php echo base_url('assets/jquery.min.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).on('click', '.enable', function(){
    var tag_id=$(this).attr('data-src');
    var status=$(this).attr('xyz');
var answer = confirm('Are you sure you want to delete this?');
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
        url: '<?php echo base_url('blocktag');?>',
        type:"post",
        data: {tag_id:tag_id,tagstatus:status},
        success:function(xyz){
          if(xyz==1){
   it.parent().html('<a  data-src="'+tag_id+'" xyz="0" class="btn btn-success enable" href="javascript:void(0)">Active</a>');
          }
          else{
            it.parent().html('<a  data-src="'+tag_id+'" xyz="1" class="btn btn-danger enable" href="javascript:void(0)">Inactive</a>');
          }
  // hide.parents("tr").delay(5000).fadeOut();
  // hide.parents("tr").fadeOut(2000);
        }

  });
});
});
</script>