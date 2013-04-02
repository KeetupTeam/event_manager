<?php

/**
 * Event manager / sponsors / view
 */

$event = elgg_extract('entity', $vars, false);

if (!($event instanceof Event)) {
	return false;
}

if (!$event->with_sponsors) {
	return false;
}

// Get sponsors
$count_sponsors = $event->getEventSponsors(array('count' => TRUE));
$sponsors = $event->listEventSponsors();

// Content
$content = '';

if ($sponsors) {
	$content .= $sponsors;
}
else {
	$content .= '<p class="sponsors_empty">'.elgg_echo('event_manager:sponsors:empty').'</p>';
}

if ($event->canEdit()) {
	$class = '';
	if ($count_sponsors >= EVENT_MANAGER_EVENTSPONSOR_MAX) {
		$class = 'hidden';
	}
	$content .= '<div class="content-sponsors-add '.$class.'">';
	$content .= elgg_view('output/url', array(
		'text' => elgg_echo('event_manager:sponsors:add'),
		'href' => 'javascript:void(0)',
		'class' => 'elgg-button elgg-button-action event-manager-sponsors-add',
		'rel' => $event->getGUID(),
	));
	$content .= '('.elgg_echo('event_manager:sponsors:maximum', array(EVENT_MANAGER_EVENTSPONSOR_MAX)).')';
	$content .= '</div>';
}

echo elgg_view_module("info", elgg_echo('event_manager:sponsors:title'), $content, array('class' => 'elgg-module-sponsors'));