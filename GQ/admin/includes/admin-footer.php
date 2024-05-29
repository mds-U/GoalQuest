<footer class="main-footer">
    <strong>GQ Company - </strong>
    Copyright &copy; 2024. All rights reserved.
    <div class="float-right d-none d-sm-inline-block"> 
      <a href="#" data-toggle="modal" data-target="#termsModal">Terms of Agreement</a>
    </div>
</footer>

<!-- Terms of Agreement Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms of Agreement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your Terms of Agreement content here -->
                <p>Welcome to GoalQuest. These Terms and Conditions govern your use 
                    of our task manager system. By accessing or using our Website, you agree 
                    to comply with and be bound by these Terms. If you do not agree with these Terms, please do not use 
                    our Website</p>
                <ol>
                    <b><li>Acceptance of Terms</li></b>
                    <p>By using our Service, you acknowledge that you have read, understood, and agree to be bound by 
                        these Terms, as well as our Privacy Policy.</p>
                    
                    <b><li>Changes to Terms</li></b>
                    <p>We reserve the right to modify these Terms at any time. Any changes will be effective immediately
                        upon posting on our Website. Your continued use of the Service following the posting of changes 
                        constitutes your acceptance of such changes.</p>
                    
                    <b><li>Use of the Service</li></b>
                    <ul>
                        <li><b>Account Registration:</b> You may need to register for an account to access certain features of 
                            the Service. You agree to provide accurate and complete information during registration and to keep your 
                            account information updated.
                        </li>
                        <li><b>Account Security:</b> You are responsible for maintaining the confidentiality of your account credentials 
                            and for all activities that occur under your account. You agree to notify us immediately of any unauthorized 
                            use of your account.
                        </li>
                        <li><b>Prohibited Conduct:</b> 
                            You agree not to:
                            <ul>
                                <li>Use the Service for any unlawful purpose.</li>
                                <li>Impersonate any person or entity or misrepresent your affiliation with any person or entity.</li>
                                <li>Interfere with or disrupt the operation of the Service.</li>
                                <li>Transmit any viruses or other harmful code.</li>
                            </ul>
                        </li>
                    </ul>

                    <b><li>Intellectual Property</li></b>
                    <ul>
                        <li><b>Our Rights: </b>All content and materials on the Website and Service, including but not limited to text, graphics, 
                            logos, and software, are the property of GoalQuest or its licensors and are protected by intellectual property laws.</li>
                        <li><b>Your Rights: </b>You are granted a limited, non-exclusive, non-transferable, and revocable license to access and 
                            use the Service for personal and internal business purposes.</li>
                    </ul>

                    <b><li>Third-Party Services</li></b>
                    <p>The Service may contain links to third-party websites or services that are not owned or controlled by us. We are not 
                        responsible for the content, privacy policies, or practices of any third-party websites or services.</p>
                    
                    <b><li>Usage Data</li></b>
                    <p>We may collect information about your use of the Service, including but not limited to the tasks you create, the time you spend on 
                        the Service, and the actions you take within the website. This data helps us improve the Service and provide you with a
                        better experience. By using the Service, you agree to our collection and use of Usage Data as described in our Privacy Policy.</p>
                    
                    <b><li>Termination</li></b>
                    <p>We may terminate or suspend your access to the Service at any time, without prior notice or liability, for any reason, 
                        including if you breach these Terms. Upon termination, your right to use the Service will immediately cease.</p>

                    <b><li>Severability</li></b>
                    <p>If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions will continue in full force and effect.</p>
                    
                    <b><li>Compliance with Laws</li></b>
                    <p>You agree to comply with all applicable local, state, national, and international laws and regulations in connection with your use of the Service.</p>

                    <b><li>Contact Us</li></b>
                    <p>If you have any questions about these Terms, please contact us at info_goalquest@gmail.com</p>
                </ol>   
            </div>
        </div>
    </div>
</div>



</div>
<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="assets/dist/js/pages/dashboard.js"></script>
<!-- Sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<!-- Summernote Initialization Script -->
<script>
    $(function () {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        $('.datetimepicker').datetimepicker({
            format:'Y/m/d H:i',
        });
    });
</script>

<!-- for table -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready( function () {
    $('#myTable').DataTable();
  });
</script>

</body>
</html>