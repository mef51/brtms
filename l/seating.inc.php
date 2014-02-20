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
		  . '<input type="radio" /></th>',
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
			isSet($_p) && $_p['seataccess'] == 1 ? 'vac' : 'vac2');
	}
}

function genSeatCol($letter, $even, $res_seats, $count = 11) {
	$output = '<table cellspacing="2" class="p1">';
	for ($j = 0; $j < $count; $j++) {
		$output .= '<tr>' . genSeatCell($letter . (($even ? 20 : 19) - 2 * $j + ($j == 10 ? 99 : 0)), $res_seats) . '</tr>';
	}
	$output .= '</table>';
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
<tr style="height:61px;"><td>&nbsp;</td></tr>
<tr>
<td style="width:125px;">&nbsp;</td>
';

	$output .= '<td><div style="padding-top:128px;">' . genSeatCol('H', 0, $res_seats, 6) . '</div></td>';
	$output .= '<td style="width:17px;">&nbsp;</td>';


	for ($i = 0; $i < 6; $i++) {
		$output .= '<td>' . genSeatCol(chr(65 + $i), 0, $res_seats) . '</td>';
		$output .= '<td style="width:28px;">&nbsp;</td>';
		$output .= '<td>' . genSeatCol(chr(65 + $i), 1, $res_seats) . '</td>';
		$output .= '<td style="width:17px;">&nbsp;</td>';
	}

	$output .= '<td><div style="padding-top:151px;">' . genSeatCol('G', 0, $res_seats, 4) . '</div></td>';
	$output .= '<td style="width:138px;">&nbsp;</td>
</tr>
<tr style="height:171px;"><td>&nbsp;</td></tr>
</table>
';

	return $output;
}

