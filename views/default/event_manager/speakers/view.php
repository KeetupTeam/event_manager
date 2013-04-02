<?php

/**
 * Event manager / speakers / view
 */

$event = elgg_extract('entity', $vars, false);

if (!($event instanceof Event)) {
	return false;
}

if (!$event->with_speakers) {
	return false;
}

// Get speakers
$count_speakers = $event->getEventSpeakers(array('count' => TRUE));
$speakers = $event->listEventSpeakers();

// Content
$content = '';

if ($speakers) {
	$content .= $speakers;
}
else {
	$content .= '<p class="speakers_empty">'.elgg_echo('event_manager:speakers:empty').'</p>';
}

if ($event->canEdit()) {
	$class = '';
	if ($count_speakers >= EVENT_MANAGER_EVENTSPEAKER_MAX) {
		$class = 'hidden';
	}
	$content .= '<div class="content-speakers-add '.$class.'">';
	$content .= elgg_view('output/url', array(
		'text' => elgg_echo('event_manager:speakers:add'),
		'href' => 'javascript:void(0)',
		'class' => 'elgg-button elgg-button-action event-manager-speakers-add',
		'rel' => $event->getGUID(),
	));
	$content .= '('.elgg_echo('event_manager:speakers:maximum', array(EVENT_MANAGER_EVENTSPEAKER_MAX)).')';
	$content .= '</div>';
}

echo elgg_view_module("info", elgg_echo('event_manager:speakers:title'), $content, array('class' => 'elgg-module-speakers'));