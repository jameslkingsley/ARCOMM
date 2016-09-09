openPanel = function(content, d) {
    var delay = d || 150;

    $('.panel-container, .panel-invisible').remove();
    $('body').prepend('<div class="panel-container">' + content + '</div>');
    $('.panel-container').animate({"right": "0"}, delay, "easeInOutCubic");
    $('body').prepend('<div class="panel-invisible"></div>');
    $('.panel-invisible').fadeIn(delay, "easeInOutCubic");

    $('.panel-invisible').bind("click", function(event) {
        $('.panel-invisible').fadeOut(delay, "easeInOutCubic", function() {
            $('.panel-invisible').remove();
        });

        $('.panel-container, .panel-child-container').animate({"right": "-1000px"}, delay, "easeInOutCubic", function() {
            $('.panel-container, .panel-child-container').remove();
        });
    });
}
//# sourceMappingURL=all.js.map
