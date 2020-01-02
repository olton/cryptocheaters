$(function(){
    $.document().on("click", "#go-to-top", function(){
        $("html, body").animate({
            scrollTop: 0
        }, 300);
    });
});