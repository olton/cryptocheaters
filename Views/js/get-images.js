function imageGridDrawItem(item, image){
    var el = $(item);
    el.removeAttr("data-original");
    el.wrap( $("<a>").attr("href", $(image).attr("data-link")) );
}

$(function(){
    var ig = $("#image-grid");

    if (!ig.length) {
        return ;
    }

    $.json("/random-photos").then(function(data){
        var photos = data && data.data && data.data.photos ? data.data.photos : {};
        var counter = Metro.utils.objectLength(photos);

        $.each(photos, function(){
            var el = this;
            var image = new Image();

            image.src = el.evidence_image;
            image.setAttribute("data-link", "/crypto_scam_report/"+el.evidence_report);
            image.onload = function(){
                counter--;
            }

            ig.append(image);
        });

        ig.imagegrid();
    })
})