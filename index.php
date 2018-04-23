<?php
class stockExchange{
	
	private $apiKey = "some api key";		// Тут хранится ключ биржи
	public $stockExBalance = array(		// Баланс первой биржи
		"USD" => 0,
		"RUB" => 0,
		"EUR" => 50
	);
	public function checkApiKey($key){	// Проверка ключа
		if($key == $this->apiKey){
			return true;
		}else{
			return false;
		}
	}

	protected function ratio($currency){		// Метод возвращает соотношение валюты к доллару
		$ratioDollar = array(
			"USD" => 1,
			"RUB" => 61.3873542,
			"EUR" => 0.81313
		);
		if(array_key_exists($currency, $ratioDollar)){	// Ищем соответсвтия, если есть, то окей, если нету, то ошибка
			return $ratioDollar[$currency];
		}else{
			return "Такой валюты не существует.";
		}
	}

	// public function setPercentForCurrency($arr){
	// 	if(array_sum($arr) != 100){
	// 		echo "Ошибка, в сумме процентов должно быть 100%";
	// 		return false;
	// 	}
	// 	dump($arr);
	// 	$currencys = $this->stockExBalance;
	// 	$summInDollar = 0;
	// 	$currancy = array();
	// 	foreach ($currencys as $key => $value) {
	// 		$summInDollar += ($value / $this->ratio($key));
	// 	}
	// 	foreach ($arr as $key => $value) {
	// 		$currancy[$key] = $summInDollar / $value;
	// 	}

	// 	$this->stockExBalance = $currancy;
	// }

	public function getPrice($currencys){		// Возвращает соотношение валют друг к другу
		if($currencys){
			$firstCurrency = substr($currencys, 0, 3);		// Делим по валютам/Первая валюта
			$secondCurrency = substr($currencys, -3);		// Делим по валютам/Вторая валюта
			if($secondCurrency == "USD"){					// Если вторая доллар, то просто отдаем соотношение первой валюты из "базы"
				return $this->ratio($firstCurrency);
			}
			$firstCurRatio =  $this->ratio($firstCurrency);		// Соотношен первой валюты к доллару
			$secondCurRatio = $this->ratio($secondCurrency);	// Соотношен второй валюты к доллару
			if(!is_numeric($firstCurRatio) || !is_numeric($firstCurRatio)){		// Есио не число, то ошибка
				return "Произошла ошибка, проверьте ввода валют.";
			}
			return ($firstCurRatio / $secondCurRatio);
		}else{
			return "Ошибка, не введена валюта!";
		}
		
	}

	public function buy($currencys, $quantity){			// Покупка валюты 
		$firstCurrency = substr($currencys, 0, 3);		// Делим по валютам/Первая валюта
		$secondCurrency = substr($currencys, -3);		// Делим по валютам/Вторая валюта
		$ratio = $this->getPrice($currencys);			// Соотношение валют друг к другу
		$costBuy = $ratio * $quantity;					// Стоймость покупки
		if($this->stockExBalance[$firstCurrency] < $costBuy){	// Проверяем хватает ли средств
			echo "Недостаточно средств.";
		}else{
			$this->stockExBalance[$secondCurrency] += $quantity;	// Покупаем
			$this->stockExBalance[$firstCurrency] -= $costBuy;		// Платим
			echo "Покупка успешно совершена!";
		}

	}

	public function getBalance(){			// Возвращает баланс
		return $this->stockExBalance;
	}
} 
/*  --- Тестовые данные ---   */
$admData = array(			// Входные параметры от Админа
	"USD" => 30,
	"RUB" => 20,
	"EUR" => 50
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

$test1 = new stockExchange;
$test1->stockExBalance = $test1balance;

$test2 = new stockExchange;
$test2->stockExBalance = $test2balance;

$test3 = new stockExchange;
$test3->stockExBalance = $test3balance;
/*  !--- Тестовые данные ---   */

dump($admData);
$arrs = array($test1,$test2,$test3);
dump($arrs);
dump(setPercentForCurrency($arrs, $admData));


function ratio($currency){			// То же самое что и в классе биржа
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
		$value->stockExBalance = setPercentForCurrencyStock($value, $admData);
	}
	return $arrStocks;
}

function setPercentForCurrencyStock($obj, $arrData){			// Изменяет процент соотношений в одной бирже
	if(array_sum($arrData) != 100){
		echo "Ошибка, в сумме процентов должно быть 100%";
		return false;
	}
	$summInDollar = 0;
	$currancy = array();
	foreach ($obj->stockExBalance as $key => $value) {			// Ходит по балансу
		$summInDollar += ($value / ratio($key));			// переводит все в доллары
	}
	foreach ($arrData as $key => $value) {				// ходит по массиву от Админа
		$currancy[$key] = $summInDollar / $value;		// делить всю сумму на нужный процент
	}
	return $currancy;				// Возвращает новый баланс в соответствии с процентами
}

function dump($var){
	echo "<pre>"; print_r($var); echo "</pre>";
}