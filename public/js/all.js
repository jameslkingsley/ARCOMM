//--- Add active class to current page
$(document).ready(function(event) {
    var pathArray = window.location.pathname.split('/');
    var indexUrl = (window.location.protocol + "//" + window.location.hostname + "/") + pathArray[1];
    $('header .nav .nav-link[href="' + indexUrl + '"]').addClass('active');
});

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

openBigWindow = function(content, delay) {
    delay = delay || 500;

    $('.large-panel-container, .large-panel-exit').remove();

    $('body').prepend(
        '<div class="large-panel-exit"></div>'+
        '<div class="large-panel-container">'+
        '<div class="large-panel-inner">' + content + '</div>'+
        '</div>'
    );

    $('.large-panel-exit').fadeIn(delay);
    $('.large-panel-container, .large-panel-sidebar').animate({"top": "0"}, delay, "easeInOutCirc");

    $('.large-panel-exit').bind('click', function(event) {
        $('.large-panel-exit').fadeOut(delay, function() {
            $('.large-panel-exit').remove();
        });

        $('.large-panel-container, .large-panel-sidebar').animate({"top": "100%"}, delay, "easeInOutCirc", function() {
            $('.large-panel-container').remove();
        });
    });
}

setUrl = function(url, title) {
    var url = (window.location.protocol + "//" + window.location.hostname + "/") + url;
    
    window.history.replaceState({
        archubPush: true
    }, document.title, url);

    if (title) document.title = title;
}

//# sourceMappingURL=all.js.map
