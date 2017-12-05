<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<? $arOffer = $arResult["OFFER"];

// флаг Новинка
if ($arOffer['CATALOG_CAN_BUY_1'] == 'Y') {
	$this->SetViewTarget("new_flag"); ?>
		<div class="new">НОВИНКА</div>
	<? $this->EndViewTarget();
}

// подключаем классы и скрипты для окна кредита
$this->addExternalCss($templateFolder."/inc/remodal/remodal.css");
$this->addExternalCss($templateFolder."/inc/remodal/remodal-default-theme.css");
$this->addExternalCss($templateFolder."/inc/nice-select.css");
$this->addExternalCss($templateFolder."/inc/pop-up.css");

//print "<pre>";print_r($arOffer);print "</pre>";

$frame = $this->createFrame()->begin(); ?>
	<form action="<?=$APPLICATION->GetCurPage();?>" class="SKU">
		<input type="hidden" name="element_id" value="<?=$arParams['ELEMENT_ID']?>">
		<input type="hidden" name="offer_id" value="<?=$arOffer['ID']?>">
		<div class="column small-24 medium-24 large-24">
			<div class="productCharacteristics">
				<div class="productInsert productSize">
					<pre></pre>
					<div class="productOption">
						<? if (!empty($arResult['SIZES'])): ?>
							<div class="row">
								<div class="column <?=count($arResult['SIZES']) > 1 ? 'small-9' : 'small-10'?>">Размер:</div>
								<div class="column <?=count($arResult['SIZES']) > 1 ? 'small-15' : 'small-14'?>">
									<? if (count($arResult['SIZES']) > 1):?>
										<select name="size" style="display:none">
											<? foreach ($arResult['SIZES'] as $arSize): ?>
												<option value="<?=$arSize['ID']?>" <?=$arSize['SELECTED'] ? 'selected' : ''?>><?=$arSize['VALUE']?></option>
											<?endforeach;?>
										</select>
									<? else: ?>
										<?=$arResult['SIZES'][0]['VALUE']?>
									<? endif; ?>
								</div>
							</div>
						<? endif; ?>

						<? $move_props = '';
						foreach ($arParams['TOP_PROPERTIES_ORDER'] as $arPropertyCode):
							$name = $arResult['PROPERTIES'][$arPropertyCode]['NAME'];
							$value = $arResult['PROPERTIES'][$arPropertyCode]['PROPERTY_TYPE'] == 'L' ? $arResult['PROPERTIES'][$arPropertyCode]['VALUE_ENUM'] : $arResult['PROPERTIES'][$arPropertyCode]['VALUE'];
							if ($value): ?>
								<? if (in_array($arPropertyCode, $arParams['MOVE_PROPERTIES_TO_BOTTOM'])): ?>
									<? $move_props .= "<p><span>$name:</span> $value</p>"; ?>
								<? else: ?>
									<div class="row" id="<?=$arPropertyCode?>">
										<div class="column small-10"><?=$name?>:</div>
										<div class="column small-14">
											<? if (in_array($arPropertyCode, array('TSVET_METALLA'))): ?>
												<img alt="" src="<?=$arResult['METAL_COLORS'][strtolower($value)] ? $arResult['METAL_COLORS'][strtolower($value)]['ICON']['SRC'] : $arResult['METAL_COLORS']['none']['ICON']['SRC']?>">
											<? elseif (in_array($arPropertyCode, array('TSVET_OSNOVNOY_VSTAVKI', 'TSVET_VSTAVKI1', 'TSVET_VSTAVKI2'))):?>
												<img alt="" src="<?=$arResult['INSERT_COLORS'][strtolower($value)] ? $arResult['INSERT_COLORS'][strtolower($value)]['ICON']['SRC'] : $arResult['INSERT_COLORS']['none']['ICON']['SRC']?>">
											<? endif; ?>
											<?=$value?> <?=$arPropertyCode == 'METALL' ? $arResult['PROPERTIES']['PROBA']['VALUE_ENUM'] : ''?>
										</div>
									</div>
								<? endif; ?>
							<? endif; ?>
						<? endforeach;
						if (!empty($move_props)) {
							echo "<div id=\"move-props-from\">$move_props</div>";
						} ?>
						<? if ($arOffer['RESULT_PRICE']['BASE_PRICE'] > 0): ?>
							<div class="row">
								<div class="column small-10">стоимость:</div>
								<div class="column small-12">
									<? if ($arOffer['CATALOG_CAN_BUY_1'] == 'Y'): // товар есть в наличии ?>
										<? if ($arOffer['RESULT_PRICE']['DISCOUNT'] > 0): ?>
											<span class="old-price"><span><?=SaleFormatCurrency($arOffer['RESULT_PRICE']['BASE_PRICE'], $arOffer['RESULT_PRICE']['CURRENCY'], true)?></span></span>
										<? endif; ?>
										<span class="price"><?=SaleFormatCurrency($arOffer['RESULT_PRICE']['DISCOUNT_PRICE'], $arOffer['RESULT_PRICE']['CURRENCY'])?></span>
										<? if ($arOffer['RESULT_PRICE']['DISCOUNT'] > 0): ?>
											<span class="price-economy">экономия <?=SaleFormatCurrency(round($arOffer['RESULT_PRICE']['DISCOUNT'] - 0.1, 0), $arOffer['RESULT_PRICE']['CURRENCY'])?></span>
										<? endif; ?>
									<? else: // товара нет в наличии ?>
										<span class="price price-noavailable"><?=SaleFormatCurrency($arOffer['RESULT_PRICE']['BASE_PRICE'], $arOffer['RESULT_PRICE']['CURRENCY'])?></span>
										<span class="text-price-noavailable">нет в наличии</span>
									<? endif; ?>
									<? if ($arOffer["CATALOG_CAN_BUY_1"] == 'Y' && !empty($arOffer['PROPERTIES']['UNDER_PRICE_TEXT']['VALUE'])): ?>
										<span style="text-transform: none; color: #<?=$arOffer['PROPERTIES']['UNDER_PRICE_TEXT_COLOR']['VALUE']?>"><?=$arOffer['PROPERTIES']['UNDER_PRICE_TEXT']['VALUE']?></span>
									<? endif; ?>
								</div>
							</div>
						<? endif; ?>
					</div>
				</div>
			</div>
		</div>
	</form>
