/*
 * basic functionality used on many pages
 */

/**
 * log value to console
 * @param sValue mixed
 */
function _d(mValue) {
    if (console) {
        if (console.log) {
            console.log(mValue);
        }
    } else {
        return false;
    }
    return true;
}

/**
 * left menu toggle left
 */
function goLeftMenu() {
    $("#main-leftColumn").addClass('small');
    $("#main-rightColumn").removeClass('padding');
    $.cookie("left-menu", 2, {
        path: "/",
        expires: 10
    });
    $(this).hide();
}

/**
 * left menu toggle right
 */
function goRightMenu() {
    $("#main-leftColumn").removeClass('small');
    $("#main-rightColumn").addClass('padding');
    $.cookie("left-menu", 1, {
        path: "/",
        expires: 10
    });
    $(this).hide();
}

/**
 * confirm a onclick
 * @param subject string
 * @param addToSentence string
 * @return mixed true/false/redirect
 */
function confirmChoice(subject, addToSentence) {

    var message = jsTranslations['js_sure_delete_1'] + ' "' + subject + '" ' + jsTranslations['js_sure_delete_2'];
    if (typeof (addToSentence) !== 'undefined') {
        message = message + " " + addToSentence;
    }
    return confirm(message);
}

/**
 * show the status update
 * @param message string
 */
function showStatusUpdate(message, type) {

    if (type == 'success') {

        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Succes / Succesvol',
            subtitle: '',
            autohide: true,
            delay: 1750,
            body: message
        })
        //alertify.success('<i class="fa fa-check" aria-hidden="true"></i> ' + message);
    } else if (type == 'error') {

        $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'Warning / Waarschuwing',
            subtitle: '',
            autohide: true,
            delay: 1750,
            body: message
        })
        //alertify.error('<i class="fa fa-exclamation-circle" aria-hidden="true"></i> ' + message);
    } else if (type == 'warning') {

        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Succes',
            subtitle: '',
            autohide: true,
            delay: 1750,
            body: message
        })
    } else {

        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Succes',
            subtitle: '',
            autohide: true,
            delay: 1750,
            body: message
        })
    }
}


/**
 * initialize tinymce default
 */
function initTinyMCE(selector, link_list, image_list, image, width, content_css, lang) {

    if (lang === undefined) {
        lang = 'nl';
    }

    if (image_list) {
        image = true;
    }

    if (content_css === undefined) {
        content_css = [
            '/themes/' + siteTemplate + '/css/layout.css?cache=' + new Date().getTime(),
            '/modules/core/admin/css/tinyMCE-fix.css'
        ]; //website stylesheet frontend
    }

    var mydata = null;
    $.get("/dashboard/paginas/button-dialog", function (data) {
        mydata = JSON.parse(data);
    });

    //tiny_MCE initiation
    tinymce.init({
        // General options
        selector: selector,
        theme: 'modern',
        plugins: 'paste tabfocus table autolink code link image visualchars visualblocks searchreplace charmap autoresize contextmenu',
        content_css: content_css,
        body_class: 'content',
        resize: 'both',
        convert_urls: false,
        relative_urls: false,
        paste_as_text: true,
        language: lang,
        image_advtab: true,
        table_default_attributes: {
            class: 'table is-striped'
        },
        menu: {
            edit: {
                title: 'Edit',
                items: 'undo redo | cut copy paste | selectall'
            },
            insert: {
                title: 'Insert',
                items: (image ? ' image' : '') + ' link charmap'
            },
            table: {
                title: 'Table',
                items: 'inserttable | cell row column deletetable'
            },
            tools: {
                title: 'Tools',
                items: 'visualchars visualblocks searchreplace code'
            }
        },
        toolbar1: 'bold italic underline strikethrough superscript subscript removeformat | alignleft  aligncenter  alignright | pastetext',
        toolbar2: 'undo redo | formatselect | bullist numlist | charmap | link unlink | buttonlink ' + (image ? '| image' : '') + ' | addform',
        block_formats: 'Paragraph=p;Header 2=h2;Header 3=h3',
        width: width,
        autoresize_min_height: 50,
        autoresize_max_height: 500,
        contextmenu: 'link image inserttable | cell row column deletetable',
        extended_valid_elements: 'img[!src|border:0|alt|title|width|height|style],a[name|href|target|title|onclick|class]',
        image_list: image_list,
        link_list: link_list,
        statusbar: false,
        setup: function (ed) {
            ed.on('keyup', function (e) {
                if ($("#frame").contents().find("#cmsContent")) {
                    $("#frame").contents().find("#cmsContent").html(ed.getContent());
                    // $('#frame').contents().scrollTop($('#cmsContent').position().top);
                    udatePageHeight();
                }
            });

            ed.addButton("buttonlink", {
                type: "Button",
                text: "Knop",
                icon: "link",
                onclick: function () {
                    ed.windowManager.open({
                        title: "Knop maken",
                        body: [
                            {
                                type: "textbox",
                                name: "button_name",
                                label: "Knop tekst",
                                value: ""
                            },
                            {
                                type: "listbox",
                                name: "button_link",
                                label: "Link naar",
                                values: mydata

                            }
                        ],
                        onsubmit: function (e) {
                            ed.insertContent('<a class="button is-primary" href="' + e.data.button_link + '">' + e.data.button_name + '</a>');
                        }

                    });

                    return false;
                }

            });

            ed.addButton("addform", {
                type: 'Button',
                text: 'Form tag toevoegen',
                icon: 'table',
                tooltip: 'Voeg de form tag toe om het form tussen content te plaatsen. Wanneer de tag niet beschikbaar is, wordt het form onderaan de pagina geplaatst. Wanneer er geen form is gekoppeld, wordt het form tag niet weergegeven op de pagina',
                onClick: function () {
                    ed.insertContent('<p>{{form}}</p>');
                }
            });
        }


    });
}

/**
 * initialize tinymce mini
 */
function initTinyMCEMini(selector, width, content_css, lang, height) {
    var max_height = 250;
    if (height === undefined) {
        var min_height = 30;
    } else {
        var min_height = height;
    }


    if (lang === undefined) {
        lang = 'nl';
    }

    if (content_css === undefined) {
        content_css = [
            '/themes/' + siteTemplate + '/css/layout.css?cache=' + new Date().getTime(),
            '/modules/core/admin/css/tinyMCE-fix.css'
        ]; //website stylesheet frontend
    }


    //tiny_MCE initiation
    tinymce.init({
        // General options
        selector: selector,
        theme: 'modern',
        plugins: 'paste tabfocus autoresize',
        content_css: content_css,
        resize: 'both',
        convert_urls: false,
        relative_urls: false,
        paste_as_text: true,
        language: lang,
        menu: false,
        toolbar1: 'bold italic underline | bullist numlist | pastetext',
        toolbar2: '',
        block_formats: '',
        autoresize_min_height: min_height,
        autoresize_max_height: max_height,
        autoresize_bottom_margin: 0,
        contextmenu: false,
        menubar: false,
        statusbar: false
    });
}

/**
 * set table sorter functionality
 */
function setTableSorterStuff() {
    // table sorter set widgte to zebra
    $.tablesorter.defaults.widgets = ['zebra'];
    // extra tablesorter parser for Dutch date format: dd/mm/yyyy nothing in front of the values!!
    $.tablesorter.addParser({
        id: 'dateNL',
        is: function (s) {
            return false;
        },
        format: function (s) {
            var date = s.match(/(\d{1,2})[\/\-]?(\d{1,2})[\/\-]?(\d{4})( (\d{1,2}):(\d{1,2})(:(\d{1,2}))?)?$/);
            if (date === null) {
                return '' + 0;
            }

            var d = String(date[1]);
            var m = String(date[2]);
            var y = String(date[3]);
            var H = (date[5] ? String(date[5]) : "00");
            var M = (date[6] ? String(date[6]) : "00");
            var S = (date[8] ? String(date[8]) : "00");
            if (d.length === 1) {
                d = "0" + d;
            }
            if (m.length === 1) {
                m = "0" + m;
            }
            if (y.length === 2) {
                y = "20" + y;
            }

            if (H.length === 1) {
                H = "0" + H;
            }
            if (M.length === 1) {
                M = "0" + M;
            }
            if (S.length === 1) {
                S = "0" + S;
            }

            return '' + y + m + d + H + M + S;
        },
        type: 'numeric'
    });
    //add tablesorter to tables with sorted class
    $("table.sorted").tablesorter();
}

