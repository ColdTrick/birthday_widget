<?php 

?>
<div>
	<?php echo elgg_echo("birthday_widget:settings:metadatafield"); ?><br />
	<input type="text" name="params[metadata_field]" value="<?php echo $vars["entity"]->metadata_field; ?>" maxlength="30" /><br />
	
	<?php echo elgg_echo("birthday_widget:settings:timeformat"); ?><br />
	<input type="radio" name="params[timeformat]" value="unix" <?php if(empty($vars["entity"]->timeformat) || $vars["entity"]->timeformat != "other") { echo "checked='yes'"; } ?> /><?php echo sprintf(elgg_echo("birthday_widget:settings:timeformat:unix"), time()); ?><br />
	<input type="radio" name="params[timeformat]" value="other" <?php if($vars["entity"]->timeformat == "other") { echo "checked='yes'"; } ?> /><?php echo elgg_echo("birthday_widget:settings:timeformat:other"); ?>
	<input type="text" name="params[timeformat_other]" value="<?php echo $vars["entity"]->timeformat_other; ?>" size="15" maxlength="30" />*<br />
	<br />
	<div class="birthday_widget_comment">
		*: <?php echo sprintf(elgg_echo("birthday_widget:settings:timeformat:description"), "http://nl2.php.net/manual/en/function.strftime.php"); ?>
	</div>
</div>