// ZMIENNE
	// zmienne interwalow
	var update_req_list_interval;
	var update_req_list_interval_time = 3000;


	function update_req_list() {

            $.ajax({
                    type: "post",
                    url: "/chat/adviser/async",
                    context: document.body,
                    data: { type: "update_req_list" },
                    success: function(data) {

                            //console.log(data);
                            var obj = data["calls"];
                            var to_save = new Array();
                            
                            if (data["calls"]) {
                                
                                
                                for (var i in obj) {
                                    to_save.push("row_"+obj[i].ss_id);
          
                                    if (!$("#row_"+obj[i].ss_id).length) {
                                        var added = $("<tr></tr>");
                                        $(added).attr('id','row_'+obj[i].ss_id);
                                        $(added).addClass("call-row");
                                        $(added).append('<td>'+obj[i].ss_id+'</td>');
                                        $(added).append('<td>'+obj[i].email+'</td>');
                                        $(added).append('<td>'+obj[i].ip+'</td>');
                                        $(added).append('<td>'+obj[i].browser+'</td>');
                                        $(added).append('<td><a href="/chat/adviser/receive/'+obj[i].ss_id+'" class="btn btn-success" role="button"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></a></td>');
                                        $(added).appendTo($(".user-calls"));
                                        
                                        //$('.user-calls').stacktable({myClass:'stacktable small-only' });
                                    }
                                    //alert(obj[i].ss_id);
                                }
                            }   
                            

                            $(".user-calls .call-row").each(function() {
                                
                                if ($(this).attr("id")) {
                                    //alert($(this).attr("id"));
                                    
                                    if (to_save.indexOf($(this).attr("id")) < 0) {
                                        $(this).remove();
                                        //console.log(this);
                                        //$('.user-calls').stacktable({myClass:'stacktable small-only' });
                                    }
                                }
                            });
                            
                            
                    }
            });
	}


// READY FUNCTION

$().ready(function() {
	// aktualizacja listy zgloszen rozmow
	update_req_list_interval = setInterval(function () {update_req_list();}, update_req_list_interval_time);
});
