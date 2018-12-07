<?php

class ATM
{
    public function __construct($banknotesCounts)
    {
        $this->addBanknotes($banknotesCounts);
    }

    public $cells = [
        5000 => 0,
        2000 => 0,
        1000 => 0,
        500 => 0,
        200 => 0,
        100 => 0,
        50 => 0
    ];

    /**
     * Добавляет банкноты распределяя по ячейкам
     * @param $banknotesCounts array асоциативный массив где ключи наминалы купюр, а значение: количество
     */
    public function addBanknotes($banknotesCounts)
    {
        foreach ($banknotesCounts as $nominal => $count) {
            if (!isset($this->cells[$nominal])) {
                throw new InvalidArgumentException ('Номинал ' . $nominal . ' не поддерживается.');
            }

            if (!is_int($count) || $count <= 0) {
                throw new InvalidArgumentException ('Количество купюр должно быть натуральным числом.');
            }

            $this->cells[$nominal] += $count;
        }
    }

    /**
     * Общая доступная сумма
     * @return int
     */
    public function getSum()
    {
        $sum = 0;

        foreach ($this->cells as $nominal => $count) {
            $sum += intval($nominal) * $count;
        }

        return $sum;
    }

    /**
     * Выдача денег из банкомата
     * @param $amount
     *
     * @return array колличество купюр каждого номинала
     * @throws Exception Данную сумму не вазможно выдать
     */
    public function giveMoney($amount)
    {
        if ($amount > $this->getSum() || $amount < 100) {
            throw new Exception('Операция не выполнена.');
        }

        $banknotesATM = [];

        foreach ($this->cells as $nominalName => $nominalCount) {
            $banknotesATM[] = intval($nominalName);
        }

        arsort($banknotesATM);

        $object = [];

        foreach ($banknotesATM as $nominalCount) {
            $object[$nominalCount] = 0;
        }

        for ($count = 0; $count < count($this->cells); $count++) {

            for ($i = $count; $i < count($this->cells); $i++) {
                while ($amount >= $banknotesATM[$i]
                    && $this->cells[$banknotesATM[$i]] - $object[$banknotesATM[$i]] > 0) {
                    $amount -= $banknotesATM[$i];
                    $object[$banknotesATM[$i]]++;
                    if ($amount === 0) {
                        foreach ($this->cells as $key => $value) {
                            $this->cells[$key] -= $object[$key];
                        }

                        return $object;
                    }
                }
            }

            if ($amount > 0) {
                foreach ($banknotesATM as $kupValue) {
                    if ($object[$kupValue] > 0) {
                        $amount += $kupValue;
                        $object[$kupValue]--;
                    }
                }
            }
        }

        throw new Exception('Операция не выполнена.');
    }
}

/**
 * Общая доступная сумма во всех банкоматах
 * @param $atms ATM[]
 *
 * @return int
 */
function getAllATMsSum($atms)
{
    $sum = 0;

    foreach ($atms as $atm) {
        $sum += $atm->getSum();
    }

    return $sum;
}

/**
 * Приводит все банкоматы в изначальное состояние
 * @param $atms ATM[] банкоматы
 * @param $atmInitialCounts array изначальное состояние
 */
function setATMs($atms, $atmInitialCounts)
{
    /**
     * @var $atm ATM
     */
    foreach ($atms as $atm_index => $atm) {
        foreach ($atm->cells as $nominal => $count) {
            if ($atmInitialCounts[$atm_index][$nominal] > $count) {
                $count = $atmInitialCounts[$atm_index][$nominal] - $count;
                $atm->addBanknotes([$nominal => $count]);
            }
        }
    }
}

$atmInitialCounts = [
    [
        5000 => 50,
        2000 => 100,
        1000 => 150,
        500 => 250,
        200 => 400,
        100 => 800,
        50 => 1000,
    ],
    [
        5000 => 70,
        2000 => 120,
        1000 => 250,
        500 => 350,
        200 => 600,
        100 => 900,
        50 => 1200
    ],
    [
        5000 => 20,
        2000 => 50,
        1000 => 80,
        500 => 130,
        200 => 200,
        100 => 400,
        50 => 550
    ]
];

/**
 * @var $atms ATM[]
 */
$atms = [];

foreach ($atmInitialCounts as $atmValue) {
    $atms[] = new ATM($atmValue);
}
echo "Виводить 935000 из первого банкомата";
echo '<br>';
//var_dump($atms[0]->giveMoney(935000));
echo '<br>';
echo "Получить сколько денег осталось первом банкомате";
echo '<br>';
echo($atms[0]->getSum());
echo '<br>';
echo "Получить сколько денег осталось во всех банкоматах";
echo '<br>';
echo(getAllAtmsSum($atms));
echo '<br>';
echo "Пополнить первую банкомату 35 штук 5000 и 32 штук 2000";
echo '<br>';
$atms[0]->addBanknotes([5000 => 35]);
$atms[0]->addBanknotes([2000 => 32]);
echo "Получить сколько денег осталось первом банкомате";
echo '<br>';
echo($atms[0]->getSum());
echo '<br>';
echo "Виводить 166000 из первого банкомата";
echo '<br>';
var_dump($atms[0]->giveMoney(166000));
echo '<br>';
echo "Получить сколько денег  осталось первом банкомате";
echo '<br>';
echo($atms[0]->getSum());
echo '<br>';
echo "восстановить состояние всех ATM до начального";
echo '<br>';
setATMs($atms, $atmInitialCounts);
echo "Получить сколько денег осталось во всех банкоматах";
echo '<br>';
echo(getAllAtmsSum($atms));
$arr = [5, 4, 3, 2, 1, 5, 4, 23, 11];
$i = 0;
$length = 0;
echo '<br>';

