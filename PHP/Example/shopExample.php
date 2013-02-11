<?php

/*
 * A sample checkout page
 */
require_once dirname(__FILE__) . '/../Facade.php';
require_once dirname(__FILE__) . '/ShopExampleConfiguration.php';

try {
    $facade = new Library_Elefunds_Facade();

    $facade->setConfiguration(new Library_Elefunds_Example_ShopExampleConfiguration());

    // Assign the total at runtime.
    //
    // Normally this would be coming from the grand total in the checkout process.
    // For our example, we'll hard-code the value.
    $actualTotal = 960;
    $facade->getConfiguration()->getView()->assign('total', $actualTotal);

    $snippet = $facade->renderTemplate();

} catch (Library_Elefunds_Exception_ElefundsCommunicationException $error) {

    // If something goes wrong, we do not render anything at all.
    $snippet = '';
}

?>

<!DOCTYPE HTML >
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>My Shop</title>
<?php foreach ($facade->getTemplateCssFiles() as $cssFile): ?>
    <link rel="stylesheet" type="text/css" href="../<?php echo $cssFile; ?>">
<?php endforeach; ?>
</head>
<body>
    <h2>My shop is awesome</h2>

    <!-- Some other HTML here -->

    <!--
        This will render the template. If you are using a form, then you will have access to the selected
        receivers automatically, because they are all rendered as input fields, so you have them to your
        service upon your next request.

        You can opt to override the template in Template/Shop/View.phtml to fit your needs if you have
        another implementation.
    -->
    <?php echo $snippet; ?>

    <!-- Even more HTML here -->

    <!-- Be sure to include jQuery as it's required by our JavaScript files -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"> </script>
    <?php foreach ($facade->getTemplateJavascriptFiles() as $javascript): ?>
     <script type="text/javascript" src="../<?php echo $javascript; ?>"></script>
    <?php endforeach; ?>
</body>
</html>
