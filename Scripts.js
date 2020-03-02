//Scripts File

//Submit button for Breaks
function breakSubmit() {
	//add info as variables (will probably need to become global to work with SQL)
	name = $("#exampleFormControlInput1").val();
	
	//startTime condensed into time format
	startTime = $("#exampleFormControlSelect1").val();
	startTime += ':';
	startTime += $("#exampleFormControlSelect2").val();
	startTime += ' ';
	startTime += $("#exampleFormControlSelect3").val();
	
	//endTime condensed into time format
	endTime = $("#exampleFormControlSelect4").val();
	endTime += ':';
	endTime += $("#exampleFormControlSelect5").val();
	endTime += ' ';
	endTime += $("#exampleFormControlSelect6").val();
	
	day = $("#exampleFormControlSelect7").val();
	
	$("#breakList").append('<li>'+ name + ': ' +  day + ' ' + startTime + ' - ' + endTime + '</li>');
	

}