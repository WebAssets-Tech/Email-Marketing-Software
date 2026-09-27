"use strict";

//translate in one click
function copy() {
    $("#tranlation-table > tbody  > tr").each(function (index, tr) {
        $(tr).find(".value").val($(tr).find(".key").text());
    });
}

var mobilesidebarToggler = document.getElementById(
    "maildoll_mobile_menu_toggler"
);
var sidebarId = document.getElementById("maildoll_sidebar_id");

/****
 *
 *  mobile sidebar toggler
 *
 *****/

$(mobilesidebarToggler).click(function () {
    $(sidebarId).toggleClass("maildoll_sidebar_active");
});

//published the all checkbox
$('input[type="checkbox"]').on("change", function () {
    var url = this.dataset.url;
    var id = this.dataset.id;

    if (url != null && id != null) {
        $.ajax({
            url: url,
            data: { id: id },
            method: "get",
            success: function (result) {},
        });
    }
});

function currencyChange() {
    $("#ru-currency").submit();
}

// floating buttons
$(".floatingButton").on("click", function (e) {
    e.preventDefault();
    $(this).toggleClass("open");
    if ($(this).children(".fa").hasClass("fa-plus")) {
        $(this).children(".fa").removeClass("fa-plus");
        $(this).children(".fa").addClass("fa-close");
    } else if ($(this).children(".fa").hasClass("fa-close")) {
        $(this).children(".fa").removeClass("fa-close");
        $(this).children(".fa").addClass("fa-plus");
    }
    $(".floatingMenu").stop().slideToggle();
});
$(this).on("click", function (e) {
    var container = $(".floatingButton");
    // if the target of the click isn't the container nor a descendant of the container
    if (
        !container.is(e.target) &&
        $(".floatingButtonWrap").has(e.target).length === 0
    ) {
        if (container.hasClass("open")) {
            container.removeClass("open");
        }
        if (container.children(".fa").hasClass("fa-close")) {
            container.children(".fa").removeClass("fa-close");
            container.children(".fa").addClass("fa-plus");
        }
        $(".floatingMenu").hide();
    }

    // if the target of the click isn't the container and a descendant of the menu
    if (
        !container.is(e.target) &&
        $(".floatingMenu").has(e.target).length > 0
    ) {
        $(".floatingButton").removeClass("open");
        $(".floatingMenu").stop().slideToggle();
    }
});


$(function () {
    $("#chatFormId").submit(function (e) {
        e.preventDefault();
        var myQuestion = $("#chatInput").val();
        var url = $("#ChatGptRoute").val();
        var chatGPTIconUrl = $("#ChatGPTIcon").val();
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serialize(),
            beforeSend: function () {
                $("#chatLoadingHTML")
                    .append(`<div class="loadingClass flex justify-center items-center">
                                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                                </div>`);
            },
            success: function (response) {
                console.log(response)
                $(".loadingClass").remove();
                var chatHTML = `<div id="cm-msg-1" class="chat-msg self" style="">
                                        <span class="msg-avatar">
                                            <img style="width:30px;height:30px;" src=${chatGPTIconUrl} >
                                        </span>
                                        <div class="cm-msg-text">${myQuestion}</div>
                                    </div>
                                    <div id="cm-msg-2" class="chat-msg user" style="">
                                        <span class="msg-avatar"> 
                                        <img style="width:30px;height:30px;" src=${chatGPTIconUrl} >
                                        </span>
                                        <div class="cm-msg-text"> ${response}</div>
                                    </div>`;
                $("#chatGPTLogs").append(chatHTML);
            },
        });
    });
});

// Open modal when trigger button is clicked
$('#chat-circle').click(function() {
    $('#modal-overlay').removeClass('hiddenChatgpt');
  });
  
  // Close modal when close button is clicked
  $('#modal-close-Chatgpt').click(function() {
    $('#modal-overlay').addClass('hiddenChatgpt');
  });
  
  // Close modal when user clicks outside of modal content
  $('#modal-overlay').click(function(event) {
    if (event.target === this) {
      $('#modal-overlay').addClass('hiddenChatgpt');
    }
  });