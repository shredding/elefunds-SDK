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
 * View Interface
 * 
 * @package    elefunds API PHP Library
 * @subpackage View
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
interface Elefunds_View_ViewInterface {
    
    /**
     * Sets the name of the template. This must be corresponding to
     * the name of a folder in the Template folder.
     * 
     * @param string $template
     * @return Elefunds_View_BaseView
     */
    public function setTemplate($template);
    
    /**
     * Returns the template name of this view.
     *
     * @return string
     */
    public function getTemplate();
    
    /**
     * Returns all javascript files that are required for this plugin to work in their correct order.
     * 
     * The given path is relative to the folder of this library without trailing slash. E.g.:
     * 
     * 'Template/YourTemplate/Javascript/script.min.js'
     * 
     * Hence, you have to add your basepath ahead of it if you want to include it.
     * 
     * Like this:
     * 
     * <code>
     *    <?php foreach($javascripts as $javascript): ?>
     *           <script type="text/javascript" src="http://elefunds.de/plugins/<?php echo $javascript; ?>"></script>
     *    <?php endforeach; ?> 
     * </code>
     * 
     * If you write your own template files, minimize your javascript and try to deliver as few as possible.
     * 
     * @return array
     */
    public function getJavascriptFiles();
    
    /**
     * Returns all css files that are required for this plugin to work in their correct order.
     * 
     * The given path is relative to the folder of this library without trailing slash. E.g.:
     * 
     * 'Template/YourTemplate/Css/styles.css'
     * 
     * Hence, you have to add your base path ahead of it if you want to include it.
     * 
     * Like this:
     * 
     * <code>
     *    <?php foreach($cssFiles as $cssFile): ?>
     *          <link rel="stylesheet" type="text/css" href="http://elefunds.de/plugins/<?php echo $cssFile; ?>">
     *    <?php endforeach; ?> 
     * </code>
     * 
     * If you write your own template files, minimize your css files and try to deliver as few as possible.
     * 
     * @return array
     */
    public function getCssFiles();

    /**
     * Adds hooks that are called when a value is assigned to the view.
     *
     * Hence you can auto-calculate dependencies (like a round up suggestion when a grand
     * total is assigned).
     *
     * Hooks are called with a reference to this view (so you can assign for yourself) and the
     * called value as second parameter.
     *
     * Be sure that all classes are required_once.
     *
     * @param string $name equals the assignValue that should be hooked.
     * @param mixed $class string (class name) or instance
     * @param string $method
     * @throws InvalidArgumentException
     * @return Elefunds_View_ViewInterface
     */
    public function registerAssignHook($name, $class, $method);

    /**
     * Returns all already assigned values.
     *
     * @return array
     */
    public function getAssignments();
    
    /**
     * Assigns variables to the view.
     * 
     * @param string $key
     * @param mixed $value
     * @throws InvalidArgumentException if given key is not a string
     * @return Elefunds_View_BaseView
     */
    public function assign($key, $value);
    
    /**
     * Add multiple variables to the view.
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2).
     * @return Elefunds_View_BaseView
     */
    public function assignMultiple(array $values);

    /**
     * Renders the given output.
     *
     * @param string $templateName name of the template to render
     * @param bool $givenTemplateNameIsAbsolutePathWithFullyQualifiedFilename
     *
     * @return string the rendered HTML
     */
    public function render($templateName = 'View', $givenTemplateNameIsAbsolutePathWithFullyQualifiedFilename = FALSE);
    
     /**
     * Add your css file with it's pure file name (e.g. 'styles.css') and save it
     * as /Template/YourTemplateFolder/Css/styles.css
     * 
     * @param string $file
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_BaseView
     */
    public function addCssFile($file);
    
    /**
     * Add your css files. 
     * 
     * Wrapper for addCss($file).
     * 
     * @param array $files
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_BaseView
     */
    public function addCssFiles(array $files);
        
    /**
     * Add your js file with it's pure file name (e.g. 'myjavascript.js') and save it
     * as /Template/YourTemplateFolder/Javascript/myjavascript.js
     * 
     * @param string $file
     * @throws InvalidArgumentException if given key is not a string
     * @return Elefunds_View_BaseView
     */
    public function addJavascriptFile($file);
    
    /**
     * Add your js files. 
     * 
     * Wrapper for addJavascriptFiles($file).
     * 
     * @param array $files
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_BaseView
     */
    public function addJavascriptFiles(array $files);

    /**
     * Removes all css files.
     *
     * @return void
     */
    public function flushCssFiles();

}