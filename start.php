<?php 
	global $CONFIG;
	
	function birthday_widget_init(){
		global $CONFIG;
		
		// Extend css
		extend_view("css", "birthday_widget/css");
		
		// Register widget
		add_widget_type('birthday_widget', elgg_echo("birthday_widget:widget:title"), elgg_echo('birthday_widget:widget:description'), "dashboard,profile,index");
		
		// fix user site membership
		run_function_once("birthday_widget_run_once_1_1");
	}
	
	function birthday_widget_run_once_1_1(){
		global $CONFIG;
		
		$sql = "SELECT e.guid";
		$sql .= " FROM " . $CONFIG->dbprefix . "entities e";
		$sql .= " WHERE e.type = 'user'";
		$sql .= " AND e.guid NOT IN";
		$sql .= " (SELECT guid_one";
		$sql .= " FROM " . $CONFIG->dbprefix . "entity_relationships";
		$sql .= " WHERE guid_two = " . $CONFIG->site_guid;
		$sql .= " AND relationship = 'member_of_site')";
		
		if($guids = get_data($sql)){
			foreach($guids as $guid_object){
				add_site_user($CONFIG->site_guid, $guid_object->guid);
			}
		}
	}
	
	register_elgg_event_handler('init', 'system', 'birthday_widget_init');
?>