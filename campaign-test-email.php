<?php
    global $table_prefix, $wpdb;
    require_once('includes/MCAPI.class.php');
    $api_key = get_option('txt_api');             
    $api = new MCAPI($api_key);
    
    $email = $_POST["email_ids"];
    $f_email =  explode(',',$email); 

    $campaignId = $camp_id;

    $retval = $api->campaignSendTest($campaignId, $f_email);

    if ($api->errorCode){
        echo "<span style=color:red>Unable to Send Test Campaign!</span>";
        echo "\n\t<span style=color:red>Msg=".$api->errorMessage."\n</span>";
    } else {
        echo "1";
    }
    die;
?>