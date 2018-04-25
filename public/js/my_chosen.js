function dynamic_mult_select(id) {
	$($('#'+id+'_chosen').find('.search-field').find('input')).keyup(function(e){
		var value = $('#'+id+'_chosen').find('.search-field').find('input').val();
		value = value.trim();

		var is_set = $('#'+id+' option[value="_n_'+value+'"]').length;
		
		var is_set2 = $('#'+id+' option').filter(function() {
			return $(this).text() === value;
		}).length;
		
		if (e.keyCode == 13 && is_set == 0 && is_set2 == 0) {
			$('#'+id).append("<option value='_n_"+value+"'>"+value+"</option>");
			$('#'+id+' option[value="_n_'+value+'"]').attr("selected","selected");
			$('#'+id).trigger("chosen:updated");
		}
	});	
}

function dynamic_select(id) {
	$($('#'+id+'_chosen').find('.chosen-search').find('input')).keyup(function(e){
		var value = $('#'+id+'_chosen').find('.chosen-search').find('input').val();
		value = value.trim();

		var is_set = $('#'+id+' option[value="_n_'+value+'"]').length;
		
		var is_set2 = $('#'+id+' option').filter(function() {
			return $(this).text() === value;
		}).length;
		
		if (e.keyCode == 13 && is_set == 0 && is_set2 == 0) {
			$('#'+id).append("<option value='_n_"+value+"'>"+value+"</option>");
			$('#'+id+' option[value="_n_'+value+'"]').attr("selected","selected");
			$('#'+id).trigger("chosen:updated");
		}
	});	
}

$().ready(function() {
 
	$("#entry_section").chosen({ width: '100%', allow_single_deselect:true });
        $("#document_type").chosen({ width: '200px', allow_single_deselect:true });
        $("#document_specialization").chosen({ width: '200px', allow_single_deselect:true });
        $("#user_role").chosen({ width: '160px' });
	//$("#article_questions").chosen({ width: '100%' });
	//$("#article_tags").chosen({ width: '100%' });
	
        $('#document_type').on('change', function(){  
            if ($(this).val() === '4') {
                $("#document_specialization").val('x').trigger("chosen:updated");
                $("#specialization-form-group").hide('slow');
            }
            else if ($("#document_specialization").val() === 'x') {
                $("#specialization-form-group").show('slow');
                $("#document_specialization").val('0').trigger("chosen:updated"); 
            }
        });
        
        
	//dynamic_select("entry_section");
	//dynamic_mult_select("article_questions");
	//dynamic_mult_select("article_tags");
	/*
	// DYNAMIC SELECT FOR ARTICLE SECTIONS
	$($('#article_sections_chosen').find('.search-field').find('input')).keyup(function(e){
		var value = $('#article_sections_chosen').find('.search-field').find('input').val();
		if (e.keyCode == 13) {
			$('#article_sections').append("<option value='n_"+value+"'>"+value+"</option>");
			$('#article_sections option[value="n_'+value+'"]').attr("selected","selected");
			$('#article_sections').trigger("chosen:updated");
		}
	});*/
	
	
	
});
