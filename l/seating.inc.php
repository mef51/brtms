<?php

/**
 * This library file contains the functions used to generate the seating chart.
 */

require_once dirname(__FILE__) . '/session.inc.php';

/**
 * Generate a table cell in the seating chart.
 * Note: The chart will be displayed differently if the user is not logged in.
 * @param $seat - The seat number (ex: J14).
 * @param $res_sets - The states of the seats (ex: occupied, reserved, etc).
 * @return The HTML code of the generated table cell.
 */
function genSeatCell($seat, $res_seats = array()) {
	global $_p;

	if (isSet($res_seats[$seat])) {
		if ($res_seats[$seat] == ' Unavailable') {
			return sPrintF('<th id="seat-%1$s"></th>', $seat);
		}
		return sPrintF('<th class="%3$s" title="Seat %1$s - %2$s" id="seat-%1$s">'
		  . '<input type="checkbox" /></th>',
			$seat,
			$res_seats[$seat] == ' Reserved' ? 'Reserved'
			  : (isSet($_p) ? 'Taken by '
			    . $res_seats[$seat] : 'Occupied'),
			$res_seats[$seat] == ' Reserved' ? 'rvd'
			  : (isSet($_p) ? ($seat == $_p['seat']
			    ? 'me' : 'occ') : 'occ'));
	} else {
		return sPrintF('<th class="%2$s" title="Seat %1$s - Vacant" id="seat-%1$s">'
		  . '<input type="radio" name="seat" value="%1$s" /></th>',
			$seat,
			isSet($_p) ? 'vac' : 'vac2');
	}
}

function genSeatRow($letter, $even, $res_seats) {
	$output = '<tr><td><table cellspacing="0" class="p1"><tr>';
	for ($j = 0; $j < 12; $j++) {
		$output .= genSeatCell($letter . (($even ? 24 : 23) - 2 * $j), $res_seats);
	}
	$output .= '</tr></table></td><tr>';
	return $output;
}

/**
 * Generate the seating chart.
 * Note: The chart will be displayed differently if the user is not logged in.
 * @param $res_sets - The states of the seats (ex: occupied, reserved, etc).
 * @return The HTML code of the generated chart.
 */
function genSeatChart($res_seats = array()) {

	$output = '<table cellspacing="0" class="seating-chart real">
<tr style="height:151px;"><td>&nbsp;</td></tr>
<tr>
<td style="width:130px;">&nbsp;</td>
';

	for ($k = 0; $k < 2; $k++) {
		$output .= '<td><table cellspacing="0" class="p1">';
		for ($i = 0; $i < 4; $i++) {
			$output .= genSeatRow(chr(68 - $i + $k * 4), 0, $res_seats);
			$output .= '<tr style="height:22px;"><td>&nbsp;</td></tr>';
			$output .= genSeatRow(chr(68 - $i + $k * 4), 1, $res_seats);
			$output .= '<tr style="height:21px;"><td>&nbsp;</td></tr>';
		}
		$output .= '</table></td><td style="width:103px;">&nbsp;</td>';
	}

	$output .= '<td style="width:35px;">&nbsp;</td>
</tr>
<tr style="height:81px;"><td>&nbsp;</td></tr>
</table>
';

	return $output;
}

