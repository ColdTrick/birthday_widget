<?php

	$widget = elgg_extract("entity", $vars);
	
	$metadata_field = elgg_get_plugin_setting("metadata_field", "birthday_widget");
	$timeformat = elgg_get_plugin_setting("timeformat", "birthday_widget");
	$timeformat_other = elgg_get_plugin_setting("timeformat_other", "birthday_widget");
	
	if(empty($timeformat) || !in_array($timeformat, array("other", "unix"))){
		$timeformat = "unix";
	}
	
	$who = $widget->who_to_show;
	
	if(empty($who) || !in_array($who, array("friends", "all"))){
		$who = "friends";
	}
	
	$count = (int) $widget->num_display;
	if($count < 1){
		$count = 10;
	}
	
	// Get all the needed users
	$user_options = array(
		"type" => "user",
		"limit" => false,
		"metadata_name_value_pairs" => array(
			"name" => $metadata_field,
			"value" => "",
			"operand" => "<>"
		)
	);
	
	switch($widget->context){
		case "groups":
			$user_options["relationship"] = "member";
			$user_options["relationship_guid"] = $widget->getOwnerGUID();
			$user_options["inverse_relationship"] = true;
			break;
		case "index":
			if(($who == "all") || !elgg_is_logged_in()){
				$user_options["relationship"] = "member_of_site";
				$user_options["relationship_guid"] = $widget->site_guid;
				$user_options["inverse_relationship"] = true;
			} else {
				$user_options["relationship"] = "friend";
				$user_options["relationship_guid"] = elgg_get_logged_in_user_guid();
			}
			break;
		default:
			$user_options["relationship"] = "friend";
			$user_options["relationship_guid"] = $widget->getOwnerGUID();
			break;
	}
	
	// start birthday sorting
	$time_array = getdate();
	$current_year = $time_array["year"];
	$current_year_day = $time_array["yday"];
	
	$birth_array = array();
	
	if($users = elgg_get_entities_from_relationship($user_options)){
		foreach($users as $user){
			$time_value = $user->$metadata_field;
			$user_timeformat = $timeformat;
			
			if(!empty($time_value)){
				$user_time_array = false;
				
				$ts = (int) $time_value;
				if((strlen($ts) == strlen($time_value)) && (($user_time_array = getdate($ts)) !== false)){
					// unix timestamp
				} elseif(($new = strtotime($time_value)) !== false){
					// time in date format
					$user_time_array = getdate($new);
					$user_timeformat = "unix";
				} elseif($timeformat != "unix"){
					$user_time_array = strptime($time_value, $timeformat_other);
				}
				
				if(!empty($user_time_array)){
					if($user_timeformat == "unix"){
						if($current_year_day > $user_time_array["yday"]){
							$user_time = mktime(0, 0, 0, $user_time_array["mon"], $user_time_array["mday"], ($current_year + 1));
						} else {
							$user_time = mktime(0, 0, 0, $user_time_array["mon"], $user_time_array["mday"], $current_year);
						}
					} else {
						if($current_year_day > $user_time_array["tm_yday"]){
							$user_time = mktime(0, 0, 0, ($user_time_array["tm_mon"] + 1), $user_time_array["tm_mday"], ($current_year + 1));
						} else {
							$user_time = mktime(0, 0, 0, ($user_time_array["tm_mon"] + 1), $user_time_array["tm_mday"], $current_year);
						}
					}
					
					$birth_array[$user->getGUID()] = $user_time;
				}
			}
		}
		// memory cleanup
		unset($users);
		
		// sort birthdays
		asort($birth_array);
		
		$count = min($count, count($birth_array));
		
		$limited = array_slice($birth_array, 0, $count, true);
		
		if(!empty($limited) && is_array($limited)){
			foreach($limited as $guid => $timestamp){
				if($user = get_user($guid)){
					$time_array = getdate($timestamp);
		
					$month = str_pad($time_array["mon"], 2, "0", STR_PAD_LEFT);
					$time = elgg_echo("date:month:" . $month, array($time_array["mday"]));
		
					$icon = elgg_view_entity_icon($user, "tiny");
					
					$params = array(
						"entity" => $user,
						"subtitle" => $time
					);
					$params = $params + $vars;
					$summary = elgg_view("user/elements/summary", $params);
					
					echo elgg_view_image_block($icon, $summary);
				}
			}
		} else {
			echo elgg_echo("notfound");
		}
	} else {
		echo elgg_echo("notfound");
	}
	