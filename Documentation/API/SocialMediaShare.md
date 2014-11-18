## Social Media Share

After a checkout with a donation, the user should be presented with the Facebook / Twitter share implementation on the checkout success page. The social media share presents the user with a friendly message to share his good deed, with a backlink to the shop, on Facebook / Twitter. To implement the social media share on the success page, the links behind the Facebook / Twitter buttons contain several parameters in the following format:

    http://connect.elefunds.de/:service/:shopId/:receivers/?language=:countryCode

* **:service** `facebook` or `twitter`
* **:clientId** the ID of the client/shop
* **:receivers** a comma separated list of the receiver IDs that the customer donated to
* **:countryCode** (optional) the country code for the language to be used in the social media share (de by default)

### Example

The client with the ID `1001` has a customer who donated to the receiver ID's `4`, `5`, and `6`. The checkout success page would provide the following links to facebook and twitter respectively:

```
http://connect.elefunds.de/facebook/1001/4,5,6/
http://connect.elefunds.de/twitter/1001/4,5,6/
```
