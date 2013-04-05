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

require_once dirname(__FILE__) . '/ViewInterface.php';
require_once dirname(__FILE__) . '/../Exception/ElefundsException.php';

/**
 * View for the elefunds API.
 * 
 * @package    elefunds API PHP Library
 * @subpackage View
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_View_BaseView implements Elefunds_View_ViewInterface {
    
    /**
     * @var array
     */
    protected $view = array();
    
    /**
     * The template of this plugin. 
     *
     * @var string
     */
    protected $template;
    
    /**
     * @var array
     */
    protected $javascriptFiles = array();
    
    /**
     * @var array
     */
    protected $cssFiles = array();

    /**
     * Array of the following structure:
     *
     * <code
     *  $hook = array(
     *      'hookName'  => array(
     *          'class'     => 'someClass',
     *          'method'    => 'someMethod'
     *      )
     *  );
     * </code>
     *
     *
     * @var array
     */
    protected $assignHooks = array();

    /**
     * Sets the name of the template. This must be corresponding to
     * the name of a folder in the Template folder.
     *
     * @param string $template
     * @return Elefunds_View_ViewInterface
     */
    public function setTemplate($template) {   
        $this->template = ucfirst(strtolower($template));
        return $this;
    }
    
    /**
     * Returns the template name of this view.
     *
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }
    
    /**
     * Returns all javascript files that are required for this plugin to work in their correct order.
     * 
     * The given path is relative to the folder of this library without trailing slash. E.g.:
     * 
     * 'Template/YourTemplate/Javascript/script.min.js'
     * 
     * Hence, you have to add your base path ahead of it if you want to include it.
     * 
     * Like this:
     * 
     * <code>
     *    <?php foreach($javaScripts as $javaScript): ?>
     *           <script type="text/javascript" src="http://elefunds.de/plugins/<?php echo $javascript; ?>"></script>
     *    <?php endforeach; ?> 
     * </code>
     * 
     * If you write your own template files, minimize your javascript and try to deliver as few as possible.
     * 
     * @return array
     */
    public function getJavascriptFiles() {
            return $this->javascriptFiles;
    }
    
    /**
     * Returns all css files that are required for this plugin to work in their correct order.
     * 
     * The given path is relative to the folder of this library without trailing slash. E.g.:
     * 
     * 'Template/YourTemplate/Css/styles.css'
     * 
     * Hence, you have to add your basepath ahead of it if you want to include it.
     * 
     * Like this:
     * 
     * <code>
     *    <?php foreach($cssFiles as $cssFile): ?>
     *          <link rel="stylesheet" type="text/css" href="http://elefunds.de/plugins/<?php echo $cssFile; ?>">
     *    <?php endforeach; ?> 
     * </code>
     * 
     * If you write your own template files, minimze your css files and try to deliver as few as possible.
     * 
     * @return array
     */
    public function getCssFiles() {
        return $this->cssFiles;
    }
    
    /**
     * Removes all css files.
     *
     * @return void
     */
    public function flushCssFiles() {
        $this->cssFiles = array();
    }
    
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
    public function registerAssignHook($name, $class, $method) {

        if (method_exists($class, $method)) {
            $this->assignHooks[$name] = array(
                    'class'     =>  $class,
                     'method'   =>  $method
            );
        } else {
            throw new InvalidArgumentException('Given method or class does not exist or is not used. Did you require_once it?', 1348567047);
        }
    }

    /**
     * Returns all already assigned values.
     *
     * @return array
     */
    public function getAssignments() {
        return $this->view;
    }
    
    /**
     * Assigns variables to the view and calls hooks if registered.
     * 
     * @param string $key
     * @param mixed $value
     * @throws InvalidArgumentException if given key is not a string
     * @return Elefunds_View_ViewInterface
     */
    public function assign($key, $value) {
        if (is_string($key)) {
            $this->view[$key] = $value;

            if (isset($this->assignHooks[$key])) {
                call_user_func_array(
                    array(
                        $this->assignHooks[$key]['class'],
                        $this->assignHooks[$key]['method']
                    ),
                    array($this, $value)
                );
            }

        } else {
            throw new InvalidArgumentException('Given key must be a string.', 1347988964615);
        }
        return $this;
    }
    
    /**
     * Add multiple variables to the view.
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2).
     * @return Elefunds_View_ViewInterface
     */
    public function assignMultiple(array $values) {
        foreach($values as $key => $value) {
            $this->assign($key, $value);
        }
        return $this;
    }


    /**
     * Renders the given output.
     *
     * @param string $templateName name of the template to render
     * @param bool $givenTemplateNameIsAbsolutePathWithFullyQualifiedFilename
     * @throws Elefunds_Exception_ElefundsException
     *
     * @return string the rendered HTML
     */
    public function render($templateName = 'View', $givenTemplateNameIsAbsolutePathWithFullyQualifiedFilename = FALSE) {

        if ($givenTemplateNameIsAbsolutePathWithFullyQualifiedFilename) {
            $filepath = $templateName;
        } else {
            $filepath = dirname(__FILE__) . '/../Template/' . $this->template . '/' . $templateName . '.phtml';
        }

        if (file_exists($filepath)) {
            $view = $this->view;
        
            ob_start();
            include_once $filepath;
            $output = ob_get_contents();
            ob_end_clean();
            
            return $output;           
        } else {
            throw new Elefunds_Exception_ElefundsException('View.phtml not found in your template folder.',
                                                                    1348041578910, 
                                                                    array('filepath' => $filepath));
        }
    }
    
     /**
     * Add your css file with it's pure file name (e.g. 'styles.css') and save it
     * as /Template/YourTemplateFolder/Css/styles.css
     * 
     * @param string $file
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_ViewInterface
     */
    public function addCssFile($file) {
        $templateFolder = ucfirst(strtolower($this->template));
        $filepath = 'Template/' . $templateFolder . '/Css/' . $file;
        if (file_exists(dirname(__FILE__) . '/../' .  $filepath)) {
            $this->cssFiles[] = $filepath;
        } else {
            throw new Elefunds_Exception_ElefundsException('Given CSS file ' . $file . ' does not exist.',
                                                                    1348041578905, 
                                                                    array('filepath' => $filepath));
        }
    }
    
    /**
     * Add your css files. 
     * 
     * Wrapper for addCss($file).
     * 
     * @param array $files
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_ViewInterface
     */
    public function addCssFiles(array $files) {
        foreach ($files as $file) {
            $this->addCssFile($file);
        }
    }
    
    /**
     * Add your js file with it's pure file name (e.g. 'myjavascript.js') and save it
     * as /Template/YourTemplateFolder/Javascript/myjavascript.js
     * 
     * @param string $file
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_ViewInterface
     */
    public function addJavascriptFile($file) {
        $templateFolder = ucfirst(strtolower($this->template));
        $filepath = 'Template/' . $templateFolder . '/Javascript/' . $file;
        if (file_exists(dirname(__FILE__) . '/../' . $filepath)) {
            $this->javascriptFiles[] = $filepath;
        } else {
            throw new Elefunds_Exception_ElefundsException('Given Javascript file ' . $file . ' does not exist.',
                                                                    1348041578907,
                                                                    array('filepath' => $filepath));
        }
    }
    
    /**
     * Add your js files. 
     * 
     * Wrapper for addJavascriptFiles($file).
     * 
     * @param array $files
     * @throws Elefunds_Exception_ElefundsException if file does not exist
     * @return Elefunds_View_ViewInterface
     */
    public function addJavascriptFiles(array $files) {
        foreach ($files as $file) {
            $this->addJavascriptFile($file);
        }
    }
     
}