// match regular expression
$.validator.addMethod("expression", function (value, element, param) {
    try {
        var match = param.match(new RegExp('^/(.*?)/([gimy]*)$'));
        var regex = new RegExp(match[1], match[2]);
    } catch (e) {
        alert(e);
        return false;
    }
    return this.optional(element) || value.match(regex);
}, $.validator.format("Enter a valid value"));
// match tinyMCE content !empty
$.validator.addMethod("required-tmce", function (value, element, param) {

    try {
        var value = tinyMCE.get(param).getContent();
    } catch (e) {
        return false;
    }

    return this.optional(element) || value;
}, $.validator.format("Enter a valid value"));

/**
 * set default validation trough jqeury
 * also add errors div construction for fancybox showing errors
 */
function setDefaultValidationStuff() {

    /* validate form with fancybox */
    $("form.validateForm").each(function () {
        $(this).validate({
            focusInvalid: true,
            errorLabelContainer: "#validationErrors ul#errors",
            wrapper: "li",
            invalidHandler: function (form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    setTimeout("$(\"#validationErrorsLink\").click();", 200);
                }
            },
            ignore: ":hidden:not('.do-validate')"
        });
    });
}

// set new validation with errorBox
function setValidateForform(selector, submithandler) {
    if (typeof submithandler !== 'function') {
        submithandler = function (form) {
            form.submit();
        };
    }

    $(selector).each(function () {
        var jForm = this;
        $(this).validate({
            focusInvalid: true,
            onkeyup: false,
            errorContainer: $(jForm).find('.errorBox'),
            errorLabelContainer: $(jForm).find('.errorBox ul'),
            invalidHandler: function (form, validator) {
                if (validator.numberOfInvalids()) {
                    $(jForm).find('.errorBox').show();
                }
            },
            submitHandler: submithandler,
            wrapper: 'li',
            meta: 'validate'
        });
    });
}

/**
 * remove submit button and show ajax-loader
 * @param submitID string if of the submit element to replace
 * @param loaderClass string class of the loader to display
 */
function hideSubmitShowAjaxLoader(submitID, loaderClass) {
    $('#' + submitID).closest('form').find('.' + loaderClass).css('display', 'block');
    $('#' + submitID).remove();
    return true;
}

/**
 * delete an image and on success its placeholder
 * @param element clicked <a> element
 */
function deleteImage(element) {

    // confirm deleting image
    if (confirmChoice(jsTranslations['js_this_image']) !== true) {
        return false;
    }

    jElement = $(element);
    var addition = jElement.closest('.im_container').data('addition');
    var imageId = jElement.closest('.placeholder').attr("imageId");
    var jContainerElement = jElement.closest('.im_container');
    $.ajax({
        type: 'POST',
        url: jElement.attr("href"),
        data: 'imageId=' + imageId + '&SecurityID=' + jElement.closest('.images').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {

            }

            if (dataObj.success === true) {
                $("div.images #placeholder-" + dataObj.imageId).hide(750, function () {
                    $(this).remove();
                    //if (jContainerElement.find('.images .placeholder').length() === 0) {
                    //    jContainerElement.find('.imagesText').hide();
                    //    jContainerElement.find('.noImagesText').show();
                    //}

                    // if checkMaxImages exists, call it
                    //if (typeof globalFunctions['checkMaxImages' + addition] === 'function') {
                    //    globalFunctions['checkMaxImages' + addition]();
                    //}

                    // if updateCoverImageAfterDelete exists, call it
                    //if (typeof globalFunctions['updateCoverImageAfterDelete' + addition] === 'function') {
                    //    globalFunctions['updateCoverImageAfterDelete' + addition](imageId);
                    //}
                });
                showStatusUpdate(jsTranslations['js_image_deleted']);
            } else {
                showStatusUpdate(jsTranslations['js_image_not_deleted']);
            }
        }
    });
    return true;
}

/**
 * set an image to online/offline
 * @param element (clicked <a> element)
 */
function setOnlineImage(element) {

    jElement = $(element);
    $.ajax({
        type: 'POST',
        url: jElement.attr("href"),
        data: 'imageId=' + jElement.closest('.placeholder').attr("imageId") + '&online=' + jElement.attr("online") + '&SecurityID=' + jElement.closest('.images').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                // set online/offline btn to right value value will be online?
                if (dataObj.online === 1) {
                    $("div.images #placeholder-" + dataObj.imageId + " a.onlineOfflineBtn").attr("online", 1).removeClass('online_icon').addClass('offline_icon');
                    $("div.images #placeholder-" + dataObj.imageId + "").effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_image_offline']);
                } else {
                    $("div.images #placeholder-" + dataObj.imageId + " a.onlineOfflineBtn").attr("online", 0).removeClass('offline_icon').addClass('online_icon');
                    $("div.images #placeholder-" + dataObj.imageId + "").effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_image_offline']);
                }
            } else {
                $("div.images #placeholder-" + dataObj.imageId + "").effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_image_not_changed']);
            }
        }
    });
}

/**
 * edit an image
 * @param element element to get data from
 */
function showEditImage(element) {

    jElement = $(element);
    jPlaceholder = $(element).closest(".placeholder");
    // set src for editing image title
    $('#editImageForm form').attr('action', jElement.attr("href"));
    $('#editImageForm img').attr('src', jPlaceholder.find('img').attr('src'));
    $('#editImageForm input[name="title"]').val(jPlaceholder.find('img').attr('title'));
    $('#editImageForm input[name="imageId"]').val(jPlaceholder.attr("imageId"));
    $('#editImageFormLink').click();
}

/**
 * save image from form
 * @param element (form element)
 */
function saveImage(element) {
    var jForm = $(element);
    $.ajax({
        type: 'POST',
        url: jForm.attr("action"),
        data: jForm.serialize(),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                //change title from image in list
                $("div.images #placeholder-" + dataObj.imageId + " img").attr("title", dataObj.title).attr("alt", dataObj.title);
                $.fancybox.close(); //close fancybox after saving
                setTimeout(function () {
                    $("div.images #placeholder-" + dataObj.imageId + "").effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_image_title_changed']);
                }, 800);// timeout for closing fancybox first
            } else {
                showStatusUpdate(jsTranslations['js_image_not_changed']);
            }
        }
    });
}

/**
 * save image order
 * @param imageIds int
 * @param url string url to save order
 * @param containerID string id of the overall container
 */
function updateImageOrder(imageIds, url, containerID, security) {
    $.ajax({
        type: 'POST',
        url: url,
        data: 'imageIds=' + imageIds.join(',') + '&SecurityID=' + security,
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                $('#' + containerID + " div.images > div.placeholder").effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_image_order_changed']);
            } else {
                showStatusUpdate(jsTranslations['js_image_order_not_changed']);
            }
        }
    });
}

/**
 * save file order
 * @param mediaIds int
 * @param url string url to save order
 * @param containerID string id of the overall container
 */
function updateFileOrder(mediaIds, url, containerID, security) {
    $.ajax({
        type: 'POST',
        url: url,
        data: 'mediaIds=' + mediaIds.join(',') + '&SecurityID=' + security,
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                $("#" + containerID + " ul.files > li.placeholder").effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_files_order_changed']);
            } else {
                showStatusUpdate(jsTranslations['js_file_not_changed']);
            }
        }
    });
}

/**
 * set a file to online/offline
 * @param element (clicked <a> element)
 */
function setOnlineFile(element) {
    jElement = $(element);
    $.ajax({
        type: 'POST',
        url: jElement.attr('href'),
        data: 'mediaId=' + jElement.closest('.placeholder').data('mediaid') + '&online=' + jElement.data('online') + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                // set online/offline btn to right value value will be online?
                if (dataObj.online === 1) {
                    $('ul.files #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 1).removeClass('online_icon').addClass('offline_icon');
                    $('ul.files #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_files_offline']);
                } else {
                    $('ul.files #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 0).removeClass('offline_icon').addClass('online_icon');
                    $('ul.files #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_files_online']);
                }
            } else {
                $('ul.files #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_file_not_changed']);
            }
        }
    });
}

/**
 * edit a file
 * @param element element to get data from
 */
