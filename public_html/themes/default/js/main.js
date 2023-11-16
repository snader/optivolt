var header = $("#header");
var navigationOffCanvas = $(".navigation-off-canvas");

if ($(header).hasClass("sticky")) {
    if ($(header).hasClass("s-position-bottom")) {
        navigationOffCanvas.addClass("has-offset-bottom");
    }
}

// Offcanvas list
var siteContainer = $(".site-container");
var navigationIcon = $(".navigation-icon");

$(navigationIcon).click(function () {
    if ($(siteContainer).hasClass("is-opened")) {
        closeNavigation();
    } else {
        openNavigation();
    }
});

function openNavigation() {
    $(siteContainer).addClass("is-opened");
    $(navigationIcon).addClass("is-active");
    $("html").css("overflow", "hidden");
}

function closeNavigation() {
    if ($(siteContainer).hasClass("is-opened")) {
        $(siteContainer).removeClass("is-opened");
        $(navigationIcon).removeClass("is-active");
        $("html").css("overflow", "");
    }
}

$(".navigation-off-canvas .has-sub-navigation > a").click(function (e) {
    e.preventDefault();
    $(this).nextAll(".sub-navigation-off-canvas").addClass("is-opened");
    return false;
});

$(".navigation-off-canvas .back a").click(function (e) {
    e.preventDefault();
    $(this).closest(".sub-navigation-off-canvas").removeClass("is-opened");
    return false;
});

// Checks on subpage if the navigation for the subpage is active
$(".navigation-off-canvas .off-canvas-list")
    .find("li.is-active")
    .closest("ul.sub-navigation-off-canvas")
    .addClass("is-opened");

//Fancybox init
$(".js-fancybox").fancybox({
    nextEffect: "none",
    prevEffect: "none",
    helpers: {
        overlay: {
            locked: false
        }
    }
});

$(".fancyBoxLink").fancybox({
    nextEffect: "none",
    prevEffect: "none",
    helpers: {
        overlay: {
            locked: false
        }
    }
});

// Prevent url hash change
$.fancybox.defaults.hash = false;

// validate form
$("form.validateForm").each(function (index, form) {
    var jForm = $(form);
    jForm.validate({
        focusInvalid: true,
        errorContainer: jForm.find(".form-errors"),
        errorLabelContainer: jForm.find(".form-errors ul"),
        wrapper: "li",
        ignore: ":hidden:not('.do-validate')"
    });
});

// validate form
$("form.validateFormInline").each(function (index, form) {
    var jForm = $(form);
    jForm.validate({
        focusInvalid: false,
        errorPlacement: function (error, element) {
            var formItemContainer = $(element).parent();

            if (!$(formItemContainer).hasClass("has-errors")) {
                $(formItemContainer).addClass("has-errors");
                $(formItemContainer).append('<span title="' + error[0].innerText + '" class="error-icon icon is-small is-right"><i class="fa fa-exclamation-triangle"></i></span>');
                $(formItemContainer).after('<span class="error-message help is-danger">' + error[0].innerText + "</span>");
                $(element).addClass('is-danger');
            }
        },
        success: function (label, element) {
            var formItemContainer = $(element).parent();
            if ($(formItemContainer).hasClass('has-errors')) {
                $(formItemContainer).removeClass('has-errors');
                $(formItemContainer).find('.error-icon').remove();
                $(formItemContainer).siblings('.error-message').remove();

                $(element).removeClass('is-danger').addClass('is-success');
            }
        }
    });
});

// Autonumeric
$("input.priceFloatOnly").autoNumeric({
    aSep: "",
    altDec: ","
});

$("input.numbersOnly").autoNumeric({
    aSep: "",
    mDec: "0"
});

// language change
$("#localeSelect, #localeSelectMobile").change(function () {
    location.href = "/change-locale?localeId=" + $("#localeSelect").val();
});

// Match heights
$(function () {
    $(".js-match-height").matchHeight();
    $(".js-match-min-height").matchHeight({
        property: "min-height"
    });
});

// Floating labels
if ($("form").hasClass("floating-labels")) {
    var allInputs = $("form.floating-labels .form-input, form.floating-labels .form-select, form.floating-labels .form-text");

    // Remove placeholders
    allInputs.removeAttr("placeholder");

    allInputs.change(function () {
        // Get id from form element
        var id = $(this).attr("id");

        if ($(this).val()) {
            $('label[for="' + id + '"]').addClass("is-focussed");
        } else {
            $('label[for="' + id + '"]').removeClass("is-focussed");
        }
    });
}

// Header images slider

var headerImagesSlider = $(".header-images");
var headerImagesLoader = $(".header-images-loader");

