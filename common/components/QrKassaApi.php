<?php

namespace common\components;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * Class QrKassaApi
 * @package common\components
 */
class QrKassaApi extends Client
{
    /**
     * @param array $checkData
     * @return array
     * @throws QrKassaApiException
     */
    public function getCheckInfo(array $checkData): array
    {
        try {
            $request = $this->createRequest()
                ->setUrl('check_json')
                ->setFormat(Client::FORMAT_JSON)
                ->setData($checkData)
                ->setMethod('POST');

            $response = $request->send();

            if ($response->isOk) {
                return $response->getData();
            } else {
                throw new QrKassaApiException($response->content);
            }
        } catch (InvalidConfigException $e) {
            throw new QrKassaApiException($e->getMessage());
        }
    }
}