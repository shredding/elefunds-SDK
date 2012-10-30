PHP Guide - Basics
==================

> If you're not exactly a bookworm, chances are you can get away by referring to the samples in the `Examples` folder, an
> excellent inline information will be there to assist you as well.


Accounts
--------

> Have already read the general Getting Started in the root Documentation folder of this repository?

In order to bake electronic fundraising into your app, you just need an API Key and a Client ID. You'll get one, once we have
settled a business contract. In the meantime you can grab a test account from the `testaccounts.csv` in the same folder!

Let's assume, you choose to use the client ID `1001` with the associated API Key of `ay3456789gg234561234`.


Setup
-----

Create a file named `SampleConfiguration.php` (the name does not really matter), save it in the root folder of the PHP
Library (next to `Facade.php`) and enter the following lines:

    <?php
        require_once dirname(__FILE__) . '/Configuration/DefaultConfiguration.php';

        class Library_Elefunds_SampleConfiguration extends Library_Elefunds_Configuration_DefaultConfiguration {

            protected $clientId = 1001;
            protected $apiKey = 'ay3456789gg234561234';

        }
    ?>

As you can see, we get away with very little code. We'll dive into the details, but for the moment this is everything needed
to setup an app. So let's retrieve some receivers.

Create a file named `sample.php` within the same folder and add the following lines:

    <?php
    require_once dirname(__FILE__) . '/Facade.php';
    require_once dirname(__FILE__) . '/SampleConfiguration.php';

    $facade = new Library_Elefunds_Facade(new Library_Elefunds_SampleConfiguration());
    ?>

Now the facade is up, running and connected to the elefunds API. Let's retrieve some charities.


Receivers
---------

Since elefunds is centered around donations, charities are called receivers - because they *receive* 100% of all made donations.
Add these (and all upcoming) lines before PHP's closing tag:

    try {
        $receivers = $facade->getReceivers();
    } catch (Library_Elefunds_Exception_ElefundsCommunicationException $exception) {

        // The API is not available!
        // Kick in fallback or omit the service if you retrieve in real time
        $message = $exception->getMessage();
    }

    foreach($receivers as $receiver) {
        /** @var  Library_Elefunds_Model_ReceiverInterface $receiver */

        echo '<p data-id="' . $receiver->getId(). '">' . $receiver->getName() . '</p>';
        echo '<img src="' .$receiver->getImage('horizontal', 'medium') . '" alt="' . $receiver->getDescription() .'"/>';
    }

This will render a tiny HTML snippet, that displays the receiver's name along with a horizontal-aligned and medium sized image.
Notice that we do render the receivers id as data-attribute and that we used the receivers description for providing an alt-attribute
for the image tag.

> Reminder: The API is really stable and robust, we're talking about best-practises here when we encourage you to watch out
> for errors.

You can use this to create an user-interface for choosing donation receivers. This may be something an administrator does in the backend
(e.g. when a donation is triggered anytime someone does something, like subscribing to a newsletter).

> The library ships with an own templating solution, hence you are not forced to mix everything in a single file. We have a pre-build template
> for shops and we are working on other templates as well.


Donations
---------

Now that we are able to retrieve receivers, we want to send back donations. Let's quickly create one.

> All setters in the library are chainable.

    $donation = $facade->createDonation()
                       ->setForeignId(125)
                       ->setAmount(25)
                       ->setSuggestedAmount(20)
                       ->setGrandTotal(1005)
                       ->setReceiverIds(array(2, 3))
                       ->setAvailableReceiverIds(array(1001, 1002, 1003))
                       ->setTime(new DateTime());

We use the facade to create an instance of the donation. Next, we set some values:

- the foreignId must be an integer, you are free to add whatever you like here but you must be able to match this foreignId
with the donating process on your site. If you are implementing for a shop, the orderId would be a perfect match.

- you must set the donated amount

- you can optionally set the amount that was suggested to the user, if your service has some kind of suggestion mechanism (this is something
that's suitable for shops)

- the grand total is an optional value and is the sum of donation plus all invoiced items (like the overall costs of an order); if your service
does not have something like a grand total at all, just omit this

- you must send back the receivers that are qualified for the donation; the id is available through `$receivers->getId()`

- the available receivers are optional, if your service offers a chance to select / unselect receivers, you can send back information about
which receivers where displayed. This will greatly help us to improve our services

- set the time, when the donation was made; if you do not send the donation directly after the checkout (e.g. to batch-send them at night),
please do send back the original time when the donation was made

That's it, now just send back the donation:

    try {
         $responseMessage = $facade->addDonation($donation);
    } catch (Library_Elefunds_Exception_ElefundsCommunicationException $exception) {
         $message = $exception->getMessage();
    }


If everything went well, you'll retrieve a status code of `200` and a response message:

> 1 of 1 donation(s) have been saved. 0 donation(s) failed the validation. 0 donation(s) failed due to a server problem.

If you got an error, you can simply send again the donation later. If you've used the `$facade->addDonations()` method to
add multiple donations, you can as well simply send all of them again - if some of them were saved before something went wrong,
the API is smart enough to sort things out.