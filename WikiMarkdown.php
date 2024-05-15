<?php

class WikiMarkdown extends \cebe\markdown\GithubMarkdown
{
	protected function parseImage($markdown)
	{}

	protected function parseLinkOrImage($markdown)
	{
		$paren  = strpos($markdown, '(');
		$close  = strpos($markdown, ')');
		$start  = 1 + $paren;
		$end    = $close - $start;
		$preurl = substr($markdown, $start, $end);
		$addOff = 0;

		if(strpos($preurl, ' ') > -1)
		{
			$origLen  = strlen($preurl);
			$encoded  = urlencode($preurl);
			$markdown = substr($markdown, 0, 1 + $paren) . $encoded . ')';
			$addOff = $origLen - strlen($preurl);
		}

		[$text, $url, $title, $offset, $key] = parent::parseLinkOrImage($markdown);

		var_dump([$addOff, $text, $url, $title, $offset, $key]);

		if($url && (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://'))
		{
			$text = $title = '';
			$url = '';
		}

		$url = sprintf('/wiki.php?page=%s', $url);

		return [$text, $url, $title, $offset + $addOff, $key];
	}
}