<? $frame->end(); ?>

<? if (!empty($arOffer)): ?>
	<div class="column small-24">
		<form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
			<? $frame = $this->createFrame()->begin(); ?>
				<div class="buy">
					<? if($arOffer['CATALOG_CAN_BUY_1'] == 'Y'): ?>
						<input type="hidden" name="<?=$arParams["ACTION_VARIABLE"]?>" value="YAM_BUY">
						<input type="hidden" name="<?=$arParams["PRODUCT_ID_VARIABLE"]?>" value="<?=$arOffer["ID"]?>">
						<input type="hidden" name="path_redirect_to" value="<?=$arParams["BASKET_URL"]?>">
						<input type="hidden" name="quantity" value="1">
						<input type="hidden" name="<?=$arParams["ACTION_VARIABLE"]."SKU_BUY"?>" value="Y">
						<input type="button" class="yam-buy-button" value="Купить" name="<?=$arParams["ACTION_VARIABLE"]."SKU_BUY"?>">
					<? else: ?>
						<? $APPLICATION->IncludeComponent(
							'alexbabintsev:simple.form',
							'order',
							array(
								'BUTTON_TEXT' => 'Заказать',
								'ELEMENT_ID' => $arParams['ELEMENT_ID'],
								'OFFER_ID' => $arOffer['ID'],
							),
							false
						); ?>
					<? endif; ?>
				</div>
					<?
					//print "<pre>arParams=";print_r($arParams);print "</pre>";
					//print "<pre>arResult=";print_r($arResult);print "</pre>";
					function show_loan($SECTION_PATH)
					{
						CModule::IncludeModule("iblock");
						$show_loan = 0;
						$rsSection = CIBlockSection::GetList(array(), array('IBLOCK_ID'=>34, 'ID'=>$SECTION_PATH), false, array("UF_LOAN"));
						while ($arSection = $rsSection->GetNext())
						{
							//echo '<pre>';
							//var_dump($arSection);
							//echo '</pre>';
							if($arSection["UF_LOAN"]==1)
							{
								//$show_loan=1;
							}
							else
							{
								//$show_loan=0;
								return false;
							}
						}
						return true;
					}
					if(show_loan($arParams["SECTION_PATH"]))
					{
					?>
					<input type="button" class="yam-buy-button" data-remodal-target="modal" value="Купить в кредит" name="actionSKU_BUY_LOAN" placeholder="" style="    width: 238px;    font-size: 17px;    font-family: 'PFDinTextCondPro', sans-serif;    text-transform: uppercase;    line-height: 35px;    height: 35px;    background-color: #227144;    border: none;    color: #fff;    transition: all 300ms ease;    align-items: flex-start;    text-align: center;    margin-left: 15px;    margin-bottom: 15px;">
					<?
					}
					?>


				<? if ($arOffer["CATALOG_CAN_BUY_1"] == 'Y'): ?>
					<? $APPLICATION->IncludeComponent(
						'alexbabintsev:simple.form',
						'fast_order',
						array(),
						false
					); ?>
				<? endif; ?>
			<? $frame->end(); ?>

			<? $frame = $this->createFrame()->begin(); ?>
				<? if ($USER->IsAuthorized()): ?>
					<? $liked = in_array($arParams["ELEMENT_ID"], CYamastersLike::GetArLikes()); ?>
					<a href="?like=<?=$liked ? 'dell' : 'yes'?>&id=<?=$arParams["ELEMENT_ID"]?>&offer_id=<?=$arOffer["ID"]?>" onclick="" class="likemy <?=$liked ? 'active' : ''?>" id="like-button"><span>Добавить в избранное</span></a>
				<? else: ?>
					<a href="#" class="likemy" id="like-button-auth"><span>Добавить в избранное</span></a>
				<? endif; ?>
			<? $frame->beginStub(); ?>
				<a href="#" class="likemy" id="like-button-auth"><span>Добавить в избранное</span></a>
			<? $frame->end(); ?>
		</form>
	</div>
