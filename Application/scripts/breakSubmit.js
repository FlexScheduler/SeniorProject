//Submit button for Breaks
function breakSubmit() {
	//add info as variables (will probably need to become global to work with SQL)
	name = $("#breakName").val();

	//startTime condensed into time format
	startTime = $("#startHour").val();
	startTime += ':';
	startTime += $("#startMinute").val();
	startTime += ' ';
	startTime += $("#startPeriod").val();

	//endTime condensed into time format
	endTime = $("#endHour").val();
	endTime += ':';
	endTime += $("#endMinute").val();
	endTime += ' ';
	endTime += $("#endPeriod").val();

	day = $("#breakDay").val();

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
			$("#breakList").append('<li>'+ name + ': ' +  day + ' ' + startTime + ' - ' + endTime + '</li>');
	}

}
