<?php

function curl_get_contents($url)
{
	$c = curl_init();
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_URL, $url);
	$contents = curl_exec($c);
	curl_close($c);
	
	return ($contents) ? $contents : false;
}

function google_docs_markdown_parse($url)
{
	include_once('markdown.php');
	include_once('smartypants.php');
	
	$doc = curl_get_contents($url);
	
	$start_of_div = strpos($doc, '<div id="doc-contents">');
	$end_of_div = strpos($doc, '<div id="google-view-footer">');
	
	if ( $start_of_div === false ) return 'Sorry, cannot retrieve content at this time.';
	
	// get just the content div
	$content = substr($doc, $start_of_div, ($end_of_div - $start_of_div));
	
	// strip new lines, replace div's with new lines
	// strip remaining tags, trim the ends
	$content = trim(strip_tags(str_replace(array("\n", '<div>'), array('', "\n"), $content)));
	
	// format with Markdown and return
	return SmartyPants(Markdown($content));
}

?>