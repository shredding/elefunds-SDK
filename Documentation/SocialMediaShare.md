## Social Media Share

After a checkout with a donation, the user should be presented with the Facebook / Twitter share implementation on the checkout success page. The social media share presents the user with a friendly message to share his good deed, with a backlink to the shop, on Facebook / Twitter. To implement the social media share on the success page, the links behind the Facebook / Twitter buttons contain several parameters in the following format:

    http://share.elefunds.de/on/:service/:shopId/:orderID/:receivers/:checksum

* **:service** `facebook` or `twitter`
* **:shopId** the ID of the shop
* **:orderId** the ID of the placed order
* **:receivers** a comma seperated list of the receiver IDs that the customer donated to
* **:checksum** a checksum validating the paramters and authorizing the share from the shop. The checksum is computed as follows:

        sha1( :shopId + :orderId + :receivers + :hashedKey )

### Example

The shop with the ID `1001` (and hashedKey `eb85fa24f23b7ade5224a036b39556d65e764653`) has a customer who checks out with the order ID `99` and donated to the receiver ID's `4`, `5`, and `6`. The checkout success page would provide the following links to facebook and twitter respectively:

```
http://share.elefunds.de/on/facebook/1001/99/4,5,6/c98ec3d33e99bcf08e161519a4aff3fcb7217b77
http://share.elefunds.de/on/twitter/1001/99/4,5,6/c98ec3d33e99bcf08e161519a4aff3fcb7217b77
```

    sha1( 1001994,5,6eb85fa24f23b7ade5224a036b39556d65e764653 ) == c98ec3d33e99bcf08e161519a4aff3fcb7217b77