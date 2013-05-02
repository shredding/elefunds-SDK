## CHANGELOG

### [VERSION 1.2.3](https://github.com/elefunds/elefunds-SDK/tree/v1.2.3)

- PHP SDK moved to its own repository: [elefunds-PHP](https://github.com/elefunds/elefunds-PHP)

---

### [VERSION 1.2.2](https://github.com/elefunds/elefunds-SDK/tree/v1.2.2)

#### Breaking Changes

- Form names submitted during the donation process changed

### Features

- [RequestHelper](https://github.com/elefunds/elefunds-SDK/blob/master/PHP/Template/Shop/Helper/RequestHelper.php) added to handle and verify said input data
- Javascript saves donation data to the `#elefunds` element for easier access
- Javascript events `elefunds_enabled`, `elefunds_enabled` and `elefunds_donationChange` are triggered on the `#elefunds` element

---

### [VERSION 1.2](https://github.com/elefunds/elefunds-SDK/tree/14da25585541f6c5700544b12aaa614b2ebb97d7)

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

---

### [VERSION 1.1](https://github.com/elefunds/elefunds-SDK/tree/d08561dd3856b4a33a854248d710513dd587da6c)

#### Breaking Changes

- rewrite of the share API

In order to use the share API, you have to exchange the Facebook and Twitter Share Links. If you are using the SDK and it's
Templates, just update and you're good to go.


#### Features

- added donation receipt to API as well as to the templates
- added multiple HTML Templates for basic usage without PHP SDK
