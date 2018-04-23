<?php
require "api.php";
/*  --- Тестовые данные ---   */
$admData = array(			// Входные параметры от Админа
	"USD" => 80,
	"RUB" => 10,
	"EUR" => 10
);
$test1balance = array(		// Баланс первой биржи
		"USD" => 700,
		"RUB" => 25000,
		"EUR" => 200
	);
$test2balance = array(		// Баланс второй биржи
		"USD" => 800,
		"RUB" => 10000,
		"EUR" => 400
	);
$test3balance = array(		// Баланс третей биржи
		"USD" => 500,
		"RUB" => 1000,
		"EUR" => 100
	);

$test1 = new stockExchange("qwerty123");
$test1->stockExBalance = $test1balance;

$test2 = new stockExchange("qwerty123");
$test2->stockExBalance = $test2balance;

$test3 = new stockExchange("qwerty123");
$test3->stockExBalance = $test3balance;
/*  !--- Тестовые данные ---   */

dump($admData);
$arrTests = array($test1,$test2,$test3);
dump($arrTests);
dump(setPercentForCurrency($arrTests, $admData));

function ratio($currency){			// Функция возвращает курс по отношению к доллару
	$ratioDollar = array(
		"USD" => 1,
		"RUB" => 61.3873542,
		"EUR" => 0.81313
	);
	if(array_key_exists($currency, $ratioDollar)){
		return $ratioDollar[$currency];
	}else{
		return "Такой валюты не существует.";
	}
}

function setPercentForCurrency($arrStocks,$admData){		// Изменяет процент соотношений в массиве бирж
	if(array_sum($admData) != 100){
		echo "Ошибка, в сумме процентов должно быть 100%";
		return false;
	}
	foreach ($arrStocks as $key => $value) {			// Ходим по входящему массиву бирж и меняем процент
		$value = setPercentForCurrencyStock($value, $admData);
	}
	return $arrStocks;
}

function setPercentForCurrencyStock($obj, $arrData){			// Изменяет процент соотношений в одной бирже
	if(array_sum($arrData) != 100){
		echo "Ошибка, в сумме процентов должно быть 100%";
		return false;
	}
	$currancy = array();
	foreach ($obj->stockExBalance as $key => $value) {
		$costPrice = $value / $obj->getPrice($key."USD");	// Вычисляем сколько купить долларов, на сумму нашей валюты
		$obj->buy($key."USD", $costPrice);					// Покупаем
	}
	$summInDollar = $obj->stockExBalance["USD"];			// Тут вся валюта в долларах
	foreach ($arrData as $key => $value) {
		$neededVal = $summInDollar/100*$value;					// Сколько долларов нужно потратить что бы получить нужный процент
		$costPrice = $neededVal * $obj->getPrice($key."USD");		// вычисляем сколько нужно купить необходимой валюты
		$obj->buy("USD".$key, $costPrice);							// покупает валюту
	}
	
	return $obj;
}

function dump($var){
	echo "<pre>"; print_r($var); echo "</pre>";
}