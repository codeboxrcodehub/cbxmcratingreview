'use strict';

function cbxmcratingreview_readonlyrating_process($) {
    //any where read only rating
    $('.cbxmcratingreview_readonlyrating_score_js').each(function (index, element) {
        var $element = $(element);


        var $applied = parseInt($element.data('processed'));

        if ($applied == 0) {
            $(element).cbxmcratingreview_raty({
                hints: cbxmcratingreview_public.rating.hints,
                noRatedMsg: cbxmcratingreview_public.rating.noRatedMsg,
                starType: 'img',
                starHalf: cbxmcratingreview_public.rating.img_path + 'star-half.png',                                // The name of the half star image.
                starOff: cbxmcratingreview_public.rating.img_path + 'star-off.png',                                 // Name of the star image off.
                starOn: cbxmcratingreview_public.rating.img_path + 'star-on.png',                                  // Name of the star image on.
                half: cbxmcratingreview_public.rating.half_rating,
                halfShow: cbxmcratingreview_public.rating.half_rating,
                readOnly: true
            });

            $element.data('processed', 1);
        }


    });
}

var cbxmcratingreview_video_filetypes = ["mp4", "m4v", "webm", "ogv", "wmv", "flv"];
var cbxmcratingreview_audio_filetypes = ["mp3", "m4a", "ogg", "wav", "wma"];


/**
 * Get file extension from a file name
 *
 * @param $file_url
 * @returns string
 */
function cbxmcratingreview_getFileExtension(file_url) {
    return file_url.split('.').pop();
}

/**
 * Check if the string is valid url
 * https://stackoverflow.com/a/14582229/341955
 *
 * @param str
 * @returns {boolean}
 */
function cbxmcratingreview_isURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                             '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
                             '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                             '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                             '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                             '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    return pattern.test(str);
}

