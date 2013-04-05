## CHANGELOG 
### VERSION 1.0.1

- small bug fixes


### VERSION 1.1

#### Breaking Changes

- rewrite of the share API

In order to use the share API, you have to exchange the Facebook and Twitter Share Links. If you are using the SDK and it's
Templates, just update and you're good to go.


#### Features

- added donation receipt to API as well as to the templates
- added multiple HTML Templates for basic usage without PHP SDK


### VERSION 1.2

#### Breaking Changes

- the `Library` prefix of all pseudo namespaces where removed.
- the `deleteDonation` and `deleteDonations` methods of the elefunds facade are renamed to `cancelDonation` and `cancelDonations`.

#### Features

- donations can be marked as completed via `completeDonation` or `completeDonations` on the facade
- the shop template now supports themes with mulitple colors
- CSS files are now preprocessed with LESS
- the `/bin` folder contains a python script to compile and minify the LESS and JS files (requires lessc and yuicompressor)

### Nice to know

- for convenience, shared logic between `CheckoutConfiguration` and `CheckoutConfiguration` have been moved to `ShopConfiguration`.