<? else: ?>
	<div class="column small-24">
		<div class="buy">
			Такого товара не найдено
		</div>
	</div>
<? endif; ?>

<a href="<?=$arParams["BASKET_URL"]?>" style="display:none" id="basket_redirect"></a>


<script type="text/javascript">
	$(document).ready(function () {
		<? if ($arOffer["CATALOG_CAN_BUY_1"] == 'Y'): // товар есть в наличии ?>
			if ($(".product-slider .product-thumb .sale").length > 0) {
				$(".product-slider .product-thumb .sale").show();
			}
			if ($(".product-slider .product-thumb .hit").length > 0) {
				$(".product-slider .product-thumb .hit").show();
			}
			if ($(".product-slider .product-thumb .new").length > 0) {
				$(".product-slider .product-thumb .new").show();
			}
		<? else: ?>
			if ($(".product-slider .product-thumb .sale").length > 0) {
				$(".product-slider .product-thumb .sale").hide();
			}
			if ($(".product-slider .product-thumb .hit").length > 0) {
				$(".product-slider .product-thumb .hit").hide();
			}
			if ($(".product-slider .product-thumb .new").length > 0) {
				$(".product-slider .product-thumb .new").hide();
			}
		<? endif; ?>

		$("#move-props-to").html($("#move-props-from").html());
	});
</script>

<div class="popup">
	<div class="window" data-window="add-product">
		<div class="overlay"></div>
		<div class="container">
			<span class="close">x</span>
			<div class="h">Товар добавлен в корзину</div>
			<div class="item-c"></div>
			<a href="<?=$arParams['BASKET_URL']?>" class="btn make-order">Перейти в корзину</a><a href="/yuvelirnye-ukrasheniya/" class="link">Продолжить выбор товаров</a>
		</div>
	</div>
</div>


