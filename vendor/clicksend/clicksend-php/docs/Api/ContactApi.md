# ClickSend\ContactApi

All URIs are relative to *https://rest.clicksend.com/v3*

Method | HTTP request | Description
------------- | ------------- | -------------
[**listsContactsByListIdAndContactIdDelete**](ContactApi.md#listsContactsByListIdAndContactIdDelete) | **DELETE** /lists/{list_id}/contacts/{contact_id} | Delete a contact
[**listsContactsByListIdAndContactIdGet**](ContactApi.md#listsContactsByListIdAndContactIdGet) | **GET** /lists/{list_id}/contacts/{contact_id} | Get a specific contact
[**listsContactsByListIdAndContactIdPut**](ContactApi.md#listsContactsByListIdAndContactIdPut) | **PUT** /lists/{list_id}/contacts/{contact_id} | Update specific contact
[**listsContactsByListIdGet**](ContactApi.md#listsContactsByListIdGet) | **GET** /lists/{list_id}/contacts | Get all contacts in a list
[**listsContactsByListIdPost**](ContactApi.md#listsContactsByListIdPost) | **POST** /lists/{list_id}/contacts | Create new contact
[**listsCopyContactPut**](ContactApi.md#listsCopyContactPut) | **PUT** /lists/{from_list_id}/contacts/{contact_id}/copy/{to_list_id} | Copy contact to another list
[**listsRemoveOptedOutContactsByListIdAndOptOutListIdPut**](ContactApi.md#listsRemoveOptedOutContactsByListIdAndOptOutListIdPut) | **PUT** /lists/{list_id}/remove-opted-out-contacts/{opt_out_list_id} | Remove all opted out contacts
[**listsTransferContactPut**](ContactApi.md#listsTransferContactPut) | **PUT** /lists/{from_list_id}/contacts/{contact_id}/transfer/{to_list_id} | Transfer contact to another list


# **listsContactsByListIdAndContactIdDelete**
> string listsContactsByListIdAndContactIdDelete($list_id, $contact_id)

Delete a contact

Delete a contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$list_id = 56; // int | List ID
$contact_id = 56; // int | Contact ID

try {
    $result = $apiInstance->listsContactsByListIdAndContactIdDelete($list_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsContactsByListIdAndContactIdDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **list_id** | **int**| List ID |
 **contact_id** | **int**| Contact ID |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsContactsByListIdAndContactIdGet**
> string listsContactsByListIdAndContactIdGet($list_id, $contact_id)

Get a specific contact

Get a specific contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$list_id = 56; // int | Your contact list id you want to access.
$contact_id = 56; // int | Your contact id you want to access.

try {
    $result = $apiInstance->listsContactsByListIdAndContactIdGet($list_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsContactsByListIdAndContactIdGet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **list_id** | **int**| Your contact list id you want to access. |
 **contact_id** | **int**| Your contact id you want to access. |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsContactsByListIdAndContactIdPut**
> string listsContactsByListIdAndContactIdPut($list_id, $contact_id, $contact)

Update specific contact

Update specific contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$list_id = 56; // int | Contact list id
$contact_id = 56; // int | Contact ID
$contact = new \ClickSend\Model\Contact(); // \ClickSend\Model\Contact | Contact model

try {
    $result = $apiInstance->listsContactsByListIdAndContactIdPut($list_id, $contact_id, $contact);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsContactsByListIdAndContactIdPut: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **list_id** | **int**| Contact list id |
 **contact_id** | **int**| Contact ID |
 **contact** | [**\ClickSend\Model\Contact**](../Model/Contact.md)| Contact model |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsContactsByListIdGet**
> string listsContactsByListIdGet($list_id, $page, $limit)

Get all contacts in a list

Get all contacts in a list

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$list_id = 56; // int | Contact list ID
$page = 1; // int | Page number
$limit = 10; // int | Number of records per page

try {
    $result = $apiInstance->listsContactsByListIdGet($list_id, $page, $limit);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsContactsByListIdGet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **list_id** | **int**| Contact list ID |
 **page** | **int**| Page number | [optional] [default to 1]
 **limit** | **int**| Number of records per page | [optional] [default to 10]

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsContactsByListIdPost**
> string listsContactsByListIdPost($contact, $list_id)

Create new contact

Create new contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$contact = new \ClickSend\Model\Contact(); // \ClickSend\Model\Contact | Contact model
$list_id = 56; // int | List id

try {
    $result = $apiInstance->listsContactsByListIdPost($contact, $list_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsContactsByListIdPost: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **contact** | [**\ClickSend\Model\Contact**](../Model/Contact.md)| Contact model |
 **list_id** | **int**| List id |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsCopyContactPut**
> string listsCopyContactPut($from_list_id, $contact_id, $to_list_id)

Copy contact to another list

Copy contact to another list

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$from_list_id = 56; // int | List ID for list that contains contact.
$contact_id = 56; // int | Contact ID
$to_list_id = 56; // int | List ID for list you want to copy the contact to.

try {
    $result = $apiInstance->listsCopyContactPut($from_list_id, $contact_id, $to_list_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsCopyContactPut: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **from_list_id** | **int**| List ID for list that contains contact. |
 **contact_id** | **int**| Contact ID |
 **to_list_id** | **int**| List ID for list you want to copy the contact to. |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsRemoveOptedOutContactsByListIdAndOptOutListIdPut**
> string listsRemoveOptedOutContactsByListIdAndOptOutListIdPut($list_id, $opt_out_list_id)

Remove all opted out contacts

Remove all opted out contacts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$list_id = 56; // int | Your list id
$opt_out_list_id = 56; // int | Your opt out list id

try {
    $result = $apiInstance->listsRemoveOptedOutContactsByListIdAndOptOutListIdPut($list_id, $opt_out_list_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsRemoveOptedOutContactsByListIdAndOptOutListIdPut: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **list_id** | **int**| Your list id |
 **opt_out_list_id** | **int**| Your opt out list id |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **listsTransferContactPut**
> string listsTransferContactPut($from_list_id, $contact_id, $to_list_id)

Transfer contact to another list

Transfer contact to another list

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new ClickSend\Api\ContactApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$from_list_id = 56; // int | List ID for list that contains contact.
$contact_id = 56; // int | Contact ID
$to_list_id = 56; // int | List ID for list you want to transfer contact to.

try {
    $result = $apiInstance->listsTransferContactPut($from_list_id, $contact_id, $to_list_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactApi->listsTransferContactPut: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **from_list_id** | **int**| List ID for list that contains contact. |
 **contact_id** | **int**| Contact ID |
 **to_list_id** | **int**| List ID for list you want to transfer contact to. |

### Return type

**string**

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

