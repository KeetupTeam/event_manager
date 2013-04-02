<?php

/**
 * Object event speaker
 */

$entity = elgg_extract('entity', $vars, false);
if (!($entity instanceof EventSpeaker)) {
	return false;
}

$full = elgg_extract('full', $vars, false);

if ($full) {
	$image = elgg_view_entity_icon($entity, 'medium');
	
	$title = $entity->speaker_name;
	if (!empty($entity->speaker_url)) {
		$title = elgg_view('output/url', array(
			'href' => $entity->speaker_url,
			'text' => $entity->speaker_name,
			'target' => '_blank',
		));
	}
	
//	$metadata = elgg_view_menu('entity', array(
//		'entity' => $entity,
//		'handler' => 'event_manager',
//		'sort_by' => 'priority',
//		'class' => 'elgg-menu-hz',
//	));
	
	if($entity->canEdit()) {
//		$edit = "<a href='#' class='event_manager_program_slot_edit' rel='" . $slot->getGUID() . "'>" . elgg_echo("edit") . "</a>";
//		$delete_slot = "<a href='#' class='event_manager_program_slot_delete'>" . elgg_echo("delete") . "</a>";
		
		$edit = elgg_view('output/url', array(
			'href' => 'javascript:void(0)',
			'text' => elgg_Echo('edit'),
			'class' => 'event-manager-speakers-edit',
			'rel' => $entity->getGUID(),
		));
		
		$delete = elgg_view('output/url', array(
			'href' => elgg_add_action_tokens_to_url('action/event_manager/speakers/delete?guid='.$entity->guid),
			'text' => elgg_echo('delete'),
			'class' => 'event-manager-speakers-delete',
		));

		$title .= " [ " . $edit . " | " . $delete . " ]";
	}
	
	if (!empty($entity->speaker_bio)) {
		$content = '<b>'.elgg_echo('event_manager:speakers:form:bio').': </b>';
		$content .= $entity->speaker_bio;
	}
	
	$params = array(
		'entity' => $entity,
		'title' => $title,
//		'metadata' => $metadata,
		'content' => $content,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($image, $body, $vars);
?>

<?php
}