function showEditFile(element) {

    jElement = $(element);
    jPlaceholder = $(element).closest('.placeholder');
    // set src for editing image title
    $('#editFileForm form').attr('action', jElement.attr('href'));
    $('#editFileForm input[name="title"]').val(jPlaceholder.attr('data-title'));
    $('#editFileForm input[name="mediaId"]').val(jPlaceholder.attr("data-mediaid"));
    $('#editFileFormLink').click();
}

/**
 * save file from form
 * @param element (form element)
 */
function saveFile(element) {
    var jForm = $(element);
    $.ajax({
        type: 'POST',
        url: jForm.attr('action'),
        data: jForm.serialize(),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                //change title from image in list
                $('ul.files #placeholder-' + dataObj.mediaId).attr('data-title', dataObj.title);
                $('ul.files #placeholder-' + dataObj.mediaId + ' .filePlaceholder').attr('title', dataObj.title ? dataObj.title : dataObj.name);
                $('ul.files #placeholder-' + dataObj.mediaId + ' .filePlaceholder .title').html(dataObj.title ? dataObj.title : dataObj.name);
                $.fancybox.close(); //close fancybox after saving
                setTimeout(function () {
                    $('ul.files #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_file_title_changed']);
                }, 800);// timeout for closing fancybox first
            } else {
                showStatusUpdate(jsTranslations['js_file_not_changed']);
            }
        }
    });
}

/**
 * delete a file and on success its placeholder
 * @param element clicked <a> element
 */
function deleteFile(element) {

    // confirm deleting image
    if (confirmChoice('dit bestand') !== true) {
        return false;
    }

    jElement = $(element);
    var addition = jElement.closest('.fm_container').data('addition');
    var mediaId = jElement.closest('.placeholder').attr("data-mediaid");
    var jContainerElement = jElement.closest('.fm_container');

    $.ajax({
        type: 'POST',
        url: jElement.attr('href'),
        data: 'mediaId=' + mediaId + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                $('ul.files #placeholder-' + dataObj.mediaId).hide(750, function () {
                    $(this).remove();
                    // if checkMaxImages exists, call it
                    if (typeof globalFunctions['checkMaxFiles' + addition] === 'function') {
                        globalFunctions['checkMaxFiles' + addition]();
                    }
                });
                showStatusUpdate(jsTranslations['js_file_deleted']);
            } else {
                showStatusUpdate(jsTranslations['js_file_not_deleted']);
            }
        }
    });
    return true;
}

/**
 * save link order
 * @param mediaIds int
 * @param url string url to save order
 * @param containerID string id of the overall container
 */
function updateLinkOrder(mediaIds, url, containerID, security) {
    $.ajax({
        type: 'POST',
        url: url,
        data: 'mediaIds=' + mediaIds.join(',') + '&SecurityID=' + security,
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                $("#" + containerID + " ul.links > li.placeholder").effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_link_order_changed']);
            } else {
                showStatusUpdate(jsTranslations['js_link_order_not_changed']);
            }
        }
    });
}

/**
 * set a link to online/offline
 * @param element (clicked <a> element)
 */
function setOnlineLink(element) {

    jElement = $(element);
    $.ajax({
        type: 'POST',
        url: jElement.attr('href'),
        data: 'mediaId=' + jElement.closest('.placeholder').data('mediaid') + '&online=' + jElement.data('online') + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                // set online/offline btn to right value value will be online?
                if (dataObj.online === 1) {
                    $('ul.links #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 1).removeClass('online_icon').addClass('offline_icon');
                    $('ul.links #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_link_offline']);
                } else {
                    $('ul.links #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 0).removeClass('offline_icon').addClass('online_icon');
                    $('ul.links #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_link_online']);
                }
            } else {
                $('ul.links #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_link_not_changed']);
            }
        }
    });
}

/**
 * edit a link
 * @param element element to get data from
 */
function showEditLink(element) {

    jElement = $(element);
    jPlaceholder = $(element).closest(".placeholder");
    // set src for editing image title
    $('#editLinkForm form').attr('action', jElement.attr('href'));
    $('#editLinkForm input[name="title"]').val(jPlaceholder.data('title'));
    $('#editLinkForm input[name="link"]').val(jPlaceholder.data('link'));
    $('#editLinkForm input[name="mediaId"]').val(jPlaceholder.data("mediaid"));
    $('#editLinkFormLink').click();
}

/**
 * save file from form
 * @param element (form element)
 */
function saveLink(element) {
    var jForm = $(element);
    $.ajax({
        type: 'POST',
        url: jForm.attr('action'),
        data: jForm.serialize(),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                //change title from image in list
                $('ul.links #placeholder-' + dataObj.mediaId).data('title', dataObj.title);
                $('ul.links #placeholder-' + dataObj.mediaId + ' .linkPlaceholder').attr('title', dataObj.link);
                $('ul.links #placeholder-' + dataObj.mediaId + ' .linkPlaceholder .title').html(dataObj.title ? dataObj.title : dataObj.link);
                $('ul.links #placeholder-' + dataObj.mediaId).data('url', dataObj.link);
                $('ul.links #placeholder-' + dataObj.mediaId + ' .linkPlaceholder a').attr('href', dataObj.link);
                $.fancybox.close(); //close fancybox after saving
                setTimeout(function () {
                    $('ul.links #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_link_saved']);
                }, 800);// timeout for closing fancybox first
            } else {
                showStatusUpdate(jsTranslations['js_link_order_not_changed']);
            }
        }
    });
}

/**
 * delete a file and on success its placeholder
 * @param element clicked <a> element
 */
function deleteLink(element) {

    // confirm deleting image
    if (confirmChoice('deze link') !== true) {
        return false;
    }

    jElement = $(element);
    $.ajax({
        type: 'POST',
        url: jElement.attr('href'),
        data: 'mediaId=' + jElement.closest('.placeholder').data('mediaid') + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                $('ul.links #placeholder-' + dataObj.mediaId).hide(750, function () {
                    $(this).remove();
                });
                showStatusUpdate(jsTranslations['js_link_deleted']);
            } else {
                showStatusUpdate(jsTranslations['js_link_not_deleted']);
            }
        }
    });
    return true;
}

/**
 * save videoLink order
 * @param mediaIds int
 * @param url string url to save order
 * @param containerID string id of the overall container
 */
function updateVideoLinkOrder(mediaIds, url, containerID, security) {
    $.ajax({
        type: 'POST',
        url: url,
        data: 'mediaIds=' + mediaIds.join(',') + '&SecurityID=' + security,
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                $('#' + containerID + ' ul.videoLinks > li.placeholder').effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_video_order_changed']);
            } else {
                showStatusUpdate(jsTranslations['js_video_not_changed']);
            }
        }
    });
}

/**
 * set a link to online/offline
 * @param element (clicked <a> element)
 */
function setOnlineVideoLink(element) {

    jElement = $(element);
    $.ajax({
        type: 'POST',
        url: jElement.attr('href'),
        data: 'mediaId=' + jElement.closest('.placeholder').data('mediaid') + '&online=' + jElement.data('online') + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                // set online/offline btn to right value value will be online?
                if (dataObj.online === 1) {
                    $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 1).removeClass('online_icon').addClass('offline_icon');
                    $('ul.videoLinks #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_link_offline']);
                } else {
                    $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' a.onlineOfflineBtn').data('online', 0).removeClass('offline_icon').addClass('online_icon');
                    $('ul.videoLinks #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_link_online']);
                }
            } else {
                $('ul.videoLinks #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                showStatusUpdate(jsTranslations['js_video_not_changed']);
            }
        }
    });
}

/**
 * edit a videoLink
 * @param element element to get data from
 */
var showEditVideoLink = function (element) {

    jElement = $(element);
    jPlaceholder = $(element).closest(".placeholder");
    // set src for editing videoLink
    $('#editVideoLinkForm form').attr('action', jElement.attr('href'));
    $('#editVideoLinkForm input[name="title"]').val(jPlaceholder.data('title'));
    $('#editVideoLinkForm input[name="link"]').val(jPlaceholder.data('link'));
    $('#editVideoLinkForm input[name="mediaId"]').val(jPlaceholder.data("mediaid"));
    $('#editVideoLinkFormLink').click();
}

/**
 * save videoLink from form
 * @param element (form element)
 */
