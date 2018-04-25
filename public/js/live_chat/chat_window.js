// ZMIENNE
	// zmienne interwalow
	var check_connected_interval;
	var check_connected_interval_time = 5000;

// FUNKCJE

function status_waiting() {
        $(document).find("#live_chat .chat_toggle .input_area").css("display", "none");
	$(document).find("#live_chat .chat_toggle .chat_normal").hide();
	$(document).find("#live_chat .chat_toggle .chat_waiting").show();
	$(document).find("#live_chat .chat_toggle .chat_connected").hide();
	$(document).find("#live_chat .leave_chat").hide();
	$(document).find("#live_chat .chat_status").html("waiting");
	$(document).find("#live_chat .buttons_bar .chat_status_icon").attr("src", "/img/live_chat/chat_loading.gif");
	$(document).find("#live_chat .chat_toggle .input_area textarea").attr("disabled", "disabled");
}

function status_disconnect() {
        $(document).find("#live_chat .chat_toggle .input_area").css("display", "none");
	$(document).find("#live_chat .chat_toggle .chat_waiting").hide();
	$(document).find("#live_chat .chat_toggle .chat_normal").show();
	$(document).find("#live_chat .chat_status").html("disconnect");
	$(document).find("#live_chat .buttons_bar .chat_status_icon").attr("src", "/img/live_chat/normal_chat.png");
	$(document).find("#live_chat .chat_toggle .input_area textarea").attr("disabled", "disabled");
	$(document).find("#live_chat .chat_toggle .chat_connected").hide();
	$(document).find("#live_chat .leave_chat").hide();
        clearInterval(check_connected_interval);
}

function status_connected() {
	$(document).find("#live_chat .chat_toggle .chat_waiting").hide();
	$(document).find("#live_chat .chat_toggle .chat_normal").hide();
	$(document).find("#live_chat .chat_toggle .chat_connected").show();
	$(document).find("#live_chat .leave_chat").show();
	$(document).find("#live_chat .chat_status").html("connected");
	$(document).find("#live_chat .buttons_bar .chat_status_icon").attr("src", "/img/live_chat/connected_chat.png");
	$(document).find("#live_chat .chat_toggle .input_area textarea").prop("disabled", false);
        $(document).find("#live_chat .chat_toggle .input_area").css("display", "block");
	//$(".chat_area").animate({ scrollTop: $('.chat_area')[0].scrollHeight}, 0);
}

function arrow_icon_change() {
	if ($(document).find('#live_chat .chat_toggle').css('display') == "none") {
		$(document).find('img.toggle_button').attr("src", "/img/live_chat/arrow_upper.png");
	} else {
		$(document).find('img.toggle_button').attr("src", "/img/live_chat/arrow_bottom.png");
	}
}

function change_chat_status(chat_status) {
    //alert(chat_status);
	$.ajax({
		type: "post",
		url: "/chat/index/async",
		context: document.body,
		data: { type: "change_chat_status", chat_state: chat_status },
                
		success: function(data) {
                    //alert(data);
                    // wywolywanie funkcji sprawdzajacej czy polaczyl sie doradca
                    if (chat_status == "waiting") {
                        check_connected_interval = setInterval(function () {check_connected("x")}, check_connected_interval_time);
                    }
                    else if (chat_status == "disconnect") {
                        clearInterval(check_connected_interval);
                    }
		},
                error: function (xhr, ajaxOptions, thrownError) {
                  //alert(xhr.status);
                  //alert(thrownError);
                }
	});
}

// zmiana stanu czatu po odebraniu sygnalu
function check_connected(from_msg) {
	$.ajax({
		type: "post",
		url: "/chat/index/async",
		context: document.body,
		data: { type: "chat_check_connected"},
		success: function(data) {
                    //console.log(data);
                    if (data == 'sig_connected') {
                        status_connected();
                        var chat_status = $(document).find("#live_chat .chat_status").html();
                        change_chat_status(chat_status);
                    } else if (data == 'sig_notconnected') {
                        status_waiting();
                        clearInterval(check_connected_interval);
                        var chat_status = $(document).find("#live_chat .chat_status").html();
                        change_chat_status(chat_status);
                    }
		},
                error: function (xhr, ajaxOptions, thrownError) {
                  //alert(xhr.status);
                  //alert(thrownError);
                }
	});


	var msg_count = $(".chat_connected .msg_box").length;

	if (msg_count > 0) {
            if ($(".chat_connected .msg_a").length == 0)
                    var last_id = 0;
            else {
                if (from_msg == "x")
                        var last_id = $("#live_chat .msg_box").last().attr("id");
                else if (from_msg == "u")
                        var last_id = $("#live_chat .msg_u").last().attr("id");
                else if (from_msg == "a")
                        var last_id = $("#live_chat .msg_a").last().attr("id");


                last_id = last_id.substr(4, last_id.length);
            }
	}
	else {
            var last_id = 0;
	}

	$.ajax({
            type: "post",
            url: "/chat/index/async",
            context: document.body,
            data: { type: "get_msg", from: from_msg, last_msg: last_id},
            success: function(data) {
                //console.log(data);

                if (data["messages"]) {
                    var obj = data["messages"];
                    
                    for (var i in obj) {
                        if ($("#msg_"+obj[i].msg_id).length < 1 && obj[i].content !== "") {
                            var msg_box = $('<div>'+obj[i].content+'</div>');
                            $(msg_box).attr('id', 'msg_'+obj[i].msg_id);
                            $(msg_box).addClass("msg_box");
                            $(msg_box).addClass("msg_"+obj[i].from_type);
                            $(document).find("#live_chat .chat_toggle .chat_connected").append(msg_box);
                            $(".chat_area").animate({ scrollTop: $('.chat_area')[0].scrollHeight}, 0);
                        }
                    }
                }
            }
	});
}

