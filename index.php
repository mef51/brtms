<?php

/**
 * Display the main home page with static content.
 */

require_once dirname(__FILE__) . '/l/view.inc.php';

mp('<h1>Players Portal</h1>

<p>Welcome to the Players Portal.  It is through this portal that registered Battle Royale players can
<a href="${ROOT}/tournaments">join tournaments</a>, <a href="${ROOT}/tournaments">create new tournaments</a>, and
<a href="${ROOT}/seats">choose their seats</a>.</p>

<p>New this year for Battle Royale VII, we are trying a new tournaments mechanism: we have Major
Tournaments, Minor Tournaments, and Crowdsourced Tournaments.</p>

<h2>Major Tournaments</h2>
<p style="margin-left:20px;">
These are tournaments that the Battle Royale Organizing Committee will run.
These tournaments will have strict schedules, specific rule sets, and large prizes.
Your participation in these tournaments is limited by your ticket type: you can join up to the
number of tournaments you have purchased. However, if you want to upgrade your ticket type,
you can do so at either of the IEEE offices at uOttawa or Carleton, and also at the event itself.
</p>

<h2>Minor Tournaments</h2>
<p style="margin-left:20px;">
These are tournaments that the Battle Royale Organizing Committee will also run.
These tournaments will have looser schedules, specific rule sets, but no prizes.
Anyone can participate in Minor tournaments throughout the event. You can join as many
Minor tournaments as you like. If a scheduling conflict arises (and you need to play two games at the same time)
you will be asked to forfeit one of the matches.
</p>

<h2>Crowdsourced Tournaments</h2>
<p style="margin-left:20px;">
These are tournaments that will be organized by players themselves.
For example, many players will be running various Minecraft servers.
These tournaments can be of very casual nature, or serious, but will be organized by the players themselves:
it is up to them to arrange the schedules (avoiding conflicts with the Major Tournaments if they like),
choose the rules, and make the bracketing.
These tournaments are free to join, no matter which ticket type you have purchased. Crowdsourced tournaments
are created by the players through the Players Portal.
</p>

<p>
To manage registrations to the tournaments, use the interface on the
<a href="${ROOT}/tournaments">Tournaments page</a> to make your selections.
</p>'
, 'Home Dashboard');

