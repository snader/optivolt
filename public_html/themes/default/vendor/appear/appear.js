
//Elements Appear from right

$('.fade-right').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 1,
            right: "0px"
        }, 800);
    });
});

//Elements Appear from left

$('.fade-left').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 1,
            left: "0px"
        }, 800);
    });
});

//Elements Appear from top

$('.fade-top').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 1,
            top: "0px"
        }, 800);
    });
});

//Elements Appear from bottom

$('.fade-bottom').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 1,
            bottom: "0px"
        }, 800);
    });
});

//Elements fade in

$('.fade').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 1
        }, 800);
    });
});

//Elements fade out

$('.fade-out').each(function () {
    $(this).appear(function () {
        $(this).delay(400).animate({
            opacity: 0
        }, 800);
    });
});