var saveVideoLink = function (element) {
    var jForm = $(element);
    $.ajax({
        type: 'POST',
        url: jForm.attr('action'),
        data: jForm.serialize(),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }
            if (dataObj.success === true) {
                //change title from image in list
                $('ul.videoLinks #placeholder-' + dataObj.mediaId).data('title', dataObj.title);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId).data('link', dataObj.link);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoLinkPlaceholder').attr('title', dataObj.link);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoLinkPlaceholder .title').html(dataObj.title);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoLinkPlaceholder a').attr('href', dataObj.embedLink);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoThumbsPlaceholder img.thumb1').attr('src', dataObj.thumbLink1);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoThumbsPlaceholder img.thumb2').attr('src', dataObj.thumbLink2);
                $('ul.videoLinks #placeholder-' + dataObj.mediaId + ' .videoThumbsPlaceholder img.thumb3').attr('src', dataObj.thumbLink3);
                $.fancybox.close(); //close fancybox after saving
                setTimeout(function () {
                    $('ul.videoLinks #placeholder-' + dataObj.mediaId).effect('highlight', 1500);
                    showStatusUpdate(jsTranslations['js_video_saved']);
                }, 800);// timeout for closing fancybox first
            } else {
                showStatusUpdate(jsTranslations['js_video_not_changed']);
            }
        }
    });
}

/**
 * delete a videoLink and on success its placeholder
 * @param element clicked <a> element
 */
var deleteVideoLink = function (element) {

    // confirm deleting image
    if (confirmChoice('deze video link') !== true) {
        return false;
    }

    jElement = $(element);
    var addition = jElement.closest('.ym_container').data('addition');
    var jContainerElement = jElement.closest('.ym_container');
    $.ajax({
        type: 'POST',
        url: jElement.attr("href"),
        data: 'mediaId=' + jElement.closest('.placeholder').data("mediaid") + '&SecurityID=' + jElement.closest('ul').data('security'),
        success: function (data) {
            var dataObj = null;
            try {
                dataObj = $.parseJSON(data);
            } catch ($e) {
            }

            if (dataObj.success === true) {
                $("ul.videoLinks #placeholder-" + dataObj.mediaId).hide(750, function () {
                    $(this).remove();
                    if (jContainerElement.find('.videoLinks .placeholder').size() === 0) {
                        jContainerElement.find('.videoLinksText').hide();
                        jContainerElement.find('.noVideoLinksText').show();
                    }

                    // if checkMaxImages exists, call it
                    if (typeof globalFunctions['checkMaxVideoLinks' + addition] === 'function') {
                        globalFunctions['checkMaxVideoLinks' + addition]();
                    }

                });
                showStatusUpdate(jsTranslations['js_video_deleted']);
            } else {
                showStatusUpdate(jsTranslations['js_video_not_deleted']);
            }
        }
    });
    return true;
}

/**
 * set datepicker region values
 */
