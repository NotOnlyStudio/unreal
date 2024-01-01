<?php

namespace App\Service\Freekassa;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FreeKassaWithdrawlServices extends FreekassaServices
{
    private $apiUrl = 'https://api.freekassa.ru/v1/';

    public function withDrawl(int $amount, string $account)
    {
        $postData = [
            'shopId' => $this->merchat_id,
            'i' => 1,
            'account' => $account,
            'amount' => $amount,
            'currency' => 'RUB',
            'nonce' => time(),
        ];

        ksort($postData);

        $sign = hash_hmac('sha256', implode('|', $postData), $this->apiKey);
        $postData['signature'] = $sign;

        $request = json_encode($postData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.freekassa.ru/v1/withdrawals/create');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $result = trim(curl_exec($ch));
        curl_close($ch);

        $response = json_decode($result, true);
        return $response;
    }

    public function api($request, $options = [])
    {
        $data = array_merge(
            [
                'shopId' => $this->merchat_id,
                'nonce' => (int)(microtime(true) * 10000),
            ],
            $options
        );
        ksort($data);
        $sign = hash_hmac('sha256', implode('|', $data), $this->secret_word);
        $data['signature'] = $sign;
        $request = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.freekassa.ru/v1/' . $request);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $result = trim(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode != 200) {
            throw new \Exception("FreeKassa Error: " . $httpcode . ";\n" . $result);
        }
        curl_close($ch);

        $array = json_decode($result, true);

        if ($array['type'] != 'success') {
            throw new \Exception("FreeKassa Error: unsuccess;\n" . $result);
        }

        return $array;
    }
}
