<?php

namespace abstractFactoryMethod;

/**
 * Интерфейс абстрактной фабрики
 *
 * @package abstractFactoryMethod
 */
interface AbstractFactory
{
    /**
     * Отправка лидов в банки через API
     *
     * @return \abstractFactoryMethod\sendWithAPIInterface
     */
    public function sendWithAPI(): sendWithAPIInterface;

    /**
     * Отправка лидов в банки с помощью ручной отправки
     *
     * @return \abstractFactoryMethod\handJobSendInterface
     */
    public function handJobSend(): handJobSendInterface;
}

/**
 * Фабрика для БЦК
 *
 * @package abstractFactoryMethod
 */
class BCCFactory implements AbstractFactory
{
    /**
     * @return \abstractFactoryMethod\sendWithAPIInterface
     */
    public function sendWithAPI(): sendWithAPIInterface
    {
        return new BCCSendWithAPI();
    }

    /**
     * @return \abstractFactoryMethod\handJobSendInterface
     */
    public function handJobSend(): handJobSendInterface
    {
        return new BCCSendWithHandJob();
    }
}

/**
 * Фабрика для ХКБ
 *
 * @package abstractFactoryMethod
 */
class HCBFactory implements AbstractFactory
{
    /**
     * @return \abstractFactoryMethod\sendWithAPIInterface
     */
    public function sendWithAPI(): sendWithAPIInterface
    {
        return new HCBSendWithAPI();
    }

    /**
     * @return \abstractFactoryMethod\handJobSendInterface
     */
    public function handJobSend(): handJobSendInterface
    {
        return new HCBSendWithHandJob();
    }
}

/**
 * Интерфейс для отпраки по API
 *
 * @package abstractFactoryMethod
 */
interface sendWithAPIInterface
{
    /**
     * Конфигурации отправщиков
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Возвращает API банка
     *
     * @return string
     */
    public function getAPI(): string;

    /**
     * Возвращает лидов для отправки по API
     *
     * @return array
     */
    public function getLeads(): array;
}

/**
 * Отправка в БЦК по API
 *
 * @package abstractFactoryMethod
 */
class BCCSendWithAPI implements sendWithAPIInterface
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'bankName' => 'БЦК',
            'jobName'  => 'Отправка лидов в БЦК через API',
            'limit'    => 100,
        ];
    }

    /**
     * @return string
     */
    public function getAPI(): string
    {
        return "https://bcc.api.kz/v1/partners/leads";
    }

    /**
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
 * Отправка в ХКБ по API
 *
 * @package abstractFactoryMethod
 */
class HCBSendWithAPI implements sendWithAPIInterface
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'bankName' => 'ХКБ',
            'jobName'  => 'Отправка лидов в ХКБ через API',
            'limit'    => 250,
        ];
    }

    /**
     * @return string
     */
    public function getAPI(): string
    {
        return "https://hcb.api.kz/leads";
    }

    /**
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
 * Интерфейс для ручной отправки
 *
 * @package abstractFactoryMethod
 */
interface handJobSendInterface
{
    /**
     * Возвращает конфигурации отправщика
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Ручная отправка в банк с помощью API банка,
     * полученного с помощью интерфейса
     *
     * @param \abstractFactoryMethod\sendWithAPIInterface $collaborator
     *
     * @return string
     */
    public function sendWithApi(sendWithAPIInterface $collaborator): string;
}

/**
 * Ручная отправка в БЦК
 *
 * @package abstractFactoryMethod
 */
class BCCSendWithHandJob implements handJobSendInterface
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'jobName' => 'Ручная отправка в БЦК',
            'path'    => '/tmp/bcc_send.xls',
        ];
    }

    /**
     * @param \abstractFactoryMethod\sendWithAPIInterface $collaborator
     *
     * @return string
     */
    public function sendWithApi(sendWithAPIInterface $collaborator): string
    {
        $result = $collaborator->getAPI();

        return "Ручная отправка в БЦК осуществлена через API: ({$result})";
    }
}

/**
 * Ручная отправка в ХКБ
 *
 * @package abstractFactoryMethod
 */
class HCBSendWithHandJob implements handJobSendInterface
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'jobName' => 'Ручная отправка в ХКБ',
            'path'    => '/tmp/hcb_send.xls',
        ];
    }

    /**
     * @param \abstractFactoryMethod\sendWithAPIInterface $collaborator
     *
     * @return string
     */
    public function sendWithApi(sendWithAPIInterface $collaborator): string
    {
        $result = $collaborator->getAPI();

        return "Ручная отправка в ХКБ осуществлена через API: ({$result})";
    }
}

/**
 * Клиент
 *
 * @param \abstractFactoryMethod\AbstractFactory $factory
 */
function client(AbstractFactory $factory)
{
    $apiSend     = $factory->sendWithAPI();
    $leads       = $apiSend->getLeads();
    $handJobSend = $factory->handJobSend();
    $apiConfig   = $apiSend->getConfig();
    echo '<h2>___' . $apiConfig['bankName'] . '___</h2>';
    echo '<h3>' . $apiConfig['jobName'] . '</h3>';
    echo '<h4>Лимит для отпраки по API: ' . $apiConfig['limit'] . ' заявок </h4>';
    echo '<h4>API: ' . $apiSend->getAPI() . '</h4>';
    echo 'Отправленные лиды в ' . $apiConfig['bankName'] . ':';
    foreach ($leads as $lead) {
        echo '<br>';
        foreach ($lead as $key => $value) {
            echo $key . ' => ' . $value . '<br>';
        }
    }
    $handJobConfig = $handJobSend->getConfig();
    echo '<h3>' . $handJobConfig['jobName'] . '</h3>';
    echo '<h4>Путь до файла для ручной отправки: ' . $handJobConfig['path'] . '</h4>';
    echo $handJobSend->sendWithApi($apiSend) . '<br>';
}

echo "<h1>Abstract Factory Method</h1>";
client(new BCCFactory());
echo '<br><br><br>';
client(new HCBFactory());