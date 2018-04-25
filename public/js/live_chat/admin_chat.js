// ZMIENNE
	// zmienne interwalow
	var check_connected_interval_admin;
	var check_connected_interval_time_admin = 4000;


// FUNKCJE
function status_waiting_admin() {
        $(document).find("#live_chat_admin .chat_toggle .input_area").css("display", "none");
	$(document).find("#live_chat_admin .chat_toggle .chat_normal").hide();
	$(document).find("#live_chat_admin .chat_toggle .chat_waiting").show();
	$(document).find("#live_chat_admin .leave_chat").hide();
	$(document).find("#live_chat_admin .chat_toggle .chat_connected").hide();
	$(document).find("#live_chat_admin .chat_toggle .chat_connected .leave_chat").hide();
	$(document).find("#live_chat_admin .chat_status").html("waiting");
	$(document).find("#live_chat_admin .chat_status_icon").attr("src", "/img/live_chat/chat_loading.gif");
	$(document).find("#live_chat_admin .chat_toggle .input_area textarea").attr("disabled", "disabled");
}

function status_connected_admin() {
	$(document).find("#live_chat_admin .chat_toggle .chat_waiting").hide();
	$(document).find("#live_chat_admin .chat_toggle .chat_normal").hide();
	$(document).find("#live_chat_admin .chat_toggle .chat_connected").show();
	$(document).find("#live_chat_admin .leave_chat").show();
	$(document).find("#live_chat_admin .chat_toggle .chat_connected .leave_chat").show();
	$(document).find("#live_chat_admin .chat_status").html("connected");
	$(document).find("#live_chat_admin .chat_status_icon").attr("src", "/img/live_chat/connected_chat.png");
	$(document).find("#live_chat_admin .chat_toggle .input_area textarea").prop("disabled", false);
        $(document).find("#live_chat_admin .chat_toggle .input_area").css("display", "block");
	//$("#live_chat_admin .chat_area").animate({ scrollTop: $('#live_chat_admin .chat_area')[0].scrollHeight}, 0);
}


// pobieranie wiadomosc / aktualizacja obecnosci
function req_accept_admin(sid, from_msg) {
	$.ajax({
		type: "post",
		url: "/chat/adviser/async",
		context: document.body,
		data: { type: "req_accept_admin", ss_id: sid},
		success: function(data) {
                        //console.log(data);
			if (data == 'sig_connected') {
				status_connected_admin();
				var chat_status = $(document).find("#live_chat_admin .chat_status").html();
			} else if (data == 'sig_notconnected') {
				status_waiting_admin();
				clearInterval(check_connected_interval);
				var chat_status = $(document).find("#live_chat_admin .chat_status").html();
			}
		}
	});


	var msg_count = $(".chat_connected .msg_box").length;

	if (msg_count > 0) {

		if ($(".chat_connected .msg_u").length == 0)
			var last_id = 0;
		else {
			if (from_msg == "x")
				var last_id = $("#live_chat_admin .msg_box").last().attr("id");
			else if (from_msg == "u")
				var last_id = $("#live_chat_admin .msg_u").last().attr("id");
			else if (from_msg == "a")
				var last_id = $("#live_chat_admin .msg_a").last().attr("id");

			last_id = last_id.substr(4, last_id.length);
		}
	}
	else {
		var last_id = 0;
	}

	$.ajax({
		type: "post",
		url: "/chat/adviser/async",
		context: document.body,
		data: { type: "get_msg_admin", ss_id: sid, from: from_msg, last_msg: last_id},
		success: function(data) { 
                    

                    if (data["messages"]) {
                        var obj = data["messages"];

                        for (var i in obj) {
                            if ($("#msg_"+obj[i].msg_id).length < 1 && obj[i].content !== "") {
                                var msg_box = $('<div>'+obj[i].content+'</div>');
                                $(msg_box).attr('id', 'msg_'+obj[i].msg_id);
                                $(msg_box).addClass("msg_box");
                                $(msg_box).addClass("msg_"+obj[i].from_type);

                                $(document).find("#live_chat_admin .chat_toggle .chat_connected").append(msg_box);
                                $("#live_chat_admin .chat_area").animate({ scrollTop: $('#live_chat_admin .chat_area')[0].scrollHeight}, 0);
                            }
                        }
                    }
		}
	});
}


// READY FUNCTION

$().ready(function() {
	// PO ZALADOWANIU STRONY
	// odebranie polaczenia - dodanie admin_id w rekordzie sesji
	var ss_id = $(document).find("#live_chat_admin .chat_ssid").text();
	var chat_state = $(document).find("#live_chat_admin .chat_status").text();
	
	req_accept_admin(ss_id, "x");
	check_connected_interval_admin = setInterval(function () {req_accept_admin(ss_id, 'u');}, check_connected_interval_time_admin);


	// KLIKNIECIE W PRZYCISK ANULUJ / OPUSC ROZMOWE
	$(document).find("#live_chat_admin .chat_toggle .chat_waiting .chat_cancel, .leave_chat_admin").click(function() {
		$.ajax({
			type: "post",
			url: "/chat/adviser/async",
			context: document.body,
			data: { type: "cancel_chat_admin", ss_id: ss_id},
			success: function(data) {
                            //alert(data);
                            document.location.href="/chat/adviser";
			}
		});
	
	});

	// WYSLANIE WIADOMOSCI DO KLIENTA
        // wcisniecie ENTER podczas pisania
        $("#live_chat_admin .chat_toggle .input_area textarea").keypress(function(e){
            if (e.keyCode === 13) {
                
                e.preventDefault();

                var new_msg = $("#live_chat_admin .chat_toggle .input_area textarea").val();
                new_msg = stripScripts(new_msg);
                new_msg.replace(/^\s+|\s+$/g, '');
                
                $("#live_chat_admin .chat_toggle .input_area textarea").val("");

                // jezeli cos wpisalismy
                if (new_msg !== '') {
                    var msg_box = $('<div></div>');
                    $(msg_box).text(new_msg);
                    $(msg_box).addClass("msg_waiting");
                    $(msg_box).addClass("msg_box");
                    $(msg_box).addClass("msg_a");
                    $(document).find("#live_chat_admin .chat_toggle .chat_connected").append(msg_box);

                    $("#live_chat_admin .chat_area").animate({ scrollTop: $('#live_chat_admin .chat_area')[0].scrollHeight}, 0);
                    
                    // ajax wyslanie
                    $.ajax({
                        type: "post",
                        url: "/chat/adviser/async",
                        context: document.body,
                        data: { type: "send_msg_admin", ss_id: ss_id, msg: new_msg},
                        success: function(data) {
                            //console.log(data);
                            
                            if (data !== "fail") {
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
