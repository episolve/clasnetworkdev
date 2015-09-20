(function ($) {
    Drupal.behaviors.bootstrap_theme = {
        attach: function (context, settings) {
            var alert_block = $('.alert-block');
            if (alert_block.length != 0 && $('.user-dashboard').length != 0 && $('.user-dashboard').find(alert_block).length == 0) {
                alert_block.insertBefore($('.dashboard-tab-content form'));
            }

            if ($('.dashboard-search-form').length != 0) {
                $('.dashboard-search-form').append($('#block-search-form'));
            }

            $('.collection-page #collection-contributions table a.remove-button').on('click', function() {
                $('.collection-page #collection-contributions form button[type="submit"]').trigger('click');

                return false;
            });

            // Private Message Inbox Page
            $('body.page-messages .messages-board table.data-table.messages-list tr th a.remove-button').on('click', function() {

                $('body.page-messages .messages-board form input[name="action"]').val('remove');
                $('body.page-messages .messages-board form button[type="submit"]').trigger('click');

                return false;
            });

            /*$('body.page-messages .messages-board table.data-table.messages-list tr th a.block-user-button').on('click', function() {

                $('body.page-messages .messages-board form input[name="action"]').val('blocking-user');
                $('body.page-messages .messages-board form button[type="submit"]').trigger('click');

                return false;
            });*/

            $('#share_with_member_modal .modal-footer button.btn-primary').click(function() {
                $('#share_with_member_form_container form button[type="submit"]').trigger('click');
                return false;
            });

            $('#new_message_modal .modal-footer button.btn-primary').click(function() {
                $('#new_message_form_container form button[type="submit"]').trigger('click');
                return false;
            });

            // Page for pending contributions to approve
            $('#bootstrap_theme_pending_contributions table tr td a.ctb-action-publish').click(function() {

                $('#bootstrap_theme_pending_contributions table tr td:first-child input[type="checkbox"]').attr('checked', false);
                $('#bootstrap_theme_pending_contributions table tr th:first-child input[type="checkbox"]').attr('checked', false);
                $(this).parent().parent().find('td:first-child input[type="checkbox"]').attr('checked', true);
                $('#bootstrap_theme_pending_contributions .form-submit').trigger('click');

                return false;
            });

            $('.profile-edit-form-container .profile-fields-info .profile-field .profile-change-password a').click(function() {
                var password_change_field = $('.profile-edit-form-container .profile-fields-info .profile-fields-change-password');
                if (password_change_field.css('display') == 'none') {
                    password_change_field.slideDown();
                    $('.profile-edit-form-container .profile-fields-info .profile-field .profile-change-password a').html('Hide');
                } else {
                    password_change_field.slideUp();
                    $('.profile-edit-form-container .profile-fields-info .profile-field .profile-change-password a').html('Change Password');
                }

                return false;
            });

            $('.profile-step-button.profile-to-become-contributor button').click(function() {
                $.ajax($(this).attr('data-url'), {
                    'type': 'post',
                    'dataType': 'json',
                    'success': function(response) {
                        window.location.href = response['url'];
                    }
                });
                return false;
            });
        }
    };
})(jQuery);
