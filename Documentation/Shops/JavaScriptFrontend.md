# JavaScript Guide for Shops
The SDK includes a JavaScript application which is fully responsible for rendering the necessary views (module / social-media-share),
and handling the logic independent of the system you are developing for.

We highly recommend you to use the JS-Application as it takes the heavy frontend lifting from you and let's you concentrate on the backend.

> We appreciate your work and interest in the elefunds API. Whenever you need assistance, found a bug or just want to get in touch, just drop
> us a line: hello@elefunds.de

## Overview
Include the following script on your checkout page:

https://43ce0697b91280cbef31-14c96824618f1f6e4d87350d0f98c98a.ssl.cf1.rackcdn.com/static/elefunds.min.js

Configuring and setting up the JavaScript-Application is pretty straight forward. All you need to do is provide is a simple JSON-Object.

The passed parameters handle which view to show (module / social media),
which total to use to calculate the suggested donation and the grand total including the donation,
and many other configuration-steps like skinning and look.

Take a look at the full list of parameters you may pass to the app.
Not all of them are necessary and fall back to their default value if not provided.

```javascript
window.elefundsOptions = {

    //The view to render.
    //Values: 'module', 'socialMediaShare'
    //Default: 'module'
    view: "module",

    //Design options for the module view.
    skin: {

        //Values: 'dark', 'light'
        //Default: 'light'
        theme: 'light',

        //The color to use for highlighted elements, such as the call-to-action button and + button.
        //Values: all hexadecimal color-values
        //Default: '#00efa2'
        color: '#00efa2',

        //Layout of the module. Flexible will adapt to the width of your container.
        //Values: 'small', 'medium', 'large', 'flexible'
        //Default: 'flexible'
        dimensions: 'flexible',

        //Orientation of the receiver logos.
        //Values: 'horizontal', 'vertical'
        //Default: 'horizontal'
        orientation: 'horizontal'
    },

    //Show the option for donors to receive a donation receipt.
    //Values: bool
    //Default: false
    offerDonationReceipt: true,

    //Currency symbol to use.
    //Values: any currency symbol
    //Default: '€'
    currency: '€',

    //Values: any delimiter symbol
    //Default: ','
    currencyDelimiter: ',',

    //The order total to round up (in cent)
    // *Required*
    sumExcludingDonation: 1995,

    //A selector for the form to be submitted needs to be provided. Values required by the module will be copied here.
    //Values: any DOM selector (class, element, id)
    // *Required*
    formSelector: '#donation-form',

    //ClientId
    // *Required*
    clientId: 1001,

    //ForeignId, or unique identifier for the transaction
    // *Required*
    foreignId: 'QWE123',

    //If a grand total is shown, a DOM selector needs to be provided to update the total when the module is activated
    //Values: any DOM selector (class, element, id)
    totalSelector: '#grand-total',

    //Country code for the user
    //Values: 'de', 'en'
    //Default: 'en'
    countryCode: 'en',

    // You have a couple of different choices to show the donation amount in the order summary.
    // If you have a table-like layout containing the shipping amount, tax, etc. with a row for each position,
    // you can define the following key (rowContainer) to copy and append the enitre row to the order summary.
    // Alternatively, you can just set the rowLabel and rowValue to be copied individually.

    //Define a row to be copied in the order summary list. An item listing like the shipping or tax is usually a good choice.
    //If your checkout summary layout doesn't have the label and value in the same element, leave this undefined and only provide the rowLabel and rowValue keys.
    //Values: any DOM selector (class, element, id)
    rowContainer: '#shipping-row',

    // Additionally, you have the option to set the following boolean flag. Only set this if rowContainer is defined.

    //Define the following boolean flag if the following rowLabel and rowValue elements are pre-defined (ie. they are already in the template and don't need to be copied).
    //This is helpful, for example, when your template doesn't seperate the label and value into different elements by default, but you're able to define them manually for the elefunds donation.
    //Values: bool
    //Default: false
    rowContainerInline: false,

    //If the rowContainer is set, the element you define here will be looked up relative to the rowContainer element. //Otherwise, the element defined here will be cloned and appended after itself with the donation label as its content (elefunds donation)
    //Values: any DOM selector (class, element, id)
    rowLabel: '#lfnds-row-label',

    //If the rowContainer is set, the element you define here will be looked up relative to the rowContainer element. //Otherwise, the element defined here will be cloned and appended after itself with the donation amount as its content (ie. 12.00 €)
    //Values: any DOM selector (class, element, id)
    rowValue: '#lfnds-row-value'
}
```


## 1. Displaying the module view

The module view is the main view of the app and the view you want to display in your checkout / transaction process.
To get the module running on your desired page, simply follow these steps:

1. Create a new html-element in your template with the id `#elefunds`. The module will automatically append itself to this element, so make sure it exists.
2. Include the JavaScript file in your page - **after** any libraries you might have are including (jQuery, Prototype, etc.)
3. Provide the JSON-object (as shown above) in a global variable called `window.elefundsOptions` on the page.

The module view requires all values marked with **&nbsp;\*Required\*&nbsp;**, whereas the others will use their default value if not provided.


## 2. Using the donation values in the backend

With the `formSelector` defined in the module view, all the necessary values will be available in your form and then available for POST-processing.
If you're running on PHP, you will want to use our PHP SDK. If not, the following values were appended to your form:

* `elefunds_agree` Checks if the module is active (true / false)
* `elefunds_receivers` Checked receivers as Ids, separated by comma (for example: *1,2,3*).
* `elefunds_suggested_round_up` The calculated suggested roundup in cent.
* `elefunds_donation_cent` The chosen donation-amount in cent
* `elefunds_receipt` Checks if donation-receipt is checked (true / false)
* `elefunds_receiver_names` Names of the selected receivers, separated by comma (for example: *Example 01,Example 02,Example 03*)
* `elefunds_available_receivers` Ids of the shown receivers, separated by comma (for example: *4,5,6*)


## 3. Display the socialMediaShare view

The social media-view is just as easy to display and requires even less options.

1. Create a new html-element in your template with the id `#elefunds-share`
2. Include the JavaScript file in your page - **after** any libraries you might already be including (jQuery, Prototype, etc.)
3. Provide the JSON-object (as shown above) in a global variable called `window.elefundsOptions` on the page.

The only values required in the JSON object are:

* `view: 'socialMediaShare'` to show the SocialMediaShare
* `foreignId: xxx` must be set to the transaction's unique foreignId

The other properties used in the social media view will be fetched from the SessionStorage which is automatically filled by the module view.


## Error handling

When the module is not shown or doesn't act as expected,
the first step should be checking your console in the browser.
The application fires warnings when something goes wrong and tells you where the error appears.

If you can not fix the error you can always contact us!

## Summary

Congratulations, you're done!

A good idea might be to contact the elefunds team (if you haven't already) so we can come together and promote your solution and assist you on getting the module up and running in a community store. We can also host it via our GitHub account and offer additional support and testing.

Thank you for taking your time, we hope you had fun along the way!
