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
 * Base Configuration for the checkout success configuration of the shop template.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Template_Shop_CheckoutSuccessConfiguration extends Elefunds_Template_Shop_ShopConfiguration {

    /*
     * NOTE:
     *
     * To change this template, instead of modifying this file, do the following:
     *
     *  * create a new Folder /Template/MyTemplate
     *  * copy View.phtml and CheckoutSuccess.phtml in it (and adjust them)
     *  * create a MyTemplateBaseConfiguration class, that extends this class (and adjust it)
     */

    /**
     * Assigns the basic variables for the shop.
     *
     * @return void
     */
    public function init() {
        parent::init();

        $this->view->assign('clientId', $this->facade->getConfiguration()->getClientId());
        $this->view->assign('hashedKey', $this->facade->getConfiguration()->getHashedKey());

        $this->view->assign('availableShareServices', array(
            'facebook'      =>  array(
                'image'     =>  'https://0ce8ff584bf613ee6639-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/share_on_facebook.png',
                'width'     =>  660,
                'height'    =>  350,
                'title'     =>  $this->countrycode === 'de' ? 'Auf Facebook teilen' : 'Share on facebook'
            ),
            'twitter'      =>  array(
                'image'     =>  'https://0ce8ff584bf613ee6639-c1fc539e0df6af03ccc14b5020ab4161.ssl.cf1.rackcdn.com/tweet_on_twitter.png',
                'width'     =>  620,
                'height'    =>  415,
                'title'     =>  $this->countrycode === 'de' ? 'Auf Twitter tweeten' : 'Tweet on twitter'
            ),
        ));
        
        if($this->countrycode === 'de') {
            $this->view->assign('learnMore', 'Erfahre mehr über');
        } else {
            $this->view->assign('learnMore', 'Learn more about');
        }
        
        $this->view->assign('shareServices', array('facebook', 'twitter'));

        // L18n
        if ($this->countrycode === 'de') {
            $this->view->assign('tellAboutIt', 'Juhu! Vielen Dank für Deine Spende.');
            $this->view->assign('IDonatedAndWantToTellAboutIt', 'Teilen kann man nicht nur mit seinem Einkauf, sondern auch mit seinen Freunden.<br />Erzähle Deinen Freunden von elefunds!');
        } else {
            $this->view->assign('tellAboutIt', 'Yay! Thank you for your donation.');
            $this->view->assign('IDonatedAndWantToTellAboutIt', 'If everyone used their change for change we could make the world a better place. Tell your friends about elefunds.');
        }

        // Hooks
       $this->view->registerAssignHook('receivers', 'Elefunds_Template_Shop_Hooks_ShopHooks', 'onReceiversAdded');
       $this->view->registerAssignHook('foreignId', 'Elefunds_Template_Shop_Hooks_ShopHooks', 'onForeignIdAdded');
    }

}