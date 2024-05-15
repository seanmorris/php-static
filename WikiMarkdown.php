<?php

class WikiMarkdown extends \cebe\markdown\GithubMarkdown
{
	protected function parseImage($markdown)
	{}

	protected function parseLinkOrImage($markdown)
	{
		$paren   = strpos($markdown, '(');
		$preurl  = substr($markdown, 1 + $paren, -1);

		if(strpos($preurl, ' ') > -1)
		{
			$markdown = substr($markdown, 0, 1 + $paren) . urlencode($preurl) . ')';
		}

		[$text, $url, $title, $offset, $key] = parent::parseLinkOrImage($markdown);

		var_dump($markdown, $text, $url, $title, $offset, $key);

		if($url && (substr($url, 0, 7) === 'http://' || substr($url, 0, 8) === 'https://'))
		{
			$text = $title = '';
			$url = '';
		}

		$url = sprintf('/wiki.php?page=%s', $url);

		return [$text, $url, $title, $offset, $key];
	}
}
