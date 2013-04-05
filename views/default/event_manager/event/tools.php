<?php 

	$event = $vars["entity"];

	$in_context_admin = elgg_in_context('admin');
	
	$toolLinks = "<span class='event_manager_event_actions'>" . elgg_echo('tools') . "</span>";
	$toolLinks .= "<ul class='event_manager_event_actions_drop_down actions_tools'>";
//	$toolLinks .= "<li>" . elgg_view("output/url", array("href" => "events/event/edit/" . $event->getGUID(), "text" => elgg_echo("event_manager:event:editevent"))) . "</li>";
//	$toolLinks .= "<li>" . elgg_view("output/confirmlink", array("href" => "action/event_manager/event/delete?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:deleteevent"))) . "</li>";
	
	$url_upload = "events/event/upload/" . $event->getGUID();
	if ($in_context_admin) {
		$url_upload = 'admin/moderator/publish/events_upload/'.$event->getGUID();
	}
	$toolLinks .= "<li>" . elgg_view("output/url", array("href" => $url_upload, "text" => elgg_echo("event_manager:event:uploadfiles"))) . "</li>";
	
	if($event->registration_needed)	{
		$url_registration = "events/registrationform/edit/". $event->getGUID();
		if ($in_context_admin) {
			$url_registration = 'admin/moderator/publish/events_registrationform/'.$event->getGUID();
		}
		$toolLinks .= "<li>" . elgg_view("output/url", array("href" =>  $url_registration, "text" => elgg_echo("event_manager:event:editquestions"))) . "</li>";
	}
	
	$toolLinks .= "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/attendees/export?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:exportattendees"))) . "</li>";
	if($event->waiting_list_enabled && $event->countWaiters()) {
		$toolLinks .= "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/attendees/export_waitinglist?guid=" . $event->getGUID(), "text" => elgg_echo("event_manager:event:exportwaitinglist"))) . "</li>";
	}
	$toolLinks .= "</ul>";
	
	echo $toolLinks;