// READY FUNCTION

$().ready(function() {

	// PO ZALADOWANIU STRONY
		var chat_state = $(document).find("#live_chat .chat_status").html();

		// wywolywanie funkcji sprawdzajacej czy polaczyl sie doradca - przy zaladowaniu strony
		if (chat_state == "waiting" || chat_state == "connected") {
			check_connected("x");
			
			clearInterval(check_connected_interval);
			
			check_connected_interval = setInterval(function () {check_connected("a")}, check_connected_interval_time);
			
			$.ajax({
				type: "post",
				url: "/chat/index/async",
				context: document.body,
				data: { type: "chat_check_connected"},
				success: function(data) {
                                    //alert(data);
				}
			});
		}
		else if (chat_state == "disconnect") {
			clearInterval(check_connected_interval);
		}

		// zmiana ikonki strzalki przy wczytywaniu strony
		arrow_icon_change();


	// PODCZAS KLIKANIA W BELKE CZATU
	$("#live_chat .buttons_bar").click(function() {
		// wysuwanie i ukrywanie
		$(document).find('#live_chat .chat_toggle').toggle();

		// zmiana ikonki strzalki
		arrow_icon_change();

		// zapisywanie w sesji stanu okna - ajax
		var window_display = $(document).find('#live_chat .chat_toggle').css('display');
		$.ajax({
			type: "post",
			url: "/chat/index/async",
			context: document.body,
			data: { type: "change_chat_window_state", window_state: window_display },
			success: function(data) {
                                //alert(data);
			}
		});
	});

	// KLIKNIECIE W PRZYCISK POLACZ
	$(document).find("#live_chat .chat_toggle .chat_normal .chat_connect").click(function() {
		// zmiana konspektu czatu
		status_waiting();

		// zapisywanie w sesji stanu połączenia - ajax
		var chat_status = $(document).find("#live_chat .chat_status").html();
                
		change_chat_status(chat_status);

	});

	// KLIKNIECIE W PRZYCISK ANULUJ / OPUSC ROZMOWE
	$(document).find("#live_chat .chat_toggle .chat_waiting .chat_cancel, #live_chat .leave_chat").click(function() {
                clearInterval(check_connected_interval);
		// zmiana konspektu czatu
		status_disconnect()

		// zapisywanie w sesji stanu połączenia - ajax
		var chat_status = $(document).find("#live_chat .chat_status").html();
		change_chat_status(chat_status);
	});

	// WYSLANIE WIADOMOSCI DO DORADCY
            // wcisniecie ENTER podczas pisania
            $("#live_chat .chat_toggle .input_area textarea").keypress(function(e){
                if (e.keyCode == 13) {
                    e.preventDefault();

                    var new_msg = $("#live_chat .chat_toggle .input_area textarea").val();
                    new_msg = stripScripts(new_msg);
                    new_msg.replace(/^\s+|\s+$/g, '');
                    
                    $("#live_chat .chat_toggle .input_area textarea").val("");
                    
                    
                    // jezeli cos wpisalismy
                    if (new_msg != '') {
                        
                        var msg_box = $('<div></div>');
                        $(msg_box).text(new_msg);
                        $(msg_box).addClass("msg_box");
                        $(msg_box).addClass("msg_u");
                        $(msg_box).addClass("msg_waiting");
                        $(document).find("#live_chat .chat_toggle .chat_connected").append(msg_box);
                        $(".chat_area").animate({ scrollTop: $('.chat_area')[0].scrollHeight}, 0);
                        
                        // ajax wyslanie
                        $.ajax({
                            type: "post",
                            url: "/chat/index/async",
                            context: document.body,
                            data: { type: "send_msg", msg: new_msg},
                            success: function(data) {
                                //console.log(data);
                                
                                if (data != "fail") {
                                    if ($("#msg_"+data).length < 1) {
                                        $(msg_box).attr('id', 'msg_'+data);
                                        $(msg_box).removeClass("msg_waiting");
                                    }
                                }
                            }
                        });
                    }
                }
            });
});
