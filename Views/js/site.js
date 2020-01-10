function post(to, form, ret) {
    $.post(to, form).then(function(data){
        const result = JSON.parse(json(data));
        if (result.result === false) {
            Metro.infobox.create("<div class='h4 text-normal'>"+result.message+"</div>", "alert", {}, true);
        } else {
            if (ret !== "stop") {
                window.location.href = ret || "/";
            }
        }
    }, function(xhr){
        Metro.infobox.create("Server operation error!", "alert", {}, true);
    })
}

function json(data){
    const index = data.indexOf("{\"json\":\"ok\"");
    if (index === -1) {
        return false;
    }
    return (""+data).substr(index);
}

function login(form){
    post("/login/process", form, "/");
}

function signup(form){
    post("/signup/process", form, "/");
}

function addReport(form){
    $.post("/add/process", form).then(function(data){
        const result = JSON.parse(json(data));
        if (result.result === false) {
            Metro.infobox.create("<div class='h4 text-normal'>"+result.message+"</div>", "alert", {}, true);
            return ;
        }

        const report_id = result.data['report_id'];

        console.log(form.elements);

        //window.location.href = "/scams";
    })
}

function updateReport(form){
    post("/update/process", form);
}

function delReport(form){
    post("/delete/process", form);
}

$(function(){
    $.document().on("click", "#go-to-top", function(){
        $("html, body").animate({
            scrollTop: 0
        }, 300);
    });

    $.document().on("click", ".evidence > .button", function(){
        $(this).parent().remove();
    });

    $("input[type=file]").on("select", function(e){
        const files = e.detail.files;
        const template = $("#evidence-template");
        const container = $("#evidences");

        $.each(files, function(index, file){
            const reader = new FileReader();
            reader.onloadend = function(){
                const result = reader.result;
                const evidence = template.clone(true).removeClass("d-none");

                if (!result.contains("data:image")) {
                    return ;
                }

                evidence.removeAttr("id").addClass("evidence");
                evidence.find("img").attr("src", reader.result);
                evidence.find("input").val(reader.result).attr("name", "evidence[]");
                evidence.find("textarea").attr("name", "evidence_desc[]");
                evidence.appendTo(container);
            };
            reader.readAsDataURL(file);
        });

        Metro.getPlugin(this, "file").clear();
    });

    if (window.markdownit) {
        const md_target = $(".markdown-source");
        const md = window.markdownit();

        md_target.html(md.render(md_target.html()));
    }
});