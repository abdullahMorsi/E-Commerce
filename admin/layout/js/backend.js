$(function(){
    
    "use strict";
    
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

    //convert passwoord field to text field on hover
    
    var passField = $('.password');

    $('.show-pass').hover(function(){
        passField.attr('type', 'text');
    }, function(){
        passField.attr('type', 'password');
    });


    //Confirmation Message on button

    $('.confirm').click(function(){
        return confirm("Are You Sure");
    })

    //category view option

    $('.cat h3').click(function(){

        $(this).next('.full-view').fadeToggle(200);

    });

    $('.option span').click(function(){

        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data('view')==='full'){

            $('.cat .full-view').fadeIn(200); 

        }else{
            $('.cat .full-view').fadeOut(200); 
        }

    });
    $('.child-link').hover(function(){
        $(this).find('.show-delete').fadeIn(200);
    },function(){
        $(this).find('.show-delete').fadeOut(200);
    })

});