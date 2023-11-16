
// Basket opening
var basket = $('.basket');
var basketIcon = $('.basket-icon');

$(basketIcon).click(function () {
    if ($(basket).hasClass('is-opened')) {
        closeMiniBasket();
    } else {
        openMiniBasket();
    }
});

function openMiniBasket() {
    $(basket).addClass('is-opened');
    $(templateOverlay).addClass('is-visible');
    $('html').addClass('is-locked');
}

function closeMiniBasket() {
    if ($(basket).hasClass('is-opened')) {
        $(basket).removeClass('is-opened');
        $(templateOverlay).removeClass('is-visible');
        $('html').removeClass('is-locked');
    }
}

/**
 * add a product from the basket
 * @param {javascript element} form
 * @returns {Boolean}
 */

$('form.addProductToBasket').submit(function (e) {
    var thisElement = $(this);
    var basketAmount = $('.basket-amount');
    if($(this).valid()) {
        $.ajax({
            type: "POST",
            url: thisElement.attr('action'),
            data: thisElement.serialize() + '&ajax=1',
            success: function (data) {
                var dataObj = eval('(' + data + ')');

                if (dataObj.success == true) {
                    $('#orders-mini-basket').html(dataObj.ordersMiniBasketContent);
                    openMiniBasket();
                    $(basketAmount).html(dataObj.countProducts);
                }
                if (dataObj.orderStatusUpdate) {
                    showStatusUpdate(dataObj.orderStatusUpdate, thisElement.find('.orderStatusUpdate'));
                }
            }
        });
        e.preventDefault();
    }
});


/**
 * remove a product from the basket
 * @param {javascript element} form
 * @returns {Boolean}
 */

function removeProductFromBasket(form) {
    var thisElement = $(form);
    var basketAmount = $('.basket-amount');

    $.ajax({
        type: "POST",
        url: thisElement.attr('action'),
        data: thisElement.serialize() + '&removeProductFromBasket=1&ajax=1',
        success: function (data) {
            var dataObj = eval('(' + data + ')');

            if (dataObj.success == true) {
                $('#orders-mini-basket').html(dataObj.ordersMiniBasketContent);
            }
            if (dataObj.orderStatusUpdate) {
                showStatusUpdate(dataObj.orderStatusUpdate, thisElement.find('.orderStatusUpdate'));
            }
            $(basketAmount).html(dataObj.countProducts);
        }
    });
    return false;
}