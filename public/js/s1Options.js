$(document).ready(function(){
	$(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	});

	$('#add_question_btn').click(function(){
		var structure = $('<div class="d-inputs"><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3 label-align">Option<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><input type="text" class="form-control" name="options[]" placeholder="Enter Current Health Option" required></div><div class="col-md-2 col-sm-2 col-xs-2"><button class="remove__btn tableButtons trash" style="height: 36px;"><i class="fa fa-minus"></i></button></div></div></div>');
	    $('.row-duplicate').append(structure);
	});

	$("body").on('click', '.remove__btn', function(event) {
		let elem = $(this);
		elem.parents('.d-inputs').remove();
		return false;	    
  	});
});