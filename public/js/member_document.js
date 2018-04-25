// modal change filename
$('.filemane-change-modal').on('show.bs.modal', function (event) {
    var filebox = $(event.relatedTarget).parent(); // Button that triggered the modal
    //console.log();
    var filename_with_ext = filebox.children("p.file-title").text();
    var dot_pos = filename_with_ext.lastIndexOf(".");
    var filename = filename_with_ext.substr(0, dot_pos);
    var ext = filename_with_ext.substr(dot_pos, filename_with_ext.length);

    var modal = $(this);
    //modal.find('input.file-name').text('New message to ' + recipient);
    modal.find('#old-filename').val(filename_with_ext);
    modal.find('#file-name').val(filename);
    modal.find('#file-extension').val(ext);
});

$('.request-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var centext = button.data('context');
    var modal = $(this);
    
    switch(centext) {
        case "delete-file":
            var filename = button.parent().children(".file-title").text();
            
            $(".modal .accept-button").addClass("delete-file-button");
            modal.find('#item_context').val(filename);
            modal.find('.modal-title').text("Usuwanie pliku");
            modal.find('.request-question').text("Na pewno chcesz usunąć wybrany plik?");
        break;
    }
    //console.log();


});

$().ready(function() {
    // ajax change filename
    $(".change-filename-button").on('click', function(event){
        var old_filename = $(".filemane-change-modal #old-filename").val();
        
        var file_box_name = $('.file-box').find('.file-title').filter(function() {
            return $(this).text() === old_filename;
        });
        
        var file_download_link = file_box_name.parent().children(".download-link");
        var file_preview_button = file_box_name.parent().children(".preview-file-button");
        
        $(this).text("Zmieniam...");
        $(this).prop("disabled", true);

        var this_button = $(this);
        var new_filename = $(".filemane-change-modal #file-name").val() + $(".filemane-change-modal #file-extension").val();

        var document_id = $("#pass_document_id").val();
        var member_id = $("#pass_member_id").val();
        

        
        if (old_filename !== new_filename && $(".filemane-change-modal #file-name").val().length > 0) {
            file_box_name.parent().children(".progress-action").fadeIn();
            
            $.ajax({
                type: 'post',
                url: "/center/document/change-filename",
                data: {new_filename: new_filename, old_filename: old_filename, document_id: document_id, member_id: member_id },
                dataType: 'json',
                success: function(data)
                {
                    file_box_name.parent().children(".progress-action").fadeOut();
                    
                    this_button.text("Zmień");
                    this_button.prop("disabled", false);
                    
                    if (data.status === 'success') {
                        file_box_name.text(data.new_filename);
                        file_download_link.attr("href", "/files/member-doc/"+member_id+"/"+document_id+"/"+data.new_filename);
                        file_preview_button.attr("href", "/files/member-doc-preview/"+member_id+"/"+document_id+"/"+data.new_filename);
                        $(".filemane-change-modal").modal('hide');
                    }
                },
                error: function(textStatus)
                {
                    file_box_name.parent().children(".progress-action").fadeOut();
                    
                    this_button.text("Zmień");
                    this_button.prop("disabled", false);
                }
            });
            
        } else {
            this_button.text("Zmień");
            this_button.prop("disabled", false);
            
            $('.filemane-change-modal').modal('hide');
        }
        
        //console.log(member_id);
    });
    
    $(".request-modal").on('click', '.delete-file-button', function(event){
        $('.request-modal').modal('hide');
        
        var document_id = $("#pass_document_id").val();
        var member_id = $("#pass_member_id").val();
        
        //console.log();
        
        var filebox = $('.file-box').find('.file-title').filter(function() {
            return $(this).text() === $(".request-modal #item_context").val();
        }).parent();
        
        var filename = $(".request-modal #item_context").val();

        filebox.children(".progress-action").fadeIn();

        $.ajax({
            type: 'post',
            url: "/center/document/delete-file",
            data: {filename: filename, document_id: document_id, member_id: member_id },
            dataType: 'json',
            success: function(data)
            {
                filebox.children(".progress-action").fadeOut();
                
                if (data.status === 'success') {
                    filebox.fadeOut("slow", function() {
                        filebox.remove();

                        if ($(".uploaded-files").html().trim() === "") {
                            $(".uploaded-files").append('<p class="empty-text" style="margin-left:30px;font-style:italic">Nie dodano żadnych plików...</p>');
                        }
                    });
                }
            },
            error: function(textStatus)
            {
                filebox.children(".progress-action").fadeOut();
            }
        });
        
        $(".delete-file-button").removeClass("delete-file-button");
    });
});