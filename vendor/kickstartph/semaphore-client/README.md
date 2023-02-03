# Semaphore Client

Semaphore Client is a PHP wrapper for the Semaphore SMS API'

# Table of Contents
 - [Installation](#installation)
 - [Basic Usage](#basic-usage)

## Installation

```sh
composer require kickstartph/semaphore-client
```

## Basic Usage

### Sending Messages
```php
<?php
    require_once( 'vendor/autoload.php' );

    use Semaphore\SemaphoreClient;
    $client = new SemaphoreClient( '<YOUR_API_KEY>', '<OPTIONAL_SENDER_ID' );
    echo $client->send( '09991234567', 'Your message' );
```
The sender ID can be overridden through the client send command as well:
```php
<?php

    echo $client->send( '09991234567', 'Your message', '<NEW_SENDER_ID>' );
```

Bulk messages (to up to 20 numbers ) can also be sent through the same API call by setting the bulk option to true:

```php
    echo $client->send( '09991234567,09997654321,', 'Your message', null, true );
```


### Account Balance
```php
    $client = new SemaphoreClient( '<YOUR_API_KEY>', '<OPTIONAL_SENDER_ID' );
    echo $client->balance();
```
This call should return something along the lines of:
```json
{
    "status": "success",
    "balance": 72,
    "account_status": "Allowed"
}
```

### Message Status
The encoded_id of the message is returned as a response when a message is sent
```php
    echo $client->message( <ENCODED_ID> );
```

### Retrieving Messages
You can retrieve up to 100 sent messages at a time, with support for pagination by passing the optional $page variable:

```php 
    echo $client->messages( 2 ); //Will return the results for page 2 of sent messages
```

Messages by date range:
```php 
    echo $client->messagesByDate( 'january 1 2015', 'feb 1 2015' ); //Use any date format str_to_time() supports 
```
Messages by telco network:
```php 
    echo $client->messages( 'globe' ); //Returns all messages sent to recipients on the Globe network
```


