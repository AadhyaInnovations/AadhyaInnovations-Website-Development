jQuery(document).ready(function ($) {
    $(".ccpw-delete-transient").attr("disabled", false); 
    $(".ccpw-delete-transient").on("click",function(e){
        $(this).text('Wait...');        
        var ajax_url = $(this).data('ajax-url');
        var send_data = {
            'action': 'ccpw_delete_transient'        
        };
        $.ajax({
        type: 'POST',
        url: ajax_url,
        data: send_data,
        success: function (response){
            $(".ccpw-delete-transient").text('Cache Deleted');
           // $(".ccpw-delete-transient").attr("disabled", true); 
        },
         error:function(error){
            console.log(error);
        }
        });
        return false;
    }); 








        var url = window.location.href;
        console.log(url)
    if (url.indexOf('?page=ccpw_options') > 0) {
            $('[href=\"admin.php?page=ccpw_options\"]').parent('li').addClass('current');

        }
       
    let cmc_data = $('#adminmenu #toplevel_page_cool-crypto-plugins ul li a[href=\"admin.php?page=ccpw_options\"]')

        cmc_data.each(function (e) {
            if ($(this).is(':empty')) {
                $(this).hide();
            }
        });


});