<?php
eval('?>'.vrzno_await( vrzno_await((new Vrzno)->fetch('https://seanmorris.github.io/php-static/CloudAutoloader.php'))->text()));

CloudAutoloader::register('/zip-proxy?zip=');

eval('?>'.vrzno_await( vrzno_await((new Vrzno)->fetch('https://seanmorris.github.io/php-static/WikiMarkdown.php'))->text()));

$parser = new WikiMarkdown();

$vrzno = new Vrzno;

$viewButton  = $vrzno->document->querySelector('#view');
$editButton  = $vrzno->document->querySelector('#edit');
$pageSection = $vrzno->document->querySelector('#page');
$content = $vrzno->document->querySelector('#view-content');
$editContent = $vrzno->document->querySelector('[name="PageContent"]');

$edited = '';

$editButton->addEventListener('click', function() use($vrzno, $pageSection, $editContent, &$edited) {
	$edited = $editContent->value;
	$pageSection->setAttribute('data-current-view', 'edit');
});

$viewButton->addEventListener('click', function() use($vrzno, $parser, $pageSection, $content, &$edited) {
	$content->innerHTML = $parser->parse($edited);
	$pageSection->setAttribute('data-current-view', 'view');
});

$editContent->addEventListener('input', function($event) use($vrzno, $pageSection, &$edited) {
	$pageSection->setAttribute('data-edited', 'true');
	$edited = $event->target->value;
});
