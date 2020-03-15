<<<<<<< Updated upstream
//Submit button for Breaks

//keeps track of the number of breaks user has entered
var noBreaks = 0;

function breakSubmit() {
	//add info as variables (will probably need to become global to work with SQL)
	name = $("#breakName").val();

	//startTime condensed into time format
	startTime = $("#startHour").val();
	startTime += ':';
	startTime += $("#startMinute").val();
	startTime += $("#startPeriod").val();

	//endTime condensed into time format
	endTime = $("#endHour").val();
	endTime += ':';
	endTime += $("#endMinute").val();
	endTime += $("#endPeriod").val();

	//set default value for day
	day = "";
	
	if ($('#breakDay1').is(':checked')) {
		day = "M";
	}
	if ($('#breakDay2').is(':checked')) {
		day += "T";
	}
	if ($('#breakDay3').is(':checked')) {
		day += "W";
	}
	if ($('#breakDay4').is(':checked')) {
		day += "R";
	}
	if ($('#breakDay5').is(':checked')) {
		day += "F";
	}

	//Flag to prevent addition of Break if user incorrectly enters information
	errorFlag = false;

	//Check that there is in fact a name given to the break
	if (name === "") {
		alert("Enter a name for your break");
	}
	//Check for period mismatch
	else if ($("#startPeriod").val() == "PM" && $("#endPeriod").val() == "AM") {
		alert("Selected End Time occurs before selected Start Time!");
	}
	//Check that at least one day was entered
	else if(day === "") {
		alert("Select the day(s) of the break");
	}
	//Do math with times
	else {
		//if both are AM or both are PM
		if($("#startPeriod").val() == $("#endPeriod").val()) {
			//If the Break is during the same hour
			if($("#startHour").val() == $("#endHour").val()) {
				//AND the same minute, tell the user they're wrong
				if($("#startMinute").val() == $("#endMinute").val()) {
					alert("Your Start Time and End Time are identical!");
					errorFlag = true;
				}
				//If end time is before start time, tell the user they're still wrong
				else if(parseInt($("#endMinute").val()) <  parseInt($("#startMinute").val())) {
					alert("Selected End Time occurs before selected Start Time!");
					errorFlag = true;
				}
			}
			//If end time is before start time, tell the user they're still wrong
			else if(parseInt($("#startHour").val()) > parseInt($("#endHour").val())) {
				alert("Selected End Time occurs before selected Start Time!");
				errorFlag = true;
			}
		}
		//If the flag was not set, add Break to list
		if(!errorFlag)
		{
			//$("#breakList").append('<li>'+ name + ': ' +  day + ' ' + startTime + ' - ' + endTime + '</li>'); //old code
		
			//increment breaks as they are added
			noBreaks++;
		
			$("#break-table-body").append("<tr><td class='pt-3-half' contenteditable='true'>" + name + "</td><td class='pt-3-half' contenteditable='true'>" + day + "</td><td class='pt-3-half' contenteditable='true'>" + startTime + "</td><td class='pt-3-half' contenteditable='true'>"+ endTime + "</td><td><span class='table-remove'><button type='button' onclick = 'remBreak(this);'class='btn btn-danger btn-rounded btn-sm my-0'>Remove</button></span></td></tr>");
		}
	}
	
	//exit modal
	$('#breakModal').modal('hide');

}

//Remove button for breaks
remBreak = (function(row) {
    $(row).closest("tr").remove();
	
	//decrement breaks if they are removed
	noBreaks--;

	//console.log(noBreaks);
});

remClass = (function(row) {
	$(row).closest("tr").remove();
});
=======
//Submit button for Breaks

//keeps track of the number of breaks user has entered
var noBreaks = 0;

function breakSubmit() {
	//add info as variables (will probably need to become global to work with SQL)
	name = $("#breakName").val();

	//startTime condensed into time format
	startTime = $("#startHour").val();
	startTime += ':';
	startTime += $("#startMinute").val();
	startTime += $("#startPeriod").val();

	//endTime condensed into time format
	endTime = $("#endHour").val();
	endTime += ':';
	endTime += $("#endMinute").val();
	endTime += $("#endPeriod").val();

	//set default value for day
	day = "";
	
	if ($('#breakDay1').is(':checked')) {
		day = "M";
	}
	if ($('#breakDay2').is(':checked')) {
		day += "T";
	}
	if ($('#breakDay3').is(':checked')) {
		day += "W";
	}
	if ($('#breakDay4').is(':checked')) {
		day += "R";
	}
	if ($('#breakDay5').is(':checked')) {
		day += "F";
	}

	//Flag to prevent addition of Break if user incorrectly enters information
	errorFlag = false;

	//Check that there is in fact a name given to the break
	if (name === "") {
		alert("Enter a name for your break");
	}
	//Check for period mismatch
	else if ($("#startPeriod").val() == "PM" && $("#endPeriod").val() == "AM") {
		alert("Selected End Time occurs before selected Start Time!");
	}
	//Check that at least one day was entered
	else if(day === "") {
		alert("Select the day(s) of the break");
	}
	//Do math with times
	else {
		//if both are AM or both are PM
		if($("#startPeriod").val() == $("#endPeriod").val()) {
			//If the Break is during the same hour
			if($("#startHour").val() == $("#endHour").val()) {
				//AND the same minute, tell the user they're wrong
				if($("#startMinute").val() == $("#endMinute").val()) {
					alert("Your Start Time and End Time are identical!");
					errorFlag = true;
				}
				//If end time is before start time, tell the user they're still wrong
				else if(parseInt($("#endMinute").val()) <  parseInt($("#startMinute").val())) {
					alert("Selected End Time occurs before selected Start Time!");
					errorFlag = true;
				}
			}
			//If end time is before start time, tell the user they're still wrong
			else if(parseInt($("#startHour").val()) > parseInt($("#endHour").val())) {
				alert("Selected End Time occurs before selected Start Time!");
				errorFlag = true;
			}
		}
		//If the flag was not set, add Break to list
		if(!errorFlag)
		{
			//$("#breakList").append('<li>'+ name + ': ' +  day + ' ' + startTime + ' - ' + endTime + '</li>'); //old code
		
			//increment breaks as they are added
			noBreaks++;
		
			$("#break-table-body").append("<tr><td class='pt-3-half' contenteditable='true'>" + name + "</td><td class='pt-3-half' contenteditable='true'>" + day + "</td><td class='pt-3-half' contenteditable='true'>" + startTime + "</td><td class='pt-3-half' contenteditable='true'>"+ endTime + "</td><td><span class='table-remove'><button type='button' onclick = 'remBreak(this);'class='btn btn-danger btn-rounded btn-sm my-0'>Remove</button></span></td></tr>");
		}
	}
	
	//exit modal
	$('#breakModal').modal('hide');

}

//Remove button for breaks
remBreak = (function(row) {
    $(row).closest("tr").remove();
	
	//decrement breaks if they are removed
	noBreaks--;

	//console.log(noBreaks);
});

remClass = (function(row) {
	$(row).closest("tr").remove();
});
>>>>>>> Stashed changes
