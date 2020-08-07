var editor;

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
    m4q.post(to, form).then(function(data){
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

function sendVerificationRequest(form){
    var activity = Metro.activity.open({
        type: 'cycle',
        overlayColor: '#000',
        overlayAlpha: .8,
        text: '<div class=\'mt-2 text-small\'>Please, wait...</div>',
        overlayClickClose: false
    });
    post("/verification/process", form, function(result){
        Metro.activity.close(activity);
        Metro.dialog.create({
            removeOnClose: true,
            title: "We receive your request!",
            content: "" +
                "Thank you for trusting us. We will analyze your request as soon as possible and give you a comprehensive answer." +
                "<br><br>Your Request UID:<div class='text-bold'>"+result.data.request_uid+"</div>" +
                "<br>Please store this UID for additional information about your request. Thanks!",
            clsDialog: "secondary",
            actions: [
                {
                    caption: "Goto scam list",
                    cls: "js-dialog-close primary",
                    onclick: function(){
                        window.location.href = "/crypto_scammers";
                    }
                },
                {
                    caption: "New request",
                    cls: "js-dialog-close warning",
                    onclick: function(){
                        window.location.href = "/verification";
                    }
                },
                {
                    caption: "Add new report",
                    cls: "js-dialog-close alert",
                    onclick: function(){
                        window.location.href = "/report_crypto_scam";
                    }
                }
            ]
        });
    })
}

function login(form){
    post("/login/process", form);
}

function signup(form){
    post("/signup/process", form, "/");
}

function addReport(form){
    const $ = m4q;
    const activity = Metro.activity.open({
        type: 'cycle',
        overlayColor: '#000',
        overlayAlpha: .8,
        text: '<div class=\'mt-2 text-small\'>Please, wait...</div>',
        overlayClickClose: false
    });

    $(form).find("#desc-editor > textarea").val(editor.getMarkdown());

    post("/add/process", form, function(result){
        Metro.activity.close(activity);
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
                        window.location.href = "/crypto_scammers";
                    }
                },
                {
                    caption: "Open report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/crypto_scam_report/" + result.data['report_id'];
                    }
                },
                {
                    caption: "Add new report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/report_crypto_scam";
                    }
                }
            ]
        });
    });
}

function updateReport(form){
    const $ = m4q;
    const activity = Metro.activity.open({
        type: 'cycle',
        overlayColor: '#000',
        overlayAlpha: .8,
        text: '<div class=\'mt-2 text-small\'>Please, wait...</div>',
        overlayClickClose: false
    });

    $(form).find("#desc-editor > textarea").val(editor.getMarkdown());

    post("/update/process", form, function(result){
        Metro.activity.close(activity);
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
                        window.location.href = "/crypto_scammers";
                    }
                },
                {
                    caption: "Open report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/crypto_scam_report/" + result.data['report_id'];
                    }
                },
                {
                    caption: "Add new report",
                    cls: "js-dialog-close",
                    onclick: function(){
                        window.location.href = "/report_crypto_scam";
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
    const $ = m4q;
    post("/vote/process", {id: id}, function(result){
        $("#votes").text(result.data['votes']);
    });
}

function pageLinkClick(l){
    const $ = m4q;
    const link = $(l);
    const item = link.parent();
    const pagination = link.closest(".pagination").parent();
    const view = pagination.attr("data-view");
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
    const order = Metro.utils.getURIParameter(null, "order");
    const params = [];

    if (q) {
        params.push("q="+q);
    }
    params.push("page="+currentPage);
    if (order) {
        params.push("order="+order);
    }

    window.location.href = "/"+view+"?" + params.join("&");
}

function openScammerLink(a){
    const $ = m4q;
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

function sortChange(val){
    const q = Metro.utils.getURIParameter(null, "q");
    const page = Metro.utils.getURIParameter(null, "page");
    const view = window.location.pathname;
    const params = [];

    if (q) {
        params.push("q="+q);
    }
    if (page) {
        params.push("page="+page);
    }
    params.push("order="+val[0]);

    window.location.href = view+"?" + params.join("&");
}

$(function(){
    const $ = m4q;
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

    $("#add_evidence").on("select", function(e){
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

    // $("#add_doc").on("select", function(e){
    //     const files = e.detail.files;
    //     const template = $("#evidence-template");
    //     const container = $("#docs");
    //
    //     $.each(files, function(index, file){
    //         const reader = new FileReader();
    //         reader.onloadend = function(){
    //             const result = reader.result;
    //             const evidence = template.clone(true).removeClass("d-none");
    //
    //             if (!result.contains("data:image")) {
    //                 return ;
    //             }
    //
    //             evidence.removeAttr("id").addClass("evidence");
    //             evidence.find("img").attr("src", reader.result);
    //             evidence.find("input").val(reader.result).attr("name", "doc[]");
    //             evidence.find("textarea").attr("name", "doc_desc[]");
    //             evidence.appendTo(container);
    //         };
    //         reader.readAsDataURL(file);
    //     });
    //
    //     Metro.getPlugin(this, "file").clear();
    // });

    if (window.markdownit) {
        const md_target = $(".markdown-source");
        const md = window.markdownit({
            linkify: true,
            html: true,
            breaks: true,
            typographer: true
        });

        md_target.html(md.render(md_target.html()));
    }

    // $.document().on("click", ".report-evidences .evidence .photo-container", function(e){
    //     const img = $(this).children("img");
    //     const desc = $(this).siblings(".evidence-desc");
    //     const title = "<h2>Scammer's photo</h2>" + (desc.length && desc.text().trim() !== '' ? "<hr><div class='evidence-image-desc'>"+desc.html()+"</div>" : "");
    //     const content = "<div class='evidence-image-wrapper'><img src='"+img.attr("src")+"'/></div>";
    //
    //     Metro.dialog.create({
    //         title: title,
    //         content: content,
    //         closeButton: true,
    //         clsDialog: "light dialog-view-evidence",
    //         removeOnClose: true
    //     })
    // });

    $.each(["pagination", "pagination-top", "pagination-bottom"], function(){
        const pagination = $("#"+this);
        if (pagination.length > 0) {
            Metro.pagination({
                target: "#"+this,
                length: parseInt(pagination.data("length")),
                rows: parseInt(pagination.data("rows")),
                current: parseInt(pagination.data("page"))
            })
        }
    });

    if ($("#desc-editor").length) {
        editor = editormd("desc-editor", {
            width  : "100%",
            height : 400,
            path   : "/Views/editor.md/lib/",
            watch : false,
            toolbarIcons : function() {
                return [
                    "undo", "redo", "|",
                    "bold", "del", "italic", "quote", "uppercase", "lowercase", "|",
                    "list-ul", "list-ol", "hr", "|",
                    "link", "image", "table", "|",
                    "preview", "clear"
                ]
            }
        });
    }

    var br = $(".report-text.markdown-source").find("br");
    if (br.length) br.wrap("<div>").addClass("br-wrap");
});