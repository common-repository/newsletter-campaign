<?php
    global $table_prefix, $wpdb;
    /*$res_detail = $wpdb->get_results("SELECT * FROM wp_contactus where id=".$_GET['id'],ARRAY_A);    
    $res_detail = $res_detail[0];*/

    //For Status Updated
    if(!empty($_REQUEST['error'])){
        echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>'.urldecode($_GET['error']).'</strong></p>
        </div>';  
    }
    //END Status Updated
    $admin_url = admin_url();
    $plugin_path = plugin_dir_path( __FILE__ );
    if(isset($_POST['btn_save'])){
        if(!empty($_REQUEST['txt_newsletter']) && !empty($_REQUEST['txt_subject']) && !empty($_REQUEST['txt_sen_email']) && !empty($_REQUEST['email_content']) ){
            require_once('includes/MCAPI.class.php');

            $api_key = get_option('txt_api');             
            $api = new MCAPI($api_key);
            $type = 'regular';
            $opts['list_id'] = get_option('txt_listid');
            $opts['subject'] = $_REQUEST['txt_subject'];
            $opts['from_email'] = $_REQUEST['txt_sen_email']; 
            $opts['from_name'] = $_REQUEST['txt_sen_name'];

            $opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);

            $opts['authenticate'] = true;
            $opts['analytics'] = array('google'=>'my_google_analytics_key');
            $opts['title'] = $_REQUEST['txt_newsletter'];

            $content = array('html'=>$_REQUEST['email_content'], 
            'text' => 'Unsubscribe *|UNSUB|*'
            );  

            $retval = $api->campaignCreate($type, $opts, $content);

            if ($api->errorCode){
                $msg = "Unable to Create New Campaign ";
                $msg .= " Code = ".$api->errorCode;
                $msg .=  ", Msg = ".$api->errorMessage;
                $encode_url = urlencode($msg);
            ?>
            <script type="text/javascript">window.location.href= "<?php echo $admin_url; ?>/admin.php?page=mailchimp_new&error=<?php echo $encode_url; ?>";</script>
            <?php
            } else {
                //$msg = "New Campaign Created ID:".$retval;
                $msg = "New Campaign Created";
                //update_post_meta( $newsletter_id, 'camp_id',$retval ); // For Save Campaign ID      
                $campaign_id = $retval;
                if($_REQUEST['chk_sendnow']){
                    $send_now =  $api->campaignSendNow($retval);
                    $send_status = 'yes';                    
                }else{
                    $send_status = 'no';                        
                }
                $news_insert = "INSERT INTO {$table_prefix}mailchimp_newsletter (`title` ,`subject`,`sender_name` ,`email` ,`content` ,`send_status` ,`campaign_id` )VALUES ('".$_REQUEST['txt_newsletter']."', '".$_REQUEST['txt_subject']."', '".$_REQUEST['txt_sen_name']."', '".$_REQUEST['txt_sen_email']."', '".$_REQUEST['email_content']."', '".$send_status."', '".$campaign_id."')";  
                $insert = $wpdb->query($news_insert);
                if($insert){
                ?>
                <script type="text/javascript">window.location.href= "<?php echo $admin_url; ?>/admin.php?page=mailchimp_page&s=1";</script>
                <?php
                }else{
                ?>
                <script type="text/javascript">window.location.href= "<?php echo $admin_url; ?>/admin.php?page=mailchimp_page&s=1";</script>
                <?php
                }
            }
        }
    }
?>
<div class="wrap">
    <?php screen_icon('options-general'); ?>
    <h2>Create new campaign</h2>
</div>
<style type="text/css">
    .nicEdit-main {
        background: none repeat scroll 0 0 #FFFFFF;
        max-height: 340px !important;
        overflow: auto !important;
    }
    .div_btn{
        padding: 10px 0;
    }
    .div_loading {
        background: none repeat scroll 0 0 #000000;
        height: 100%;
        opacity: 0.7;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 99999;
    }
