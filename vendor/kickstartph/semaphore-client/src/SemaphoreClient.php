<?php

namespace Semaphore;

use Guzzle\Http\Client;

/**
 * Class SemaphoreClient
 * @package Semaphore
 */

class SemaphoreClient
{

    public $apiKey;
    public $senderId = null;
    protected $client;

    /**
     * Initializes the Semaphore API Client
     *
     * @param $apiKey - Your API Key
     * @param null $senderId - Optional Sender ID (Defaults to SEMAPHORE)
     */
    public function __construct( $apiKey, $senderId = null )
    {
        $this->apiKey = $apiKey;
        $this->senderId = $senderId;
        $this->client = new Client( 'http://api.semaphore.co/' );
    }

    /**
     * Check the balance of your account
     *
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function balance()
    {
        $query = [
            'query' => [
                'api' => $this->apiKey,
            ]
        ];

        $request = $this->client->get( 'api/sms/account', [], $query);
        $response = $request->send();
        return $response->getBody();
    }

    /**
     * Send SMS message(s)
     *
     * @param $number - The recipient phone number(s)
     * @param $message - The message
     * @param null $senderId - Optional Sender ID (defaults to initialized value or SEMAPHORE)
     * @param bool|false $bulk - Optional send as bulk
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function send( $number, $message, $senderId = null, $bulk = false )
    {
        $postFields = array(
            'api' => $this->apiKey,
            'message' => $message,
            'number' => $number,
            'from' => $this->senderId
        );

        if( $senderId != null )
        {
            $postFields[ 'from' ] = $senderId;
        }

        if( $bulk != true )
        {
            $request = $this->client->post('api/sms')->addPostFields( $postFields );
        } else {
            $request = $this->client->post('v3/bulk_api/sms')->addPostFields( $postFields );
        }
        $response = $request->send();
        return $response->getBody();
    }

    /**
     * Retrieves data about a specific message
     *
     * @param $messageId - The encoded ID of the message
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function message( $messageId )
    {
        $query = [
            'query' => [
                'api' => $this->apiKey,
            ]
        ];
        $request = $this->client->get( 'api/messages/' . urlencode( $messageId ), [], $query );
        $response = $request->send();
        return $response->getBody();
    }

    /**
     * Retrieves up to 100 messages, offset by page
     * @param null $page - Optional page for results past the initial 100
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function messages( $page = null )
    {
        $query = [
            'query' => [
                'api' => $this->apiKey,
                'page' => $page,
            ]
        ];
        $request = $this->client->get( 'api/messages', [], $query );
        $response = $request->send();
        return $response->getBody();
    }

    /**
     * Retrieve messages between a range of Dates/Times
     *
     * @param $startDate - Automatically converted to UNIX timestamp via str_to_time
     * @param $endDate - Automatically converted to UNIX timestamp via str_to_time
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function messagesByDate( $startDate, $endDate )
    {
        $startDate = strtotime( $startDate );
        $endDate = strtotime( $endDate );
        $query = [
            'query' => [
                'api' => $this->apiKey,
                'starts_at' => $startDate,
                'ends_at'  => $endDate
            ]
        ];
        $request = $this->client->get( 'api/messages/period',[], $query );
        $response = $request->send();
        return $response->getBody();
    }

    /**
     * Retrieve messages sent to a specific network
     *
     * @param $network - (globe, smart, smart_others)
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function messagesByNetwork( $network )
    {
        $query = [
            'query' => [
                'api' => $this->apiKey,
                'telco' => $network,
            ]
        ];
        $request = $this->client->get( 'api/messages/network', [], $query );
        $response = $request->send();
        return $response->getBody();

    }

}