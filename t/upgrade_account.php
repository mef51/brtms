<?php

/**
 * Upgrade the registration level of a user, or display a form for such request.
 */

require_once dirname(__FILE__) . '/../l/config.inc.php';
require_once dirname(__FILE__) . '/../l/db.inc.php';
require_once dirname(__FILE__) . '/../l/session.inc.php';
require_once dirname(__FILE__) . '/../l/view.inc.php';

requireAdminSession();

// The possible upgraded registration levels
// The numbers are % by 10 to get the credits. This array should associate the other way
$tickets = array(
	1 => '1 Major Tournament (upgrade)',
	2 => '2 Major Tournaments (upgrade)',
	12 => 'Console-only 2 Major Tournaments (upgrade)',
	4 => 'Volunteer Ticket (upgrade)',
);

// If the form was submitted, ...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$pid = $_POST['pid'];
	$tic = (int) $_POST['tic'];

	if ($tic == 0) {
		mp('<p>No Change.</p>');
	}

	// Calculate their early-bird status and credit level
	$credits = $tic % 10;

	var_export($credits);

	// Make the changes in the DB
	$res = $db->query($sql = sPrintF('UPDATE `players`
	  SET `credits`=%2$s, `ticket`=%3$s
	  WHERE `pid`=%1$s
	  ', s($pid), s($credits), s($tickets[$tic])));
	if (!$res) {
		error($sql);
	}

	$res = $db->query($sql = sPrintF('SELECT `dname`, `fname`, `lname`, `token`
	  FROM `players`
	  WHERE `pid`=%1$s
	  ', s($pid)));
	if (!$res) {
		error($sql);
	}
	$p = $res->fetch_assoc();
	if (!$p) {
		die('Could not find player.');
	}

	// Display the results page
	$src = sPrintF('<h1>Upgraded Account: %1$s (%2$s %3$s)</h1>
	<p>Successful!</p>
	', $p['dname'], $p['fname'], $p['lname']);

	mp($src);

} else {

	// Form was not submitted; display the form
	$pid = $_GET['pid'];

	$res = $db->query($sql = sPrintF('SELECT `dname`, `fname`, `lname`, `token`
	  FROM `players`
	  WHERE `pid`=%1$s
	  ', s($pid)));
	if (!$res) {
		error($sql);
	}
	$p = $res->fetch_assoc();
	if (!$p) {
		die('Could not find player.');
	}

	// Display the upgrade request form
	$src = sPrintF('<h1>Upgrade Account: %1$s (%2$s %3$s)</h1>
	<form action="#" method="post">
<input type="hidden" name="pid" value="%4$s" />
<select name="tic">
<option value="0">No Change</option>
	', $p['dname'], $p['fname'], $p['lname'], $pid);

	foreach ($tickets as $val => $name) {
		$src .= sPrintF('<option value="%1$s">%2$s</option>',
		  $val, $name);
	}
	$src .= '</select><input type="submit" value="Change" /></form>';

	mp($src);
}
