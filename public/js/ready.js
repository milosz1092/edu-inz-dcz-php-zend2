// FUNKCJE
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    //alert(out);
    // or, if you wanted to avoid alerts...

    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre);
}

function stripScripts(s) {
    var div = document.createElement('div');
    div.innerHTML = s;
    var scripts = div.getElementsByTagName('script');
    var i = scripts.length;
    while (i--) {
        scripts[i].parentNode.removeChild(scripts[i]);
    }
    return div.innerHTML;
}

function home_background() {
    var margin_height = $(".home_background").height();
    
    $(".row.home_margin_set").css("margin-top", margin_height+"px");
    
    if (margin_height > 0) {
        $(".navbar-fixed-top").addClass("bigger_navbar_shadow");
    } else {
        $(".navbar-fixed-top").removeClass("bigger_navbar_shadow");
    }
}

$().ready(function() {
    // resisizing and style home_background
    home_background();
    
    $( window ).resize(function() {
        home_background();
    });
    
    // lazy load private image
    $(".circle-link.member-link img").load( function(){ 
        $(this).fadeIn(500);
    }); 
    
    $('.entry-content table').stacktable({myClass:'stacktable small-only' });
    $('.user-table').stacktable({myClass:'stacktable small-only' });
    $('.entry-table').stacktable({myClass:'stacktable small-only' });
    $('.illness-table').stacktable({myClass:'stacktable small-only' });
    
    var item = $('.item').eq(Math.floor((Math.random() * $('.item').length)));
    item.addClass("active");
    
    var url = $('.item.active').find("img").attr('url'); 
    $('.item.active').find("img").attr("src", url); //set value : src = url
    
    var next_url = item.next('.item').find("img").attr('url');
    item.next('.item').find("img").attr("src", next_url);
    
    var prev_url = item.prev('.item').find("img").attr('url');
    item.prev('.item').find("img").attr("src", prev_url); 
    
    var first_url = $('.item').first().find("img").attr('url');
    $('.item').first().find("img").attr("src", first_url);
    
    var last_url = $('.item').last().find("img").attr('url');
    $('.item').last().find("img").attr("src", last_url); 

    $('.carousel').carousel({
        interval: 15000
    });
    
    $('.carousel').on('slid.bs.carousel', function () {
        var act_item = $('.item.active');
        
        var url = act_item.find("img").attr('url'); 
        act_item.find("img").attr("src", url); //set value : src = url
        
        var next_url = act_item.next('.item').find("img").attr('url');
        act_item.next('.item').find("img").attr("src", next_url);

        var prev_url = act_item.prev('.item').find("img").attr('url');
        act_item.prev('.item').find("img").attr("src", prev_url); 
    });

});