<?php

namespace common\components;

use bulldozer\App;
use DateTime;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Class TaxApi
 * @package frontend\components
 */
class TaxApi extends Client
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @param int $fn
     * @param int $i
     * @param int $fp
     * @param int $n
     * @param DateTime $t
     * @param float $sum
     * @return bool
     * @throws TaxApiException
     */
    public function isCheckExist(int $fn, int $i, int $fp, int $n, DateTime $t, float $sum): bool
    {
        try {
            $this->callMethod('GET', '/v1/ofds/*/inns/*/fss/' . $fn . '/operations/' . $n
                . '/tickets/' . $i . '?fiscalSign=' . $fp . '&date=' . $t->format(DATE_ATOM)
                . '&sum=' . $sum);

            return true;
        } catch (TaxApiException $e) {
            if ($e->status == 406) {
                return false;
            } else {
                throw new TaxApiException($e->status, $e->getMessage());
            }
        }
    }

    /***
     * @param int $fn
     * @param int $i
     * @param int $fp
     * @return array
     * @throws TaxApiException
     */
    public function getCheckInfo(int $fn, int $i, int $fp)
    {
        $url = '/v1/inns/*/kkts/*/fss/' . $fn
            . '/tickets/' . $i . '?fiscalSign=' . $fp . '&sendToEmail=no';

        $result = $this->callMethod('GET', $url);

        if ($result['status'] != 200) {
            $result = $this->callMethod('GET', $url);
        }

        if ($result['status'] == 200) {
            return $result['data'];
        } else {
            throw new TaxApiException($result['status'], 'Check not found');
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $postParams
     * @return array
     * @throws TaxApiException
     */
    protected function callMethod(string $method, string $url, array $postParams = []): ?array
    {
        try {
            $request = $this->createRequest()
                ->setMethod($method)
                ->addHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                    'Device-Id' => '43a9bd55-****-****-****-28741941293b',
                    'Device-OS' => 'Android 6.0.1',
                    'Version' => 2,
                    'ClientVersion' => '1.4.2',
                    'Host' => 'proverkacheka.nalog.ru:9999',
                    'Connection' => 'Keep-Alive',
                    'Accept-Encoding' => 'application/json',
                    'User-Agent' => 'okhttp/3.0.1',
                ])
                ->setUrl($url);

            if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
                $request->setData($postParams);
            }

            $response = $request->send();

            App::error('Request url: ' . $url);
            App::error('Method: ' . $method);
            App::error('POST Data: ' . var_export($postParams, true));
            App::error('Response status: ' . $response->statusCode);
            App::error('Response: ' . $response->content);

            if ($response->isOk) {
                return ['status' => $response->statusCode, 'data' => $response->getData()];
            } else {
                throw new TaxApiException($response->statusCode, 'Response error. Status: ' . $response->statusCode . '. Content: ' . $response->content);
            }
        } catch (InvalidConfigException $e) {
            throw new TaxApiException(500, $e->getMessage());
        } catch (Exception $e) {
            throw new TaxApiException(500, $e->getMessage());
        }
    }
}