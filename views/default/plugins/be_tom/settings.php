<?php
$to_be_tommed = $vars['entity']->to_be_tommed;

$logged_in_guid = elgg_get_logged_in_user_guid();

if (!elgg_get_plugin_setting('to_be_tommed', 'be_tom')) {
	elgg_set_plugin_setting('to_be_tommed', $logged_in_guid, 'be_tom');
}

echo '<div>';
echo elgg_echo('be_tom:settings',array($logged_in_guid)); 
echo ' ';
echo elgg_view('input/text', array('name' => "params[to_be_tommed]", 'value' => $to_be_tommed)); 
echo '</div>';
?>