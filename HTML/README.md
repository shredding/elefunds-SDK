# HTML: Basic Template

Included in the SDK is a basic HTML template which can be used, for example, in the checkout process of shops. It contains all the necessary CSS, JavaScript and HTML elements to get started.

![elefunds](https://b0bec35ac9f01a5bd9a6-1c454ef842b6544cfc5c0d520d1538d7.ssl.cf1.rackcdn.com/mockup.png)

## JavaScript

The template relies on jQuery and uses the included [TipTip](https://github.com/drewwilson/TipTip) plugin for the description tooltips. To use the template as your frontend, simply provide the following required parameters:

    elefundsVars['grandTotal'] = 1880;
    elefundsVars['roundSumContainer'] = '#elefunds_round_sum_container';
    elefundsVars['roundSum'] = '#elefunds_round_sum';
    elefundsVars['decimal'] = '.';
    
`grandTotal` is the final sum of the transaction (before the donation)
`roundSumContainer` is the selector of the element containing the round-up sum if the user checked the box
`roundSum` is the selector of the final round-up sum (including the donation)
`decimal` is the delimiter used in currency


