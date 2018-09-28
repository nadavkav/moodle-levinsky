<?php
defined ( 'MOODLE_INTERNAL' ) || die ();

$observers = array (
		array (
				'eventname' => '\core\event\user_created',
				'callback' => 'local_user_profile_update\observer::user_created_handler'
		),
		array (
				'eventname' => '\core\event\user_updated',
				'callback' => 'local_user_profile_update\observer::user_updated_handler'
		)
);
