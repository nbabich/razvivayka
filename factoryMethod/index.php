<?php

namespace factoryMethod;

/**
 * Главный класс
 *
 * Class Creator
 *
 * @package factoryMethod
 */
abstract class Creator
{
    abstract public function factoryMethod(): Render;

    public function someOperation(): array
    {
        $product          = $this->factoryMethod();
        $result['config'] = $product->renderConfig();
        $result['leads']  = $product->getLeads();

        return $result;
    }
}

/**
 * Типа отправляет данные в БЦК по своему
 *
 * @package factoryMethod
 */
class BCCCreator extends Creator
{

    public function factoryMethod(): Render
    {
        return new BCCRender;
    }
}

/**
 * Типа отправляет данные в ХКБ по своему
 *
 * @package factoryMethod
 */
class HCBCreator extends Creator
{
    public function factoryMethod(): Render
    {
        return new HCBRender;
    }
}

/**
 * Интерфейс для приведения к общему формату данных
 *
 * @package factoryMethod
 */
interface Render
{
    public function renderConfig(): array;

    public function getLeads(): array;
}

/**
 * Генерирует данные для БЦК
 *
 * @package factoryMethod
 */
class BCCRender implements Render
{
    /**
     * Генерит или получает откуда-то конфиги для БЦК
     *
     * @return array
     */
    public function renderConfig(): array
    {
        return [
            'bank'  => 'Банк Центр Кредит',
            'api'   => 'https://bcc.api.kz/send/leads',
            'limit' => 100,
        ];
    }

    /**
     * Генерит или получает откуда-то данные для БЦК
     *
     * @return array
     */
    public function getLeads(): array
    {
        return [
            [
                'id'      => 2136,
                'surname' => 'Дорофеев',
                'name'    => 'Федор',
                'amount'  => '99999999',
            ],
            [
                'id'      => 6541,
                'surname' => 'Бакеев',
                'name'    => 'Виталий',
                'amount'  => '300000',
            ],
            [
                'id'      => 8455,
                'surname' => 'Хабухаев',
                'name'    => 'Реат',
                'amount'  => '1',
            ],
        ];
    }
}

/**
 * Генерирует данные для ХКБ
 *
 * @package factoryMethod
 */
class HCBRender implements Render
{
    /**
     * Генерит или получает откуда-то конфиги для ХКБ
     *
     * @return array
     */
    public function renderConfig(): array
    {
        return [
            'bank'  => 'Хоум Кредит Банк',
            'api'   => 'https://hcb.kz/api/v1/leads/send',
            'limit' => 200,
        ];
    }

    /**
     * Генерит или получает откуда-то данные для ХКБ
     *
     * @return array
     */
    public function getLeads(): array
    {
        return [
            [
                'id'      => 12435,
                'surname' => 'Исахмет',
                'name'    => 'Бек',
                'amount'  => '500000',
            ],
            [
                'id'      => 12566,
                'surname' => 'Рамазан',
                'name'    => 'Максат',
                'amount'  => '1000000',
            ],
            [
                'id'      => 83452,
                'surname' => 'Багдаулет',
                'name'    => 'Ердаулет',
                'amount'  => '100',
            ],
        ];
    }
}

/**
 * Выполняет типа отправку в банки
 *
 * @param \factoryMethod\Creator $creator
 */
function client(Creator $creator)
{
    echo "<table style='width:50%; border: 2px solid black'><tr><th style='text-align: left; border: 1px solid black'>Конфигурация</th><th style='text-align: left; border: 1px solid black'>Значение</th></tr>";
    $array = $creator->someOperation();
    echo '<h3>Конфигурация отправщика</h3>';
    foreach ($array['config'] as $key => $value) {
        echo "<tr>
                    <td>" . $key . "</td>
                    <td>" . $value . "</td>
              </tr>";
    }
    echo "</table>";

    echo '<h3>Готовые лиды для отправки</h3>';
    echo "<table style='width:50%; border: 2px solid black'>
            <tr>
                <th style='text-align: left; border: 1px solid black'>id</th>
                <th style='text-align: left; border: 1px solid black'>lead_id</th>
                <th style='text-align: left; border: 1px solid black'>surname</th>
                <th style='text-align: left; border: 1px solid black'>name</th>
                <th style='text-align: left; border: 1px solid black'>amount</th>
            </tr>";
    $counter = 1;
    foreach ($array['leads'] as $key => $value) {
        echo '<tr><td>' . $counter . '</td>';
        $counter++;
        foreach ($value as $keyLead => $valueLead) {
            echo '<td>' . $valueLead . '</td>';
        }
        echo '</tr>';
    }
    echo "</table>";
    $new = $counter - 1;
    echo '<h4>Количество лидов для ' . $array['config']['bank'] . ' = ' . $new . '</h4>';

}

echo "<h1>Factory Method</h1>";
echo "<h3>Отправка в БЦК.</h3>";
client(new BCCCreator);
echo "<h3>Отправка в ХКБ.</h3>";
client(new HCBCreator());
