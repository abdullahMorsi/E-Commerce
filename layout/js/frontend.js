$(function(){
    
    "use strict";
    
    //switch between login and signup
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).attr('data-class')).fadeIn(100);
    });

    //dashboard
    $(".toggle-info").click(function(){

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        
        if($(this).hasClass("selected")){
            
            $(this).html('<i class="fa fa-minus"></i>')
        
        }else{

            $(this).html('<i class="fa fa-plus"></i>')

        }

    });


    //trigger select box It
    $("select").selectBoxIt({
        autoWidth:false
    });


    //Hide Placeholder On Form Focus
    
    $('[placeholder]').focus(function(){
        
        $(this).attr("data-text", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
        
        }).blur(function(){
        
        $(this).attr('placeholder', $(this).attr('data-text'));
        
    })

    //add asterisk on required fields
    $('input').each(function(){

        if($(this).attr('required')==="required"){
            $(this).after('<span class="asterisk">*</span>');
        }

    });


    //Confirmation Message on button

    $('.confirm').click(function(){
        return confirm("Are You Sure");
    })

    // $('.live-name').keyup(function(){
    //     $('.live-preview .caption h3').text($(this).val())
    // });
    // $('.live-description').keyup(function(){
    //     $('.live-preview .caption p').text($(this).val())
    // });
    // $('.live-price').keyup(function(){
    //     $('.live-preview .price-tag').text('$' + $(this).val())
    // });
    $('.live').keyup(function(){
        $($(this).data('class')).text($(this).val())
    });

});