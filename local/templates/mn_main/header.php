<!DOCTYPE HTML>
<!--[if IE 8]>      <html class="no-js lt-ie9" lang="ru-RU"> <![endif]-->
<!--[if IE 9]>      <html class="no-js ie9" lang="ru-RU"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="page" lang="ru-RU"><!--<![endif]-->
<head>
    <title><?$APPLICATION->ShowTitle()?></title>
    <?
    $page_site = $APPLICATION->GetCurPage();
    if(!$menu_tip) $menu_tip = '';
    ?>
	<?
	$APPLICATION->ShowHead();
	CUtil::InitJSCore(array());
	CJSCore::Init(array("fx"));
	if (!$USER->IsAuthorized()) {
		CJSCore::Init(array('ajax', 'json', 'ls', 'session', 'jquery', 'popup', 'pull'));
	}
	?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />

	<meta name="HandheldFriendly" content="True">
	<meta name="format-detection" content="telephone=yes">
	<meta name="MobileOptimized" content="320">
	<? //<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">?>
<?
	$tpl_path_front = CStatic::$pathV;
	//$tpl_path_front = '/mockup/build/';


	$APPLICATION->SetAdditionalCSS($tpl_path_front.'css/style.css', true);
	$APPLICATION->SetAdditionalCSS($tpl_path_front.'js/jquery-ui-1.12.1/jquery-ui.css', true);
	$APPLICATION->SetAdditionalCSS('/tpl/css/style_k.css', true);
	$APPLICATION->SetAdditionalCSS('https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700&amp;subset=cyrillic', true);

	//$APPLICATION->AddHeadScript($tpl_path_front."js/jquery-3.2.1.min.js");
	$APPLICATION->AddHeadScript($tpl_path_front."js/mobile.js");
	$APPLICATION->AddHeadScript($tpl_path_front."js/jquery-3.3.1.min.js");
	$APPLICATION->AddHeadScript($tpl_path_front."js/jquery-ui-1.12.1/jquery-ui.min.js");
	
	$APPLICATION->AddHeadScript("/tpl/js/lazyload.min.js");
	
	$APPLICATION->AddHeadScript($tpl_path_front."js/script.min.js");
	$APPLICATION->AddHeadScript($tpl_path_front."js/frontend-works.js");
	$APPLICATION->AddHeadScript($tpl_path_front."js/jquery.ui.touch-punch.min.js");
	
	
	

		$APPLICATION->AddHeadScript("//yastatic.net/es5-shims/0.0.2/es5-shims.min.js");
		$APPLICATION->AddHeadScript("//yastatic.net/share2/share.js");
		
		
		$APPLICATION->AddHeadScript("/tpl/js/jquery.cookie.js");
		$APPLICATION->AddHeadScript("/tpl/js/inputmask.js");
	
	
	$APPLICATION->AddHeadScript("/tpl/js/core.js");
	$APPLICATION->AddHeadScript("/tpl/js/custom.js");

	$APPLICATION->AddHeadScript('/_ecommerce/ecommerce.js');

		$arrNoDefaultPage = array(
			"docs_frame",
			"glossary",
			"catalog",
			"reviews_store",
			"tov_detail",
			"cart",
			"video",
			"favorites",
			"compare",
			"news",
			"news_detail",
			"about",
			"contacts",
			"docs",
			"faq",
			"nofind",
			"reviews",
			"docs",
			"search",
			"main",
		);
	
	
	$arrNoNav = array(
		"main",
		"nofind",
	);	
	
	$arrNoColumns = array(
		"docs_frame",		
		"main",		
		"compare",		
		"reviews_store",		
		"cart",
		"news_detail",
		"tov_detail",
		"nofind",
	);

	$arrNoZag = array(		
		"main",
		"compare",
		"tov_detail",
		"nofind",
	);

	$arrTopZagData = array(		
		"docs_frame",		
		"cart",		
		"compare",
		"favorites",
		"news",
		"news_detail",
		"tov_detail",
	);

	$arrZagDefault = array(
		"news"
	);


	$arData = CStatic::getElement(CStatic::$DataIdByRegion[$_COOKIE["K_REGION"]], 31);
	
	
	
	$phone_1 = $arData["PROPERTIES"]["PHONE_1"]["VALUE"];
	$phone_1_f = preg_replace("([^0-9])", "", $phone_1);
	
	$phone_2 = $arData["PROPERTIES"]["PHONE_2"]["VALUE"];
	$phone_2_f = preg_replace("([^0-9])", "", $phone_2);	
	
	global $USER;
