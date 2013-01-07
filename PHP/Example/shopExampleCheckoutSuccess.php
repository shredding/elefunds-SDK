<?php

/*
 * A sample successful order page
 */
require_once dirname(__FILE__) . '/../Facade.php';
require_once dirname(__FILE__) . '/ShopExampleCheckoutSuccessConfiguration.php';

$facade = new Library_Elefunds_Facade();
$facade->setConfiguration(new Library_Elefunds_Example_ShopExampleCheckoutSuccessConfiguration());

// We've prepared this for you: The names of all receivers are given as hidden field
// in the checkout template and you get the ids of the receivers via the checked fields.
//
// Just check which receivers have been checked by the user and assign them here.
//
// You can get the id of the receiver from the checkbox input field id
// Like this: substr('elefunds_receiver_123', 18);
//
// We assume, that Care and UNO have been chosen.
$facade->getConfiguration()->getView()->assign('receivers', array(
    2 => 'UNO',
    3 => 'Care'
));

// Assign current orderId as foreignId
$facade->getConfiguration()->getView()->assign('foreignId', 1234);

?>

<!DOCTYPE HTML >
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>My Shop</title>
    <?php foreach($facade->getTemplateCssFiles() as $cssFile): ?>
    <link rel="stylesheet" type="text/css" href="../<?php echo $cssFile; ?>">
    <?php endforeach; ?>
</head>
<body>
<h2>My shop is awesome</h2>

<!-- Some other HTML here -->

<?php echo $facade->renderTemplate('CheckoutSuccess'); ?>

<!-- Even more HTML here -->

</body>
</html>