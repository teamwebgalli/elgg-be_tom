<?php

elgg_register_event_handler('init', 'system', 'betom_init');
function betom_init() {
	// For new users
	elgg_register_event_handler('create', 'user', 'betom_friend_with_tom_user', 501);
	// For already existing users
    elgg_register_event_handler('login:after', 'user', 'betom_friend_with_tom_user', 501);
	// Dont allow removing of the tom friend
	elgg_register_plugin_hook_handler('action', 'friends/remove', 'betom_prevent_unfriending_tom_user', 0);
}

function betom_friend_with_tom_user($event, $object_type, $object){
	if ($object instanceof ElggUser) {
		$tom_users = elgg_get_plugin_setting('to_be_tommed', 'be_tom');
		$split = explode(",", $tom_users);
		$userGuid = $object->getGUID();
		foreach ($split as $tomGuid) {
			// assignment intentional
			if (!($tom = get_user($tomGuid))) { 
				continue;
			}
			// this is tom
			if ($userGuid == $tomGuid) {
				continue;
			}
			// already friends
			if(check_entity_relationship($userGuid, "friend", $tomGuid)){
				continue;
			} elseif (!add_entity_relationship($userGuid, "friend", $tomGuid)) {
				return false;
			}
		}	
	}
	return true;
}

function betom_prevent_unfriending_tom_user($hook, $type, $value, $params) {
	$friend_guid = (int) get_input('friend');
	if ($friend = get_user($friend_guid)) {
		$tom_users = elgg_get_plugin_setting('to_be_tommed', 'be_tom');
		$split = explode(",", $tom_users);
		if(in_array($friend_guid, $split)) {
			register_error(elgg_echo('be_tom:removeFailed', array($friend->name)));
			forward(REFERER);
		}
	}
}
