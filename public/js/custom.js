$(document).ready(function () {

    $("#copy").click( function () {
        getselectedcheckbox('checkbox-key');
    });
    $("#copy_all").click( function () {
        copy_all();
        getselectedcheckbox('checkbox-key');
        $('#select_all').trigger('click');
        copy_all();

    });

    $('#select_all').click(function(){
        copy_all();
    });

    function getselectedcheckbox(bing_checkbox) {

            var selectedCheckboxValue = '';
            var checked = document.getElementsByName('checkbox-key');
            var len = checked.length;
            for (var i = 0; i < len; i++) {
                if (checked[i].checked) {
                    selectedCheckboxValue += (checked[i].value + "\n");
                    //selectedCheckboxValue += selectedCheckboxValue.join("\n");
                }
            }

                 //selectedCheckboxValue= selectedCheckboxValue.replace(/\n\r?/g, "<br>");
                //selectedCheckboxValue.toString();
                // Create a dummy input to copy the string array inside it
                var copy = document.createElement("INPUT");
                copy.type = "text";
                // Add it to the document
                document.body.appendChild(copy);
                // Set its ID
                copy.setAttribute("id", "copy_id");
                // Output the array into it
                document.getElementById("copy_id").value = selectedCheckboxValue;
                // Select it
                copy.select();
                // Copy its contents
                document.execCommand("copy");
                // Remove it as its not needed anymore
                document.body.removeChild(copy);

    }

    function copy_all(){

        var checkboxes = document.getElementsByName('checkbox-key');
        var click_all = document.getElementById('select_all');

        if(click_all.value == 'select'){
            for (var i in checkboxes){
                checkboxes[i].checked = 'FALSE';
            }
            click_all.value = 'deselect'
        }else{
            for (var i in checkboxes){
                checkboxes[i].checked = '';
            }
            click_all.value = 'select';
        }
    }


    $('#table-div-id').tablePaginate({navigateType:'navigator',recordPerPage:200});
    $('.pagination-btn').live('click', function(){
        $('.pagination-btn').each(function(){
            $("html, body").animate({ scrollTop: 0 });
        });
    });
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
       $("html, body").animate({ scrollTop: 0 });
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


