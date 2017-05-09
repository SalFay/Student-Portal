</div>
</section>
<footer class="row clearfix">
    <div class="col-xs-12">
        <p class="text-muted small text-center">Copyright &copy; 2017 Student Portal Team</p>
    </div>
</footer>
</div><!-- // Container Fluid -->

<script src="../content/assets/plugins/jquery.js"></script>
<script src="../content/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
    function cdel(){
        if(confirm("Are you sure you want to delete it?")){
            return true;
        }else{
            return false;
        }
    }
</script>
<?php include __DIR__.'/../content/files/chatbox.php'?>
</body>
</html>
<?php ob_flush();
