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
		$costBuy = $ratio * $quantity;	
			// Стоймость покупки
		if($this->stockExBalance[$firstCurrency] < $costBuy){	// Проверяем хватает ли средств
			echo "Недостаточно средств.";
		}else{
			$this->stockExBalance[$secondCurrency] += $quantity;
			//dump($this->stockExBalance[$secondCurrency]);	// Покупаем
			$this->stockExBalance[$firstCurrency] -= $costBuy;		// Платим
			//echo "Покупка успешно совершена!";
		}

	}

	public function getBalance(){			// Возвращает баланс
		return $this->stockExBalance;
	}
} 