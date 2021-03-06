<?php

/**
 * AJAX request for the current user to join a team/group.
 */

require_once dirname(__FILE__) . '/../l/db.inc.php';
require_once dirname(__FILE__) . '/../l/session.inc.php';
require_once dirname(__FILE__) . '/../l/utils.inc.php';

requireSession('json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$ret = array();
	$gid = @$_POST['gid'];
	
	// Add them to the team/group
	$res = $db->query($sql = sPrintF('UPDATE `tournament_players`
	  SET `gid`=%1$s
	  WHERE `tid`=(SELECT `tid` FROM `groups` WHERE `gid`=%1$s) AND `pid`=%2$s
	  ', s($gid), s($_p['pid'])));
	if (!$res) {
		error($sql);
	}

	$affected = $db->affected_rows;
	if ($affected == 1) {
		$ret = array('result' => 'success');
	} else {
		$ret = array('result' => 'error', 'errorType' => 'affected: ' . $affected);
	}
	
	header('Content-Type: application/json');
	echo json_encode($ret);
	
} else {
	http_response_code(400);
}

