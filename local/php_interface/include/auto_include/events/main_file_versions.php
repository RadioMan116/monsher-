<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

AddEventHandler("main", "OnEndBufferContent", array("CMNTFileVersionsEvents", "OnEndBufferContentHandler"));

class CMNTFileVersionsEvents
{
	function OnEndBufferContentHandler($content)
	{
		$reg = '/(?:href|src)="(?!http)(?!\/\/)([^"]+.(?:js|css))"/sim';
		preg_match_all($reg, $content, $arMatches);
		
		$arMatches[1] = array_unique($arMatches[1]);
		
		foreach($arMatches[1] as $match)
		{
			$fullpath = $_SERVER["DOCUMENT_ROOT"].$match;
			
			if(file_exists($fullpath) && !strpos($match, "?"))
				$content = str_replace($match, $match."?".filemtime($fullpath), $content);
		}
	}
}
?>