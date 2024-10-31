jQuery(document).ready(function(){
    var rr = jQuery("#toplevel_page_mailchimp_page").find('ul li:nth-child(4)').hide();
var hdn_path = jQuery.trim(jQuery("#hdn_path").val());
    jQuery('#send_test_fancy').click(function(){
        var camp_id = jQuery.trim(jQuery("#hdn_camp_id").val());
        var email_ids = jQuery.trim(jQuery(".txt_fancy_email").val());
        if(email_ids == ''){
            alert('please enter email address');return false;
        }
        jQuery(".loader_img").css('display','block');
        jQuery.ajax({
            type: "POST",
            url: hdn_path+"admin-ajax.php",
            data: {
                action:"post_test_email",
                "camp_id" : camp_id,
                "email_ids" : email_ids,
            },
            success:function(data) {
                jQuery(".loader_img").css('display','none');
                if(jQuery.trim(data) == '1'){
                    jQuery('body #div_test_email .test_container').html('<div class="fancy_msg"><h1>Thank You </h1><h2>Please check your email inbox</h2></div>');
                }else{
                    jQuery('body #div_test_email .test_container').html(data);
                }
                jQuery('body').find('#hdn_camp_id').remove();              
            },
            error: function(errorThrown){
                //console.log(errorThrown);
                alert(errorThrown);
                return false;
            }
        }); 
    }); 

    jQuery('.send_campaign').click(function(){
        jQuery('body').append('<div class="div_loading"></div>');
        var camp_id = jQuery.trim(jQuery(this).attr('id'));
        var id = jQuery.trim(jQuery(this).attr('ids'));
        jQuery.ajax({
            type: "POST",
            url: hdn_path+"admin-ajax.php",
            data: {
                action:"post_send_now",
                "camp_id" : camp_id,
                "id" : id
            },
            success:function(data) {
                if(jQuery.trim(data) == '1'){
                    alert('Campaign Send Successfully.');
                    location.reload();
                }else{
                    alert(data);
                    location.reload();
                }
                jQuery('body').find('#hdn_camp_id').remove();
                jQuery('body').find('.div_loading').remove();
            },
            error: function(errorThrown){
                //console.log(errorThrown);
                alert(errorThrown);
                return false;
            }
        }); 
    });

});