<div class="remodal remodal-credit" data-remodal-id="modal">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="credit_header">
			<div class="wr">
				<div class="roww name">Выберите условия для кредитования</div>
				<div class="roww under_name">Кредит оформляется на безналичную стоимость товара - <span id="mainPrice"><?=$arOffer['RESULT_PRICE']['DISCOUNT_PRICE']?></span> грн.</div>
			</div>
		</div>
		<div class="credit_content">
			<div class="wr">
				<form action="/personal/order/new/add.php" class="item_cr">
					<input type="hidden" name="product_id" value="<?=$arOffer['ID']?>">
					<input type="hidden" name="pay_sys" value="9">
					<input type="hidden" name="action" value="addToBasket">
					<div class="colll colll1">
						<div class="call_name">Оплата частями</div>
						<div class="call_under_name"></div>
					</div>
					<div class="colll colll2">
						<div class="select_wrapper ">
							<select name="period" id="select_option" class="select_option nice_select">
								<option selected value="3">3 мес.</option>
								<option value="6">6 мес.</option>
								<option value="9">9 мес.</option>
								<option value="12">12 мес.</option>
								<option  value="15">15 мес.</option>
								<option value="18">18 мес.</option>
								<option value="21">21 мес.</option>
								<option value="24">24 мес.</option>
							</select>
						</div>
						<input id="range1" class="select_range" type="range" min="3" // default 0 max="24" // default 100 step="3" // default 1 value="3" // default min + (max-min)/2 data-orientation="vertical" // default horizontal>
						<div class="range_marker start_range">3 мес.</div>
						<div class="range_marker end_range">24 мес.</div>
					</div>
					<div class="colll colll3 price">
						<p><span id="partPrice">1 238</span> грн/мес</p>
					</div>
					<div class="colll colll4">
						<!--<button type="submit" data-remodal-action="confirm" class="cr_btn">купить</button>-->
						<input type="submit" class="cr_btn" value="купить">
					</div>
				</form>

				<form action="" class="item_cr">
					<input type="hidden" name="product_id" value="<?=$arOffer['ID']?>">
					<input type="hidden" name="pay_sys" value="10">
					<input type="hidden" name="action" value="addToBasket">
					<div class="colll colll1">
						<div class="call_name">МГНОВЕННАЯ РАССРОЧКА</div>
						<div class="call_under_name">комиссия банка - <span id="bankComission">2,9</span>% </div>
					</div>
					<div class="colll colll2">
						<div class="select_wrapper">
							<select name="period" id="select_option1" class="select_option nice_select">
								<option selected="selected" value="3">3 мес.</option>
								<option value="6">6 мес.</option>
								<option value="9">9 мес.</option>
								<option  value="12">12 мес.</option>
								<option  value="15">15 мес.</option>
								<option value="18">18 мес.</option>
								<option value="21">21 мес.</option>
								<option value="24">24 мес.</option>
							</select>
						</div>
						<input id="range2" class="select_range" type="range" min="3" // default 0 max="24" // default 100 step="3" // default 1 value="3" // default min + (max-min)/2 data-orientation="vertical" // default horizontal>
						<div class="range_marker start_range">3 мес.</div>
						<div class="range_marker end_range">24 мес.</div>
					</div>
					<div class="colll colll3 price">
						<p><span id="partPrice1">903</span> грн/мес</p>
					</div>
					<div class="colll colll4">
						<!--<button type="submit" data-remodal-action="confirm" class="cr_btn">купить</button>-->
						<input type="submit" class="cr_btn" value="купить">
					</div>
				</form>
			</div>
		</div>

		<div class="credit_footer">
			<div class="wr">
				<p>Для оформления кредита необходимо нажать кнопку “Купить”, перейти к оформлению заказа, либо продолжить покупки и добавить дополнительный товар в “Корзину”, заполнить анкету и отправить на рассмотрение. После этого с Вами свяжется сотрудник для уточнения данных.</p>
			</div>
		</div>
	</div>



