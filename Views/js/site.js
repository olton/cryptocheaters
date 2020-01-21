function back(){
    window.history.back();
}

function json(data){
    const index = data.indexOf("{\"json\":\"ok\"");
    if (index === -1) {
        return false;
    }
    return (""+data).substr(index);
}

function post(to, form, cb) {
    $.post(to, form).then(function(data){
        const result = JSON.parse(json(data));
        if (result.result === false) {
            Metro.infobox.create("<div class='h4 text-normal'>"+result.message+"</div>", "alert", {}, true);
        } else {
            if (typeof  cb === "function") {
                cb(result);
            } else {
                window.location.href = cb || "/";
            }
        }
    }, function(xhr){
        Metro.infobox.create("Server operation error!", "alert", {}, true);
    })
}

function login(form){
    post("/login/process", form);
}

function signup(form){
    post("/signup/process", form, "/");
}

function addReport(form){
    post("/add/process", form, function(result){
        Metro.dialog.create({
            removeOnClose: true,
            title: "Report added!",
            content: "The report is successfully added to database. What next?",
            clsDialog: "secondary",
            actions: [
                {
                    caption: "Goto scam list",
                    cls: "js-dialog-close primary",
                    onclick: function(){
                        window.location.href = "/scams";
                    }
                },
                {
                    caption: "Open report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/report/" + result.data['report_id'];
                    }
                },
                {
                    caption: "Add new report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/add";
                    }
                }
            ]
        });
    });
}

function updateReport(form){
    post("/update/process", form, function(result){
        Metro.dialog.create({
            removeOnClose: true,
            title: "Report updated!",
            content: "The report is successfully updated to database. What next?",
            clsDialog: "secondary",
            actions: [
                {
                    caption: "Goto scam list",
                    cls: "js-dialog-close primary",
                    onclick: function(){
                        window.location.href = "/scams";
                    }
                },
                {
                    caption: "Open report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/report/" + result.data['report_id'];
                    }
                },
                {
                    caption: "Add new report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/add";
                    }
                }
            ]
        });
    });
}

function delReport(id, ret){
    Metro.dialog.create({
        title: "Delete report",
        content: "Do you want to really delete this report?",
        removeOnClose: true,
        clsDialog: "alert",
        actions: [
            {
                caption: "Yes, delete",
                cls: "js-dialog-close alert",
                onclick: function(){
                    post("/delete/process", {id: id}, ret || "/");
                }
            },
            {
                caption: "No, keep it",
                cls: "js-dialog-close link"
            }
        ]
    });
}

function printReport(id){

}

function voteReport(id){
    post("/vote", {id: id}, function(result){
        $("#votes").text(result.data['votes']);
    });
}

function pageLinkClick(l){
    const link = $(l);
    const item = link.parent();
    const pagination = link.closest("#pagination");
    const pagesCount = Math.ceil(parseInt(pagination.data("length")) / parseInt(pagination.data("rows")));
    let currentPage = parseInt(pagination.data("page"));

    if (item.hasClass("active")) {
        return ;
    }

    if (item.hasClass("service")) {
        if (link.data("page") === "prev") {
            currentPage--;
            if (currentPage === 0) {
                currentPage = 1;
            }
        } else {
            currentPage++;
            if (currentPage > pagesCount) {
                currentPage = pagesCount;
            }
        }
    } else {
        currentPage = link.data("page");
    }

    const q = Metro.utils.getURIParameter(null, "q");
    const params = [];

    if (q) {
        params.push("q="+q);
    }
    params.push("page="+currentPage);

    window.location.href = "/scams?" + params.join("&");
}

function openScammerLink(a){
    const link = $(a).text();
    Metro.dialog.create({
        title: "Open link",
        content: "Do you want to really open scammer link?<br>"+link,
        removeOnClose: true,
        clsDialog: "warning",
        actions: [
            {
                caption: "Yes, open",
                cls: "js-dialog-close warning",
                onclick: function(){
                    window.location.href = link;
                }
            },
            {
                caption: "No, thanks",
                cls: "js-dialog-close link"
            }
        ]
    });
}

$(function(){
    $.document().on("click", ".pagination .page-link", function(){
        pageLinkClick(this)
    });

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
        const md = window.markdownit({
            linkify: true
        });

        md_target.html(md.render(md_target.html()));
    }

    $.document().on("click", ".report-evidences .evidence .photo-container", function(e){
        const img = $(this).children("img");
        const desc = $(this).siblings(".evidence-desc");
        const title = "<h2>Scammer's photo</h2>" + (desc.length && desc.text().trim() !== '' ? "<hr><div class='evidence-image-desc'>"+desc.html()+"</div>" : "");
        const content = "<div class='evidence-image-wrapper'><img src='"+img.attr("src")+"'/></div>";

        Metro.dialog.create({
            title: title,
            content: content,
            closeButton: true,
            clsDialog: "light dialog-view-evidence",
            removeOnClose: true
        })
    });

    const pagination = $("#pagination");
    if (pagination.length > 0) {
        Metro.pagination({
            target: "#pagination",
            length: parseInt(pagination.data("length")),
            rows: parseInt(pagination.data("rows")),
            current: parseInt(pagination.data("page"))
        })
    }
});