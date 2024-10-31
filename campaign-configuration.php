<?php
    global $table_prefix, $wpdb;
    /*$res_detail = $wpdb->get_results("SELECT * FROM wp_contactus where id=".$_GET['id'],ARRAY_A);    
    $res_detail = $res_detail[0];*/
    $plugin_path = plugin_dir_path( __FILE__ );
    $admin_url = admin_url();
    if(isset($_POST['btn_save'])){
        if(!empty($_REQUEST['txt_api']) && !empty($_REQUEST['txt_listid']) ){
            $api_key = $_REQUEST['txt_api'];
            $listid = $_REQUEST['txt_listid'];
            update_option('txt_api',$api_key);
            update_option('txt_listid',$listid);
        ?>
        <script type="text/javascript">window.location.href= "<?php echo $admin_url; ?>/admin.php?page=mailchimp_configuration&conf=1";</script>
        <?php            
        }
    }
?>
<div class="wrap">
    <?php screen_icon('options-general'); ?>
    <!--<h2>Create new campaign</h2>-->
    <h2>Configuration</h2>
</div>
<style type="text/css">

</style>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#btn_save').click(function(){
            var err = '';
            if(jQuery.trim(jQuery('#txt_api').val()) == ''){
                err += 'Please Enter API key \n';
            }
            if(jQuery.trim(jQuery('#txt_listid').val()) == ''){
                err += 'Please Enter List Id \n';
            }            
            if(err.length > 0){
                alert(err);
                return false;    
            }
        });
    });

</script>
<form action="" method="post">
    <div style="clear: both; float: left; padding: 5px; width: 50%;">
        <table style="width: 100%; display: block;">
            <tbody><tr>
                    <td style="font-weight: 700;">API Key</td>
                    <td>:</td>
                    <td><input type="text" id="txt_api" maxlength="100" size="40" name="txt_api" value="<?php echo get_option('txt_api'); ?>"></td>
                </tr>

                <tr>
                    <td style="font-weight: 700;">List ID</td>
                    <td>:</td>
                    <td><input type="text" maxlength="100" size="40" id="txt_listid" name="txt_listid" value="<?php echo get_option('txt_listid'); ?>"> </td>
                </tr>

            </tbody>
        </table>
        <div class="div_btn">
            <input type="submit" id="btn_save" name="btn_save" value="Save / Create" class="button button-primary">
        </div>  

    </div>
    </form>
    


 