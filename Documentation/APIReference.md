# API Reference

## Authentication

Authentication is done by adding your clientId and your hashed key as parameters to the resource URI.

    ?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Responses**

*On success*

JSON, as specified in the resource documentation.

*On failure*

*HTTP Code:* 403

    {
        "error":"Access to resources have been denied."
    }


## Receivers

### GET /receivers

Retrieves all receivers that are registered for a client.

**Resource URL**

    https://connect.elefunds.de/receivers

**Parameters**

Only the basic authentication.

**Example Request**

    https://connect.elefunds.de/receivers/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Response**

*HTTP Code:* 200

    {
       "meta":{
          "valid":"2012-12-31T12:00:00"
       },
       "receivers":{
          "de":[
             {
                "images":{
                   "horizontal":{
                      "small":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01h.png",
                      "large":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_02h.png",
                      "medium":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_03h.png"
                   },
                   "vertical":{
                      "small":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_01v.png",
                      "large":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_02v.png",
                      "medium":"https://bbbf9fd0e36d5cb36b93-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/receivers/sample_receiver_03v.png"
                   }
                },
                "description":"A description of the receiver.",
                "id":101,
                "name":"The name of the receiver."
             }
          ]
       }
    }


## Donations POST /donations

Creates multiple donations. The JSON must be sent as raw POST body.

**Resource URL**

    https://connect.elefunds.de/donations

**Parameters**

Only the basic authentication.

**Example Request**

    https://connect.elefunds.de/donations/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

*Example POST body*

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
              "donationAmountSuggested":30
           }
    ]

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

    {
        "success":true,
        "message":"2 of 2 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
    }

## Donations POST /donations/delete

Deletes multiple donations. The JSON must be sent as raw POST body and must contain an array of foreignIds.

**Resource URL**

    https://connect.elefunds.de/donations/delete

**Parameters**

Only the basic authentication.

**Example Request**

    https://connect.elefunds.de/donations/delete/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

*Example POST body*

    [
        125,123
    ]

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

    {
        "success":true,
        "message":"2 of 2 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
    }


## Donation DELETE /donations/:foreignId

Deletes a single donation identified by the foreignId that you provided during the inital post.

**Resource URL**

    https://connect.elefunds.de/donation/:foreignId

**Parameters**

- `foreignId` Unique identifier of the made donation. The origin of the foreignId is up to you (ie. order ID, transaction ID), but it has to be an integer to identify the donation.

**Example Request**

    https://connect.elefunds.de/donation/123/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

**Response**

*HTTP Code:* 200

To improve performance, we update the status of donations and process them later in the background. Hence, you get a
status response as soon as the donation information has been received.

    {
        "success":true,
        "message":"1 of 1 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem."
    }