function setDatepickerRegionValues() {
    $.datepicker.regional['nl'] = {
        closeText: 'Sluiten',
        prevText: '←',
        nextText: '→',
        currentText: 'Vandaag',
        monthNames: [
            'januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'
        ],
        monthNamesShort: [
            'jan', 'feb', 'maa', 'apr', 'mei', 'jun',
            'jul', 'aug', 'sep', 'okt', 'nov', 'dec'
        ],
        dayNames: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
        dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],
        dayNamesMin: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        weekHeader: 'Wk',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.regional['en'] = {
        closeText: 'Close',
        prevText: '←',
        nextText: '→',
        currentText: 'Today',
        monthNames: [
            'january', 'february', 'march', 'april', 'may', 'june',
            'july', 'august', 'september', 'october', 'november', 'december'
        ],
        monthNamesShort: [
            'jan', 'feb', 'mar', 'apr', 'may', 'jun',
            'jul', 'aug', 'sep', 'oct', 'nov', 'dec'
        ],
        dayNames: ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
        dayNamesShort: ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
        dayNamesMin: ['su', 'mo', 'tu', 'we', 'th', 'fr', 'sa'],
        weekHeader: 'Wk',
        dateFormat: 'mm-dd-yy',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '←',
        nextText: '→',
        currentText: 'Hoy',
        monthNames: [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ],
        monthNamesShort: [
            'ene', 'feb', 'mar', 'abr', 'may', 'jun',
            'jul', 'ago', 'sep', 'oct', 'nov', 'dic'
        ],
        dayNames: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
        dayNamesShort: ['dom', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab'],
        dayNamesMin: ['do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
}

/**
 * set timepicker region values
 */
function setTimepickerRegionValues() {
    $.timepicker.regional['nl'] = {
        currentText: 'Huidige tijd',
        closeText: 'Klaar',
        ampm: false,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        timeFormat: 'HH:mm',
        timeSuffix: '',
        timeOnlyTitle: 'Kies tijd',
        timeText: 'Tijd',
        hourText: 'Uren',
        minuteText: 'Minuten',
        secondText: 'Seconden',
        millisecText: 'Milliseconden',
        timezoneText: 'Tijd zone'
    };
    $.timepicker.regional['en'] = {
        currentText: 'Current time',
        closeText: 'Done',
        ampm: true,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        timeFormat: 'HH:mm',
        timeSuffix: '',
        timeOnlyTitle: 'Select time',
        timeText: 'Time',
        hourText: 'Hours',
        minuteText: 'Minutes',
        secondText: 'Seconds',
        millisecText: 'Milliseconds',
        timezoneText: 'Time zone'
    };
    $.timepicker.regional['es'] = {
        currentText: 'Hora actual',
        closeText: 'Hecho',
        ampm: false,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        timeFormat: 'HH:mm',
        timeSuffix: '',
        timeOnlyTitle: 'Elija hora',
        timeText: 'Hora',
        hourText: 'Horas',
        minuteText: 'Minutos',
        secondText: 'Secondes',
        millisecText: 'Milisegundos',
        timezoneText: 'Zona horario'
    };
}

/**
 * attach a random pass button to a input
 * generate pass on click and put in input
 */
(function ($) {
    $.fn.randomPass = function (options) {

        var defaults = {
            length: 16,
            charsLower: "abcdefghijkmnpqrstuvwxyz",
            charsUpper: "ABCDEFGHIJKLMNPQRSTUVWXYZ",
            numbers: "23456789",
            specialChars: "!@#$%^&*()?",
            btnClass: "action_icon present_icon",
            minLower: 1, // minimum number of lower case chars
            minUpper: 1, // minimum number of upper case chars
            minNumber: 1, // minimum number of number chars
            minSpecial: 1 // minimum number of spacial chars
        };
        var o = $.extend(defaults, options);

        /**
         * generate a random password
         * @param length integer
         * @param charsLower string
         * @param charsUpper string
         * @param numbers string
         * @param specialChars string
         * @param minLower integer
         * @param minUpper integer
         * @param minNumber integer
         * @param minSpecial integer
         */
        function randomPassword(length, charsLower, charsUpper, numbers, specialChars, minLower, minUpper, minNumber, minSpecial) {

            var charLowerArr = charsLower.split("");
            var charUpperArr = charsUpper.split("");
            var numberArr = numbers.split("");
            var specialArr = specialChars.split("");
            //all characters together
            var totalArr = charUpperArr.concat(numberArr, specialArr, charLowerArr);
            var passChars = ""; //all chars for generating a password
            //for minimum number of lower case chars
            for (var i = 0; i < minLower; i++) {
                var index = Math.floor(Math.random() * charLowerArr.length);
                passChars += charLowerArr[index];
            }

            //for minimum number of upper case chars
            for (var i = 0; i < minUpper; i++) {
                var index = Math.floor(Math.random() * charUpperArr.length);
                passChars += charUpperArr[index];
            }

            //for minimum number of number chars
            for (var i = 0; i < minNumber; i++) {
                var index = Math.floor(Math.random() * numberArr.length);
                passChars += numberArr[index];
            }

            //for minimum number of upper case chars
            for (var i = 0; i < minSpecial; i++) {
                var index = Math.floor(Math.random() * specialArr.length);
                passChars += specialArr[index];
            }

            //add chars to pass chars string until enough chars
            for (var i = passChars.length; i < length; i++) {
                var index = Math.floor(Math.random() * totalArr.length);
                passChars += totalArr[index];
            }

            //convert passChars string to an array
            var passCharsArr = passChars.split("");
            var pass = ''; //real password for input field
            //shuffle password chars for real random password
            for (var i = 0; i < length; i++) {
                var index = Math.floor(Math.random() * passCharsArr.length);
                pass += passCharsArr[index];
                passCharsArr.splice(index, 1); //remove used char from array
            }

            return pass;
        }

        return this.each(function (e) {

            var now = new Date();
            var input = $(this);

                var pass = randomPassword(o.length, o.charsLower, o.charsUpper, o.numbers, o.specialChars, o.minLower, o.minUpper, o.minNumber, o.minSpecial);
                input.val(pass);
                e.preventDefault();

        });
    };
})(jQuery);
/**
 * add a hint to an input on blur and remove on focus
 * show the hint attribute in the input and empty on focus
 * reset on blur
 */
(function ($) {
    $.fn.withHint = function (options) {

        var defaults = {
            attr: 'hint',
            className: 'withHint'
        };

        /**
         * add hint to input
         * element javascript element
         * o object options from plugin
         */
        function setHint(element, o) {
            var jElement = $(element);
            // input is empty, set hint
            if (jElement.val() == '') {
                jElement.val(jElement.attr(o.attr)).addClass(o.className);
            }

        }

        /**
         * remove hint from input
         * element javascript element
         * o object options from plugin
         */
        function removeHint(element, o) {
            var jElement = $(element);
            // input equals hint, remove hint
            if (jElement.val() == jElement.attr(o.attr)) {
                jElement.val('').removeClass(o.className);
            }
        }

        var o = $.extend(defaults, options);
        return this.each(function () {
            // on load set hint
            setHint(this, o);
            // on focus remove hint
            $(this).focus(function (e) {
                removeHint(this, o);
            });
            // on blur set hint
            $(this).blur(function (e) {
                setHint(this, o);
            });
        });
    };
})(jQuery);
/**
 * count words or chars and print amount in specified field
 * type : what needs to be counted (string words/chars)
 * counterID : field to put amount of chars/words in ('' = let plugin try)
 * min : min amount of chars/words wanted (int)
 * max : max chars/words wanted (int)
 * limit: limit field to max words/chars (true,false)
 * showLeft: show how many chars/words are left (true,false)
 */
(function ($) {
    $.fn.charWordCounter = function (options) {

        var defaults = {
            type: 'chars', // chars or words
            counterID: '', // will try ID from element added with `Counter`
            min: 0,
            max: 0, // unlimited, just count
            limit: false, // limit number of chars/words entered in the field
            format: '{0}', // format for text in counter element ({0} = already typed, {1} = max, {2} = min, {3} = chars/words left)
            errorClass: 'errorColor', // class when not between min and max
            validClass: 'validColor' // class when not between min and max
        };

        /**
         * char counter/limiter
         * @param element javascript element
         * @param o options object
         */
        function handleChars(element, o) {
            var value = $(element).val();
            var count = value.length;
            var countOK = true;
            // if a max is set, check max
            if (o.max > 0) {
                if (count > o.max) {
                    countOK = false;
                    // if limit is set, limit value at max
                    if (o.limit) {
                        value = value.substr(0, o.max); // reset value
                        count = value.length; // reset count
                        $(element).val(value);
                    }
                }
            }

            // is minimum is set, check min
            if (o.min > count) {
                countOK = false;
            }

            // show text imn counter
            showCounterText(element, o, countOK, count);
        }

        /**
         * word counter/limiter
         * @param element javascript element
         * @param o options object
         */
        function handleWords(element, o) {
            var value = $(element).val();
            var words = value.split(" ");
            var count = words.length;
            var countOK = true;
            // if no chars, do words -1 (becomes `0` then)
            if (value.length == 0) {
                count -= 1;
            }

            // if string ends with a space, count one word less
            if (value.substring(value.length - 1, value.length) == " ") {
                count -= 1;
            }

            // if a max is set, check max
            if (o.max > 0) {
                if (count > o.max) {
                    countOK = false;
                    // if limit is set, limit value at max
                    if (o.limit) {
                        words = words.splice(0, o.max, 999999); // reset value
                        count = words.length; // reset count
                        $(element).val(words.join(' ') + ' ');
                    }
                }
            }

            // is minimum is set, check min
            if (o.min > count) {
                countOK = false;
            }

            // show text imn counter
            showCounterText(element, o, countOK, count);
        }

        /**
         * @param element javascript element
         * @param o options object
         * @param countOK boolean is count ok?
         * @param count int number of words/chars
         */
        function showCounterText(element, o, countOK, count) {
            var counterEl = null;
            // if counterID is empty, try name attr from element and add Counter
            if (o.counterID == '') {
                var elementID = $(element).attr('id');
                counterEl = $('#' + elementID + 'Counter');
            } else {
                counterEl = $('#' + o.counterID);
            }

            // if counter is not OK (under min or above max) add and remove classes
            if (!countOK) {
                counterEl.addClass(o.errorClass);
                counterEl.removeClass(o.validClass);
            } else {
                counterEl.removeClass(o.errorClass);
                counterEl.addClass(o.validClass);
            }

            // show status in counter Element
            counterEl.html(o.format.format(count, o.max, o.min, (o.max - count)));
        }

        var o = $.extend(defaults, options);
        return this.each(function () {

            // do on call once
            if (o.type == 'chars') {
                handleChars(this, o);
            } else if (o.type == 'words') {
                handleWords(this, o);
            }

            // do on keyup
            $(this).keyup(function (e) {
                if (o.type == 'chars') {
                    handleChars(this, o);
                } else if (o.type == 'words') {
                    handleWords(this, o);
                }
            });
        });
    };
})(jQuery);
/**
 * function for easy formatting string in javascript replaces {\d} with arguments
 * takes a few arguments and for each match it checks if there is a replacement value
 * if there is, it replaces the match otherwise the match stays itself
 */
String.prototype.format = function () {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function (match, number) {
        return typeof args[number] != 'undefined' ? args[number] : match;
    });
};

/**
 * add classes odd and even to child elements within a container element
 * @param string elementID ID of the container with children
 */
function makeZebra(elementID) {
    $(elementID + ' > *').each(function (index, element) {
        // even?
        if (index % 2 == 0) {
            $(element).removeClass('odd').addClass('even');
        } else {
            $(element).removeClass('even').addClass('odd');
        }
    });
}

/**
 * set javascript for handling accordion
 */
$('.js-accordion .js-accordion-holder .accordion-title').each(function () {
    $(this).click(function (e) {
        e.preventDefault();
        // close all panels
        $('.js-accordion .js-accordion-holder .accordion-title').not(this).removeClass('is-active').closest('.js-accordion-holder').find('.js-accordion-panel').removeClass('is-opened');
        // open clicked panel
        $(this).toggleClass('is-active').closest('.js-accordion-holder').find('.js-accordion-panel').toggleClass('is-opened');
    });
});

/**
 * load different locale on form change
 */
$('#localeId').change(function () {
    $('#setLocaleForm').submit();
});

/**
 * make simple tabs but keep symantics intact
 */
(function ($) {
    $.fn.prestatieTabs = function (options) {

        var defaults = {
            tabsHolder: '.tabsHolder', // holder for the generated tabs
            tabDetails: '.tabDetails', // holder for tab title and tab
            tabLabel: '.tabLabel', // label used for text in generated tab
            tabContent: '.tabContent', // content to show on click tab
            tabClass: 'tab', // content to show on click tab (! do not take same name as tabLabel!)
            openTab: null, // open specific tab (index or name)
            onAfterTabOpen: function () {
            }
        };

        var o = $.extend(defaults, options);

        // open single tab and close all others
        function openTab(element, tabsContainer, o) {
            var tabIndex = element.attr('data-prestatietabs-index');
            tabsContainer.find('.' + o.tabClass + ',' + o.tabLabel).removeClass('active'); // remove class active states for all tabs and labels
            tabsContainer.find(o.tabContent).removeClass('opened'); // close all content parts
            tabsContainer.find(o.tabContent + '[data-prestatietabs-index="' + tabIndex + '"]').addClass('opened'); // open specific tab
            tabsContainer.find('.' + o.tabClass + '[data-prestatietabs-index="' + tabIndex + '"]' + ',' + o.tabLabel + '[data-prestatietabs-index="' + tabIndex + '"]').addClass('active'); // add class active to tab and label

            o.onAfterTabOpen.call(this, element);
        }

        // open tab if closed and vice versa
        function openCloseTab(element, tabsContainer, o) {
            var tabIndex = element.attr('data-prestatietabs-index');
            if (element.hasClass('active')) {
                tabsContainer.find(o.tabContent + '[data-prestatietabs-index="' + tabIndex + '"]').removeClass('opened'); // open specific tab
                tabsContainer.find('.' + o.tabClass + '[data-prestatietabs-index="' + tabIndex + '"]' + ',' + o.tabLabel + '[data-prestatietabs-index="' + tabIndex + '"]').removeClass('active'); // add class active to tab and label
            } else {
                tabsContainer.find(o.tabContent + '[data-prestatietabs-index="' + tabIndex + '"]').addClass('opened'); // open specific tab
                tabsContainer.find('.' + o.tabClass + '[data-prestatietabs-index="' + tabIndex + '"]' + ',' + o.tabLabel + '[data-prestatietabs-index="' + tabIndex + '"]').addClass('active'); // add class active to tab and label

                o.onAfterTabOpen.call(this, element);
            }
        }

        return this.each(function (index) {
            var tabsContainer = $(this);
            var tabsHolder = tabsContainer.find(o.tabsHolder);
            var tabsDetails = tabsContainer.find(o.tabDetails);

            // generate tabs based on labels
            var tabsHTML = '<ul class="cf">';
            for (i = 0; i < tabsDetails.size(); i++) {
                var tabDetail = $(tabsDetails[i]);
                tabsHTML += '<li data-prestatietabs-name="' + tabDetail.attr('data-prestatietabs-name') + '" data-prestatietabs-index="' + i + '" class="' + o.tabClass + '"><span class="label">' + tabDetail.find(o.tabLabel).html() + '</span></li>';
                tabDetail.find(o.tabLabel).attr('data-prestatietabs-index', i);
                tabDetail.find(o.tabContent).attr('data-prestatietabs-index', i);
            }

            // add tabs to holder
            tabsHolder.html(tabsHTML + '</ul>');

            // make generated tabs and original labels clickable
            tabsContainer.find('li.' + o.tabClass).click(function (e) {
                openTab($(this), tabsContainer, o);
                e.preventDefault();
            });
            tabsContainer.find(o.tabLabel).click(function (e) {
                openCloseTab($(this), tabsContainer, o);
                e.preventDefault();
            });

            // open tab on startup
            if (o.openTab !== null) {
                if (tabsContainer.find('li.' + o.tabClass + '[data-prestatietabs-name="' + o.openTab + '"]').size()) {
                    // is name
                    openTab(tabsContainer.find('li.' + o.tabClass + '[data-prestatietabs-name="' + o.openTab + '"]'), tabsContainer, o);
                } else if (tabsContainer.find('li.' + o.tabClass + '[data-prestatietabs-index="' + o.openTab + '"]').size()) {
                    // is index
                    openTab(tabsContainer.find('li.' + o.tabClass + '[data-prestatietabs-index="' + o.openTab + '"]'), tabsContainer, o);
                } else {
                    // can't find tab
                    tabsHolder.find('li:first').click();
                }
            } else {
                tabsHolder.find('li:first').click();
            }
        });
    };
})(jQuery);
var vars = {};

(function ($) {
    $.fn.mfupload = function (options) {

        var defaults = {
            file_upload_container: '.file_upload_container',
            progress_container: '.progressInfo',
            drop_zone: '.uploadsDropZone',
            show_thumb: false,
            upload_action: null,
            upload_hidden_action: null,
            show_thumbnail: true,
            upload_post_name: 'file',
            max_files: 0, // 0 is unlimited
            place_holder_selector_for_count: null, // this selector will be used for counting the amount of uploaded images
            max_file_size: 0, // maximum file size of files in bytes (0 equals unlimited)
            allowed_extensions: '', // allowed extensions of files (null equals all extensions) komma seperated
            callback_additional_post_values: function () {
            },
            callback_check_max_uploads: function () {
            },
            callback_file_upload_success: function () {
            }
        };

        function queueFileList(files, varsCounter) {

            // add files to queue
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                file.uid = vars[varsCounter].uploadsCounter;
                addToQueue(file, varsCounter);
                vars[varsCounter].uploadsCounter++;
            }

            // reset file input
            $(vars[varsCounter].input).val('');
        }

        function addToQueue(file, varsCounter) {

            var showCloseBtn = false;
            var errorMsg = '';
            // check max file size for file
            if (vars[varsCounter].o.max_file_size > 0 && file.size > vars[varsCounter].o.max_file_size) {
                showCloseBtn = true;
                errorMsg = jsTranslations['js_mfu_max_file_size_reached'].replace(/%s/, formatFileSize(vars[varsCounter].o.max_file_size));
            }

            // get extension from name
            var extension = '';
            if (filenameparts = file.name.match(/^.+\.([^.]+)$/)) {
                extension = filenameparts[1].toLowerCase();
            }

            var allowed_extensions_string = vars[varsCounter].o.allowed_extensions.replace(/(\.| |;)/g, '');
            if (allowed_extensions_string) {
                var allowed_extensions_array = allowed_extensions_string.split(',');
            }

            // check file extension
            if (!extension) {
                showCloseBtn = true;
                errorMsg = jsTranslations['js_mfu_no_file_extension'];
            } else if (allowed_extensions_string && allowed_extensions_array.indexOf(extension) === -1) {
                showCloseBtn = true;
                errorMsg = jsTranslations['js_mfu_invalid_file_extension'].replace(/%s/, extension).replace(/%s/, allowed_extensions_string);
            }

            // check max files are already uploading
            if (vars[varsCounter].o.max_files > 0 && (vars[varsCounter].o.max_files - (vars[varsCounter].uploadingCount + $(vars[varsCounter].container).find(vars[varsCounter].o.place_holder_selector_for_count).size()) <= 0)) {
                showCloseBtn = true;
                errorMsg = jsTranslations['js_mfu_max_files_reached'].replace(/%s/, vars[varsCounter].o.max_files);
            }

            // show progress info
            $(vars[varsCounter].container).find(vars[varsCounter].o.progress_container).show();
            // append new row
            var html = '';
            html += '<div class="progressContainer fileProgess_' + file.uid + '">';
            html += '<div class="cf">';
            if (vars[varsCounter].o.show_thumb) {
                html += '<div class="progressThumbHolder">&nbsp;</div>';
            }
            html += '<div class="progressTextHolder ' + (vars[varsCounter].o.show_thumb ? 'with-thumb' : '') + '">';
            html += '<div class="progressName">' + file.name + ' (' + formatFileSize(file.size) + ')' + '</div>';
            if (errorMsg) {
                html += '<div class="progressStatus error">' + errorMsg + '</div>';
            } else {
                html += '<div class="progressStatus">' + jsTranslations['js_mfu_waiting'] + '...</div>';
            }
            html += '</div>';
            html += '</div>';
            if (errorMsg) {
                html += '<div class="progressBar aborted"></div>';
            } else {
                html += '<div class="progressBar"></div>';
            }
            html += '</div>';
            $(vars[varsCounter].progressContainer).append(html);
            if (vars[varsCounter].o.show_thumb) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(vars[varsCounter].progressContainer).find('.fileProgess_' + file.uid + ' .progressThumbHolder').html('<img class="progressThumb" src="' + e.target.result + '">');
                }
                reader.readAsDataURL(file);
            }

            // do not add to queue if max files is reached
            if (showCloseBtn) {
                // set close icon
                setCloseIcon(varsCounter, $(vars[varsCounter].progressContainer.find('.fileProgess_' + file.uid)));
                return false;
            }

            vars[varsCounter].filesQueue.push(file);
            if (!vars[varsCounter].processing) {
                startProcessing(varsCounter);
            }
        }

        function startProcessing(varsCounter) {
            uploadFiles(varsCounter);
        }

        function uploadFiles(varsCounter) {
            // processing started
            vars[varsCounter].processing = true;
            // loop through queue
            while (vars[varsCounter].filesQueue.length > 0) {
                var file = vars[varsCounter].filesQueue[0];
                var fileProgressHolder = $(vars[varsCounter].container).find('.fileProgess_' + file.uid);
                fileProgressHolder.find('.progressStatus').html(jsTranslations['js_mfu_uploading'] + '...');
                // Create a new FormData object.
                var formData = new FormData();
                // Add the file to the request.
                formData.append(vars[varsCounter].o.upload_post_name, file, file.name);
                // Display the key/value pairs
                var object = vars[varsCounter].o.callback_additional_post_values();
                for (var prop in object) {
                    formData.append(prop, object[prop]);
                }

                var uploadCall = $.ajax({
                    url: vars[varsCounter].o.upload_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        try {
                            var dataObj = $.parseJSON(data);
                            if (dataObj.success) {
                                // remove abort icon
                                fileProgressHolder.find('.progressName').find('.abort_icon').remove();
                                fileProgressHolder.find('.progressStatus').html(jsTranslations['js_mfu_ready'] + '...');
                                //location.reload();

                                // if callback_file_upload_success exists, call it
                                if (typeof vars[varsCounter].o.callback_file_upload_success === 'function') {
                                    vars[varsCounter].o.callback_file_upload_success(dataObj, vars[varsCounter].addition);
                                }

                                // if callback_check_max_uploads exists, call it
                                if (typeof vars[varsCounter].o.callback_check_max_uploads === 'function') {
                                    vars[varsCounter].o.callback_check_max_uploads();
                                }

                                // file uploaded decrease files uploading
                                vars[varsCounter].uploadingCount--;

                                // set close icon
                                setCloseIcon(varsCounter, fileProgressHolder);

                                // also automatically close progress holder after X seconds
                                setTimeout(function () {
                                    fileProgressHolder.fadeOut(750, function () {
                                        this.remove();
                                    });
                                }, 2000);
                            } else {
                                fileProgressHolder.find('.progressStatus').addClass('error').html(jsTranslations['js_mfu_failure'] + ': ' + dataObj.errorMsg);
                                fileProgressHolder.find('.progressBar').addClass('aborted');
                                // set close icon
                                setCloseIcon(varsCounter, fileProgressHolder);
                            }
                        } catch (e) {
                            //alert(e);
                            $('.progressContainer').hide();
                        }
                    },
                    xhr: function () {
                        // get the native XmlHttpRequest object
                        var xhr = $.ajaxSettings.xhr();
                        // set the onprogress event handler
                        xhr.upload.onprogress = function (evt) {
                            $(fileProgressHolder).find('.progressBar').css('width', (evt.loaded / evt.total * 100) + '%');
                            // 100% uploaded
                            if ((evt.loaded / evt.total * 100) === 100) {
                                // remove abort, call is already made
                                fileProgressHolder.find('.progressName .abort_icon').remove();
                                fileProgressHolder.find('.progressStatus').html(jsTranslations['js_mfu_process_and_save'] + '...');
                            }
                        };
                        // return the customized object
                        return xhr;
                    }
                });

                // file starts uploading so increase counter
                vars[varsCounter].uploadingCount++;

                // remove uploaded entry
                vars[varsCounter].filesQueue.splice(0, 1);

                // set abort icon
                setAbortIcon(varsCounter, fileProgressHolder, uploadCall);

            }

            // set processing to false to reset process
            vars[varsCounter].processing = false;
        }

        function setAbortIcon(varsCounter, fileProgressHolder, uploadCall) {
            fileProgressHolder.find('.progressName').append('aa<a class="action_icon abort_icon" href="#"></a>');
            fileProgressHolder.find('.progressName .abort_icon').click(function (e) {
                // stop any normal click handling
                e.preventDefault();
                e.stopPropagation();

                if (uploadCall) {
                    // abort call
                    uploadCall.abort();

                    // show aborted messages
                    fileProgressHolder.find('.progressBar').addClass('aborted');
                    fileProgressHolder.find('.progressStatus').html(jsTranslations['js_mfu_aborted'] + '...');
                    fileProgressHolder.find('.progressName .abort_icon').remove();

                    // set close icon
                    setCloseIcon(varsCounter, fileProgressHolder);
                }
            });
        }

        function setCloseIcon(varsCounter, fileProgressHolder) {
            fileProgressHolder.find('.progressName').append('bb<a class="action_icon close_icon" href="#"></a>');
            fileProgressHolder.find('.progressName .close_icon').click(function (e) {
                // stop any normal click handling
                e.preventDefault();
                e.stopPropagation();

                $(fileProgressHolder).fadeOut(750, function () {
                    this.remove();
                });
            });
        }

        var o = $.extend(defaults, options);
        return this.each(function () {
            var container = $(this).closest(o.file_upload_container);
            var addition = $(container).attr('data-addition');
            var prefix = $(container).attr('data-prefix');
            varsCounter = prefix + addition;
            var variables = {};
            variables.uniqueID = [];
            variables.filesQueue = [];
            variables.uploadingCount = 0; // amount of files now uploading
            variables.uploadsCounter = 1;
            variables.container = container;
            variables.progressContainer = $(this).closest(o.file_upload_container).find(o.progress_container);
            variables.addition = addition;
            variables.prefix = prefix;
            variables.input = this;
            variables.o = o;

            // set variables to be global accessable
            vars[varsCounter] = variables;

            // enable multiple file upload
            $(this).attr('multiple', true).addClass('hide');

            // handle change
            $(this).change(function (e) {
                var container = $(this).closest(o.file_upload_container);
                var addition = $(container).attr('data-addition');
                var prefix = $(container).attr('data-prefix');
                varsCounter = prefix + addition;
                queueFileList(this.files, varsCounter);
            });
            $(this).closest('form').submit(function (e) {
                e.preventDefault();
                $(this).change();
                return false;
            });
            $(vars[varsCounter].container).find(o.drop_zone).on('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });
            $(vars[varsCounter].container).find(o.drop_zone).on('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });
            $(vars[varsCounter].container).find(o.drop_zone).on('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
                // check if any files are dropped
                if (e.originalEvent.dataTransfer) {
                    if (e.originalEvent.dataTransfer.files.length) {
                        $(this).removeClass('dragover');
                        var container = $(variables.input).closest(o.file_upload_container);
                        var addition = $(container).attr('data-addition');
                        var prefix = $(container).attr('data-prefix');
                        varsCounter = prefix + addition;
                        queueFileList(e.originalEvent.dataTransfer.files, varsCounter);
                    }
                }
            });
        });
    }
    ;
})(jQuery);

