<?
IF(!$_COOKIE["K_SORT"]) $_COOKIE["K_SORT"] = 'PRICE';
IF(!$_COOKIE["K_ORDER"]) $_COOKIE["K_ORDER"] = 'ASC';
?>
<div class="catalog__sorting">
							<ul class="catalog__ul">
								Сортировать по:
								
								<?
								$dd_s = '';
								$dd_so = 'ASC';
								
								if($_COOKIE["K_SORT"] == 'HIT') {
									
									$dd_s = 'active';									
									if($_COOKIE["K_ORDER"] == 'ASC') {$dd_s.= ' sorting__link_up'; $dd_so = 'DESC';}
									else {$dd_s.= '';$dd_so = 'ASC';}					
								
								}?>
								<li class="catalog__li <?=$dd_s?>">
									<a href="" data-sort="HIT" data-order="<?=$dd_so?>" class="catalog__sort js-sort_change">
										<span>популярности</span>
										<i></i>
									</a>
								</li>
								<?
								$dd_s = '';
								$dd_so = 'ASC';
								if($_COOKIE["K_SORT"] == 'NEW') {
									
									$dd_s = 'active';									
									if($_COOKIE["K_ORDER"] == 'ASC') {$dd_s.= ' sorting__link_up'; $dd_so = 'DESC';}
									else {$dd_s.= '';$dd_so = 'ASC';}					
								
								}?>
								<li class="catalog__li <?=$dd_s?>">
									<a href="" data-sort="NEW" data-order="<?=$dd_so?>" class="catalog__sort js-sort_change">
										<span>новизне</span>
										<i></i>
									</a>
								</li>
								<?
								$dd_s = '';
								$dd_so = 'ASC';
								if($_COOKIE["K_SORT"] == 'PRICE') {
									
									$dd_s = 'active';									
									if($_COOKIE["K_ORDER"] == 'ASC') {$dd_s.= ' sorting__link_up'; $dd_so = 'DESC';}
									else {$dd_s.= '';$dd_so = 'ASC';}					
								
								}?>
								<li class="catalog__li <?=$dd_s?>">								
									<a href="" data-sort="PRICE" data-order="<?=$dd_so?>" class="catalog__sort js-sort_change">
										<span>цене</span>
										<i></i>
									</a>
								</li>
							</ul>
							<div class="sorting__info">
								<span class="sorting__number js-catalog_count-blk"></span>
							</div>
</div>