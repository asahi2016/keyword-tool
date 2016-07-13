$(document).ready(function () {

   $('.social-link li a').click(function(){
       $('.search-form').hide();
       $('.social-link li a').removeClass('active');
       var type = $(this).attr('class');
       if(type.length > 0){
           $('#'+type).show();
           $('#'+type+'-tbl').show();
           $('.social-link li').find('.'+type).addClass('active');
       }
       else{
           $('#google').show();
           $('#google-tbl').show();
           $('.social-link li').eq(0).find('a').addClass('active');
       }
       $("html, body").animate({ scrollTop: 0 }, "slow");
   });

    tab();
    function tab(){
        $('.search-form').hide();
        $('.social-link li a').removeClass('active');
        var type = window.location.hash.substr(1);
        if(type.length > 0){
            $('#'+type).show();
            $('#'+type+'-tbl').show();
            $('.social-link li').find('.'+type).addClass('active');
        }
        else{
            $('#google').show();
            $('#google-tbl').show();
            $('.social-link li').eq(0).find('a').addClass('active');
        }
        $("html, body").animate({ scrollTop: 0 });
    }
});


