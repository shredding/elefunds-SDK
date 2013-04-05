PHP Guide - Basics
==================

> If you're not exactly a bookworm, chances are you can get away by referring to the samples in the `Examples` folder, an
> excellent inline information will be there to assist you as well.


Accounts
--------

> Have you already read the general Getting Started guide in the root Documentation folder of this repository?

In order to integrate elefunds into your app, you just need an API key and a Client ID. You can contact us at <hello@elefunds.de> to receive your own authentication keys and get started collecting donations.
In the meantime you can grab a test account from the `testaccounts.csv` in the root's documentation folder!

Let's assume, you choose to use the client ID `1001` with the associated API Key of `ay3456789gg234561234`.


Setup
-----

Create a file named `SampleConfiguration.php` (the name does not really matter), save it in the root folder of the PHP
Library (next to `Facade.php`) and enter the following lines:

    <?php
        require_once dirname(__FILE__) . '/Configuration/DefaultConfiguration.php';

        class Elefunds_SampleConfiguration extends Configuration_DefaultConfiguration {

            protected $clientId = 1001;
            protected $apiKey = 'ay3456789gg234561234';

        }
    ?>

As you can see, we can get started with very little code. We'll dive into the details later, but at the moment this is all that's needed
to setup an app. So let's retrieve some receivers.

Create a file named `sample.php` within the same folder and add the following lines:

    <?php
    require_once dirname(__FILE__) . '/Facade.php';
    require_once dirname(__FILE__) . '/SampleConfiguration.php';

    $facade = new Elefunds_Facade(new Elefunds_SampleConfiguration());
    ?>

Now the facade is up, running and connected to the elefunds API. Let's retrieve some charities.


Receivers
---------

Since elefunds is centered around donations, charities are called receivers - because they *receive* 100% of all made donations.
Add these (and all upcoming) lines before the closing PHP tag:

    try {
        $receivers = $facade->getReceivers();
    } catch (Elefunds_Exception_ElefundsCommunicationException $exception) {

        // The API is not available!
        // Kick in fallback or omit the service if you retrieve in real time
        $message = $exception->getMessage();
    }

    foreach($receivers as $receiver) {
        /** @var  Elefunds_Model_ReceiverInterface $receiver */

        echo '<p data-id="' . $receiver->getId(). '">' . $receiver->getName() . '</p>';
        echo '<img src="' .$receiver->getImage('horizontal', 'medium') . '" alt="' . $receiver->getDescription() .'"/>';
    }

This will render a tiny HTML snippet that displays the receiver's name along with a horizontal-aligned, medium sized image.
Notice that we render the receivers id as a data-attribute and that we use the receivers description for providing an alt-attribute
for the image tag.

> Reminder: The API is really stable and robust, we're talking about best-practices here when we encourage you to watch out
> for errors.

You can use this to create a user-interface for choosing donation receivers. This may be something an administrator does in the backend
(e.g. when a donation is triggered anytime someone does something, like subscribing to a newsletter).

> The library ships with its own templating solution, hence you are not forced to mix everything into a single file. We have a pre-build template
> for shops with more coming soon.


Donations
---------

Now that we are able to retrieve receivers, we want to collect donations and send those back to the API. Let's create a donation object now.

> All setters in the library are chainable.

    $donation = $facade->createDonation()
                       ->setForeignId(125)
                       ->setAmount(25)
                       ->setSuggestedAmount(20)
                       ->setGrandTotal(1005)
                       ->setReceiverIds(array(2, 3))
                       ->setAvailableReceiverIds(array(1001, 1002, 1003))
                       ->setTime(new DateTime())
                       ->setDonator('christian@elefunds.de', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin');


We use the facade to create an instance of the donation. Next, we set some values:

- `foreignId` must be an integer. You are free to add whatever you like here but you must be able to match this foreignId
with the donating process on your site. If you are implementing for a shop, the orderId would be a perfect match.

- `amount` you must set the donated amount.

- `suggestedAmount` is optional. If your service allows the user to adjust their donation, this value is the amount originally suggested as a donation to the user.

- `grand total` is optional. It's the sum of donation plus all invoiced items (like the overall costs of an order). If your service
does not have something like a grand total at all, just omit this.

- `receiverIds` are the ID's of the receivers that are qualified for the donation. The ID is available through `$receivers->getId()`

- `availableReceivers` are optional. If your service gives the user the choice to select from a range of receivers, this array should be filled with all receivers that were available to the user. This will greatly help us to improve our service.

- `time` is when the donation was made. If you do not send the donation directly after the checkout (e.g. to batch-send them at night),
please provide the original time when the donation was made.

- `donator` is the user that made the donation, you can send the information (optionally) along, when you want to provide donation receipts. In
that case, elefunds and its foundation will take care of all the paperwork for you.

That's it, now just send back the donation:

    try {
         $responseMessage = $facade->addDonation($donation);
    } catch (Elefunds_Exception_ElefundsCommunicationException $exception) {
         $message = $exception->getMessage();
    }


If everything went well, you'll retrieve a status code of `200` and a response message:

> 1 of 1 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem.

If you get an error, simply send the donation again later. If you used the `$facade->addDonations()` method to
add multiple donations, you can send them all again later as well - if some of them were saved before something went wrong,
the API is smart enough to sort things out.

At this stage, the donation has a status of `pending` in our API. You can set it to completed, once you got your money by just
sending in the `foreignId`, that you used when adding the donation:

    // One donation:
    $facade->completeDonation($foreignId);

    // Multiple donations:
    $facade->completeDonations(array($foreignId, $anotherForeignId));

If you have to cancel the donation, you can do so by calling the cancel donation method:

    // One donation:
    $facade->cancelDonation($foreignId);

    // Multiple donations:
    $facade->cancelDonations(array($foreignId, $anotherForeignId));

