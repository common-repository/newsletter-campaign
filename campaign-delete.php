<?php
    $id = $_GET['id'];
    $tbl_cat_name = $table_prefix."mailchimp_newsletter"; 
    $delete = "delete from $tbl_cat_name where id = '".$id."' ";
    $del = $wpdb->query($delete);
    echo "<script type=text/javascript>";
    echo "location.href='admin.php?page=mailchimp_page&del=1'";
    echo "</script>";
?>