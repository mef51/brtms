<?php

/**
 * Display a static seating chart.
 */

require_once dirname(__FILE__) . '/l/seating.inc.php';
require_once dirname(__FILE__) . '/l/session.inc.php';
require_once dirname(__FILE__) . '/l/view.inc.php';

// Specify the seats that are special
$res_seats = array(
);

$res = $db->query($sql = 'SELECT `seat`, `dname` FROM `players` WHERE `seat` IS NOT NULL');
if (!$res) {
    qerror($sql);
}

// Generate the array of occupied seats
while ($p = $res->fetch_assoc()) {
    if (!isSet($res_seats[$p['seat']])) {
        $res_seats[$p['seat']] = $p['dname'];
    }
}

$src = '';

// Display instructions and legend
// Note: The display is different if the user is not logged in.
if (isSet($_p) && $_p['seataccess'] == 1) {
	$seat_str = '';
	if ($_p['seat']) {
		$seat_str = sPrintF(', seat %1$s', $_p['seat']);
	}
	$src .= sPrintF('
<fieldset class="faded-bg" style="float:right;margin-left:1em;width:380px;">
<legend>Seat Legend</legend>
<table class="center seating-chart">
<col /><col /><col width="25" />
<col /><col /><col width="25" />
<col /><col />
<tr>
<th class="vac"><input type="radio" /></th><td>&nbsp; Available</td><td></td>
<th class="occ"><input type="checkbox" /></th><td>&nbsp; Taken</td><td></td>
<th class="me"><input type="checkbox" /></th><td>&nbsp; You%1$s</td>
</tr>
</table>
</fieldset>

<h1>Seating Plan</h1>
<p>Choose your desired seat on the map below.  Your seat number will be used to determine which network port and power outlet you should use to setup your computer.</p>
<p>You may hover over any seat with your cursor to see its detailed status.</p>
<p><strong>Note: If you are not part of a team, we would appreciate if you hold-off on selecting your seat for a few days to allow teams to choose seats next to each other.</strong></p>

<form action="#" onsubmit="return chooseSeat(this);">
', $seat_str);
	
} else {
	$src .= sPrintF('
<fieldset class="faded-bg" style="float:right;margin-left:1em;width:380px;">
<legend>Seat Legend</legend>
<table class="center seating-chart">
<col /><col /><col width="25" />
<col /><col />
<tr>
<th class="vac2"><input type="checkbox" /></th><td>&nbsp; Available</td><td></td>
<th class="occ"><input type="checkbox" /></th><td>&nbsp; Taken</td>
</tr>
</table>
</fieldset>

<h1>Seating Plan</h1>
<p>%s</p>
<p>You may hover over any seat with your cursor to see its status.</p>
', isSet($_p) ? 'Seats are picked in order of registration but it is not currently your turn.' : 'You must login in order to choose your seat.');
}

// Generate the chart
// Note: The chart will be displayed differently if the user is not logged in.
$src .= genSeatChart($res_seats);

if (isSet($_p)) {
	$src .= '
</form>

<script type="text/javascript">
	$(".seating-chart.real").find("input").click(function() {chooseSeat(this.form);});
</script>
';
}

mp($src, 'Seating Plan');

