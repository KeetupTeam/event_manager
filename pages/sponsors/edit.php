<?php

/**
 * Sponsors edit
 */

$is_xhr = elgg_is_xhr();
if (!$is_xhr) {
	forward();
}

if (!elgg_is_logged_in()) {
	forward();
}

$event_guid = get_input('event_guid');
$sponsor_guid = get_input('sponsor_guid');

$vars = array(
	'event_guid' => $event_guid,
	'sponsor_guid' => $sponsor_guid,
);

echo elgg_view('event_manager/forms/sponsors/edit', $vars);

exit;