/**
 * add image to list
 * @param imageFile object
 * @param settings object
 */
function addImage(imageFile, settings) {
    var html = '';
    html += '<div id="placeholder-' + imageFile.imageId + '" style="display:none;" imageId="' + imageFile.imageId + '" class="placeholder">';

    if (!imageFile.hasNeededImageFiles) {
        html += '<img class="notAllCrops" src="/modules/core/admin/images/icons/exclamation_icon.png" alt="' + jsTranslations['js_mfu_no_cutout'] + '" title="' + jsTranslations['js_mfu_no_cutout'] + '" />';
    }

    html += '<div class="imagePlaceholder">';
    html += '<div class="centered">';
    html += '<img src="' + imageFile.link + '?t=' + Math.random() + '" alt="' + (imageFile.title ? imageFile.title : '') + '" title="' + (imageFile.title ? imageFile.title : '') + '" />';
    html += '</div>';
    html += '</div>';
    html += '<div class="actionsPlaceholder"><span style="float:left; font-size:13px;">' + (imageFile.title ? imageFile.title : '') + '</span>';
    if (settings.onlineChangeable) {
        if (imageFile.isOnlineChangeable) {
            html += '<a class="action_icon ' + (imageFile.online ? 'online' : 'offline') + '_icon onlineOfflineBtn" onclick="setOnlineImage(this); return false;" online="' + (imageFile.online ? 0 : 1) + '" href="' + settings.changeOnlineLink + '"></a>';
        } else {
            html += '<a onclick="return false;" class="action_icon grey ' + (imageFile.online ? 'online' : 'offline') + '_icon onlineOfflineBtn"  href="#"></a>';
        }
    }
    if (settings.cropable) {
        if (imageFile.isCropable) {
            html += '<a class="action_icon crop_icon" href="' + settings.cropLink + '?imageId=' + imageFile.imageId + '"></a>';
        } else {
            html += '<a onclick="return false;" class="action_icon grey crop_icon" href="#"></a>';
        }
    }
    if (settings.editable) {
        if (imageFile.isEditable) {
            html += '<a class="action_icon edit_icon" onclick="showEditImage(this); return false;" href="' + settings.editLink + '"></a>';
        } else {
            html += '<a class="action_icon grey edit_icon" onclick="return false;" href="#"></a>';
        }
    }
    if (settings.deletable) {
        if (imageFile.isDeletable) {
            html += '<a class="tn btn-danger btn-xs action_icon delete_icon" onclick="deleteImage(this); return false;" href="' + settings.deleteLink + '"><i class="fas fa-trash"></i></a>';
        } else {
            html += '<a class="action_icon grey delete_icon" onclick="return false;" href="#"></a>';
        }
    }
    html += '</div>';
    html += '</div>';

    // insert html to DOM
    $('#' + 'im_' + settings.containerIDAddition + ' .images').append(html);

    //alert('test');
    //var imageCount = $('#' + 'im_' + settings.containerIDAddition + ' .images .placeholder').length();
var imageCount = 1;

    //show other text after uploading first image (no images uploaded text will change)
    if (imageCount === 1) {
        //$('#' + 'im_' + settings.containerIDAddition + ' .imagesText').show();
       // $('#' + 'im_' + settings.containerIDAddition + ' .noImagesText').hide();
    }

    $('#placeholder-' + imageFile.imageId).show(500);

    if (imageCount === 1 && settings.coverImageShow) {
        //$('#' + 'im_' + settings.containerIDAddition + ' .coverImageContainer').find('.imagePlaceholder').html('<img src="' + imageFile.link + '" data-imageid="' + imageFile.imageId + '" />');
        //$('#' + 'im_' + settings.containerIDAddition + ' .coverImageContainer').show();
        //$('#' + 'im_' + settings.containerIDAddition + ' .coverImageContainer .placeholder').effect('highlight', 1500);
    }
}

