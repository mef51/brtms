<?php

/**
 * Display the comprehensive administration dashboard.
 */

require_once dirname(__FILE__) . '/../l/db.inc.php';
require_once dirname(__FILE__) . '/../l/session.inc.php';
require_once dirname(__FILE__) . '/../l/view.inc.php';

requireAdminSession();

function fd($ts) {
	if (!$ts) {
		return '';
	}
	$delta = time() - strToTime($ts);
	if ($delta < 90) {
		return sPrintF('%ds ago', $delta);
	} else if ($delta < 90 * 60) {
		return sPrintF('%dmin ago', $delta / 60);
	} else if ($delta < 36 * 60 * 60) {
		return sPrintF('%dh ago', $delta / 60 / 60);
	} else if ($delta < 45 * 24 * 60 * 60) {
		return sPrintF('%dd ago', $delta / 60 / 60 / 24);
	} else {
		return sPrintF('%d mo ago', $delta / 60 / 60 / 24 / 30);
	}
}

$src = '<h1>Players List</h1>';

$res = $db->query('SELECT
  (SELECT COUNT(*) FROM `players`) AS `total`,
  (SELECT COUNT(*) FROM `players` WHERE `firstlogints` IS NOT NULL) AS `signups`,
  (SELECT COUNT(*) FROM `players` WHERE `seat` IS NOT NULL) AS `seated`,
  (SELECT COUNT(*) FROM `players` WHERE `seataccess`=1) AS `seataccess`,
  (SELECT COUNT(*) FROM `players` WHERE `invitedts` IS NULL) AS `notinvited`,
  (SELECT COUNT(*) FROM `players` WHERE `invitedts` > DATE_SUB(NOW(), INTERVAL 1 HOUR)) AS `lasthour`,

  (SELECT COUNT(*) FROM `players`
    WHERE `early`!=2 AND `credits`=0) AS `tickets_0cred`,
  (SELECT COUNT(*) FROM `players`
    WHERE `early`!=2 AND `credits`=1) AS `tickets_1cred`,
  (SELECT COUNT(*) FROM `players`
    WHERE `early`!=2 AND `credits`=2) AS `tickets_2cred`,
  (SELECT COUNT(*) FROM `tournament_players`
    INNER JOIN `tournaments` USING (`tid`)
    INNER JOIN `players` USING (`pid`)
    WHERE `tourney_type`=2 AND `early`!=2) AS `joined_major`,
  (SELECT SUM(`credits`) FROM `players`
    WHERE `early`!=2 AND `credits`<10) AS `credits_major`,
  (SELECT COUNT(*) FROM `tournament_players`
    INNER JOIN `tournaments` USING (`tid`)
    WHERE `tourney_type`=1) AS `joined_minor`,
  (SELECT COUNT(*) FROM `tournament_players`
    INNER JOIN `tournaments` USING (`tid`)
    WHERE `tourney_type`=0) AS `joined_crowd`,
  (SELECT COUNT(*) FROM `tournaments` WHERE `tourney_type`=0) AS `tours_crowd`,
  (SELECT COUNT(*) FROM `tournaments` WHERE `published`=0) AS `tours_unpublished`,
  (SELECT COUNT(DISTINCT `gid`) FROM `tournament_players`) AS `total_teams`,
  (SELECT COUNT(`gid`) FROM `tournament_players`) AS `total_team_members`,
  (SELECT COUNT(*) FROM `tournament_players`
    INNER JOIN `tournaments` USING (`tid`)
    WHERE `teamsize`>1 AND `gid` IS NULL) AS `free_agents`
  FROM DUAL');
$stats = $res->fetch_assoc();

// Display the comprehensive statistical information
$src .= '<div class="center">';
$src .= mt('Total Players', $stats['total'], 'yellow');
$src .= mt('Signed Up', $stats['signups'], 'green', sPrintF('equiv to %d%%', $stats['signups'] / $stats['total'] * 100));
$src .= mt('Seated', $stats['seated'], 'green', sPrintF('equiv to %d%%', $stats['seated'] / $stats['seataccess'] * 100));
$src .= mt('Not Invited', $stats['notinvited'], 'red');
$src .= mt('Invites Sent', $stats['lasthour'], 'orange', 'Last Hour');
$src .= '</div>';
$src .= '<div class="center">';
$src .= mt('Tickets', $stats['tickets_0cred'] . '/' . $stats['tickets_1cred']
  . '/' . $stats['tickets_2cred'], 'yellow', 'of 0/1/2');
$src .= mt('Total Teams', $stats['total_teams'], 'green', $stats['total_team_members'] . ' members');
$src .= mt('Free Agents', $stats['free_agents'], 'yellow');
$src .= mt('Crowd Tours', $stats['tours_crowd'], 'green', $stats['tours_unpublished'] . ' pending');
$src .= '</div>';
$src .= '<div class="center">';
$src .= mt('Joined Majors', $stats['joined_major'], 'blue', 'out of ' . $stats['credits_major']);
$src .= mt('Joined Minors', $stats['joined_minor'], 'blue');
$src .= mt('Joined Crowds', $stats['joined_crowd'], 'blue');
$src .= '</div>';

$res = $db->query('SELECT `pid`, `fname`, `lname`, `seat`, `seataccess`, `credits`, `firstlogints`, `lastlogints`,
  (SELECT COUNT(`tid`) FROM `tournaments` `t`
    INNER JOIN `tournament_players` `tp` USING (`tid`)
    WHERE `tourney_type`=2 AND `tp`.`pid`=`p`.`pid`) AS `tours_major`,
  (SELECT COUNT(`tid`) FROM `tournaments` `t`
    INNER JOIN `tournament_players` `tp` USING (`tid`)
    WHERE `tourney_type`=1 AND `tp`.`pid`=`p`.`pid`) AS `tours_minor`,
  (SELECT COUNT(`tid`) FROM `tournaments` `t`
    INNER JOIN `tournament_players` `tp` USING (`tid`)
    WHERE `tourney_type`=0 AND `tp`.`pid`=`p`.`pid`) AS `tours_crowd`,
  (SELECT COUNT(`gid`) FROM `tournament_players` `tp`
    WHERE `tp`.`pid`=`p`.`pid`) AS `teams`
  FROM `players` `p`
  ORDER BY `tours_major`, `credits`, `tours_crowd`, `firstlogints`');

// Display the full list of users with some of their stats
$src .= '<table cellspacing="0" class="border center tac">
';
$ths = '<tr><th>#</th><th>Name</th><th>Seat</th><th>Major</th><th>Minor</th><th>Crowd</th><th>Teams</th>
  <th>First Login</th><th>Last Login</th><th>Actions</th></tr>';

$i = 0;
while ($p = $res->fetch_assoc()) {
	if (($i++ % 25) == 0) {
		$src .= $ths;
	}
	$last_login = fd($p['lastlogints']);
	if ((strToTime($p['lastlogints']) - strToTime($p['firstlogints'])) < 60) {
		$last_login = '<small>same</small>';
	}
	$seat_str = $p['seat'];
	if (!$seat_str) {
		$seat_str = $p['seataccess'] == '1' ? '(not selected)' : '<small>(no access)</small>';
	}
	$src .= sPrintF('<tr>
	  <td>%s</td>
	  <td class="l">%s %s</td>
	  <td><a href="change_seataccess?pid=%1$s" title="Change Seat Access Permission">%s</a></td>
	  <td>%s/%s</td>
	  <td>%s</td>
	  <td>%s</td>
	  <td>%s</td>
	  <td>%s</td>
	  <td>%s</td>
	  <td><a href="reset_password?pid=%1$s" title="Reset Password">[R]</a>
	    <a href="upgrade_account?pid=%1$s" title="Upgrade Account">[U]</a></td>
</tr>
', $p['pid'], $p['fname'], $p['lname'], $seat_str,
  $p['tours_major'], $p['credits'], $p['tours_minor'], $p['tours_crowd'],
  $p['teams'], fd($p['firstlogints']), $last_login);
}

$src .= '</table>';

mp($src, 'Players List');

