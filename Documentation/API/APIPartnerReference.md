# API Reference for Partners

## Client Registration
### POST /register/client

Registers a new, pending client with the API. The JSON must be sent as raw POST body.

**Resource URL**

    https://connect.elefunds.de/register/client

**Example Request**

    POST https://connect.elefunds.de/register/client

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

**Response**

*HTTP Code:* 200

New client registrations are marked as pending and will be notified by email as soon as their application has been reviewed / accepted.

```js
{
    "success": true,
    "message": "The client has been registered successfully."
}
```