function addFile(file, settings) {
    var html = '';
    html += '<li id="placeholder-' + file.mediaId + '" style="display:none;" data-title="' + file.title + '" data-mediaid="' + file.mediaId + '" class="placeholder">';
    html += '<div class="filePlaceholder" title="' + file.title + '">';
    html += '<span class="fileType ' + file.extension + '"></span><a class="title" target="_blank" href="' + file.link + '">' + (file.title ? file.title : file.name) + '</a>';
    html += '</div>';
    html += '<div class="actionsPlaceholder">';
    if (settings.onlineChangeable) {
        if (file.isOnlineChangeable) {
            html += '<a class="action_icon ' + (file.online ? 'online' : 'offline') + '_icon onlineOfflineBtn" onclick="setOnlineFile(this); return false;" data-online="' + (file.online ? 0 : 1) + '" href="' + settings.changeOnlineLink + '"></a>';
        } else {
            html += '<a onclick="return false;" class="action_icon grey ' + (file.online ? 'online' : 'offline') + '_icon onlineOfflineBtn"  href="#"></a>';
        }
    }
    if (settings.editable) {
        if (file.isEditable) {
            html += '<a class="action_icon edit_icon" onclick="showEditFile(this); return false;" href="' + settings.editLink + '"></a>';
        } else {
            html += '<a class="action_icon grey edit_icon" onclick="return false;" href="#"></a>';
        }
    }
    if (settings.deletable) {
        if (file.isDeletable) {
            html += '<a class="action_icon delete_icon" onclick="deleteFile(this); return false;" href="' + settings.deleteLink + '"></a>';
        } else {
            html += '<a class="action_icon grey delete_icon" onclick="return false;" href="#"></a>';
        }
    }
    html += '</div>';
    html += '</li>';

    // insert html to DOM
    $('#fm_' + settings.containerIDAddition + ' .files').append(html);

    //show other text after uploading first file (no files uploaded text will change)
    if ($('#fm_' + settings.containerIDAddition + ' .files .placeholder').length() === 1) {
        $('#fm_' + settings.containerIDAddition + ' .filesText').show();
        $('#fm_' + settings.containerIDAddition + ' .noFilesText').hide();
    }

    $('#placeholder-' + file.mediaId).show(500);
}

