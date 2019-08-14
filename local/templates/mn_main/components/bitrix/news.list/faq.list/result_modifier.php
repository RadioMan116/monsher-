<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(0 < count($arResult["ITEMS"]))
{
    foreach($arResult["ITEMS"] as $index => &$arItem)
    {
        // Ресайзим картинки превью
       //$arImg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 447, "height" => 341), BX_RESIZE_IMAGE_EXACT);
      // $arItem["PICTURE"] = $arImg["src"];
	   
	   
			if($arItem["PROPERTIES"]["VIDEO"]["VALUE"])
			{
				$arVideos = array();
				foreach($arItem["PROPERTIES"]["VIDEO"]["VALUE"] as $video_id) {
					
					$arVideo = CStatic::getElement($video_id, CStatic::$videoIdBlock);
					
					$arImg = CFile::ResizeImageGet($arVideo["PREVIEW_PICTURE"], array("width" => 147, "height" => 141), BX_RESIZE_IMAGE_EXACT);
					$arVideo["SMALL"] = $arImg["src"];				
					
					$arVideos[] = $arVideo;
				}
				$arItem["VIDEOS"] = $arVideos;				
			}	
	   
	   
			if($arItem["PROPERTIES"]["PHOTOS"]["VALUE"])
			{
				$arPhotos = array();
				foreach($arItem["PROPERTIES"]["PHOTOS"]["VALUE"] as $photo_id) {					
					
					$arImg = CFile::ResizeImageGet($photo_id, array("width" => 1200, "height" => 1200), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
					$arPhoto["BIG"] = $arImg["src"];
					
					$arImg = CFile::ResizeImageGet($photo_id, array("width" => 147, "height" => 141), BX_RESIZE_IMAGE_EXACT);
					$arPhoto["SMALL"] = $arImg["src"];				
				
					$arPhotos[] = $arPhoto;
				}
				$arItem["PHOTOS"] = $arPhotos;				
			}	
	   
	   
	   
	   
    }
}		  
		

?>