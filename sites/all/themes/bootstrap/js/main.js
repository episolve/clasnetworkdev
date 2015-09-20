jQuery(document).ready(function($) {

	if ( $.browser.msie ) {

		if ($.browser.version == "8.0") {
			$(".form-item-pass-pass1").css('display', 'none');
			$(".form-item-pass-pass2").css('display', 'block');
			$("#edit-pass-pass2").val('Set a password');

//			$("#edit-name").css("line-height", "38px");
//			$("#edit-mail").css("line-height", "38px");
//			$("#edit-pass-pass1").css("line-height", "38px");
//			$("#edit-pass-pass2").css("line-height", "38px");
			
			$('[data-placeholder]').focus(function() {
				var input = $(this);
				
				if (input.attr('id') == 'edit-pass-pass2') {
					$("#edit-pass-pass1").val('');
					$(".form-item-pass-pass2").hide();
					$(".form-item-pass-pass1").show();
					$("#edit-pass-pass1").focus();
				}

				if (input.val() == input.attr('data-placeholder')) {
					input.val('');
					input.removeClass('placeholder');
				}
			}).blur(function() {
				var input = $(this);
				
				if (input.attr('id') == 'edit-pass-pass1' && $("#edit-pass-pass1").val() == '') {
					$(".form-item-pass-pass1").hide();
					$(".form-item-pass-pass2").show();
				}

				if (input.val() == '' || input.val() == input.attr('data-placeholder')) {
					input.addClass('placeholder');
					input.val(input.attr('data-placeholder'));
				}
			}).blur().parents('form').submit(function() {
				$(this).find('[data-placeholder]').each(function() {
					var input = $(this);
					if (input.val() == input.attr('data-placeholder')) {
						input.val('');
					}
				})
			});
		}
	}
	
    $('input[type="text"], input[type="password"], textarea').each(function() {
        var placeholder = $(this).attr('data-placeholder');
        if (!placeholder) {
            return true;
        }

        this.type = 'text';
        $(this).val(placeholder);
    });

    $('input[type="text"], input[type="password"], textarea').die('focus');
    $('input[type="text"], input[type="password"], textarea').die('blur');
    $('input[type="text"], input[type="password"], textarea').live('focus', function() {
        var placeholder = $(this).attr('data-placeholder');
        if (!placeholder)
            return true;
        this.value = (this.value == placeholder?'':this.value);

        if ($(this).attr('data-input-type') == 'password')
            this.type = 'password';
    }).live('blur', function() {
            var placeholder = $(this).attr('data-placeholder');
            if (!placeholder)
                return true;

            if ($(this).attr('data-input-type') == 'password' && this.value == '') {
                this.type = 'text';
            }

            this.value = (this.value == ''?placeholder:this.value);
    });
    

    $('#user-register-form button.form-submit').click(function() {
        $('#edit-pass-pass2').val($('#edit-pass-pass1').val());
        return true;
    });
});