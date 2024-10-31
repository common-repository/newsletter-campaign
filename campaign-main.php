<div class="wrap">
    <?php screen_icon('options-general'); ?>
    <h2>Mailchimp Campaign</h2>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#closeLogin").click(function() {
            jQuery('#signup-overlay').css('display','none'); 
            jQuery('#div_test_email').css('display','none');     
            window.location.reload();        
        });
        jQuery('.send_test_campaign').click(function(){            
            jQuery('#signup-overlay').css('display','block'); 
            jQuery('#div_test_email').css('display','block');             
            var camp_id = jQuery.trim(jQuery(this).attr('id'));
            jQuery('#div_test_email').append('<input type="hidden" value='+camp_id+' id="hdn_camp_id">');
        });
    });
</script>
<div style="clear: both; padding: 5px;"></div> 
<?php 
    global $wpdb, $table_prefix;   
    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
    $limit = 10;
    $offset = ( $pagenum - 1 ) * $limit; 

    $tbl_cat_name = $table_prefix."mailchimp_newsletter";
    $sql = "select * from $tbl_cat_name ORDER BY id DESC LIMIT $offset, $limit";
    $ord_data = $wpdb->get_results($sql);

?>
<?php if(isset($_COOKIE['msg']) && $_COOKIE['msg'] != ""){ ?>
    <div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong><?php echo $_COOKIE['msg']; setcookie("msg", "", time()-100);; ?></strong></p>
    </div>
    <?php } ?>
<table cellpadding="0" cellspacing="0" id="category_table" class="wp-list-table widefat fixed posts">
    <thead>
        <tr>
            <th><label>Campaign Name</label></th>
            <th><label>Subject</label></th>
            <th><label>Sending Status</label></th>
            <th><label>Send Test Email</label></th>
            <th><label>Campaign Send Now</label></th>
            <th><label>Action</label></th>            
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><label>Campaign Name</label></th>
            <th><label>Subject</label></th>
            <th><label>Sending Status</label></th>
            <th><label>Send Test Email</label></th>
            <th><label>Campaign Send Now</label></th>
            <th><label>Action</label></th>            
        </tr>
    </tfoot>
    <tbody>
        <?php if(count($ord_data) > 0){
                $send_status = 'Already Sent';
            ?>
            <?php for($i=0; $i<count($ord_data); $i++){?>
                <tr>
                    <td>
                        <?php if($ord_data[$i]->send_status == 'no'){ ?>
                            <a href="admin.php?page=mailchimp_edit&id=<?php echo $ord_data[$i]->id; ?>" title="Click To Detail Information"><?php echo $ord_data[$i]->title; ?></a><?php }else{ echo $ord_data[$i]->title; } ?>
                    </td>
                    <td><?php echo $ord_data[$i]->subject; ?></td>
                    <td><?php if($ord_data[$i]->send_status == 'no'){
                                echo 'Not Send';   
                            }else{
                                echo $send_status;    
                    } ?></td>                                       
                    <td>
                        <?php if($ord_data[$i]->send_status == 'no'){ ?>
                            <a href="#div_test_email" title="Enter To Send Test Email" class="send_test_campaign" id="<?php echo $ord_data[$i]->campaign_id; ?>">Click Here</a> 
                            <?php }else{ 
                                echo $send_status;
                            }
                        ?>
                    </td>
                    <td>
                        <?php if($ord_data[$i]->send_status == 'no'){ ?>
                            <a href="#" title="Click Here To Send Email List of Subscriber" class="send_campaign" id="<?php echo $ord_data[$i]->campaign_id; ?>" ids="<?php echo $ord_data[$i]->id; ?>">Click Here</a>
                            <?php }else{ 
                                echo $send_status;
                            }
                        ?>
                    </td>
                    <td>
                        <?php if($ord_data[$i]->send_status == 'no'): ?> <a href="admin.php?page=mailchimp_edit&id=<?php echo $ord_data[$i]->id; ?>" class="ord_edit" title="Click To Edit">Edit</a> | <?php endif; ?><a href="admin.php?page=mailchimp_page&action=delete_camp&id=<?php echo $ord_data[$i]->id; ?>" class="ord_delete" title="Click To Delete" onclick="return confirm('Are You Sure To Delete');">Delete</a>
                    </td> 
                </tr>
                <?php } ?>
            <?php }else{ ?>
            <tr>
                <td colspan="4">No Record Found.</td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<?php 
    $total = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$table_prefix}mailchimp_newsletter" );
    $num_of_pages = ceil( $total / $limit );
    $page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'aag' ),
    'next_text' => __( '&raquo;', 'aag' ),
    'total' => $num_of_pages,
    'current' => $pagenum
    ) );
    if( $page_links ) {
        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0; float:left;">' . $page_links . '</div></div>';
    }
?>
<div style="display: none;" id="signup-overlay" class="signup-overlay"></div>
<div style="display: none;" id="div_test_email">
    <a id="closeLogin">&nbsp;</a>
    <div class="test_container">
        <h2>Enter Test Email Address </h2>
        <table>
            <tr>
                <td>Enter Email</td>
                <td><input type="text" value="" name="txt_email_send" class="txt_fancy_email"></td>
            </tr>   
            <tr>
                <td colspan="2">
                Separate by comma for multiple emails Ex. xyz@yahoo.com,pqr@gmail.com
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" class="button button-primary" value="Send Test" id="send_test_fancy">
                    <div class="loader_img"></div>
                </td>                
            </tr>
        </table>
    </div>
</div>
</div>