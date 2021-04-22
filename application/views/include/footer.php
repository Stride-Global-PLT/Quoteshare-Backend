</div>

<footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Quoteshare 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Are you sure you want to the logout.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?php echo base_url('adminlogout');?>">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>

  <!-- Core plugin JavaScript-->
  <!--<script src="<?php //echo base_url('assets/vendor/jquery-easing/jquery.easing.min.js');?>"></script>-->

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url('assets/js/sb-admin-2.min.js');?>"></script>

  <!-- Page level plugins -->
  <!-- <script src="<?php echo base_url('assets/vendor/chart.js/Chart.min.js');?>"></script> -->

  <!-- Page level custom scripts -->
  <!-- <script src="<?php //echo base_url('assets/js/demo/chart-area-demo.js');?>"></script>
  <script src="<?php //echo base_url('assets/js/demo/chart-pie-demo.js');?>"></script>-->
  <script src="<?php echo base_url('assets/js/demo/datatables-demo.js');?>"></script> 

  <script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js');?>"></script>
  <script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js');?>"></script>
</body>

</html>
<!-- <script src="<?php //echo base_url('assets/jquery.min.js'); ?>"></script> -->
<script> 

  $(document).ready(function(){

        setTimeout(function() {
        $('.loginmsg').fadeOut('slow');
        }, 2000);

         setTimeout(function() {
        $('.updatemsg').fadeOut('slow');
        }, 2000);
    // $(".collapse-item").on("click", function() {
    //   //$("").removeClass("active");
    //   $(this).parent().addClass("show");


    //Edit user function 
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
//_______________________
        
});
    </script>