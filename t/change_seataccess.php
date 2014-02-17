<?php

/**
 * Change the seat acces for a player.
 */

require_once dirname(__FILE__) . '/../l/config.inc.php';
require_once dirname(__FILE__) . '/../l/db.inc.php';
require_once dirname(__FILE__) . '/../l/session.inc.php';
require_once dirname(__FILE__) . '/../l/view.inc.php';

requireAdminSession();

$message = '';

// If the form was submitted, ...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$pid = $_POST['pid'];
	$action = $_POST['action'];

	if ($action == 'grantaccess') {

		// Make the changes in the DB
		$res = $db->query($sql = sPrintF('UPDATE `players`
		  SET `seataccess`="1"
		  WHERE `pid`=%1$s
		  ', s($pid)));
		if (!$res) {
			error($sql);
		}

	} else if ($action == 'revokeaccess') {

		// Make the changes in the DB
		$res = $db->query($sql = sPrintF('UPDATE `players`
		  SET `seataccess`="0", `seat`=NULL
		  WHERE `pid`=%1$s
		  ', s($pid)));
		if (!$res) {
			error($sql);
		}
	}

	// Display the results page
	$message = 'Change Successful!';

}

// Form was not submitted; display the form
$pid = $_GET['pid'];

$res = $db->query($sql = sPrintF('SELECT `dname`, `fname`, `lname`, `seat`, `seataccess`
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

$seat_str = $p['seataccess'] != 1 ? 'Does not currently have seat access.'
  : ($p['seat'] ? sPrintF('Has selected seat %s.', $p['seat']) : 'Has seat access, but has not selected a seat.');

// Display the upgrade request form
$src = sPrintF('
<div class="center">
<h1>Change Seat Access: %1$s (%2$s %3$s)</h1>
<form action="#" method="post">
<input type="hidden" name="pid" value="%4$s" />
<p><strong>%6$s</strong></p>
<p>%5$s</p>
<ul class="spaced" style="list-style-type:none;">
<li><label><input type="radio" name="action" value="nochange" checked="checked" /> No change</label></li>
<li><label><input type="radio" name="action" value="grantaccess" /> Grant Access</label></li>
<li><label><input type="radio" name="action" value="revokeaccess" /> Revoke Access</label></li>
</ul>
<input type="submit" value="Change" />
</form>
</div>
', $p['dname'], $p['fname'], $p['lname'], $pid, $seat_str, $message);

mp($src);

