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
 * Shop Configuration for a shop template.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2013 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.2.0
 */
class Elefunds_Template_Shop_ShopConfiguration extends Elefunds_Configuration_DefaultConfiguration {

    /**
     * @var array
     */
    protected $themes;

    /**
     * @var array
     */
    protected $colors;

    /**
     * Assigns the receivers.
     *
     * @return void
     */
    public function init() {

        parent::init();

        $this->setView(new Elefunds_View_BaseView());
        $this->view->setTemplate('Shop');

        //Available theme and color choices
        $this->themes = array('light', 'dark');
        $this->colors = array('orange', 'blue', 'green', 'purple', 'grey');

        //Chose your theme and color
        $theme = $this->themes[0];
        $color = $this->colors[0];

        $this->view->addJavascriptFile('elefunds.jquery.min.js');

        $this->view->registerAssignHook('skin', 'Elefunds_Template_Shop_Hooks_ShopHooks', 'chooseCssFile');
        $this->view->assign('skin',
            array(
                'theme' =>  $theme,
                'color' =>  $color
            )
        );

    }

    /**
     * Get the available theme options
     *
     * @return array
     */
    public function getAvailableThemes() {
        return $this->themes;
    }

    /**
     * Get the available color options
     *
     * @return array
     */
    public function getAvailableColors() {
        return $this->colors;
    }

}