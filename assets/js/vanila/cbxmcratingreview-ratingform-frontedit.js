	'use strict';

	//dom ready
	jQuery(document).ready(function ($) {

		//apply rating edit form
		$('.cbxmcratingreviewmainwrap').each(function (index, element) {
			var $wrapper = $(element);
			var $form    = $wrapper.find('.cbxmcratingreview-form');
			var $log_id = parseInt($form.find('#cbxmcratingreview-review-id').val());


			$form.find('.cbxmcratingreview_review_custom_criteria').each(function (index, element) {
				var $element         = $(element);
				var $trigger_element = $element.find('.cbxmcratingreview_rating_trigger');
				var $hints           = $trigger_element.data('hints');

				$trigger_element.cbxmcratingreview_raty({
					cancelHint : cbxmcratingreview_ratingeditform.rating.cancelHint,
					hints      : $hints,
					noRatedMsg : cbxmcratingreview_ratingeditform.rating.noRatedMsg,
					starType   : 'img',
					starHalf   : cbxmcratingreview_ratingeditform.rating.img_path + 'star-half.png',                                // The name of the half star image.
					starOff    : cbxmcratingreview_ratingeditform.rating.img_path + 'star-off.png',                                 // Name of the star image off.
					starOn     : cbxmcratingreview_ratingeditform.rating.img_path + 'star-on.png',                                  // Name of the star image on.
					half       : cbxmcratingreview_ratingeditform.rating.half_rating,
					halfShow   : cbxmcratingreview_ratingeditform.rating.half_rating,
					targetScore: $element.find('.cbxmcratingreview_rating_score')
				});
			});

			$.validator.setDefaults({
				ignore: ":hidden:not(select)",
			}); //for all select

			$.extend($.validator.messages, {
				required   : cbxmcratingreview_ratingeditform.validation.required,
				remote     : cbxmcratingreview_ratingeditform.validation.remote,
				email      : cbxmcratingreview_ratingeditform.validation.email,
				url        : cbxmcratingreview_ratingeditform.validation.url,
				date       : cbxmcratingreview_ratingeditform.validation.date,
				dateISO    : cbxmcratingreview_ratingeditform.validation.dateISO,
				number     : cbxmcratingreview_ratingeditform.validation.number,
				digits     : cbxmcratingreview_ratingeditform.validation.digits,
				creditcard : cbxmcratingreview_ratingeditform.validation.creditcard,
				equalTo    : cbxmcratingreview_ratingeditform.validation.equalTo,
				maxlength  : $.validator.format(cbxmcratingreview_ratingeditform.validation.maxlength),
				minlength  : $.validator.format(cbxmcratingreview_ratingeditform.validation.minlength),
				rangelength: $.validator.format(cbxmcratingreview_ratingeditform.validation.rangelength),
				range      : $.validator.format(cbxmcratingreview_ratingeditform.validation.range),
				max        : $.validator.format(cbxmcratingreview_ratingeditform.validation.max),
				min        : $.validator.format(cbxmcratingreview_ratingeditform.validation.min)
			});

			$.validator.addMethod('cbxmcratingreview_multicheckbox', function (value, element) {
				var $parent = $(element).closest('.cbxmcratingreview_q_field_label_multicheckboxes');
				if($parent.find('.cbxmcratingreview_q_field_option').is(':checked'))return true;
				return false;
			}, cbxmcratingreview_ratingeditform.validation.cbxmcratingreview_multicheckbox);

			var $require_headline = (cbxmcratingreview_ratingeditform.review_common_config.require_headline == 1);
			var $require_comment  = (cbxmcratingreview_ratingeditform.review_common_config.require_comment == 1);


			var $formvalidator = $form.validate({
				ignore        : [],
				errorPlacement: function (error, element) {
					if(element.hasClass('cbxmcratingreview_rating_score')){
						error.appendTo(element.closest('.cbxmcratingreview_review_custom_criteria'));
					}
					else if(element.hasClass('cbxmcratingreview_q_field_option_multicheckbox')){

						if(element.closest('.cbxmcratingreview_q_field_label_multicheckboxes').find('p.error').length == 0){
							error.appendTo(element.closest('.cbxmcratingreview_q_field_label_multicheckboxes'));

						}
					}
					else{
						error.appendTo(element.closest('.cbxmcratingreview-form-field'));
					}

				},
				errorElement  : 'p',
				rules         : {
					'cbxmcratingreview_ratingForm[headline]': {
						required : $require_headline,
						minlength: 2,
					},
					'cbxmcratingreview_ratingForm[comment]' : {
						required : $require_comment,
						minlength: 10,
					},
				},
				messages      : {}
			});


			$formvalidator.focusInvalid = function () {
				//console.log(this.settings.focusInvalid);
				// put focus on tinymce on submit validation
				if (this.settings.focusInvalid) {
					try {
						var toFocus = $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []);

						if (toFocus.is("textarea")) {
							/*if(typeof tinyMCE !== 'undefined') {
								tinyMCE.get(toFocus.attr("id")).focus();
							}*/
						} else {
							toFocus.filter(":visible").focus();
						}


					} catch (e) {
						// ignore IE throwing errors when focusing hidden elements
					}
				}
			};


			$form.on('keypress', ":input:not(textarea):not([type=submit])", function (e) {
				if (e.keyCode == 13) {
					return false; // prevent the button click from happening
				}
			});



			//validation done
			$form.submit(function (e) {
				e.preventDefault();

				/*if(typeof tinyMCE !== 'undefined') {
					tinyMCE.triggerSave();
				}*/

				var $busy = parseInt($form.data('busy'));

				if ($formvalidator.valid() && $busy == 0) {

					$form.find('.btn-cbxmcratingreview-submit').prop('disabled', true);
					$form.find('.label-cbxmcratingreview-submit-processing').show();

					$.ajax({
						type    : "post",
						dataType: 'json',
						url     : cbxmcratingreview_ratingeditform.ajaxurl,
						data    : $form.serialize() + '&action=cbxmcratingreview_review_rating_front_edit' + '&security=' + cbxmcratingreview_ratingeditform.nonce,// our data object
						success : function (data) {
							if (data.ok_to_process == 1) {
								$.each(data.success, function (key, valueObj) {
									if (key != '' && valueObj != '') {
										var $exp_all_msg = '<p class="cbxmcratingreview-alert alert-' + key + '">' + valueObj + '</p>';
										$form.prev('.cbxmcratingreview_global_msg').html($exp_all_msg);
									}
								});

								$form.data('busy', 0);
								$form.find('.btn-cbxmcratingreview-submit').prop('disabled', false);
								$form.find('.label-cbxmcratingreview-submit-processing').hide();

								var $scroll_to = $("#cbxmcratingreviewmainwrap").offset().top - 150;

								$('html, body').animate({
									scrollTop: $scroll_to
								}, 1000);

								CBXscRatingReviewEvents_do_action('cbxmcratingreview_review_frontedit_success', $);

							} else {
								$form.data('busy', 0);
								$form.find('.btn-cbxmcratingreview-submit').prop('disabled', false);
								$form.find('.label-cbxmcratingreview-submit-processing').hide();
                                $.each(data.error, function (key, valueObj) {
                                    $.each(valueObj, function (key2, valueObj2) {
                                        if(key == 'cbxmcratingreview_questions_error'){
                                            //key2 = question id

                                            var $field_parent = $form.find('#cbxmcratingreview_review_custom_question_'+ key2).closest('.cbxmcratingreview-form-field');

                                            if($field_parent.find('p.error').length > 0){
                                                $field_parent.find('p.error').html(valueObj2).show();
                                            }
                                            else{
                                                $('<p for="cbxmcratingreview_q_field_'+key2+'" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
                                            }

                                        }
                                        else if (key == 'top_errors') {
                                            $.each(valueObj2, function (key3, valueObj3) {
                                                var $exp_all_msg = '<p class="cbxmcratingreview-alert cbxmcratingreview-alert-warning">' + valueObj3 + '</p>';
                                                $form.prev('.cbxmcratingreview_global_msg').html($exp_all_msg);
                                            });
                                        }
                                        else {

                                            $form.find("#" + key).addClass('error');
                                            $form.find("#" + key).remove('valid');
                                            var $field_parent = $form.find("#" + key).closest('.cbxmcratingreview-form-field');
                                            if($field_parent.find('p.error').length > 0){
                                                $field_parent.find('p.error').html(valueObj2).show();
                                            }
                                            else{
                                                $('<p for="'+key+'" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
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
				}
				else {
					return false;
				}
			});




		}); //form submit ends
	});

