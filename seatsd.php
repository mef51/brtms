<?php

/**
 * Display a dynamic seating chart (experimentatal).
 */

require_once dirname(__FILE__) . '/l/seating.inc.php';
require_once dirname(__FILE__) . '/l/session.inc.php';
require_once dirname(__FILE__) . '/l/view.inc.php';

// Specify the seats that are special
$res_seats = array(
  'D98' => ' Reserved',
  'D99' => ' Reserved',
  'H5' => ' Reserved',
  'H7' => ' Reserved',
  'H13' => ' Reserved',
  'H15' => ' Reserved',
  'H17' => ' Reserved',
  'H19' => ' Reserved',
);

$src = '';

// Display instructions and legend
// Note: The current display is as if the user is not logged in.
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
<p>You may hover over any seat with your cursor to see its status.</p>
';

unSet($_p);

// Generate the chart
// Note: The chart will be displayed as if the user is not logged in.
$src .= genSeatChart($res_seats);

$src .= '
<script src="seatsd_l.js" type="text/javascript"></script>
';

mp($src, 'Seating Plan');

