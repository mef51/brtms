<?php

/**
 * Display a static seating chart.
 */

require_once dirname(__FILE__) . '/l/seating.inc.php';
require_once dirname(__FILE__) . '/l/session.inc.php';
require_once dirname(__FILE__) . '/l/view.inc.php';

// Specify the seats that are special
$res_seats = array(
  'A2' => ' Unavailable',
  'A10' => ' Unavailable',
  'A18' => ' Unavailable',
  'B1' => 'Network Admin',
  'B3' => 'Network Admin',
  'B14' => ' Unavailable',
  'C2' => 'Players Portal Admin',
  'C4' => 'Players Portal Admin',
  'C14' => ' Unavailable',
  'D1' => ' Unavailable',
  'D9' => ' Unavailable',
  'D17' => ' Unavailable',
  'E8' => ' Unavailable',
  'E16' => ' Unavailable',
  'E24' => ' Unavailable',
  'F14' => ' Unavailable',
  'G14' => ' Unavailable',
  'H7' => ' Unavailable',
  'H15' => ' Unavailable',
  'H23' => ' Unavailable',
);

$res = $db->query($sql = 'SELECT `seat`, `dname` FROM `players` WHERE `seat` IS NOT NULL');
if (!$res) {
    error($sql);
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
if (isSet($_p)) {
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

<form action="#" onsubmit="return chooseSeat(this);">
', $seat_str);

} else {
    $src .= '
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
<p>You must login in order to choose your seat.</p>
<p>You may hover over any seat with your cursor to see its status.</p>
';
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

