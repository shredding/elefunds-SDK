# Getting Started

The elefunds API is what we like to call *A better world as a service*.

It enables you to bake electronic fundraising directly into your applications.

Whether it's a shop; a mobile app where you give away a few bucks every time a user checks in at your café; a ticket
system where your company donates any time your employees finishes a milestone; or an open source system, where you enable agencies to contribute a donation every time a bug is resolved.

We build the elefunds API to handle massive amounts of concurrent requests and it is aggressively pushed for speed. It
resides upon a powerful backend, that treats donation, receiver, campaign management and much more with pleasure and is accompanied with a rich social media integration of the most successful social networks around.

And since simplicity is power in IT, this massive amount of business intelligence is at your service via a remarkably
elegant API.

You can access everything via two URIs:

    https://connect.elefunds.de/receivers
    https://connect.elefunds.de/donations

## Client Libraries

Sounds simple, right? But can it get any simpler? Yes, it can. Check out our client libraries!

Currently available is a full featured Library for PHP. It supports full access to the elefunds API, ships with a powerful templating system, prebuilt implementations for shops, elegant class abstraction, features persistence layer integration - and that's just an overview!

You're system is written in PHP? Skim through this guide and be sure to grab a copy of our PHP client library.


## Prerequisites

In order to connect to the API, all you need is an API-Key and your client ID. For testing purposes, you are free to use
one of our test accounts.

> Donations that are pushed to the test accounts are not creating a real payment request and can be used for all testing
> purposes.
>
> However, you are not allowed to use the API with a test account in a production environment or claim that the delivered
> receivers in the test accounts are somehow associated with your business or services.

Here are some sample clientIDs with their API keys.

    clientId                API Key

    1001                    ay3456789gg234561234
    1002                    vXqolz79vY80Bms037cM
    1003                    TDls1Dj34Dc7KHpfkzbA

The entire list is included in the `Documentation` folder as `testaccounts.csv`


## Connecting to the API

There are basically two scenarios when you may want to connect to the API:

* When you are interested in the potential donation receivers for your app.
* When you want to tell elefunds about a donation.

For all requests against the API, you need your clientId as well as a hashedKey - that is a sha1 version of your API Key
with your cliendId as SALT. Hence you need to concatenate the clientId with your API Key. Here are some examples for
various programming languages and the clientId 1001:

    # PHP
    $hashedKey = sha1(1001 . 'ay3456789gg234561234');

    # Ruby
    require 'digest/sha1'
    hashedKey = Digest::SHA1.hexdigest 1001.to_s() + 'ay3456789gg234561234'

    # node.js
    var crypto = require('crypto')
      , shasum = crypto.createHash('sha1');

    shasum.update(1001 + 'ay3456789gg234561234');
    var hashedKey = shasum.digest('hex');

    # Unix Shell
    hash=$(echo -n '1001ay3456789gg234561234' | openssl sha1 -hex)

Authenticating against the API is as easy as adding these values as query parameters to your request.

### Retrieving Receivers

Receivers are organizations that may benefit from donations. They are picked, looked after and verified by the elefunds
foundation. Every donation is forwarded to 100% to the respective receiver.

You may want to use the receiver information to select a recipient for the to-be-made donation. This can be done via a
roundup suggestion in your shop, via an admin panel of plugin or even random.

In order to retrieve receivers that are registered to your account, you can just call the `/receivers` resource with
your clientId and hashed key.

An example for the clientId of 1001 would be:

    GET https://connect.elefunds.de/receivers/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

This returns, based on the nature of your client arrangement, a JSON in the following format:

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

The bread and butter of this JSON is the receivers object, which contains information about all receivers that are linked to your account. In this case, we have access to exactly one receiver.

Note that the object may contain multiple languages - these will be german and english by default, but we will add more in the future. Inside of each language is an array with the information about the receiver:

- the **images** that are associated with the receiver in various sizes, horizontal and vertical
- the receivers **description**
- their **id**
- the **name**

You can use any of the given information to build a nice UI for your users. However, the id is essential if a donation
is finally made, as it identifies who will benefit from the donation. Thus, you must ensure, that the id is available,
once you send a donation back to the API. This can be achieved by persisting them to the database or make them part of the session during a checkout process. Have a look at our client libraries for more information and examples.

Our API is very fast, optimized for massive amounts of concurrent requests and build to be scalable as our business grows. Therefore, you can easily request the receivers every time you need them. However, if the nature of your site requires to cache the receivers on your server side, you are free to do so. Just make sure, that the cache expires within the valid time.

