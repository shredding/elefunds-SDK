# API Reference for Partners

## Client Registration
### POST /register/client

Registers a new, pending client with the API. The JSON must be sent as raw POST body.

**Resource URL**

    https://connect.elefunds.de/register/client

**Parameters**

Only the basic authentication.

**Example Request**

    POST https://connect.elefunds.de/register/client/?partnerId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

*Example POST body*

```js
{
    translations: {
        de: {
            name: 'Client Shop ABC',
            url: 'http://www.shop-abc.de'
        }
    },
    contacts: [
        {
            gender: 'm',
            firstName: 'Max',
            lastName: 'Mustermann',
            email: 'max@mustermann.de',
            address: 'Musterstr. 1',
            zip: '12345',
            city: 'Berlin',
            country: 'DE'
        }
    ],
    receivers: [1,2,3]
}
```

The above listed attributes are all required.

*gender*: 'm' for male or 'f' for female  
*receivers*: an array of receiver IDs as selected by the client from the pool of [receivers](https://github.com/elefunds/elefunds-SDK/blob/master/Documentation/API/APIReference.md#get-receivers)


**Response**

*HTTP Code:* 200

New client registrations are marked as pending and will be notified by email as soon as their application has been reviewed / accepted.

```js
{
    "success": true,
    "message": "The client has been registered successfully."
}
```
