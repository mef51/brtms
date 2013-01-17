function getSeats() {
	$.ajax({
	  url: '${ROOT}/a/getseats',
	  type: 'GET',
	  dataType: 'json'
	}).done(loadSeats).fail(function(jqSHR, textStatus) {
		debug && alert(textStatus + ': ' + jqSHR.responseText);
	});
}

function loadSeats(data) {
	var i = 0;
	for (key in data) {
		setTimeout(function() {loadSeat(key, data[key);}, i * 1000);
		i++;
		if (i > 3)
			break;
	}
}

getSeats();

