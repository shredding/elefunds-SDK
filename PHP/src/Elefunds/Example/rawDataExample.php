<?php

require_once dirname(__FILE__) . '/../Facade.php';
require_once dirname(__FILE__) . '/RawDataConfiguration.php';

// Set up API
$facade = new Elefunds_Facade();
$facade->setConfiguration(new Example_RawDataConfiguration());

/**
 * BASIC ACTIONS
 */

// Get receivers
$receivers = $facade->getReceivers();
var_dump($receivers);

// Send donation
$response = $facade->addDonations(

                array(

                    $facade->createDonation()->setForeignId(123)
                                             ->setAmount(50)
                                             ->addReceiverId(1001)
                                             ->setAvailableReceiverIds(array(1001, 1002, 1003))
                                             ->setTime(new DateTime()),

                    $facade->createDonation()->setForeignId(125)
                                             ->setAmount(25)
                                             ->setSuggestedAmount(20)
                                             ->setGrandTotal(1005)
                                             ->setReceiverIds(array(1001, 1002))
                                             ->setAvailableReceiverIds(array(1001, 1002, 1003))
                                             ->setTime(new DateTime())
                                             ->setDonator('hello@elefunds.de', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin')

)

);

// Sample server response
echo('<p>Added: ' . $response . '</p>');

// Some users did not pay or an order got canceled? Just delete their donations with the corresponding foreignId
$response = $facade->cancelDonation(123);
// Or Multiple
# $response = $facade->cancelDonations(array(123, 124));

// Sample server response
echo('<p>Deleted: ' . $response . '</p>');

// NOTE: Normally you should wrap API calls around try ... catch! Like this:
try {
    $receivers = $facade->getReceivers();
} catch (Elefunds_Exception_ElefundsCommunicationException $exception) {
    $message = $exception->getMessage();
    $additionalInformation = $exception->getAdditionalInformation();
}

/**
 * COOL TRICKS
 */

// Wanna change language at runtime? Here's an example for TYPO3:
# $countrycode = $GLOBALS['TSFE']->sys_language_uid === 0 ? 'de' : 'en';
# $facade->getConfiguration()->setCountrycode($countrycode);
# $receivers = $facade->getReceivers();

// Use the API for different shops and want to switch configuration at will?
# $configuration = $shop->getId() === 1 ? new MyFirstShopConfiguration() : new MySecondShopConfiguration();
# $facade->setConfiguration($configuration);


// Wanna switch from curl to some rare php module no one uses?
// You can opt to push this into your configuration file, but you're free to do it at runtime

# require_once('path/to/class/that/implements/our/RestInterface/Implementation.php);
# $facade->getConfiguration()->setRestImplementation(new Implementation());


// You have three different shops, one on doctrine, one on propel and one on your self build persistence layer?
// You want to have the donation class be a part of your backend, so it gets auto persisted after retrieving?
// You want to decide at runtime, which backend to use?

// 1. Generate a donation class for your backend and implement the Elefunds_Model_DonationInterface
// 2. Use the full power of your persistence backend of choice
// 3. Set the backend at runtime like this (assumes that your shop / framework uses autoloading for the persistence backend)
// 4. That's it.

/*
switch($persistenceBackend) {

    case self::DOCTRINCE:
        $facade->getConfiguration()->setDonationClassName('Application\Domain\Model\YourDonationClass');
        break;

    case self::PROPEL:
        $facade->getConfiguration()->setDonationClassName('Application\Domain\Model\YourOtherDonationClass');
        break;

    case self::MYCOOLPERSISTENCEBACKEND:
        $facade->getConfiguration()->setDonationClassName('Application\Domain\Model\YourThirdDonationClass');
        break;

}
*/

// Same is true for Receivers!