<?/*
	<div class="remodal remodal-credit" data-remodal-id="modal">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div class="credit_header">
			<div class="wr">
				<div class="roww name">Выберите условия для кредитования</div>
				<input type="hidden" name="result_price_discount_price" id="result_price_discount_price" value="<?=$arOffer['RESULT_PRICE']['DISCOUNT_PRICE']?>">
				<div class="roww under_name">Кредит оформляется на безналичную стоимость товара - <span><?=$arOffer['RESULT_PRICE']['DISCOUNT_PRICE']?></span> грн.</div>
			</div>
		</div>
		<div class="credit_content">
			<div class="wr">
				<form action="/personal/order/new/add.php" class="item_cr">
					<input type="hidden" name="product_id" value="<?=$arOffer['ID']?>">
					<input type="hidden" name="pay_sys" value="9">
					<input type="hidden" name="action" value="addToBasket">
					<div class="colll colll1">
						<div class="call_name">Оплата частями</div>
						<div class="call_under_name"></div>
					</div>
					<div class="colll colll2">
						<div class="select_wrapper">
							<select name="period" id="select_option1" class="select_option nice_select">
								<option selected value="3">3 мес.</option>
								<option value="6">6 мес.</option>
								<option value="9">9 мес.</option>
								<option value="12">12 мес.</option>
								<option  value="15">15 мес.</option>
								<option value="18">18 мес.</option>
								<option value="21">21 мес.</option>
								<option value="24">24 мес.</option>
							</select>
						</div>
						<input class="select_range1" type="range" min="3" // default 0 max="24" // default 100 step="3" // default 1 value="3" // default min + (max-min)/2 data-orientation="vertical" // default horizontal>
						<div class="range_marker start_range">3 мес.</div>
						<div class="range_marker end_range">24 мес.</div>
					</div>
					<div class="colll colll3 price">
						<p><span id="part_result_price"><?=round($arOffer['RESULT_PRICE']['DISCOUNT_PRICE']/3)?></span> грн/мес</p>
					</div>
					<div class="colll colll4">
						<!--<button type="submit" data-remodal-action="confirm" class="cr_btn">купить</button>-->
						<input type="submit" class="cr_btn" value="купить">
					</div>
				</form>

				<form action="/personal/order/new/add.php" class="item_cr">
					<input type="hidden" name="product_id" value="<?=$arOffer['ID']?>">
					<input type="hidden" name="pay_sys" value="10">
					<div class="colll colll1">
						<div class="call_name">МГНОВЕННАЯ РАССРОЧКА</div>
						<div class="call_under_name">комиссия банка - 2,9% </div>
					</div>
					<div class="colll colll2">
						<div class="select_wrapper">
							<select name="period" id="select_option2" class="select_option nice_select">
								<option selected="selected" value="3">3 мес.</option>
								<option value="6">6 мес.</option>
								<option value="9">9 мес.</option>
								<option  value="12">12 мес.</option>
								<option  value="15">15 мес.</option>
								<option value="18">18 мес.</option>
								<option value="21">21 мес.</option>
								<option value="24">24 мес.</option>
							</select>
						</div>
						<input class="select_range2" type="range" min="3" // default 0 max="24" // default 100 step="3" // default 1 value="3" // default min + (max-min)/2 data-orientation="vertical" // default horizontal>
						<div class="range_marker start_range">3 мес.</div>
						<div class="range_marker end_range">24 мес.</div>
					</div>
					<div class="colll colll3 price">
						<p><span id="loan_result_price"><?=round(($arOffer['RESULT_PRICE']['DISCOUNT_PRICE']/3)*1.029)?></span> грн/мес</p>
					</div>
					<div class="colll colll4">
						<input type="submit" class="cr_btn" value="купить">
					</div>
				</form>
			</div>
		</div>

		<div class="credit_footer">
			<div class="wr">
				<p>Для оформления кредита необходимо нажать кнопку “Купить”, перейти к оформлению заказа, либо продолжить покупки и добавить дополнительный товар в “Корзину”, заполнить анкету и отправить на рассмотрение. После этого с Вами свяжется сотрудник для уточнения данных.</p>
			</div>
		</div>
	</div>

	<script>
	$( "#select_option1" ).change(function() {
  		console.log("part changed jq");
    	var period = document.getElementById("select_option1").value;
    	var result_price_discount_price = document.getElementById("result_price_discount_price").value;
    	document.getElementById("part_result_price").innerHTML = Math.round(result_price_discount_price/period);
	});

	$( "#select_option2" ).change(function() {
  		console.log("part changed 2 jq");
    	var period = document.getElementById("select_option2").value;
    	var result_price_discount_price = document.getElementById("result_price_discount_price").value;
    	document.getElementById("loan_result_price").innerHTML = Math.round((result_price_discount_price/period)*1.029);
	});

	function part_change1(val)
	{
		//alert("part changed js");
    	console.log("part changed js");
    	var period = document.getElementById("select_option1").value;
    	console.log(period);
    	var result_price_discount_price = document.getElementById("result_price_discount_price").value;
    	console.log(result_price_discount_price);
    	document.getElementById("part_result_price").innerHTML = Math.round(result_price_discount_price/period);
    	console.log('end');
	}
	function part_change2(val)
	{
		//alert("part changed js");
    	console.log("part changed 2 js");
		var period = document.getElementById("select_option2").value;
    	var result_price_discount_price = document.getElementById("result_price_discount_price").value;
    	document.getElementById("loan_result_price").innerHTML = Math.round((result_price_discount_price/period)*1.029);
	}
	</script>

<?*/
	//$this->addExternalJS($templateFolder."/inc/remodal/jquery-3.2.1.min.js");
	$this->addExternalJS($templateFolder."/inc/remodal/remodal.js");
	$this->addExternalJS($templateFolder."/inc/jquery.nice-select.min.js");
