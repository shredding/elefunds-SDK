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

/**
 * Hooks for the shop template.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Template_Shop_Hooks_ShopHooks {

    private static $foreignId;
    private static $receiverIds;

    /**
     * Calculates the suggested roundup to be displayed in the shop.
     *
     * @param Elefunds_View_ViewInterface $view
     * @param int $total in the smallest unit of currency
     * @return void
     */
    public static function calculateRoundup(Elefunds_View_ViewInterface $view, $total) {
        
        //Convert to float
        $total = round(($total / 100), 2);

        //Checkout total price tiers
        $tiers = array(16, 100, 1000, 999999);

        //Percentage of checkout total
        $percent = array(0.10, 0.06, 0.04, 0.03);

        //Round to the nearest "round sum"
        $roundup = array(2, 5, 10, 20);

        //Default tier
        $tier = count($tiers)-1;

        //Determine checkout price tier
        foreach($tiers as $key => $value) {
            if ($total < $value) {
                $tier = $key;
                break;
            }
        }

        //Percentage of the checkout total...
        $donationSuggestion = $total * $percent[$tier];

        //Round up total sum, rounded to the nearest $roundup...
        $roundTotal = ceil(($total + $donationSuggestion) / $roundup[$tier]) * $roundup[$tier];

        //Donation suggestion is the difference between the round total and the checkout total
        $donationSuggestion = $roundTotal - $total;

        //In case the suggestion is higher than the nearest $roundup, we subtract the nearest $roundup
        //and adjust the $roundTotal
        if ($donationSuggestion > $roundup[$tier] && $tier < count($tiers)-2) {
            $donationSuggestion -= $roundup[$tier];
        }

        //Round the donationSuggestion to eliminate float bugs
        $assigns = $view->getAssignments();
        $suggestedDonationAmount = round($donationSuggestion, 2);

        //Limit the donation suggestion to 30
        if($suggestedDonationAmount > 30) {
            $removeFromSuggestion = round($suggestedDonationAmount / 10, 2);
            $removeFromSuggestion = floor($removeFromSuggestion) * 10;
            $suggestedDonationAmount = round($suggestedDonationAmount - $removeFromSuggestion, 2) + 20;
        }

        $donationAmountString = number_format($suggestedDonationAmount, 2, $assigns['currencyDelimiter'], '');
        $roundedSum = number_format(($suggestedDonationAmount + $total), 2, $assigns['currencyDelimiter'], '');

        $view->assign('suggestedDonationAmountCent', $suggestedDonationAmount * 100);
        $view->assign('suggestedDonationAmount', $donationAmountString);
        $view->assign('roundedSum', $roundedSum);
        $view->assign('currencyDelimiter', $assigns['currencyDelimiter']);

    }

    /**
     * Calculates number of providers displayed and their sizes
     *
     * @param Elefunds_View_ViewInterface $view
     * @param int $width
     * @return void
     */
    public static function calculatePadding(Elefunds_View_ViewInterface $view, $width) {

        if ($width < 632) {
            $width = 632;
        }

        $width = $width - 2;
        
        // Max number of receivers that can be displayed
        $receiversCount = (int) floor($width / 210);

        // Adjust receivers & override assignments
        $assigns = $view->getAssignments();
        
        // If we have less receivers assigned than space available, reduce the count
        if (count($assigns['receivers']) < $receiversCount) {
            $receiversCount = count($assigns['receivers']);
        }
        
        $receivers = array_slice($assigns['receivers'], 0, $receiversCount);
        $view->assign('receivers', $receivers);

        if ($width % 210 == 0) {
            $paddingLeft = 8;
        } else {
            $paddingLeft = 9;
        }

        $paddingRight = 0;

        //subtract the borders between the buttons
        $padding = $width - ($receiversCount - 1);

        //subtract image width
        $padding = $padding - (166 * $receiversCount);

        //subtract min padding
        $padding = $padding - ($receiversCount * ($paddingLeft + $paddingRight));

        $paddingEach = $padding / $receiversCount;

        $mod = $padding % $receiversCount;

        if ($mod == 0) {
            $paddingEach = intval($paddingEach);
            $bonus = 0;
        } elseif ($mod == 1) {
            $paddingEach = floor($paddingEach);
            $bonus = 1;
        } else {
            $paddingEach = ceil($paddingEach);
            $bonus = $mod - $receiversCount;
        }

        //Padding of the receivers buttons
        $paddingLeft = $paddingLeft + ceil($paddingEach / 2);
        $paddingRight = $paddingRight + floor($paddingEach / 2);

        //Padding of the last receiver buttons
        $paddingLeftLast = $paddingLeft + floor($bonus / 2);
        $paddingRightLast = $paddingRight + ceil($bonus / 2);

        $view->assignMultiple(
            array(
                'paddingLeft' => $paddingLeft . 'px',
                'paddingRight' => $paddingRight . 'px',
                'paddingLeftLast' => $paddingLeftLast . 'px',
                'paddingRightLast' => $paddingRightLast . 'px',
            )
        );

        $view->assign('actualShopWidth', $width);
    }
    
    /**
     * Chooses the correct CSS to load in the view based on the theme and color defined in $skin
     *
     * @param Elefunds_View_ViewInterface $view
     * @param array $skin
     */
    public static function chooseCssFile(Elefunds_View_ViewInterface $view, array $skin) {

        $default_theme = 'light';
        $default_color = 'orange';
        
        if(!isset($skin['theme']) || !isset($skin['color'])) {
            $theme = $default_theme;
            $color = $default_color;
        } else {
            $theme = $skin['theme'];
            $color = $skin['color'];
        }
        
        //Set theme & color to use in the view
        $view->assign('theme', $theme);
        $view->assign('color', $color);
        
        //Reset the css array in case assign('skin') has already been invoked
        $view->flushCssFiles();
        $view->addCssFile('elefunds_' . $theme . '_' . $color . '.min.css');
    }

    /**
     * Forwards the handling to the assignShares method if foreignId is already set. If not,
     * it just assigns the receiverIds to a private property. The action will then be invoked
     * once the foreignId is added.
     *
     * Invokes as well calculateReceiversText.
     *
     * @param Elefunds_View_ViewInterface $view
     * @param array $receivers
     */
    public static function onReceiversAdded(Elefunds_View_ViewInterface $view, array $receivers) {
        self::$receiverIds = array_keys($receivers);

        if (self::$foreignId !== NULL) {
            self::assignShares($view);
        }
    }

    /**
     * Forwards the handling to the assignShares method if receiverIds are already set. If not,
     * it just assigns the foreignId to a private property. The action will then be invoked
     * once receivers are added.
     *
     * @param Elefunds_View_ViewInterface $view
     * @param $foreignId
     */
    public static function onForeignIdAdded(Elefunds_View_ViewInterface $view, $foreignId) {
        self::$foreignId = $foreignId;

        if (self::$receiverIds !== NULL) {
            self::assignShares($view);
        }
    }

    /**
     * Assigns shares to the success page!
     *
     * @param Elefunds_View_ViewInterface $view
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private static function assignShares(Elefunds_View_ViewInterface $view) {

        $assigns = $view->getAssignments();

        // No services are configured, hence we skip this.
        if (!isset($assigns['shareServices'])) {
            return;
        }

        $services = $assigns['shareServices'];
        $baseUrl = 'https://share.elefunds.de/on/%s/%d/%d/%s/%s';
        $receivers = implode(',', self::$receiverIds);
        $clientId = $assigns['clientId'];
        $hashedKey = $assigns['hashedKey'];
        $checksum = sha1($clientId . self::$foreignId . $receivers . $hashedKey);

        $shares = array();
        $availableShareServices = $assigns['availableShareServices'];

        foreach($services as $service) {

            if (!array_key_exists($service, $availableShareServices)) {
                throw new InvalidArgumentException('Service must be one of the following: ' . implode(', ', $availableShareServices));
            }
            $serviceObj = new stdClass();
            $serviceObj->url = sprintf($baseUrl, $service, $clientId, self::$foreignId, $receivers, $checksum);
            $serviceObj->imageUrl = $availableShareServices[$service]['image'];
            $serviceObj->width = $availableShareServices[$service]['width'];
            $serviceObj->height = $availableShareServices[$service]['height'];
            $serviceObj->title = $availableShareServices[$service]['title'];
            $shares[] = $serviceObj;
        }
        $view->assign('shares', $shares);

    }


}
