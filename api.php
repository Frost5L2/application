<?php 
class stockExchange{

	private $key = "qwerty123";			// Тут хранится ключ биржи

	public function __construct( $key ){	// Проверка ключа
		try {
		    if($key != $this->key){
				throw new Exception('Введен не верный apiKey');
			}
		} catch (Exception $e) {
		    die ('Выброшено исключение: '.$e->getMessage()."\n");
		}
	}

	public $stockExBalance = array(		// Баланс первой биржи
		"USD" => 0,
		"RUB" => 0,
		"EUR" => 50
	);

	protected function ratio($currency){		// Метод возвращает соотношение валюты к доллару
		$ratioDollar = array(
			"USD" => 1,
			"RUB" => 61.3873542,
			"EUR" => 0.81313
		);
		if(array_key_exists($currency, $ratioDollar)){	// Ищем соответсвтия, если есть, то окей, если нету, то ошибка
			return $ratioDollar[$currency];
		}else{
			echo "Такой валюты не существует.";
			return false;
		}
	}

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
				echo "Произошла ошибка, проверьте ввода валют.";
				return false;
			}
			return ($firstCurRatio / $secondCurRatio);
		}else{
			echo "Ошибка, не введена валюта!";
			return false;
		}
		
	}

	public function buy($currencys, $quantity){			// Покупка валюты 
		$firstCurrency = substr($currencys, 0, 3);		// Делим по валютам/Первая валюта
		$secondCurrency = substr($currencys, -3);		// Делим по валютам/Вторая валюта
		$ratio = $this->getPrice($currencys);			// Соотношение валют друг к другу
		$costBuy = $ratio * $quantity;				// Стоймость покупки
		if($this->stockExBalance[$firstCurrency] < $costBuy){	// Проверяем хватает ли средств
			echo "Недостаточно средств.";
			return false;
		}else{
			$this->stockExBalance[$secondCurrency] += $quantity;	// Покупаем
			$this->stockExBalance[$firstCurrency] -= $costBuy;		// Платим
			return true;
		}
	}

	public function getBalance(){			// Возвращает баланс
		return $this->stockExBalance;
	}
} 