?>
<?$APPLICATION->IncludeFile('/local/include_areas/counters_head.php')?>
</head>
<body>
<?
//pre($_POST);
?>
<?$APPLICATION->IncludeFile('/local/include_areas/counters_header.php')?>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>

	<div class="wrapper" id="js-wrapper">

		<header class="header">

			<div class="header__blocks">
				<div class="header__block header__block_top">
					<div class="container">
						<button class="hamburger" id="js-hamburger" aria-expanded="false">
							<div class="hamburger__img"></div>
						</button>						
						
						<div class="menu" aria-expanded="false">
							<?//if($USER->IsAdmin() || CSite::InGroup(array(6))):?>
								<?$APPLICATION->IncludeComponent("inter.olsc:city.change", "", Array(), false);?>							
							<?//endif;?>
						
								<?$APPLICATION->IncludeComponent("bitrix:menu", "top.menu", array(
											"ROOT_MENU_TYPE" => "top",
											"MENU_CACHE_TYPE" => "N",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => "",
											"MAX_LEVEL" => "2",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
								);?>	
								<div class="header__telephones">
									<a href="" class="header__call-phone-button js-viewForm" data-action="callback">Заказать звонок</a>									
									<div class="telephones">
										<a href="tel:<?=$phone_1_f?>" class="header__phone"><?=$phone_1?></a>
										<a href="tel:<?=$phone_2_f?>" class="header__phone"><?=$phone_2?></a>
									</div>
								</div>
						</div>						
						<!--end menu-->
						<div class="header__icons">
							<div class="header__search js-header__search"></div>			
							<a class="header__mobile-tel" href="tel:<?=$phone_1_f?>"></a>
							<?$APPLICATION->IncludeComponent("inter.olsc:order.count", "", Array(), false);?>							
						</div>
					</div>
				</div>
				<div class="header__block header__block_bottom" id="js-header__block_bottom" >					
					<div class="container">
					
						<div class="logo">
							<a href="/" title="Liebherr" class="logo__link">
								<img src="<?=$tpl_path_front?>images/logo-new.svg" alt="Liebherr" class="logo__pic" />
							</a>
						</div>					
						<div class="header__dropdown-menu" id="js-menu">
						
								<?$APPLICATION->IncludeComponent("inter.olsc:catalogblock.list", "catalog.menu.top" , Array("PLACE" => "TOP"), false);?>								
								
								<div class="header__telephones">
									<a href="" class="header__call-phone-button js-viewForm" data-action="callback">Заказать звонок</a>
									<div class="telephones">
										<a href="tel:<?=$phone_1_f?>" class="header__phone"><?=$phone_1?></a>
										<a href="tel:<?=$phone_2_f?>" class="header__phone"><?=$phone_2?></a>
									</div>
								</div>
						</div>
						
						<div class="menu" aria-expanded="false">
						
							<?$APPLICATION->IncludeComponent("bitrix:menu", "top.menu", array(
											"ROOT_MENU_TYPE" => "top",
											"MENU_CACHE_TYPE" => "N",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => "",
											"MAX_LEVEL" => "2",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
							);?>
							
							<div class="header__telephones">
									<a href="" class="header__call-phone-button js-viewForm" data-action="callback">Заказать звонок</a>
									<div class="telephones">
										<a href="tel:<?=$phone_1_f?>" class="header__phone"><?=$phone_1?></a>
										<a href="tel:<?=$phone_2_f?>" class="header__phone"><?=$phone_2?></a>
									</div>
							</div>
						</div>
						
					</div>					
				</div>
			</div>
			
			
			<div class="search__popup">
				<form action="/search/" class="header__form">
					<input type="text" name="q" maxlength="50" class="header__input js-search-header__input">
					<div class="header__button js-header__button"></div>
				</form>
			</div>
			<div class="header__overlay"></div>
			
		</header>


		<div class="section">
		
		
		
		<?if($menu_tip!='catalog'):?>
		
			<div class="content">
									
				<?if($menu_tip!='main'):?>			
					<div class="container">
				<?endif;?>			
				
				<?if(in_array($menu_tip, $arrTopZagData)):?>
					<?if(!in_array($menu_tip, $arrNoNav)):?>
						<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", array(
												"START_FROM" => "0",
												"PATH" => "",
												"SITE_ID" => "s3"
											),
											false
						);?>	
					<?endif;?>
				
					<?if(!in_array($menu_tip, $arrNoZag)):?>
						<h1 class="title"><?$APPLICATION->ShowTitle(false);?></h1>				
					<?endif;?>
				<?endif;?>
				
				
				<?if(!in_array($menu_tip, $arrNoColumns)):?>
				
					<div class="d-flex flex-column-reverse flex-md-row">
						<div class="col-md-3">	

							<?if($menu_tip == 'news'):?>
							<?$APPLICATION->IncludeComponent(
								"inter.olsc:art.filter.left",
								"",
								Array()
							);?>	
							<?endif;?>
							<?if($menu_tip == 'favorites'):?>
							<?$APPLICATION->IncludeComponent(
								"inter.olsc:favorites.filter.left",
								"",
								Array(
									"FAVORITE_LIST" => $_COOKIE["FAVORITE_LIST"]
								)
							);?>	
							<?endif;?>
							
							<div class="sidebar">	
							
								
								<?/*if($menu_tip != 'favorites'):?>
									<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_1.php',
														array(
															"ACTIVE" => array(
																"BANNER" => true
															),
															"ACC_FILTER" => array("PROPERTY_BLOCK_ID" => reset(CStatic::$catalogIdBlock)),
															"ACC_TITLE" => 'холодильников Liebherr'
														),
														array("mode"=>"php")
									);?>				
									
									<?$APPLICATION->IncludeComponent("inter.olsc:tech.docs", "catalog.left", 
										array(
											"BLOCK_TITLE" => strtolower($arName["PROPERTIES"]["KOGO_MORE"]["VALUE"]),
											"IBLOCK_ID" => $arBlock["ID"],
											"SECTION_ID" => $arSection["ID"],
											"LIMIT" => 3
										), 
									false);?>
								<?endif;*/?>	
												
								<?$APPLICATION->IncludeFile('/local/include_areas/block-sidebar_2.php',
													array(
														"ACTIVE" => array(
															"MENU" => true
														)
													),
													array("mode"=>"php")
								);?>
							</div>
							
						</div>
						<div class="col-md-9">
						
						<?if(!in_array($menu_tip, $arrTopZagData)):?>
							<?if(!in_array($menu_tip, $arrNoNav)):?>
								<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", array(
														"START_FROM" => "0",
														"PATH" => "",
														"SITE_ID" => "s3"
													),
													false
								);?>	
							<?endif;?>
						
							<?if(!in_array($menu_tip, $arrNoZag)):?>
								<h1 class="title"><?$APPLICATION->ShowTitle(false);?></h1>				
							<?endif;?>
						<?endif;?>
				
				<?endif;?>
				
			<?endif;?>
				
				
				
				<?if(!in_array($menu_tip, $arrNoDefaultPage)):?>	
					<div class="text-default">
				<?endif;?>	
				
			
