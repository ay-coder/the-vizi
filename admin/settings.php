<?php
	require '../config.php';
	require DOC_ROOT . 'admin/header.php';
    if(isset($_POST) && count($_POST))
    {
        $updatePolicy   = 'UPDATE settings SET data_value = "'.$_POST['policy'].'" WHERE data_key = "policy" ';
        $updateTerms    = 'UPDATE settings SET data_value = "'.$_POST['appterms'].'" WHERE data_key = "terms" ';

        $db->query($updatePolicy);
        $db->query($updateTerms);
    }
    $policy = $db->single('SELECT data_value FROM settings where data_key = "policy"');
    $terms  = $db->single('SELECT data_value FROM settings where data_key = "terms"' );
?>
  <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=sjaltj8p8fo2ondfz7ksyg7q81c7l0uvddovcxm8zgcspfld"></script>
    <div class="wrapper wrapper-content">
        <form action="#" method="POST">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <label class="control-label">Privacy Policy : </label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="policy" cols="40" rows="10" class="form-control"><?php echo $policy;?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <label class="control-label">Terms & Conditions : </label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="appterms" cols="40" rows="10" class="form-control"><?php echo $terms;?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 text-center">
                    <hr>
                        <input type="submit" name="submit" value="Update" class="btn btn-primary">
                        <input type="reset" name="reset" value="Reset" class="btn btn-primary">
                    </div>
               </div>
            </div>
        </form>
    </div>
<script>tinymce.init({
 selector:'textarea',
 menubar: false,
   plugins: [
     'advlist autolink lists link image charmap print preview anchor',
     'searchreplace visualblocks code fullscreen',
     'insertdatetime media table contextmenu paste code'
   ],
   toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
   content_css: [
     'http://fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
     'http://www.tinymce.com/css/codepen.min.css']
 });</script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>