jQuery(document).ready(function ($) {

    var cbxmcratingreview_public_awn_options = {
        labels: {
            tip: cbxmcratingreview_public.awn_options.tip,
            info: cbxmcratingreview_public.awn_options.info,
            success: cbxmcratingreview_public.awn_options.success,
            warning: cbxmcratingreview_public.awn_options.warning,
            alert: cbxmcratingreview_public.awn_options.alert,
            async: cbxmcratingreview_public.awn_options.async,
            confirm: cbxmcratingreview_public.awn_options.confirm,
            confirmOk: cbxmcratingreview_public.awn_options.confirmOk,
            confirmCancel: cbxmcratingreview_public.awn_options.confirmCancel
        }
    };

    cbxmcratingreview_readonlyrating_process($);

    //delete review
    $('.cbxmcratingreview_review_list_items').on('click', '.cbxmcratingreview-review-delete', function (e) {
        e.preventDefault();

        var $this      = $(this);
        var $review_id = parseInt($this.data('review_id'));
        var $post_id   = parseInt($this.data('post_id'));
        var $busy      = parseInt($this.data('busy'));

        var $list_parent = $this.closest('.cbxmcratingreview_review_list_items');

        if ($busy == 0) {

            var notifier = new AWN(cbxmcratingreview_public_awn_options);
            var onCancel = () => {};

            var onOk = () => {
                $this.data('busy', 1);
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxmcratingreview_public.ajaxurl,
                    data: {
                        action: "cbxmcratingreview_review_delete",
                        security: cbxmcratingreview_public.nonce,
                        review_id: $review_id
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        if (parseInt(data.success) == 1) {

                            $this.closest('.cbxmcratingreview_review_list_item').remove();
                            if ($list_parent.find('.cbxmcratingreview_review_list_item').length == 0) {
                                $list_parent.append(cbxmcratingreview_public.no_reviews_found_html);
                                $('#cbxmcratingreview_search_wrapper_' + $post_id).hide();
                            }
                        } else {
                            $this.data('busy', 0);
                        }

                        new AWN().info(data.message);
                    }// end of success
                });//end of ajax
            };

            notifier.confirm(
                cbxmcratingreview_public.are_you_sure_global,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxmcratingreview_public.are_you_sure_delete_desc
                    }
                }
            );
        }
    });

    //load more reviews
    $('.cbxmcratingreview_post_more_reviews').on('click', 'a.cbxmcratingreview_loadmore', function (event) {
        event.preventDefault();

        var $this        = $(this);
        var $parent      = $this.parent();
        var $review_list = $parent.prev('.cbxmcratingreview_review_list_items');
        var $busy        = parseInt($this.data('busy'));

        //console.log($review_list);


        var $formid  = parseInt($this.data('formid'));
        var $postid  = parseInt($this.data('postid'));
        var $perpage = parseInt($this.data('perpage'));
        var $page    = parseInt($this.data('page'));
        var $maxpage = parseInt($this.data('maxpage'));
        var $orderby = $this.data('orderby');
        var $order   = $this.data('order');


        var $score = $this.data('score');

        var $triggerText = $this.text();

        //console.log($postid);


        if ($busy == 0) {
            $this.data('busy', 1);
            $this.text(cbxmcratingreview_public.load_more_busy_text);
            $.ajax({
                type: "post",
                dataType: "json",
                url: cbxmcratingreview_public.ajaxurl,
                data: {
                    action: "cbxmcratingreview_post_more_reviews",
                    security: cbxmcratingreview_public.nonce,
                    post_id: $postid,
                    form_id: $formid,
                    perpage: $perpage,
                    page: $page,
                    orderby: $orderby,
                    order: $order,
                    //status  : $status,
                    score: $score,
                    load_more: 0
                    //show_filter:0
                },
                success: function (data, textStatus, XMLHttpRequest) {
                    //console.log(data);
                    $this.data('busy', 0);
                    $this.text($triggerText);


                    //console.log(data);
                    $review_list.append(data);
                    cbxmcratingreview_readonlyrating_process($);

                    if ($maxpage == $page) {
                        $parent.remove();
                    } else {
                        $this.data('page', ($page + 1));
                    }

                    //successful load of review items
                    CBXscRatingReviewEvents_do_action('cbxmcratingreview_post_more_reviews_success', $this, $parent, $review_list, $postid, $perpage, $page, $maxpage, $orderby, $order);

                }// end of success
            });// end of ajax
        }


    });

    $('.cbxmcratingreview_search_wrapper').each(function (index, element) {
        var $element          = $(element);
        var $postid           = parseInt($element.data('postid'));
        var $formid           = parseInt($element.data('formid'));
        var $review_list      = $('#cbxmcratingreview_review_list_items_' + $postid);
        var $review_list_more = $('#cbxmcratingreview_post_more_reviews_' + $postid);


        //console.log($formid);


        //for any select field click reload reviews.
        $element.find('.cbxmcratingreview_search_filter_js').on('change', function (event) {
            event.preventDefault();

            var $order   = $element.find('.cbxmcratingreview_search_filter_order').val();
            var $orderby = $element.find('.cbxmcratingreview_search_filter_orderby').val();
            var $score   = $element.find('.cbxmcratingreview_search_filter_score').val();

            //console.log($orderby);

            //var $orderby = $element.data('orderby');
            //var $order   = $element.data('order');

            var $perpage = parseInt($element.data('perpage'));
            var $busy    = parseInt($element.data('busy'));

            if ($busy == 0) {
                $element.data('busy', 1); //disable click before the request is complete

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxmcratingreview_public.ajaxurl,
                    data: {
                        action: "cbxmcratingreview_post_filter_reviews",
                        security: cbxmcratingreview_public.nonce,
                        post_id: $postid,
                        perpage: $perpage,
                        page: 1,
                        orderby: $orderby,
                        order: $order,
                        //status  : 1,
                        score: $score,
                        form_id: $formid
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        //console.log(data);
                        //console.log(data.load_more);
                        $element.data('busy', 0); //allow to click again


                        $review_list.empty();
                        $review_list.append(data.list_html);
                        if (parseInt(data.load_more) == 1) {
                            $review_list_more.show();
                        } else {
                            $review_list_more.hide();
                        }

                        $review_list_more.data('page', 1);
                        $review_list_more.data('orderby', data.orderby);
                        $review_list_more.data('order', data.order);
                        $review_list_more.data('score', data.score);
                        $review_list_more.data('maxpage', data.maxpage);
                    }// end of success
                });// end of ajax
            }

            //console.log($score);
        });
    });//end for each search wrapper


});

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('#quill-editor')) {
        var quill = new Quill('#quill-editor', {
            theme: 'snow'
        });

        // Handle form submission
        var form = document.querySelector('.cbxmcratingreview-form');
        if (form) {
            form.onsubmit = function () {
                // Populate hidden form field with quill content before submitting
                var content   = document.querySelector('input[name="cbxmcratingreview_ratingForm[comment]"]');
                content.value = quill.root.innerHTML;
            };
        }


        var edit_form = document.querySelector('#cbxmcratingreviewcomment-edit-form');
        if (edit_form) {
            form.onsubmit = function () {
                // Populate hidden form field with quill content before submitting
                var content   = document.querySelector('input[name="comment"]');
                content.value = quill.root.innerHTML;
            };
        }
    }

});

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('#quill-editor-extra')) {
        var quill = new Quill('#quill-editor-extra', {
            theme: 'snow'
        });

        // Handle form submission
        var form_extra = document.querySelector('.cbxmcratingreview-form');
        if (form_extra) {
            form_extra.onsubmit = function () {
                // Populate hidden form field with quill content before submitting
                var content   = document.querySelector('input[name="cbxmcratingreview_ratingForm[comment]"]');
                content.value = quill.root.innerHTML;
            };
        }


        var edit_form_extra = document.querySelector('#cbxmcratingreviewcomment-edit-form');
        if (edit_form_extra) {
            form_extra.onsubmit = function () {
                // Populate hidden form field with quill content before submitting
                var content   = document.querySelector('input[name="comment"]');
                content.value = quill.root.innerHTML;
            };
        }
    }
});

