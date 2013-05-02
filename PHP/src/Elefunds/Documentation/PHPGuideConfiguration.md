PHP Guide - Configuration
=========================

This SDK ships with a very powerful configuration mechanism. It allows you to setup a basic configuration and switch it at
runtime.

Basics
------

If you want to implement a custom configuration, it's a good idea to extend the default configuration. Here's an overview:

![overview](http://yuml.me/diagram/scruffy;dir:LR;/class///%20Cool%20Class%20Diagram,%20[note:ConfigurationInterface]-[BaseConfiguration],%20[BaseConfiguration]-%3E[DefaultConfiguration],%20[DefaultConfiguration]-%3E[YourCustomConfiguration] "ConfigurationOverview")

- the `ConfigurationInterface` is the core for all configuration files. If you are really unsatisfied with the BaseConfiguration you should drop us a mail - or - feel free to implement your own version as the SDK can handle it.

- the `BaseConfiguration` is a reference implementation, that implements algorithms for authentication, separates concerns of other key players and administrates implementations for our models.

- the `DefaultConfiguration` sets up a few options that you can use. For example, it sets the REST engine to *curl* and registers vanilla model implementations for receivers and donations.

> In most scenario's, you're good to go with the default configuration.

> The `DefaultConfiguration` registers it's setting in the `$configuration->init()` method, that is automatically called by the facade
> after the configuration is initialized. If you have custom logic yourself, this method is a good place for it.

Configuring your app is as easy as:

    <?php
    require_once dirname(__FILE__) . '/Facade.php';
    require_once dirname(__FILE__) . '/Configuration/DefaultConfiguration.php';

    $configuration = new Configuration_DefaultConfiguration();
    $configuration->setClientId(1001)
                  ->setApiKey('ay3456789gg234561234')
                  ->setCountrycode('de');

    $facade = new Elefunds_Facade($configuration);
    ?>

Another scenario would be, to implement a class, to extend the DefaultConfiguration:

    <?php
            require_once dirname(__FILE__) . '/Configuration/DefaultConfiguration.php';

            class Elefunds_YourCustomConfiguration extends Configuration_DefaultConfiguration {

                protected $clientId = 1001;
                protected $hashedKey = 'eb85fa24f23b7ade5224a036b39556d65e764653';
                protected $countrycode = 'de';
            }
    ?>

Did you noticed, that we did not provide the apiKey, but the already calculated hashedKey? That'll save us some milliseconds,
as there is no need to calculate it on every request.

Advanced
--------

You are free to configure the various files of this SDK yourself. For example, you may want to change the REST engine
to something different than curl. You'd then need to implement the `RestInterface` in your own engine. This can be configured
like this:

    require_once dirname(__FILE__) . '/YourRestEngine.php';
    $configuration->setRestImplementation(new YourRestEngine());

The same is true for the Donation and Receiver classes. A common scenario would be to implement the donation or the receiver to
support a persistence layer.

Here's a very basic example for `Doctrine`:

    <?php
    namespace YourCompany\YourProduct\Entity;

    use Doctrine\ORM\Mapping as ORM;

    require_once 'path/to/ElefundsSDK/Model/Donation.php';

    /**
     * @ORM\Entity
     * @ORM\Table(name="donation")
     */
    class Donation extends Elefunds_Model_Donation
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\Column(type="integer")
         */
        protected $foreignId;

        /**
         * @ORM\Column(type="integer")
         */
        protected $amount;

        /**
         * @ORM\Column(type="integer")
         */
        protected $suggestedAmount;

        /**
         * We have a receiver model as well!
         *
         * @ORM\OneToMany(targetEntity="Receiver", mappedBy="donation")
         */
        protected $receivers;

         /**
          * @ORM\OneToMany(targetEntity="Receiver", mappedBy="donation")
          */
         protected $availableReceivers;

         /**
          * @ORM\Column(type="integer")
          */
         protected $grandTotal;

         /**
          * @ORM\Column(type="datetime")
          */
         protected $time;
    }
    ?>

This would get the job done:

    // Let's assume $facade is already up and running.
    $facade->getConfiguration()->setDonationClassName('YourCompany\YourProduct\Entity\Donation');

    $donation = $facade->createDonation()->setForeignId(123)
                        ->setAmount(50)
                        ->addReceiverId(1001)
                        ->setAvailableReceiverIds(array(1001, 1002, 1003))
                        ->setTime(new DateTime()),

    // Let's say we use symfony and are in a controller:
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($donation);
    $entityManager->flush();

The same scenario is true for receivers and can be achieved using the `$configuration->setReceiverClassName()` method. If
you can't use the given models as base classes, you can implement the `DonationInterface` as well as the `ReceiverInterface`.