$(window).load(function () {
    headerImagesSlider.show();
    headerImagesLoader.hide();
});
$(document).ready(function () {
    headerImagesSlider.slick({
        zIndex: 80,
        infinite: true,
        speed: 600,
        autoplaySpeed: 6000,
        fade: true,
        autoplay: true,
        dots: true,
        arrows: false
    });

    // make pager visble
    setTimeout(function () {
        $(".header-images .slick-dots").addClass("is-visible");
    }, 1000);
});

// Media images slider

var mediaSlider = $(".media-slider");
var mediaLoader = $(".media-slider-loader");

$(window).load(function () {
    mediaSlider.show();
    mediaLoader.hide();
});

$(document).ready(function () {
    mediaSlider.slick({
        zIndex: 80,
        infinite: true,
        speed: 600,
        autoplaySpeed: 6000,
        fade: true,
        autoplay: true,
        dots: true,
        arrows: false
    });

    // make pager visble
    setTimeout(function () {
        $(".media-slider .slick-dots").addClass("is-visible");
    }, 1000);
});

// Environment notification close

var environmentNotification = $(".environment-notification");
var envirionmentNotificationCloseIcon = $(".js-close-environment-notification");

$(envirionmentNotificationCloseIcon).click(function (e) {
    e.preventDefault();
    setCookie("hideEnvironmentNotification", 1, 1);
    $(environmentNotification).addClass("is-hidden");
});

// Search
$(document).on("click", ".search-wrap span.search:not(.open)", function () {
    $(".search-wrap, .search-input, input.search").addClass("open");
    $("input.search-input").focus();
    $("html").click(function (e) {
        if (!$(e.target).hasClass("open")) {
            $(".search-wrap").removeClass("open");
            $("html").off("click");
        }
    });
});

// Featured search
$(".featured-search .search-icon").click(function () {
    $(this).closest("form").submit();
});

// priority menu

function calcWidth() {
    var navwidth = 0;
    var morewidth = $(".prio-menu > .pageLinks .more").outerWidth(true);
    $(".prio-menu > .pageLinks > li:not(.more)").each(function () {
        navwidth += $(this).outerWidth(true);
    });

    //var availablespace = $('nav').outerWidth(true) - morewidth;
    var availablespace = $("#navigation.prio-menu").width() - morewidth;

    if (navwidth > availablespace) {
        var lastItem = $(".prio-menu > .pageLinks > li:not(.more)").last();
        lastItem.attr("data-width", lastItem.outerWidth(true));
        lastItem.prependTo($(".prio-menu > .pageLinks .more > ul"));
        calcWidth();
    } else {
        var firstMoreElement = $(".prio-menu > .pageLinks li.more li").first();
        if (navwidth + firstMoreElement.data("width") < availablespace) {
            firstMoreElement.insertBefore($(".prio-menu > .pageLinks .more"));
        }
    }

    if ($(".prio-menu .more li").length > 0) {
        $(".prio-menu .more").removeClass("hide");
    } else {
        $(".prio-menu .more").addClass("hide");
    }
}

$(window).on("resize load", function () {
    calcWidth();
});

// priority menu end

// Template overlay
var templateOverlay = $(".template-overlay");

$(templateOverlay).click(function () {
    closeMiniBasket();
});

// collapse item logic
$(document).ready(function () {
    $(".collapse-title").on("click", function (ev) {
        $(this).nextAll(".collapse-info").slideToggle();
        $(this).find("span").toggleClass("on");

        // this is a check if collapse is used together with position:sticky component
        $.fn.matchHeight._maintainScroll = true;

        setTimeout(function () {
            $.fn.matchHeight._update();
        }, 500);
        // end check

        ev.preventDefault();
        ev.stopPropagation();
        return false;
    });
});

// sticky elements
var elements = $(".sticky");
Stickyfill.add(elements);

// link entire element
$(document).on('click', '[data-link]', function() {
   var target = $(this).data('link-target') ? $(this).data('link-target') : '_self';
   window.open($(this).data('link'), target);
});

// tabs
function openTabs() {
    var tabUrl = window.location.href.toString();
    if (tabUrl.match('#tab')) {
        var current = tabUrl.split('#')[1];

        // tabs navigation
        $('.tabs li').removeClass('is-active');
        $('.tabs li a[href="#' + current + '"]').parent().addClass('is-active');

        // tabs content
        var tab = $('.tabs-item[data-url="' + current + '"]');
        $('.tabs-item').removeClass('is-active');
        tab.addClass('is-active');
    }
}
$(window).on('load hashchange', openTabs);