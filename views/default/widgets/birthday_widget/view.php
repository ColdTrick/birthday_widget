<?php

	$widget = $vars["entity"];
	
	$metadata_field = get_plugin_setting("metadata_field", "birthday_widget");
	$timeformat = get_plugin_setting("timeformat", "birthday_widget");
	$timeformat_other = get_plugin_setting("timeformat_other", "birthday_widget");
	
	if(empty($timeformat) || !in_array($timeformat, array("other", "unix"))){
		$timeformat = "unix";
	}
	
	$who = $widget->who_to_show;
	
	if(empty($who) || !in_array($who, array("friends", "all"))){
		$who = "friends";
	}
	
	$count = (int) $widget->num_display;
	
	if($count < 1){
		$count = 5;
	}
	
	// Get all the needed users
	$user_options = array(
		"type" => "user",
		"limit" => false,
		"metadata_names" => array($metadata_field)
	);
	
	if($who == "friends"){
		$user_options["relationship"] = "friend";
		$user_options["relationship_guid"] = $widget->getOwner();
	} else {
		$user_options["relationship"] = "member_of_site";
		$user_options["relationship_guid"] = $widget->site_guid;
		$user_options["inverse_relationship"] = true;
	}
	
	// start birthday sorting
	$time_array = getdate();
	$current_year = $time_array["year"];
	$current_year_day = $time_array["yday"];
	
	$birth_array = array();
	
	if($users = elgg_get_entities_from_relationship($user_options)){
		foreach($users as $user){
			
			$time_value = $user->$metadata_field;
			
			if(!empty($time_value)){
				$user_time_array = false;
				
				$ts = (int) $time_value;
				if((strlen($ts) == strlen($time_value)) && (($user_time_array = getdate($ts)) !== false)){
					// unix timestamp
				} elseif(($new = strtotime($time_value)) !== false){
					// time in date format
					$user_time_array = getdate($new);
				} elseif($timeformat != "unix"){
					$user_time_array = strptime($time_value, $timeformat_other);
				}
				
				if(!empty($user_time_array)){
					if($timeformat == "unix"){
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
					
					$birth_array[$user->guid] = $user_time;
				}
			}
		}
	}
	asort($birth_array);
	
	$count = min($count, count($birth_array));
	
	$limited = array_slice($birth_array, 0, $count, true);
	
	echo "<div class='contentWrapper'>";
	if(!empty($limited) && is_array($limited)){
		foreach($limited as $guid => $timestamp){
			if($user = get_user($guid)){
				$time_array = getdate($timestamp);
				
				$month = str_pad($time_array["mon"], 2, "0", STR_PAD_LEFT);
				$time = sprintf(elgg_echo("date:month:" . $month), $time_array["mday"])
				
				?>
				<div class="birthday_widget_wrapper">
					<?php echo elgg_view("profile/icon", array("entity" => $user, "size" => "tiny")); ?>
					<div class="birtday_widget_info">
						<a href="<?php echo $user->getURL(); ?>" title="<?php echo $user->name; ?>"><?php echo $user->name; ?></a>
						<?php echo $time; ?>
					</div>
					<div class="clearfloat"></div>
				</div>
				<?php 
			}
		}
	} else {
		echo elgg_echo("birthday_widget:widget:no_results");
	}
	echo "</div>";
	
?>