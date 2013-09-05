# API Reference

## Authentication

Authentication is done by adding your clientId and your hashed key as parameters to the resource URI.

    ?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Responses**

*On success*

JSON, as specified in the resource documentation.

*On failure*

*HTTP Code:* 403

```js
{
    "error":"Access to resources have been denied."
}
```


## Receivers

### GET /receivers

Retrieves all receivers that are registered for a client. This api call does not require authentication.

**Resource URL**

    https://connect.elefunds.de/receivers/:clientId

**Parameters**

The clientId that requests the receivers.

**Example Request**

    https://connect.elefunds.de/receivers/1001

**Response**

*HTTP Code:* 200
```js
{       
   "receivers":{
      "de":[
         {
            "images":{
               "horizontal":{
                  "small":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01h.png",
                  "large":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01h.png",
                  "medium":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01h.png"
               },
               "vertical":{
                  "small":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01v.png",
                  "large":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01v.png",
                  "medium":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01v.png"
               }
            },
            "description":"A description of the receiver.",
            "id":101,
            "name":"The name of the receiver."
         }
      ]
   }
}
```

## Donations
### POST /donations

Creates a single or multiple donation(s). The JSON must be sent as raw POST body.

**Resource URL**

    https://connect.elefunds.de/donations

**Parameters**

Only the basic authentication.

**Example Request**

    POST https://connect.elefunds.de/donations/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

*Example POST body*

```js
[
    {
        "foreignId":125,
        "donationTimestamp":"2012-10-10T15:57:40+0200",
        "donationAmount":80,
        "receivers":[
            101,
            204
        ],
        "receiversAvailable":[
            101,
            204,
            311
        ],
        "grandTotal":1000,
        "donationAmountSuggested":30,
        "donator":{
            "email":"christian@elefunds.de",
            "firstName":"Christian",
            "lastName":"Peters",
            "streetAddress":"Sch\u00f6nhauser Allee 124",
            "zip":10243,
            "city":"Berlin",
            "countryCode":"en",
            "company":"elefunds"
        }
    }
]
```

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

```js
{
    "success": true,
    "message": "2 of 2 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
}
```

### PUT /donations/:foreignIds

Sets the status of one or multiple donation(s) to completed. You should use the this call once an order has been finalized. For example, when the money has been received and the product was shipped.

**Resource URL**

    https://connect.elefunds.de/donations/:foreignIds
    
**Parameters**

- `foreignIds` Unique or comma separated list of unique identifier(s) of the made donation(s). The origin of the foreignId is up to you (ie. order ID, transaction ID), but it has to be a unique identifier for each donation.

**Example Request**

    PUT https://connect.elefunds.de/donations/123,124,125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

```js
{
    "success": true,
    "message": "3 of 3 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
}
```

### DELETE /donations/:foreignIds

Cancels a single or multiple donations identified by a comma separated list of foreignIds that you provided during the initial post. You should use the call if an erroneous donation has been made or an order was canceled.

**Resource URL**

    https://connect.elefunds.de/donations/:foreignIds

**Parameters**

- `foreignIds` Unique or comma separated list of unique identifier(s) of the made donation(s). The origin of the foreignId is up to you (ie. order ID, transaction ID), but it has to be unique for each donation.

**Example Request**

    DELETE https://connect.elefunds.de/donations/123,124,125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

```js
{
    "success": true,
    "message": "3 of 3 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
}
```

### ~~POST /donations/delete~~ ** DEPRECATED ** (Use [DELETE /donations/:foreignIds](#delete-donationsforeignids) instead)

Deletes multiple donations. The JSON must be sent as raw POST body and must contain an array of foreignIds.

**Resource URL**

    https://connect.elefunds.de/donations/delete

**Parameters**

Only the basic authentication.

**Example Request**

    https://connect.elefunds.de/donations/delete/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

*Example POST body*

```js
[
    125,123
]
```

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

```js
{
    "success": true,
    "message": "2 of 2 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
}
```
