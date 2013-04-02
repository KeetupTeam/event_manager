<?php

/**
 * Event Speaker
 */

class EventSpeaker extends ElggObject {

	const SUBTYPE = "eventspeaker";

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes["subtype"] = self::SUBTYPE;
	}

}