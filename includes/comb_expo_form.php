<html>
<title>Payout Matcher</title>
<body>
    <main>
        <form action="<?php echo get_admin_url();?>admin-post.php" method='post' enctype='multipart/form-data'>
            <h3>Please upload the files required and click on 'Export' button</h3>
            <hr>
            
            <input type='hidden' name='action' value='submit-form' />
            <label for='user_file_upload'>User File: </label>
            <input type='file' name='user_file' id='user_file_upload'/>
            
            <label for='payout_file_upload'>Payout File: </label>
            <input type='file' name='payout_file' id='payout_file_upload' style='clear:both;'/>
            
            <label for='aff_file_upload'>Affiliate File: </label>
            <input type='file' name='aff_file' id='aff_file_upload' style='clear:both;'/>
            <button type='submit' name='export_submit'>Export</button>
        </form>
    </main>
</body>
</html>