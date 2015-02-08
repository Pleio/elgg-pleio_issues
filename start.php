<?php
require_once (dirname ( __FILE__ ) . "/lib/GithubClient.php");

elgg_register_event_handler('init', 'system', 'pleio_issues_init');

function pleio_issues_init(){
    elgg_register_page_handler("issues", "pleio_issues_page_handler");

    elgg_extend_view("js/elgg", "js/pleio_issues/site");
    elgg_extend_view("css/elgg", "css/pleio_issues/site");
}

function pleio_issues_page_handler($page){
	switch($page[0]){
		case "list":
			include(dirname(__FILE__) . "/pages/list.php");
			break;
		default:
			return false;
	}
}