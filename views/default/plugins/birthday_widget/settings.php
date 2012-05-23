<?php 

	$plugin = elgg_extract("entity", $vars);
	
	$time_format_options = array(
		elgg_echo("birthday_widget:settings:timeformat:unix", array(time())) => "unix",
		elgg_echo("birthday_widget:settings:timeformat:other") => "other"
	);
	
	$timeformat = $plugin->timeformat;
	if(!in_array($timeformat, $time_format_options)){
		$timeformat = "unix";
	}

	echo "<div>";
	echo "<label>" . elgg_echo("birthday_widget:settings:metadatafield") . "</label>";
	echo elgg_view("input/text", array("name" => "params[metadata_field]", "value" => $plugin->metadata_field));
	echo "</div>";
	
	echo "<div>";
	echo "<label>" . elgg_echo("birthday_widget:settings:timeformat") . "</label>";
	echo elgg_view("input/radio", array("name" => "params[timeformat]", "value" => $timeformat, "options" => $time_format_options));
	echo elgg_view("input/text", array("name" => "params[timeformat_other]", "value" => $plugin->timeformat_other));
	echo "<div class='elgg-subtext'>";
	echo elgg_echo("birthday_widget:settings:timeformat:description", array("http://php.net/manual/en/function.strftime.php"));
	echo "</div>";
	echo "</div>";
