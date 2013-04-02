<?php

/**
 * Speakers edit
 */

$is_xhr = elgg_is_xhr();
if (!$is_xhr) {
	forward();
}

if (!elgg_is_logged_in()) {
	forward();
}

$event_guid = get_input('event_guid');
$speaker_guid = get_input('speaker_guid');

$vars = array(
	'event_guid' => $event_guid,
	'speaker_guid' => $speaker_guid,
);

echo elgg_view('event_manager/forms/speakers/edit', $vars);

exit;