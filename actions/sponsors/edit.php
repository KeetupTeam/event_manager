<?php

/**
 * Action sponsors edit
 */

$guid = get_input('guid');
$container_guid = get_input('container_guid');
$name = get_input('sponsor_name');
$url = get_input('sponsor_url');

$eventsponsor = get_entity($guid);
$event = get_entity($container_guid);

// Validate
if (!($event instanceof Event)) {
	register_error(elgg_echo('event_manager:sponsors:save:error'));
	forward(REFERER);
}
if (!$event->canWriteToContainer()) {
	register_error(elgg_echo('event_manager:sponsors:save:error'));
	forward(REFERER);
}

// Is new?
$new = true;
if ($eventsponsor instanceof EventSponsor) {
	$new = false;
}

// Validate image
if ($new && !isset($_FILES['sponsor_image'])) {
	register_error(elgg_echo('event_manager:sponsors:image:error'));
	forward(REFERER);
}
if (isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['error'] != 0) {
	register_error(elgg_echo('event_manager:sponsors:image:error'));
	forward(REFERER);
}
if (isset($_FILES['sponsor_image']) && !substr_count($_FILES['sponsor_image']['type'],'image/')) {
	register_error(elgg_echo('event_manager:sponsors:image:type:error'));
	forward(REFERER);
}

// Validate name
if (empty($name)) {
	register_error(elgg_echo('event_manager:sponsors:name:error'));
	forward(REFERER);
}

// Validate url
//$v = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
$v = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
if (!empty($url) && !preg_match($v, $url)) {
	register_error(elgg_echo('event_manager:sponsors:url:error'));
	forward(REFERER);
}

if ($new) {
	$eventsponsor = new EventSponsor();
	$eventsponsor->owner_guid = $event->owner_guid;
	$eventsponsor->container_guid = $event->guid;
	$eventsponsor->access_id = $event->access_id;
}

$eventsponsor->sponsor_name = $name;
$eventsponsor->sponsor_url = $url;

$success = $eventsponsor->save();

// Save image
$icon_sizes = elgg_get_config('icon_sizes');

$prefix = "eventsponsor/" . $eventsponsor->guid;

$filehandler = new ElggFile();
$filehandler->owner_guid = $eventsponsor->owner_guid;
$filehandler->setFilename($prefix . ".jpg");
$filehandler->open("write");
$filehandler->write(get_uploaded_file('sponsor_image'));
$filehandler->close();
$filename = $filehandler->getFilenameOnFilestore();

$sizes = array('tiny', 'small', 'medium', 'large');

$thumbs = array();
foreach ($sizes as $size) {
	$thumbs[$size] = get_resized_image_from_existing_file(
		$filename,
		$icon_sizes[$size]['w'],
		$icon_sizes[$size]['h'],
		$icon_sizes[$size]['square']
	);
}

if ($thumbs['tiny']) { // just checking if resize successful
	$thumb = new ElggFile();
	$thumb->owner_guid = $eventsponsor->owner_guid;
	$thumb->setMimeType('image/jpeg');

	foreach ($sizes as $size) {
		$thumb->setFilename("{$prefix}{$size}.jpg");
		$thumb->open("write");
		$thumb->write($thumbs[$size]);
		$thumb->close();
	}

	$eventsponsor->icontime = time();
}

if ($success) {
	$output = array();
	$output['list'] = $event->listEventSponsors();
	
	echo json_encode($output);
	
	system_message(elgg_echo('event_manager:sponsors:save:success'));
}
else {
	register_error(elgg_echo('event_manager:sponsors:save:error'));
}

forward(REFERER);