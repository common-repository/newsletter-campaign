<?php 
    if(isset($_POST['submit']))
    {
        update_option('api_key', stripslashes($_POST['api_key']));
        update_option('list_id', $_POST['list_id']);

    }
    $api_key = get_option('api_key'); 
    $list_id = get_option('list_id'); 
?>
<div class="wrap">
    <h2>Mailchimp Settings</h2>
    <form action="admin.php?page=mailchimp_page" method="post">
        <table class="form-table">
            <tbody><tr valign="top">
                    <th scope="row"><label for="api_key">Mailchimp Api Key</label></th>
                    <td><input type="text" class="regular-text" value="<?php echo $api_key; ?>" id="api_key" name="api_key"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="list_id">Mailchimp List Id</label></th>
                    <td><input type="text" class="regular-text" value="<?php echo $list_id; ?>" id="list_id" name="list_id"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
    </form>
</div>

