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

require_once dirname(__FILE__) . '/ShopConfiguration.php';
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
class Elefunds_Template_Shop_CheckoutConfiguration extends Elefunds_Template_Shop_ShopConfiguration {

    /**
     * @var boolean
     */
    protected $autoFetchReceivers = TRUE;

    /**
     * Sets if receivers are auto fetched from the API.
     *
     * Set this to FALSE if you want to assign the receivers for yourself
     * (for example if you want to use caching).
     *
     * You should then call $this->view->assign('receivers', $receivers) for yourself.
     *
     * @param boolean $autoFetchReceivers
     */
    public function setAutoFetchReceivers($autoFetchReceivers)
    {
        $this->autoFetchReceivers = $autoFetchReceivers;
    }

    /**
     * Assigns the receivers.
     *
     * @return void
     */
    public function init() {

        parent::init();

        // If set to FALSE, no donation receipt if offered.
        // If TRUE you have to adjust T&Cs and send back donator information
        // Refer to the documentation for further information.
        $this->view->assign('offerDonationReceipt', TRUE);

        if ($this->autoFetchReceivers) {
            $this->view->assign('receivers', $this->facade->getReceivers());
        }

        // Defaults, you can opt to override this if you like.
        $this->view->assign('currency', '€');
        $this->view->assign('currencyDelimiter', '.');
        $this->view->assign('toolTipPosition', 'top');

        $this->view->assign('roundSum', 'elefunds_round_sum');
        $this->view->assign('roundSumContainer', 'elefunds_below_container');

        // L18n
        if ($this->countrycode === 'de') {
            $this->view->assign('elefundsDescription', 'Die elefunds Stiftung gUG leitet Deine Spende zu 100% an die ausgewählten Organisationen weiter.');
            $this->view->assign('slogan', 'Ja, ich möchte mit meinem Einkauf aufrunden und spenden!');
            $this->view->assign('receipt_slogan', 'Ich möchte eine Spendenquittung erhalten.');
            $this->view->assign('roundedSumString', 'Runde Summe');
        } else {
            $this->view->assign('elefundsDescription', 'elefunds is a charitable foundation proceeding 100% of your donation to the charities of your choice.');
            $this->view->assign('slogan', 'Yes, I want to roundup my purchase!');
            $this->view->assign('receipt_slogan', 'I want to receive a donation receipt.');
            $this->view->assign('roundedSumString', 'Round Sum');
        }

        $this->view->registerAssignHook('shopWidth', 'Elefunds_Template_Shop_Hooks_ShopHooks', 'calculatePadding');
        $this->view->registerAssignHook('total', 'Elefunds_Template_Shop_Hooks_ShopHooks', 'calculateRoundUp');
    }
}