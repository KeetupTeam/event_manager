<?php 

/**
 * Event manager sponsor delete
 */

$guid = get_input("guid");
$eventsponsor = get_entity($guid);

$success = false;

if($eventsponsor instanceof EventSponsor) {
	$container = $eventsponsor->getContainerEntity();
	if ($eventsponsor->canEdit() && $container->canWriteToContainer()) {
		$success = $eventsponsor->delete();
	}
}

if ($success) {
	system_message(elgg_echo('event_manager:sponsors:delete:success'));
	
	$output = array();
	
	if ($container instanceof Event) {
		$list = $container->listEventSponsors();
		if (empty($list)) {
			$list = '<p class="sponsors_empty">'.elgg_echo('event_manager:sponsors:empty').'</p>';
		}
		$output['guid'] = $guid;
		$output['list'] = $list;
	}
	
	echo json_encode($output);
}
else {
	register_error(elgg_echo('event_manager:sponsors:delete:error'));
}

forward(REFERER);