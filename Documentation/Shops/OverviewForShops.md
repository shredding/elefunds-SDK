# Overview for Shops

This guide is intended for all shops wanting to connect to the API and develop their own elefunds donation module implementation for their checkout.
This guide also assumes you've already read through the Getting Started Guide, as it covers the general API usage and principles.

> **Note**: If you're system is running on PHP, we strongly recommend you use the [PHP-SDK](https://github.com/elefunds/elefunds-PHP).
> It takes 70% of the work off your hands, was test driven developed and is used in production environments, such as Shopware and Magento.
> It's also a good reference to use when building your own solution.

## Overview

Before getting started with the implementation, we would like to get two misconceptions out of the way:

- We do not transfer money. Instead, donations are reported to the API and we invoice the donations at the end of the month
- You do not have to create donation receipts or keep track of the donations made in your shop, as we will take care of that for you

Writing an implementation for your shop system can be summed up in the following steps:

1. Create a configuration file
2. Display the elefunds JavaScript module in the checkout
3. Add any donations made to the order
4. Forward incoming donations to the API
5. Display the social media share view after the checkout
6. Add a short disclaimer regarding the donation to the invoice
7. Observe status changes and report them to the API

The good news is that a good chunk of the logic - such as retrieving the receivers from the API,
displaying the module and much more is done by the elefunds JavaScript frontend.


## 1. Create a configuration file

The first part of the process should a configuration file that contains vital information for the operation.
This configuration should include:

* your `clientId`
* your `apiKey`
* theme / color options
* various DOM selectors (that we will cover in the next step)

It is a good idea to make at least the `clientId` and the `apiKey` configurable in the shop backend by the user!

## 2. Display the elefunds JavaScript module in the checkout

We provide an entire frontend JavaScript application, that takes care of displaying the module, the receivers,
and various logic for you. The latest version can always be found here:

https://43ce0697b91280cbef31-14c96824618f1f6e4d87350d0f98c98a.ssl.cf1.rackcdn.com/static/elefunds.min.js

Just include this file after any other JavaScript libraries you might already be using (jQuery, Prototype, etc.),
preferably at the bottom of the page.

> **Note**: In case your frontend is not built in HTML, please contact us so we can work together on an alternative.

Next, check out the [JavaScriptGuide](JavaScriptFrontend.md). It will explain how to setup and display the module in the checkout.
To be Trusted Shops compatible, we recommend displaying the module *before* the listed positions that make up the shopping cart.


## 3. Add any donations made to the order

Donations that are made in the frontend need to be tied to and saved with an order.
Generally, this is best done by including a "donation item" that is added to the order. The frontend JavaScript supplies
all the necessary POST values to create a donation that can be sent to the API.

Adding the donation as an item to an order helps you and us manage the status of the donation, as we'll explain in the following steps.
Basically, it allows you to easily manage the donation when processed with an ERP system, on invoices, outgoing mails, etc.

> **Note**: the donation must be excluded from all taxes and discounts!


## 4. Forward incoming donations to the API

Once a donation is made, POST the donation to the API's `/donations` endpoint to add it as a pending donation. As you will see in step 7, there are different states a donation can have in the API.

In order to report a state change, you must persist the donation in the database. This can be done by simply saving the
`donation` data along with the order `status` and the order's unique identifier that we refer to as the `foreignId`


## 5. Display the social media share view after the checkout

After a donation has been made, it's time to display the social media share options to the customer to allow him to share
his good deed and promote your shop in the process. The social media view also comes with the JavaScript frontend, so include
the JavaScript file again in your checkout success page, with the adjusted parameters in the `window.elefundsOptions` object as detailed
in the [JavaScript Guide](JavaScriptFrontend.md).


## 6. Add a short disclaimer regarding the donation to the invoice

First of all, we assume that the donation appears on invoices and order emails, because it has been added to the order as additional position in step 3.

However, german legislative requires us to provide a disclaimer regarding the donation on each invoice containing a donation. In order to comply,
please also include the following sentence to the final invoice that is sent to the customer:

```
// English version
Your donation is processed by the elefunds Foundation gUG which forwards 100% to your chosen charities.

// German version
Die Spende wird vereinnahmt für die elefunds Stiftung gUG und zu 100% an die ausgewählten Organisationen weitergeleitet.
Der Kaufbeleg ersetzt keine Spendenbescheinigung im Sinne des Steuerrechts.
```

The common scenario would be to overwrite the invoice template (as we did in Magento) or hook into the PDF generation (as we did in Shopware).
But the solution depends heavily on the system you are implementing for.


## 7. Observe status changes and report them to the API

A donation can have three states in the API. We already introduced the first one **pending**, but there are two more:

* **cancelled**
* **completed**

A donation is cancelled when elefunds can't invoice the donation - e.g.
when an order including a donation has been refunded or if the customer refuses to pay.

A donation is completed when you received the money and the order can't be refunded.

Most shops have internal states that reflect this and all you have to do is hook into the change of said state and report it to the API.


## Summary

That's it! As always, if you have any questions or need some guidance, please don't hesitate to contact us.
We're always to happy to help and learn something along the way.