The valid time depends on your settings and the agreement for your clientId. If you are participating in campaigns, you may have a dynamic and shorter valid time; some agreements may have longer valid times and can therefore cache the receivers for a very long time.


## Sending donations

A sent donation is basically a notification of the API that a donation has been made. Using the API to report a donation is a binding transaction, with the obligation to fulfill said donation to the elefunds foundation. Depending on your contract details, this means that we will be sending you an invoice or collecting the donation amount via your registered payment method.

> Reminder: Using a test account is completely free and not associated with any obligations.

The donation should be sent in form of a JSON object to the API. For example:

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
               "countryCode":"en"
          }
       }
    ]

As you can see, the donation itself is wrapped in an array. Hence, you are able to send multiple donations via one API
call. This can be useful if you decide to collect multiple donations (or maybe wait until some payment from a customer
of yours arrived) and are therefore not sending back the donation at the time it was made.

The included information is:

- a **foreignId**, that must be a unique identifier such as an order id in your system; it'll be associated with the donation in the invoice and it will be used if a customer requests a contribution receipt.
- the **donationTimestamp** as [ISO 8601](http://en.wikipedia.org/wiki/ISO_8601 "Wikipedia Entry") Timestamp, note that this should be the timestamp, when the donation was actually made (not the time when it was send back to us).
- the **donationAmount**, this is the total donation amount that will be invoiced and split among the receivers. The donationAmount is given in the smallest unit available in your currency (ie. cents).
- the **receivers** is an array with receiver id's. The donation amount will be split in equal parts among these.
- **receiversAvailable** (optional) if you are building an app, where someone can decide who will receive the
donation, you can send this information along.
- **grandTotal** (optional) is the sum of the overall transaction (including the donation).
- **donationAmountSuggested** (optional) is the donation amount that was presented to the user (if applicable).
- **donator** (optional) is an object with information about a donator. This is only needed when the user wants to receive a donation receipt and has therefore agreed to have his information shared. If so, all you have to do is to submit the user data and the elefunds foundation will send the user a donation receipt.

Sending the donation is as easy as doing a POST request to `/donations` and sending the JSON as a raw POST body.

The URL for the client with the id of 1001 would be:

    https://connect.elefunds.de/donations/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653


## Completing Donations

To facilitate the donation and transaction process, we encourage users of our API to notify us when the status of a donation is "complete". The definition of a "completed" donation depends on the context of the transaction, but generally speaking, a donation should be marked as "completed" when the money of the transaction has been received and the likelihood of a chargeback is slim to none. For example, you run an ordinary shop where a customer places an order, including a donation, and pays using a bank transfer. The donation should be set to completed when the money is received and the order is set to completed.

On the other hand, if you run a online shoe store that has a return rate of over 50% on orders, the donation shouldn't be set to complete as soon as the money as received. Instead, the donation status should stay on pending until you feel confident that the order will not be returned by the customer.

The other extreme would be an online platform that only sells digital content, such as music. Once a customer buys digital content, donates and completes his order with PayPal, the transaction is completed immediately and the donation can be set to complete right away.

To set a donation to "completed", send a PUT request to the API. For example, a completed donation with the foreignId of 125 would look like this:

    PUT https://connect.elefunds.de/donations/125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653
    
Multiple donations can also be completed at once, by sending a comma seperated list of foreignIds in the same PUT request:

    PUT https://connect.elefunds.de/donations/123,124,125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653
    
Donations that are not set to "completed" will stay "pending" until the end of the month, at which point a line-up of pending donations will be sent to you for confirmation. Any uncanceled donations after said date will be billed as completed.


## Cancelling Donations

In the case that something went wrong with the order (ie. the customer failed to pay, the order got cancelled, etc.) you also have the option to roll back and cancel an already posted donation. You can do so by calling the API with a DELETE request to the '/donation/:foreignId' resource.

An example for the foreignId of 125 looks like this:

    DELETE https://connect.elefunds.de/donations/125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653

You can cancel multiple donations as well, by sending a comma separated list of foreignIds to the same URL.

Example:

    DELETE http://connect.elefunds.de/donations/123,124,125/?clientId=1001&hashedKey=eb85fa24f23b7ade5224a036b39556d65e764653


## Summary

We are super excited about what you are going to build with the elefunds API!

If you have any questions, suggestions, get stuck implementing, have a feature request or ideas on how to improve our
product, or simply want to get in touch with us, please do not hesitate to contact us at `hello@elefunds.de`.