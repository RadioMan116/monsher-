<div class="count-show <?=$arParams["D_CLASS"]?>">
										<div class="count-show__text">Товаров на странице</div>
										<div class="count-show__select">
											<select name="PAGE_KOL" class="js-page_kol">
												<?if($_COOKIE["PAGE_KOL"] == '9') $dd_s = ' selected="selected" '; else $dd_s = '';?>
												<option value="9" <?=$dd_s?>>9</option>
												<?if($_COOKIE["PAGE_KOL"] == '18') $dd_s = ' selected="selected" '; else $dd_s = '';?>
												<option value="18" <?=$dd_s?>>18</option>
												<?if($_COOKIE["PAGE_KOL"] == '27') $dd_s = ' selected="selected" '; else $dd_s = '';?>
												<option value="27" <?=$dd_s?>>27</option>
											</select>	
										</div>
</div>