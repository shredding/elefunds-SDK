<?php

/*
 * A sample checkout page
 */
require_once dirname(__FILE__) . '/../Facade.php';
require_once dirname(__FILE__) . '/ShopExampleConfiguration.php';

try {
    $facade = new Elefunds_Facade();

    $facade->setConfiguration(new Elefunds_Example_ShopExampleConfiguration());

    // Assign the total at runtime.
    //
    // Normally this would be coming from the grand total in the checkout process.
    // For our example, we'll hard-code the value.
    $actualTotal = 960;
    $facade->getConfiguration()->getView()->assign('total', $actualTotal);

    // Define the skin of the module. Currently, the skin is made up of the following
    // theme: 'light', 'dark'
    // color: 'orange', 'blue', 'green', 'purple'
    $facade->getConfiguration()->getView()->assign('skin',
        array(
            'theme' =>  'dark',
            'color' =>  'blue'
        )
    );

    $snippet = $facade->renderTemplate();

} catch (Elefunds_Exception_ElefundsCommunicationException $error) {

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
<!-- Be sure to include jQuery as it's required by our JavaScript files -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<style>
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
</style>
</head>
<body>
    <h2>My awesome Shop</h2>

    <!-- Lets assume the checkout total is 9.60€ -->
    <div style="margin-left: 780px; margin-bottom: 10px; font-weight: bold;">Total:&nbsp;&nbsp;&nbsp;&nbsp;9.60 €</div>

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

    <?php foreach ($facade->getTemplateJavascriptFiles() as $javascript): ?>
     <script type="text/javascript" src="../<?php echo $javascript; ?>"></script>
    <?php endforeach; ?>
</body>
</html>
