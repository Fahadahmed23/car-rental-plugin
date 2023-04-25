jQuery(document).ready(function ($) {

    $('#btn_bg_color').wpColorPicker();
    $('#btn_text_color').wpColorPicker();
    $('#btn_border_color').wpColorPicker();
    $('#input_bg_color').wpColorPicker();
    $('#input_text_color').wpColorPicker();
    $('#input_border_color').wpColorPicker();

    $('.fah_quote_btn_text_single, .fah_quote_btn_text_archive').each(function() {
        $(this).closest('tr').hide();
    });
    
    $(document).on('click', '.fah_show_quote_btn_archive, .fah_show_quote_btn_single', function(e) {
        var value = $(this).val();
        var $element = null;
        
        if($(this).hasClass('fah_show_quote_btn_archive')) {
            $element = $('.fah_quote_btn_text_archive');
        } else if($(this).hasClass('fah_show_quote_btn_single')) {
            $element = $('.fah_quote_btn_text_single');
        }
        
        if($element != null) {
            if($(this).is(':checked') && value == 'yes') {
                $element.closest('tr').fadeIn(100);
            } else {
                $element.closest('tr').fadeOut(100);
            }
        }
        
    });
    
    $(document).on('click', '.fah_type', function(e) {
        var value = $(this).val();
        
        var $ele         = $(this);
        var $infoElement = $ele.closest('td').find('.fah-info');

        //var $element = $('.fah_quote_display_type');
        
        if($(this).is(':checked') && value == 'bulk') {
            $infoElement.html('<pre class="fah-shortcode">[woo_quote_cart limit=""]</pre>')
            $infoElement.fadeIn();

            $('.fah_checkout_text, .fah_cart_page_id').closest('tr').show();

            //$element.closest('tr').fadeOut(100);

        } else {
            $infoElement.fadeOut(100, function() {
                 $infoElement.html('');   
            });

            $('.fah_checkout_text, .fah_cart_page_id').closest('tr').hide();
            //$element.closest('tr').fadeIn(100);
        }
        
    });
    
    $(document).on('click', '.fah_quote_display_type', function(e) {
        
        var value = $(this).val();
        
        /*if($(this).is(':checked') && value == 'shortcode') {
            $(this).closest('td').find('.fah-info').html('<pre class="fah-shortcode">[woo_quote_form]</pre>');
        } else {
            $(this).closest('td').find('.fah-info').html('');
        }*/
        
    });
    
    $('.fah_select_field').selectize();
    
    $(
        '.fah_show_quote_btn_archive:checked'
        + ', .fah_show_quote_btn_single:checked'
        + ', .fah_type:checked'
        + ', .fah_quote_display_type:checked'
        + ', .fah_quote_display_type:checked'

    ).click()

    $(document).on('submit', '#fah-form', function (e) {
        
        e.preventDefault();
        
        $('#fah-notice').hide();
        $('.fah-loading').show();
        
        form = $(this);
        url = form.attr('action');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if(response.status) {
                    $('#fah-setting').prepend(
                            '<div id="fah-notice" class="updated notice notice-success is-dismissible">'
                                + '<p>' 
                                    + (response.message)
                                + '</p>'
                            +'</div>'
                    );
                    
                    $('html, body, root').animate({
                        scrollTop: 0
                    });
                }
            },
            complete: function() {
                $('.fah-loading').hide();
            }
        });
    });
});