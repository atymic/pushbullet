<?php

namespace NotificationChannels\Pushbullet;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Pushbullet\Exceptions\CouldNotSendNotification;

class Pushbullet
{
    /** @var string */
    protected $token;

    /** @var \GuzzleHttp\Client */
    protected $httpClient;

    /**
     * Create small Pushbullet client.
     *
     * @param  string  $token
     * @param  \GuzzleHttp\Client  $httpClient
     */
    public function __construct($token, HttpClient $httpClient)
    {
        $this->token = $token;

        $this->httpClient = $httpClient;
    }

    /**
     * Get url to send to Pushbullet API?
     *
     * @return string
     */
    protected function getPushbulletUrl()
    {
        return 'https://api.pushbullet.com/v2/pushes';
    }

    /**
     * Get headers for API calls.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Access-Token' => $this->token,
        ];
    }

    /**
     * Send request to Pushbullet API.
     *
     * @param  array  $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send($params)
    {
        $url = $this->getPushbulletUrl();

        try {
            return $this->httpClient->post($url, [
                'json' => $params,
                'headers' => $this->getHeaders(),
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::pushbulletRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithPushbullet();
        }
    }
}
