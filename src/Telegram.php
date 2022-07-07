<?php

namespace MicroSpaceless\TelegramBot;

/**
 * Service class to comunicate with Telegram API.
 *
 * @author Edgar Poghosyan <ed.arm.2000@gmail.com>
 */

class Telegram
{
    /**
     * @var string
     */
    private static $endpoint = 'https://api.telegram.org/';

    private static $token = null;
    private static $chatId = null;

    private static $resources = [
        'sendMessage',
    ];

    private static $client = null;

    private static $response = [
        'payload' => null,
        'status' => null,
    ];

    public function __construct()
    {
        self::$token = config('telegram.token');
        self::$chatId = config('telegram.chatId');
    }

    /**
     * Generate the resource URL based on the transaction type.
     *
     * @param string $name resouce name, based on $resources
     *
     * @throws Exception if the resource name is invalid
     */
    private static function generateResourceUrl($name)
    {
        if (in_array($name, self::$resources)) {
            return static::$endpoint . 'bot'. static::$token . '/' . $name;
        }

        throw new \Exception('Invalid Telegram resource name `' . $name . '`');
    }

    /** Setup default configuration for the curl client */
    private static function setupClient()
    {
        self::$client = curl_init();

        curl_setopt(self::$client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$client, CURLOPT_ENCODING, 'gzip');
        curl_setopt(self::$client, CURLOPT_VERBOSE, false);
        curl_setopt(self::$client, CURLOPT_HEADER, false);
        curl_setopt(self::$client, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

    /**
     * Send a HTTP request to que API endpoint.
     *
     * @param string $method       HTTP method (POST, GET ...)
     * @param string $resourceName resouce name, based on $resources
     * @param array  $data         an array of data to be converted to JSON and sent to the API
     *
     * @return $this
     */
    private static function callAPI($method, $resourceName, $data)
    {
        self::setupClient();

        $url = self::generateResourceUrl($resourceName);
        $dataString = json_encode($data);

        if ($method == 'POST') {
            curl_setopt(self::$client, CURLOPT_CUSTOMREQUEST, 'POST');
            if ($data) {
                curl_setopt(self::$client, CURLOPT_POSTFIELDS, $dataString);
                curl_setopt(self::$client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($dataString)]);
            }
        }

        curl_setopt(self::$client, CURLOPT_URL, $url);

        try {
            self::$response['payload'] = curl_exec(self::$client);
            self::$response['status']  = curl_getinfo(self::$client, CURLINFO_HTTP_CODE);
            curl_close(self::$client);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() . ' - ' . $url);
        }

        return self::$response;
    }


    /**
     * Send a message to a specific chat in Telegram.
     *
     * @param string $text      a message with maximun lenght of 406 characters
     * @param string $parseMode HTML or Markdown Telegram will parse characteres
     * @param array  $additional_params Additional parameters to send to the API
     *
     * @return object $this
     */
    public function sendMessage($text, $parseMode = 'HTML', $additional_params = [])
    {
        $params = [];

        if (isset(self::$chatId) && isset($text)) {
            $stringLenUtf8 = mb_strlen($text, 'UTF-8');

            if ($stringLenUtf8 > 4096) {
                $text = mb_substr($text, 0, 4096);
            }

            $params = [
                'chat_id' => self::$chatId,
                'text'    => $text,
            ];

            if (is_array($parseMode)) {
                $params = array_merge($params, $parseMode);
            } else {
                $params['parse_mode'] = $parseMode;
                $params = array_merge($params, $additional_params);
            }

            return self::callAPI('POST', 'sendMessage', $params);
        }

        throw new \Exception('Invalid sendMessage');
    }
}
