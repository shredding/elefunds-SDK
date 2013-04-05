PHP Guide - Templating
======================

The SDK ships with simple, yet efficient templating support. We have a shop template and are working hard to implement
templates for other applications as well. This guide will walk you through setting up the shop template and give you hints
on how to implement your own templates.

Basics
------

**Checkout**

Using a template is as easy as accessing raw data. But a template gives you some additional configuration options and
requires you to provide some extra information.

The shop example has a configuration like this:

    <?php
    require_once dirname(__FILE__) . '/Template/Shop/CheckoutConfiguration.php';

    class Elefunds_Example_ShopExampleConfiguration extends Template_Shop_CheckoutConfiguration {

        protected $clientId = 1001;
        protected $apiKey = 'ay3456789gg234561234';

        public function init() {
            parent::init();

            // Width of the checkout area, ie. how wide you want the plugin to be
            $this->view->assign('shopWidth', 600);
        }
    }
    ?>

This is the bare minimum. You can see that we assign values to the view. The view needs the information `shopWidth` to
know the width of your checkout layout to calculate the size of the plugin. It needs one more information to do it's job: The total sum to round up. Your shop should give the actual total to the view and it will calculate the assumed roundup and format everything in a nice html snippet.

So what's left to do:

    <?php
    require_once dirname(__FILE__) . '/../Facade.php';
    require_once dirname(__FILE__) . '/ShopExampleConfiguration.php';

    $facade = new Elefunds_Facade();

    try {
        $facade->setConfiguration(new Elefunds_Example_ShopExampleConfiguration());
        $snippet = $facade->renderTemplate();
    } catch (Elefunds_Exception_ElefundsCommunicationException $error) {
        $snippet = '';
    }
    ?>

Nothing else is needed to get you going. `$snippet` will now either include the rendered plugin or an empty string if
the communication went wrong (the latter is a fallback to not compromise your checkout).

