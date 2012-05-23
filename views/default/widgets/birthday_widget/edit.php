<?php 
	
?>
<div>
	<?php echo elgg_echo("birthday_widget:widget:settings:who_to_show"); ?><br />
	<select name="params[who_to_show]">
		<option value="friends" <?php if(empty($vars["entity"]->who_to_show) || $vars["entity"]->who_to_show != "all" ) { echo "selected='yes'"; }?>><?php echo elgg_echo("friends"); ?></option>
		<option value="all" <?php if($vars["entity"]->who_to_show == "all") { echo "selected='yes'"; } ?>><?php echo elgg_echo("all"); ?></option>
	</select><br />
	
	<?php echo elgg_echo("birthday_widget:widget:settings:count"); ?><br />
	<select name="params[num_display]">
		<option value="1" <?php if($vars['entity']->num_display == 1) echo "selected='yes'"; ?>>1</option>
		<option value="2" <?php if($vars['entity']->num_display == 2) echo "selected='yes'"; ?>>2</option>
		<option value="3" <?php if($vars['entity']->num_display == 3) echo "selected='yes'"; ?>>3</option>
		<option value="4" <?php if($vars['entity']->num_display == 4) echo "selected='yes'"; ?>>4</option>
		<option value="5" <?php if($vars['entity']->num_display == 5) echo "selected='yes'"; ?>>5</option>
		<option value="10" <?php if($vars['entity']->num_display == 10) echo "selected='yes'"; ?>>10</option>
		<option value="15" <?php if($vars['entity']->num_display == 15) echo "selected='yes'"; ?>>15</option>
		<option value="20" <?php if($vars['entity']->num_display == 20) echo "selected='yes'"; ?>>20</option>
	</select>
</div>