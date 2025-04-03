'use strict';

//dom ready
jQuery(document).ready(function ($) {
    var cbxmcratingreview_form_awn_options = {
        labels: {
            tip: cbxmcratingreview_ratingform.awn_options.tip,
            info: cbxmcratingreview_ratingform.awn_options.info,
            success: cbxmcratingreview_ratingform.awn_options.success,
            warning: cbxmcratingreview_ratingform.awn_options.warning,
            alert: cbxmcratingreview_ratingform.awn_options.alert,
            async: cbxmcratingreview_ratingform.awn_options.async,
            confirm: cbxmcratingreview_ratingform.awn_options.confirm,
            confirmOk: cbxmcratingreview_ratingform.awn_options.confirmOk,
            confirmCancel: cbxmcratingreview_ratingform.awn_options.confirmCancel
        }
    };

    //apply rating form
    $('.cbxmcratingreviewmainwrap').each(function (index, element) {
        var $wrapper = $(element);
        var $form    = $wrapper.find('.cbxmcratingreview-form');
        var $form_id = parseInt($form.data('form_id'));


        //apply raty plugin to all star rating
        $form.find('.cbxmcratingreview_review_custom_criteria').each(function (index, element) {
            var $element         = $(element);
            var $trigger_element = $element.find('.cbxmcratingreview_rating_trigger');
            var $hints           = $trigger_element.data('hints');

            $trigger_element.cbxmcratingreview_raty({
                cancelHint: cbxmcratingreview_ratingform.rating.cancelHint,
                hints: $hints,
                noRatedMsg: cbxmcratingreview_ratingform.rating.noRatedMsg,
                starType: 'img',
                starHalf: cbxmcratingreview_ratingform.rating.img_path + 'star-half.png',                                // The name of the half star image.
                starOff: cbxmcratingreview_ratingform.rating.img_path + 'star-off.png',                                 // Name of the star image off.
                starOn: cbxmcratingreview_ratingform.rating.img_path + 'star-on.png',                                  // Name of the star image on.
                half: cbxmcratingreview_ratingform.rating.half_rating,
                halfShow: cbxmcratingreview_ratingform.rating.half_rating,
                targetScore: $element.find('.cbxmcratingreview_rating_score')
            });
        });

        $.validator.setDefaults({
            ignore: ":hidden:not(select)"
        }); //for all select

        $.extend($.validator.messages, {
            required: cbxmcratingreview_ratingform.validation.required,
            remote: cbxmcratingreview_ratingform.validation.remote,
            email: cbxmcratingreview_ratingform.validation.email,
            url: cbxmcratingreview_ratingform.validation.url,
            date: cbxmcratingreview_ratingform.validation.date,
            dateISO: cbxmcratingreview_ratingform.validation.dateISO,
            number: cbxmcratingreview_ratingform.validation.number,
            digits: cbxmcratingreview_ratingform.validation.digits,
            creditcard: cbxmcratingreview_ratingform.validation.creditcard,
            equalTo: cbxmcratingreview_ratingform.validation.equalTo,
            maxlength: $.validator.format(cbxmcratingreview_ratingform.validation.maxlength),
            minlength: $.validator.format(cbxmcratingreview_ratingform.validation.minlength),
            rangelength: $.validator.format(cbxmcratingreview_ratingform.validation.rangelength),
            range: $.validator.format(cbxmcratingreview_ratingform.validation.range),
            max: $.validator.format(cbxmcratingreview_ratingform.validation.max),
            min: $.validator.format(cbxmcratingreview_ratingform.validation.min)
        });

        $.validator.addMethod('cbxmcratingreview_multicheckbox', function (value, element) {
            var $parent = $(element).closest('.cbxmcratingreview_q_field_label_multicheckboxes');
            if ($parent.find('.cbxmcratingreview_q_field_option').is(':checked')) return true;
            return false;
        }, cbxmcratingreview_ratingform.validation.cbxmcratingreview_multicheckbox);

        var $require_headline = (cbxmcratingreview_ratingform.review_common_config.require_headline == 1);
        var $require_comment  = (cbxmcratingreview_ratingform.review_common_config.require_comment == 1);


        var $formvalidator = $form.validate({
            ignore: [],
            errorPlacement: function (error, element) {
                if (element.hasClass('cbxmcratingreview_rating_score')) {
                    error.appendTo(element.closest('.cbxmcratingreview_review_custom_criteria'));
                } else if (element.hasClass('cbxmcratingreview_q_field_option_multicheckbox')) {
                    if (element.closest('.cbxmcratingreview_q_field_label_multicheckboxes').find('p.error').length == 0) {
                        error.appendTo(element.closest('.cbxmcratingreview_q_field_label_multicheckboxes'));
                    }
                } else {
                    error.appendTo(element.closest('.cbxmcratingreview-form-field'));
                }

            },
            errorElement: 'p',
            rules: {
                'cbxmcratingreview_ratingForm[headline]': {
                    required: $require_headline,
                    minlength: 2,
                },
                'cbxmcratingreview_ratingForm[comment]': {
                    required: $require_comment,
                    minlength: 10,
                },
            },
            messages: {}
        });


        $formvalidator.focusInvalid = function () {
            // put focus on tinymce on submit validation
            if (this.settings.focusInvalid) {
                try {
                    var toFocus = $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []);
                    if (toFocus.is('textarea')) {
                        /*if (typeof tinyMCE !== 'undefined') {
                            tinyMCE.get(toFocus.attr('id')).focus();
                        }*/
                    } else {
                        toFocus.filter(":visible").focus();
                    }
                } catch (e) {
                    // ignore IE throwing errors when focusing hidden elements
                }
            }
        };


        $form.on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
            if (e.keyCode == 13) {
                return false; // prevent the button click from happening
            }
        });


        //validation done
        $form.submit(function (e) {
            e.preventDefault();

            var $post_id = parseInt($form.data('postid'));
            var $busy    = parseInt($form.data('busy'));

            if(!$formvalidator.valid()){
                new AWN().alert(cbxmcratingreview_ratingform.validation.form_invalid);

                return false;
            }

            if ($formvalidator.valid() && $busy == 0) {
                $form.find('.btn-cbxmcratingreview-submit').prop('disabled', true);
                $form.find('.btn-cbxmcratingreview-submit').addClass('running');

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxmcratingreview_ratingform.ajaxurl,
                    data: $form.serialize() + '&action=cbxmcratingreview_review_rating_frontend_submit' + '&security=' + cbxmcratingreview_ratingform.nonce,// our data object
                    success: function (data) {

                        var $global_msg = $form.prev('.cbxmcratingreview_global_msg');

                        if (data.ok_to_process == 1) {
                            $.each(data.success, function (key, valueObj) {
                                if (key != '' && valueObj != '') {
                                    var $exp_all_msg = '<p class="cbxmcratingreview-alert alert-' + key + '">' + valueObj + '</p>';
                                    $global_msg.html($exp_all_msg);
                                }
                            });

                            $form.data('busy', 0);
                            $form.find('.btn-cbxmcratingreview-submit').prop('disabled', false);
                            $form.find('.btn-cbxmcratingreview-submit').removeClass('running');


                            $form.remove();

                            if (!$.isEmptyObject(data.success.responsedata)) {

                                if (data.success.responsedata.review_html !== undefined && data.success.responsedata.review_html != '') {

                                    var $review_list_html = $('.cbxmcratingreview_review_list_items');
                                    if ($review_list_html.length > 0) {
                                        if ($review_list_html.find('.cbxmcratingreview_review_list_item_notfound').length) {
                                            $review_list_html.find('.cbxmcratingreview_review_list_item_notfound').remove();
                                            $('#cbxmcratingreview_search_wrapper_' + $post_id).show();
                                        }

                                        $review_list_html.prepend(data.success.responsedata.review_html);
                                        cbxmcratingreview_readonlyrating_process($);


                                        var $scroll_to = $global_msg.offset().top - 50;

                                        $('html, body').animate({
                                            scrollTop: $scroll_to
                                        }, 1000);

                                        CBXscRatingReviewEvents_do_action('cbxmcratingreview_review_entry_success', $);
                                    }
                                }
                            }
                        } else {
                            $form.data('busy', 0);
                            $form.find('.btn-cbxmcratingreview-submit').prop('disabled', false);


                            $.each(data.error, function (key, valueObj) {
                                $.each(valueObj, function (key2, valueObj2) {
                                    if (key == 'cbxmcratingreview_questions_error') {
                                        //key2 = question id

                                        var $field_parent = $form.find('#cbxmcratingreview_review_custom_question_' + key2).closest('.cbxmcratingreview-form-field');

                                        if ($field_parent.find('p.error').length > 0) {
                                            $field_parent.find('p.error').html(valueObj2).show();
                                        } else {
                                            $('<p for="cbxmcratingreview_q_field_' + key2 + '" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
                                        }

                                    } else if (key == 'top_errors') {
                                        $.each(valueObj2, function (key3, valueObj3) {
                                            var $exp_all_msg = '<p class="cbxmcratingreview-alert cbxmcratingreview-alert-warning">' + valueObj3 + '</p>';
                                            $form.prev('.cbxmcratingreview_global_msg').html($exp_all_msg);
                                        });
                                    } else {

                                        $form.find("#" + key).addClass('error');
                                        $form.find("#" + key).remove('valid');
                                        var $field_parent = $form.find("#" + key).closest('.cbxmcratingreview-form-field');
                                        if ($field_parent.find('p.error').length > 0) {
                                            $field_parent.find('p.error').html(valueObj2).show();
                                        } else {
                                            $('<p for="' + key + '" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
                                        }

                                    }
                                });
                            });
                        }
                    },
                    complete: function () {
                        //$form.find('.btn-cbxmcratingreview-submit').prop('disabled', false);
                    }
                });
            } else {
                return false;
            }
        });
    });//end each .cbxmcratingreviewmainwrap
});