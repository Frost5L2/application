# application
Тестовое задание

Есть биржи с их API, ключами
В этих биржах есть деньги в разных валютах.
Например USD - 1200, RUB - 30000, EUR - 4500. В каждой бирже разная сумма лежит

Надо сделать функционал перераспределения денег в биржах в % соотношении.
Администратор на странице забивает форму с валютой и с % и надо в биржах перераспределить все деньги в этом процентом соотношении. (саму форму делать не надо, просто на вход можно подавать массив валют с %)
За основной курс брать USD, У каждой биржи есть стоимость валюты по отношению к USD

Api каждой биржи позволяет получать текущий курс пары валют getPrice("RUBUSD") - стоимость рубля по отношению к доллару
совершать обмен валюты buy("RUBUSD", 15) - покупает 15 долларов за рубли
Поулчает баланс всех валют в бирже - getBalances() - возвращает ['RUB' => 30000, 'USD' => 1200, 'EUR' => 4500]

Биржи могут добавляться или удаляться. Надо сделать чтобы добавление или изменение новой биржи было как можно проще
Для работы с биржей нужен apiKey. Желательно этот ключ не светить в коде, только где нибудь в конфиге.
