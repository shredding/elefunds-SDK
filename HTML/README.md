# HTML: Basic Template

Included in the SDK is a basic HTML template which can be used, for example, in the checkout process of your shop. It contains all the necessary CSS, JavaScript and HTML elements to get started.

![elefunds](https://b0bec35ac9f01a5bd9a6-1c454ef842b6544cfc5c0d520d1538d7.ssl.cf1.rackcdn.com/mockup.png)

## JavaScript

The template relies on jQuery and uses the included [TipTip](https://github.com/drewwilson/TipTip) plugin for the description tooltips. To use the template, simply provide the following parameters in the elefunds options object:

    total: 1880,
    roundSumContainer: $('#elefunds_round_sum_container'),
    roundSum: $('#elefunds_round_sum'),
    decimal: '.',
    decimalAlt: ','
    
`total` is the final sum of the transaction (before the donation)
`roundSumContainer` is the selector of the element containing the round-up sum if the user checked the box
`roundSum` is the selector of the final round-up sum (including the donation)
`decimal` is the delimiter used in current currency
`decimalAlt` is the secondary delimiter used in current currency

## CSS & color themes

The template ships with a variety of color combinations. Basically, the theme is available in `light` and `dark` and comes in the colors `blue`, `green`, `orange`, and `purple`. To define the color scheme you wish to use, embed the corresponding CSS file (ie. elefunds_light_orange.css for `light` and `orange`) and modify the image paths in the HTML template to reflect the color choice.