</style>
<script type="text/javascript" src="<?php echo plugins_url( 'includes/nicEdit.js', __FILE__ );; ?>"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        var myNicEditor = new nicEditor();
        myNicEditor.panelInstance('email_content');         
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#btn_post').click(function(){
            var sel_post = jQuery('#sel_post').val();
            if(sel_post == ''){
                alert('Please select post');
                return false;
            }else{
                jQuery('body').append('<div class="div_loading"></div>');
                jQuery.ajax({
                    type: "POST",
                    url: "../wp-admin/admin-ajax.php",
                    data: {
                        action:"post_data",
                        "id" : jQuery("#sel_post").val(),
                    },
                    success:function(data) {
                        jQuery('body').find('.div_loading').remove();
                        jQuery("#email_content").append(data);
                        jQuery(".nicEdit-main").append(data);
                    },
                    error: function(errorThrown){
                        //console.log(errorThrown);
                        alert(errorThrown);
                        return false;
                    }
                }); 
            }
        });

        function validateEmail(sEmail) {
            var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            if (filter.test(sEmail)) {
                return true;
            }
            else {
                return false;
            }
        }
        jQuery('#btn_save').click(function(){
            var editor_content = jQuery('.nicEdit-main').html();
            jQuery('#email_content').html(editor_content);
            var err = '';
            if(jQuery.trim(jQuery('#txt_newsletter').val()) == ''){
                err += 'Please Enter Newsletter Name \n';
            }
            if(jQuery.trim(jQuery('#txt_sen_name').val()) == ''){
                err += 'Please Enter Sender Name \n';
            }
            if(jQuery.trim(jQuery('#txt_sen_email').val()) == ''){
                err += 'Please Enter Sender Email \n';
            }else{
                var sEmail = jQuery.trim(jQuery("#txt_sen_email").val());
                if (validateEmail(sEmail)) {
                }
                else {
                    err += 'Please Enter Valid Email Address\n';
                }    
            }
            if(jQuery.trim(jQuery('#txt_subject').val()) == ''){
                err += 'Please Enter Subject \n';
            }
            if(jQuery.trim(jQuery('#email_content').val()) == ''){
                err += 'Please Enter Content \n';
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
                    <td style="font-weight: 700;">Newsletter Name</td>
                    <td>:</td>
                    <td><input type="text" id="txt_newsletter" maxlength="100" size="40" name="txt_newsletter" value=""></td>
                </tr>

                <tr>
                    <td style="font-weight: 700;">Sender Name</td>
                    <td>:</td>
                    <td><input type="text" maxlength="100" size="40" id="txt_sen_name" name="txt_sen_name" value=""> </td>
                </tr>
                <tr>
                    <td style="font-weight: 700;">Sender Email</td>
                    <td>:</td>
                    <td><input type="text" maxlength="100" size="40" id="txt_sen_email" name="txt_sen_email" value=""> </td>
                </tr>  
                <tr>
                    <td style="font-weight: 700;">Subject</td>
                    <td>:</td>
                    <td><input type="text" id="txt_subject" maxlength="100" size="40" name="txt_subject" value=""> </td>
                </tr>  

                <tr>
                    <td style="font-weight: 700;">Select Post</td>
                    <td>:</td>
                    <td>
                        <select name="sel_post" id="sel_post">
                            <option value="">Select Post</option>
                            <?php
                                $postlist = get_posts( 'sort_order=asc' );
                                $posts = array();
                                foreach ( $postlist as $post ) {                    
                                ?>
                                <option value="<?php echo $post->ID ?>" id="<?php echo $post->ID ?>"><?php echo $post->post_title; ?></option>
                                <?php
                                }
                            ?>
                        </select>
                        <input type="button" id="btn_post" value="Get Post Content" class="button button-primary">
                    </td>
                </tr>
                <tr>
                    <td>Content</td>
                    <td>:</td>
                    <td><div id="container_div"><textarea id="email_content" name="email_content" style="width: 600px; height: 300px;"></textarea></div></td>
                </tr>
                <tr>
                    <td>Send Campaign Now ?</td>
                    <td>:</td>
                    <td><input type="checkbox" name="chk_sendnow" id="chk_sendnow" value="1"></td>
                </tr>

            </tbody>
        </table>
        <div class="div_btn">
            <input type="submit" id="btn_save" name="btn_save" value="Save / Create" class="button button-primary">
        </div>  

    </div>
    </form>
    


 