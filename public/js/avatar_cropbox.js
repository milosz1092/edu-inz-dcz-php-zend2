$().ready(function() {
    // cropbox
    var options =
    {
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    };
    var cropper;
    $('#file').on('change', function(){
        $('.thumbBox').removeClass("disabled");
        $('.imageBox').removeClass("disabled");
        
        if ($(".loading_avatar").css("display") === "none")
            $('#btnCrop').prop("disabled", false);
        
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('.imageBox').cropbox(options);
        };
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    });
    $('#file').mousedown(function() {
        if ($(".loading_avatar").css("display") === "none") {
            $('.thumbBox').removeClass("accepted");
            $('#btnCrop').addClass("btn-success");
            $('#btnCrop').removeClass("btn-default");
            $('#btnCrop').removeClass("disabled");
            $('#btnCrop').prop("disabled", false);
        }
    });
    $('#btnCrop').on('click', function(){
        var member_id = $("input[name='id']").val();
        
        var img = cropper.getBlob(); // getDataURL
        var avatar = new FormData();
        avatar.append('avatar', img);
        avatar.append('member', member_id);
        
        var btnCrop_this = $(this);
        $('.container .notification-area').html('');
        $(".loading_avatar").show();
        $(btnCrop_this).removeClass("btn-success");
        $(btnCrop_this).addClass("btn-default");
        $(btnCrop_this).addClass("disabled");
        $(btnCrop_this).attr("disabled", "disabled");
        
        // AJAX UPLOAD AVATAR
        $.ajax({
            type: 'post',
            url: "/center/member/change-avatar",
            data: avatar,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data)
            {
                $(".loading_avatar").hide();
                $(document).scrollTop(0);
                        
                if(typeof data.error === 'undefined' && data == 'success')
                {
                    $('.container .notification-area').prepend('<p class="top-notification top-success">Zdjęcie profilu zostało zmienione.</p>');
                    
                    $('.thumbBox').addClass("accepted");

                }
                else
                {
                    $(".loading_avatar").hide();
                    $('.container .notification-area').prepend('<p class="top-notification top-error">Błąd poczas zmieniania zdjęcia profilowego.</p>');
                    
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function(textStatus)
            {
                $(".loading_avatar").hide();
                $(document).scrollTop(0);
                
                $('.container .notification-area').prepend('<p class="top-notification top-error">Niespodziewany błąd podczas transmisji asynchronicznej.</p>');
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
        
        $('.avatar-input').attr("value", img);
        
        //$('.cropped').append('<img src="'+img+'">');
        

    });
    $('#btnZoomIn').on('click', function(){
        if ($(".loading_avatar").css("display") === "none") {
            $('.thumbBox').removeClass("accepted");
            $('#btnCrop').addClass("btn-success");
            $('#btnCrop').removeClass("btn-default");
            $('#btnCrop').removeClass("disabled");
            $('#btnCrop').prop("disabled", false);
        }
        cropper.zoomIn();
    });
    $('#btnZoomOut').on('click', function(){
        if ($(".loading_avatar").css("display") === "none") {
            $('.thumbBox').removeClass("accepted");
            $('#btnCrop').addClass("btn-success");
            $('#btnCrop').removeClass("btn-default");
            $('#btnCrop').removeClass("disabled");
            $('#btnCrop').prop("disabled", false);
        }
        cropper.zoomOut();
    });
    $('.thumbBox').mousedown(function() {
        if ($(".loading_avatar").css("display") === "none") {
            $('.thumbBox').removeClass("accepted");
            $('#btnCrop').addClass("btn-success");
            $('#btnCrop').removeClass("btn-default");
            $('#btnCrop').removeClass("disabled");
            $('#btnCrop').prop("disabled", false);
        }
    });
});