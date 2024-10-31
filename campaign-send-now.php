<?php
    global $table_prefix, $wpdb;
    require_once('includes/MCAPI.class.php');
    $api_key = get_option('txt_api');             
    $api = new MCAPI($api_key);

    $campaignId = $camp_id;



    $retval = $api->campaignSendNow($campaignId);

    if ($api->errorCode){
        echo "<span style=color:red>Unable to Send Test Campaign!</span>";
        echo "\n\t<span style=color:red>Msg=".$api->errorMessage."\n</span>";
    } else {
        echo "1";
        $send_status = 'yes'; 
        $status_update = "UPDATE `{$table_prefix}mailchimp_newsletter` SET
        `send_status` = '".$send_status."' 
        WHERE `id` ='".$id."'";  
        $wpdb->query($status_update);
    }
    die;
?>