?>
	<?/*<script src="<?=$templateFolder?>/inc/remodal/jquery-3.2.1.min.js"></script>
	<script src="<?=$templateFolder?>/inc/remodal/remodal.js"></script>
	<script src="<?=$templateFolder?>/inc/jquery.nice-select.min.js"></script>
	*/?>
	<!-- 7777777777777777777777 -->


	<script>
		$(".nice_select").niceSelect();



		function ready() {

			var mainPrice = +(document.getElementById("mainPrice").innerText.split(" ").join("")),
				bankComission = document.getElementById("bankComission").innerText.split(",").join("."),
				calculatedPrice = mainPrice + mainPrice / 100 * bankComission;

			// set start value of part prices

			document.getElementById("partPrice").innerHTML = Math.ceil((mainPrice / 24) * document.getElementById("select_option").value);
			document.getElementById("partPrice1").innerHTML = Math.ceil((calculatedPrice / 24) * document.getElementById("select_option1").value);



			//___________________________ ACTIONS BY INPUT-RANGE CHANGES

			document.getElementById("range1").addEventListener("input", function() {
				actionByChangingRanre(this);

				document.getElementById("partPrice").innerHTML = Math.ceil((mainPrice / 24) * this.value);
			});

			document.getElementById("range2").addEventListener("input", function() {
				actionByChangingRanre(this);

				document.getElementById("partPrice1").innerHTML = Math.ceil((calculatedPrice / 24) * this.value);;
			});

			function actionByChangingRanre(this1) {

				var //текущее значение (value) range
					curentRange = this1.value,
					// заголовок дроппДаун меню
					curentVal = this1.parentNode.children[0].children[1].children[0], //
					// select node
					selectNode = this1.parentNode.children[0].children[0],
					// select массив
					selectArr = selectNode.children,
					selectOptions = selectNode.options,
					// значение option select'a который соответствует текущему значению input range
					newCurentVal;
				// массив ul дроппДаун меню
				arrUl = this1.parentNode.children[0].children[1].children[1].children; //


				for (var i = 0; i < selectOptions.length; i++) {


					if (selectArr[i].value == this1.value) {
						newCurentVal = selectOptions[i].innerHTML;
						curentVal.innerHTML = newCurentVal; //
						selectNode.value = curentRange;
						//selectArr[i].setAttribute("selected");
					};
					if (arrUl[i].getAttribute("data-value") == this1.value) { //
						for (var j = 0; j < arrUl.length; j++) { //
							arrUl[j].classList.remove("selected"); //
							//
						} //
						arrUl[i].classList.add("selected"); //
					}
				}
			}




			//___________________________ ACTIONS BY SELECT CHANGES
			document.querySelectorAll(".select_option")[0].addEventListener("change", function() {
				var dataValue = this.value;
				this.parentNode.parentNode.children[1].value = this.value;
				this.parentNode.parentNode.parentNode.children[2].children[0].children[0].innerHTML = Math.ceil((mainPrice / 24) * dataValue);
			});

			document.getElementsByClassName("select_option")[1].addEventListener("change", function() {
				var dataValue = this.value;
				this.parentNode.parentNode.children[1].value = this.value;
				this.parentNode.parentNode.parentNode.children[2].children[0].children[0].innerHTML = Math.ceil((calculatedPrice / 24) * dataValue);
			});


			//___________________________ ACTIONS BY DROPP-DOWN MENU CHANGES
			var optionCollection = document.getElementsByClassName("option");
			for (var i = 0; i < optionCollection.length; i++) {
				optionCollection[i].addEventListener("click", function() {
					var dataValue = this.getAttribute("data-value"),
						setRange = this.parentNode.parentNode.parentNode.parentNode.children[1];
					setRange.value = dataValue;
					if (this.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[1].children[0]) {
						this.parentNode.parentNode.parentNode.parentNode.parentNode.children[2].children[0].children[0].innerText = Math.ceil((calculatedPrice / 24) * dataValue);
					} else {
						this.parentNode.parentNode.parentNode.parentNode.parentNode.children[2].children[0].children[0].innerText = Math.ceil((mainPrice / 24) * dataValue);
					}

				})
			}

			// при загрузке cтраницы, а так же при ресайзинге по ширине, запускаем проверку на браузер и ширину экрана
			function defineDeviceAndSize() {
				$(".nice-select").removeClass("open");
				if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) && window.innerWidth < 750) {
					$('.nice_select').niceSelect('destroy');
					$('.remodal-credit .select_wrapper').addClass('only_mob');
				} else {
					if ($(".nice_select")) {
						$('select').niceSelect();
						$('.remodal-credit .select_wrapper').removeClass('only_mob');
					}
				}
			}
			defineDeviceAndSize();

			window.addEventListener('resize', function(event) {
				defineDeviceAndSize();
			});





		}

		document.addEventListener("DOMContentLoaded", ready);

	</script>