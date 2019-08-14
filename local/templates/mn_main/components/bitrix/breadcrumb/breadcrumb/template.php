<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//delayed function must return a string

if(empty($arResult))
	return "";
$strReturn  = '<div class="breadcrumbs"><div itemscope itemtype="http://schema.org/BreadcrumbList">';
$strReturn .= '<div class="breadcrumbs__link" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="/" title="Главная" itemprop="item"><span itemprop="name">Liebherr</span></a><meta itemprop="position" content="1" /></div>';
global $APPLICATION;

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{	
	$strReturn .= '<div class="breadcrumbs__sep">»</div>';
	
	
	
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if($arResult[$index]["LINK"] <> "" && $index < (count($arResult)-1))
		$strReturn .= '<div class="breadcrumbs__link" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="'.$arResult[$index]["LINK"].'"  title="'.$title.'" itemprop="item"><span itemprop="name">'.$title.'</span></a><meta itemprop="position" content="'.($index+2).'" /></div>';
	else
		$strReturn .= '<div class="breadcrumbs__link" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'.$title.'</span><a itemprop="item" href="'.$APPLICATION->GetCurPage().'"  title="'.$title.'" ></a><meta itemprop="position" content="'.($index+2).'" /></div>';
	
}

$strReturn .= '</div></div>';
return $strReturn;
?>