/**
 * format fize size to human readable format
 * @param {int} fileSizeInBytes
 * @returns {String}
 */
function formatFileSize(fileSizeInBytes) {
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1024;
        i++;
    } while (fileSizeInBytes > 1024);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
}

function setDefaultAutocomplete(selector, url, filter, renderItem, onAfterSelect, onAfterReset, addItem) {
    $(selector).each(function (index, element) {
        if (!$(element).parent().hasClass('defaultAutocompleteHolder')) {
            var addBtnHTML = '';
            if (typeof addItem === 'function') {
                addBtnHTML += '<a class="action_icon add_icon after-input hide" href="#"></a>';
            }
            $(element).addClass('withLoader').wrap('<div class="defaultAutocompleteHolder">').closest('.defaultAutocompleteHolder').append('&nbsp;<a class="action_icon edit_icon after-input float-right hide" href="#"></a>' + addBtnHTML);
        } else {
            return false;
        }

        // set click event for delete button
        $(element).closest('.defaultAutocompleteHolder').find('a.edit_icon').click(function (e) {
            $(element).attr('disabled', false);
            $($(element).data('save-to')).val('');
            $(this).addClass('hide');
            $(element).focus().autocomplete('search');
            if (typeof onAfterReset === 'function') {
                onAfterReset.call(this, element);
            }
            e.preventDefault();
        });

        $(element).closest('.defaultAutocompleteHolder').find('a.add_icon').click(function (e) {
            addItem.call(this, e);
            e.preventDefault();
        });

        $(element).autocomplete({
            minLength: 1,
            source: function (request, response) {

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: 'term=' + request.term + '&filter=' + encodeURIComponent(filter),
                    dataType: 'json',
                    success: function (result) {
                        if (typeof JSend !== "undefined") {
                            var jsend = JSend.parse(result);

                            var data = jsend.hasData() && jsend.getData().rows ? jsend.getData().rows : [];
                        } else {
                            var data = result;
                        }
                        response(data);
                    }
                });
            },
            select: function (event, ui) {

                // set name to input and disable untill release
                if ($(element).data('disable-default-on-after-select') !== true) {

                    setTimeout(function () {
                        $(element).val(ui.item.label).attr('disabled', true).closest('.defaultAutocompleteHolder').find('.action_icon').removeClass('hide');
                    }, 500);

                    // set name to input and disable untill release
                    $($(element).data('save-to')).val(ui.item.value);
                }

                if (typeof onAfterSelect === 'function') {
                    onAfterSelect.call(event, ui);
                }

                $(element).val('').focus();
                return false;
            },
            close: function (event, ui) {
                if ($($(element).data('save-to')).val() === '') {
                    $(element).val('');
                }
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {

            if (typeof renderItem == 'function') {
                return $("<li>")
                    .append('<div>' + renderItem.call(this, item) + '</div>')
                    .appendTo(ul);
            } else {
                return $("<li>")
                    .append('<div>' + item.label + '</div>')
                    .appendTo(ul);
            }
        };

        if ($(element).data('save-to') && $($(element).data('save-to')).val() !== '') {
            // id is set, so disable input and show edit button
            $(element).attr('disabled', true).closest('.defaultAutocompleteHolder').find('.action_icon.edit_icon').removeClass('hide');
        } else {
            $(element).closest('.defaultAutocompleteHolder').find('.action_icon.add_icon').removeClass('hide');
        }

        // on event, trigger search
        $(element).click(function () {
            $(this).autocomplete('search');
        });
    });
}

/* make optgroups possible */
$.widget('custom.autocompleteWithOptgroup', $.ui.autocomplete, {
    _create: function () {
        this._super();
        this.widget().menu("option", "items", "> :not(.ui-autocomplete-optgroup)");
    },
    _renderMenu: function (ul, items) {
        var self = this;
        $.each(items, function (index, item) {
            if (item.value === 'optgroup') {
                ul.append("<li class=\"ui-autocomplete-optgroup\"><div class=\"ui-autocomplete-optgroup-wrapper\">" + item.label + "</div></li>");
            } else {
                self._renderItemData(ul, item);
            }
        });
    }
});

// disable submit btn and show ajax loader
function disableSubmitBtn(element) {
    if ($(element).is('button')) {
        $(element).attr('disabled', true).addClass('ajax-loader-loading').html('<i class="fa fa-spinner fa-spin"></i> ' + jsTranslations['js_one_moment_please'] + '...');
    } else {
        $(element).attr('disabled', true).addClass('ajax-loader-loading').attr('value', jsTranslations['js_one_moment_please'] + '...');
    }
}

