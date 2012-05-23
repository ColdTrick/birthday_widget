<?php 
	
	function birthday_widget_init(){
		// Register widget
		elgg_register_widget_type("birthday_widget", elgg_echo("birthday_widget:widget:title"), elgg_echo("birthday_widget:widget:description"), "dashboard,profile,index,groups");
	}
	
	// register default Elgg event
	elgg_register_event_handler("init", "system", "birthday_widget_init");
	