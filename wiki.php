<?php
eval('?>'.vrzno_await( vrzno_await((new Vrzno)->fetch(vrzno_env('staticOrigin') . '/CloudAutoloader.php'))->text()));

CloudAutoloader::register();

eval('?>'.vrzno_await( vrzno_await((new Vrzno)->fetch(vrzno_env('staticOrigin') . '/WikiMarkdown.php'))->text()));

$parser = new WikiMarkdown();

$get  = vrzno_env('_GET');
$post = vrzno_env('_POST');
$db   = vrzno_env('db');

$pageTitle = $get->page ?? 'index';

$select = $db
->prepare('SELECT PageTitle, PageContent FROM WikiPages WHERE PageTitle = ?')
->bind($pageTitle)
->first();

$page = vrzno_await($select);

if($pageTitle && $post->PageContent)
{
	if($page)
	{
		$update = $db
		->prepare('UPDATE WikiPages SET PageContent = ?2 WHERE PageTitle = ?1')
		->bind($pageTitle, $post->PageContent)
		->run();

		vrzno_await($update);
	}
	else
	{
		$insert = $db
		->prepare('INSERT INTO WikiPages (PageTitle, PageContent) VALUES (?1, ?2) ')
		->bind($pageTitle, $post->PageContent)
		->run();

		vrzno_await($insert);
	}

	$select = $db
	->prepare('SELECT PageTitle, PageContent FROM WikiPages WHERE PageTitle = ?')
	->bind($pageTitle)
	->first();

	$page = vrzno_await($select);
}

$page = $page ?? (object) [
	'PageTitle'   => '',
	'PageContent' => '',
];
?>

<script async type = "text/javascript" src = "https://cdn.jsdelivr.net/npm/php-wasm/php-tags.mjs"></script>
<script type = "text/php" data-stdout = "#output" data-stderr = "#error" src = "<?=vrzno_env('staticOrigin');?>/wiki-frontend.php"></script>
<link rel="stylesheet" href="<?=vrzno_env('staticOrigin');?>/wiki.css">

<navigation>
	[<a href = "/wiki.php">home</a>]
</navigation>

<section id = "page" data-current-view = "view" data-edited = "false">

	<div id = "page-edit">
		<button id = "view">view</button>
		<form method = "POST">
			<label>
				<input placeholder = "title" name = "PageTitle" value = "<?=$pageTitle;?>" readonly = "true" />
			</label>
			<label>
				<textarea name = "PageContent"><?=$page->PageContent;?></textarea>
			</label>
			<label>
				<input type = "submit">
			</label>
		</form>
	</div>

	<div id = "page-view">
		<button id = "edit">edit</button>
		<div id = "view-title"><?php echo $parser->parse('#' . $pageTitle);?>&nbsp;<span class = "edit-indicator">[edited - preview!]</span></div>
		<div id = "view-content"><?php echo $parser->parse($page->PageContent);?></div>
	</div>

</section>

<div id = "output"></div>
<div id = "error"></div>
