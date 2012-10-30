<?php

/**
 * elefunds API PHP Library
 *
 * Copyright (c) 2012, elefunds GmbH <hello@elefunds.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the elefunds GmbH nor the names of its
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once dirname(__FILE__) . '/../../Configuration/DefaultConfiguration.php';
require_once dirname(__FILE__) . '/../../View/BaseView.php';
require_once dirname(__FILE__) . '/Hooks/ShopHooks.php';

/**
 * Checkout Configuration for a shop template.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Library_Elefunds_Template_Shop_CheckoutConfiguration extends Library_Elefunds_Configuration_DefaultConfiguration {


    /**
     * Assigns the receivers.
     *
     * @return void
     */
    public function init() {

        parent::init();

        $this->setView(new Library_Elefunds_View_BaseView());
        $this->view->setTemplate('Shop');

        $this->view->assign('clientId', $this->facade->getConfiguration()->getClientId());
        $this->view->assign('hashedKey', $this->facade->getConfiguration()->getHashedKey());

        $this->view->assign('receivers', $this->facade->getReceivers());

        $this->view->addCssFile('elefunds.min.css');
        $this->view->addJavascriptFile('elefunds.jquery.min.js');

        // Defaults, you can opt to override this if you like.
        $this->view->assign('currency', '€');
        $this->view->assign('currencyDelimiter', '.');

        // L18n
        if($this->countrycode === 'de') {
            $this->view->assign('elefundsDescription', 'Die elefunds Stiftung leitet deine Spende zu 100% an die ausgewählten Organisationen weiter.');
            $this->view->assign('slogan', 'Ich möchte mit meinem Einkauf aufrunden und spenden!');
            $this->view->assign('roundedSumString', 'Runde Summe');
        } else {
            $this->view->assign('elefundsDescription', 'elefunds is a charitable foundation proceeding 100% of your donation to the charities of your choice.');
            $this->view->assign('slogan', 'I want to roundup my purchase!');
            $this->view->assign('roundedSumString', 'Round Sum');
        }

        $this->view->registerAssignHook('shopWidth', 'Library_Elefunds_Template_Shop_Hooks_ShopHooks', 'calculatePadding');
        $this->view->registerAssignHook('total', 'Library_Elefunds_Template_Shop_Hooks_ShopHooks', 'calculateRoundUp');

    }
}