In order to have everything in place, you need to add CSS and Javascript snippets. This can be done like this:

    <!-- +++ HTML Snippet for your head section +++ -->
    <?php foreach($facade->getTemplateCssFiles() as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>">
    <?php endforeach; ?>
    <!-- ^^^ HTML Snippets for your head section ^^^ -->

    <!-- +++ HTML Snippet for Javascript Files +++ -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"> </script>
    <?php foreach($facade->getTemplateJavascriptFiles() as $javascript): ?>
     <script type="text/javascript" src="<?php echo $javascript; ?>"></script>
    <?php endforeach; ?>
    <!-- ^^^ HTML Snippet for Javascript Files ^^^ -->

> CSS and Javascript path's are always relative to the SDK root (e.g. the same folder as the `Facade`).

> We're working on a version, were you can set your javascript library of choice in your configuration.
> At the moment, this template requires jQuery.


If you use the `ShopConfiguration`, you can easily change the theme!

    // Let's assume, that the facade is already configured!
    $configuration = $facade->getConfiguration();

    /** @var Elefunds_Template_Shop_ShopConfiguration $configuration  */
    $configuration->getView()
                  ->assign('skin'
                      array(
                          'theme'  =>  'light',
                          'color'  =>  'purple'
                      )
                  );

**Success**

When a checkout was successful, you can send back the donation and display our facebook and twitter share. This is as easy as:

    require_once dirname(__FILE__) . '/../Facade.php';
    require_once dirname(__FILE__) . '/ShopExampleCheckoutSuccessConfiguration.php';

    $facade = new Elefunds_Facade();
    $facade->setConfiguration(new Elefunds_Example_ShopExampleCheckoutSuccessConfiguration());


    $facade->getConfiguration()->getView()->assign('donationReceivers', array(
        2 => 'UNO',
        3 => 'Care'
    ));

    $facade->getConfiguration()->getView()->assign('foreignId', 1234);
    $snippet = $facade->renderTemplate('CheckoutSuccess');

Notice that we do not need to create try / catch blocks here, as there are no API calls yet! You have to add the donation
receivers and your foreignId to the view, as they are needed for the facebook and twitter share. `$snippet` now contains the data for the facebook share.

Since the share does not need additionally Javascript files, you're good to go with the following lines:

    <!-- +++ HTML Snippet for your head section +++ -->
    <?php foreach($facade->getTemplateCssFiles() as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>">
    <?php endforeach; ?>
    <!-- ^^^ HTML Snippets for your head section ^^^ -->

> You can configure the Share (like which ones are to display the hover title and much more directly in the init()
> method of the CheckoutSuccessConfiguration, or - better - override it's values in your extending Configuration
> file.

All that's left to do no now is send us the donation data.


Advanced
--------

You can create your own template as well. It's easy.

To get started create a new Folder named `Awesome` below the Template Folder and create a `View.phtml` file in it:

    <div>
        <p>$view['number']</p>
    </div>

> `View` is the default, if you want to name your template differently, you have to add the name as parameter to the
> `$facade->renderTemplate('YourDifferentName')` method.

Create an `AwesomeConfiguration.php` file as well, with the following content:

    <?php

    require_once dirname(__FILE__) . '/../../Configuration/DefaultConfiguration.php';
    require_once dirname(__FILE__) . '/../../View/BaseView.php';

    class Elefunds_Template_Awesome_AwesomeConfiguration extends Configuration_DefaultConfiguration {

        public function init() {
            parent::init();

            $this->setView(new Elefunds_View_BaseView());
            $this->view->setTemplate('Awesome');
            $this->view->assign('number', 42);
        }
    }
    ?>

If you now pass the AwesomeConfiguration to your facade, you are able to render the template. As you can see, everything
you assign to the view is accessible in the `$view` Array of your `View.phtml`.

But let's do some more tricks!

Fire some CSS files and Javascript files in the Folder `Template/Awesome/Css` and `Template/Awesome/Javascript`. You can assign
them to your template like this (assuming you are inside the configuration's `init()` method):

    $this->view->addCssFile('awesome.min.css');
    $this->view->addJavascriptFile('awesome.jquery.min.js');

> It's a good practise to provide minimized versions of your static files.

Lets create a hook to multiply a number by ten.

> You can see this trick in action if you are looking at the hooks in the shop template. For example, the suggested roundup gets
> calculated that way when the total is assigned to the view.

Create a file named `AwesomeHooks.php` and save it at `Template/Awesome/Hooks`. Then paste in the following content:

    <?php
    class Elefunds_Template_Awesome_Hooks_AwesomeHooks {
        public static function mulitplyByTen($view, $number) {
            $view->assign('number', $number * 10);
        }
    }
    ?>

Now adjust your Configuration file like this:

    <?php
    require_once dirname(__FILE__) . '/../../Configuration/DefaultConfiguration.php';
    require_once dirname(__FILE__) . '/../../View/BaseView.php';
    require_once dirname(__FILE__) . '/Hooks/AwesomeHooks.php';

    class Elefunds_Template_Awesome_AwesomeConfiguration extends Configuration_DefaultConfiguration {
        public function init() {
            parent::init();

            $this->setView(new Elefunds_View_BaseView());
            $this->view->setTemplate('Awesome');
            $this->view->registerAssignHook('number', 'Elefunds_Template_Awesome_Hooks_AwesomeHooks', 'mulitplyByTen');
        }
    }
    ?>

We have omitted the number but added a hook for it. If the number is assigned from the outside, the hook kicks in and adds
a variable named `numberMultipliedByTen` to the view. If the number is set, it will be available like this:

    <div>
        <p><?php echo $view['number']; ?></p>
        <p><?php echo $view['numberMultipliedByTen']; ?></p>
    </div>

In order for this to work, the number must be set, an example would be:

    <?php
    require_once 'Facade.php';
    require_once 'Template/Awesome/AwesomeConfiguration.php';

    $facade = new Elefunds_Facade(new Elefunds_Template_Awesome_AwesomeConfiguration());
    $facade->getConfiguration()->getView()->assign('number', 42);
    echo $facade->renderTemplate();
    ?>

If the number is assigned, the multiplied version is assigned as well.

> There's a whole lot more to explore. Be sure to check out the shop template to get an in-depth overview on what is
> possible with this SDK's templating system.





