<?php

/**
 * ModernWeb
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.modernweb.pl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@modernweb.pl so we can send you a copy immediately.
 *
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @subpackage  Controller
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 * @license     http://www.modernweb.pl/license/new-bsd     New BSD License
 */

/**
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @subpackage  Controller
 * @author      Rafał Gałka <rrafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
abstract class PimContact_Controller_Action extends Website_Controller_Action
{
    /**
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * @var Zend_Locale
     */
    protected $_locale;

    /**
     * @var Zend_Translate
     */
    protected $_translate;

    /**
     * @var Modern_Controller_Action_Helper_FlashMessenger
     */
    protected $_messenger;

    public function init()
    {
        parent::init();

        try {
            $this->_locale = Zend_Registry::get('Zend_Locale');
        } catch (Exception $e) {
            $this->_locale = new Zend_Locale('en');
            Zend_Registry::set('Zend_Locale', $this->_locale);
        }
        $this->view->locale = $this->_locale;

        $this->_translate = $this->initTranslation();

        $this->view->setScriptPath(
            array_merge(
                $this->view->getScriptPaths(),
                array(
                    PIMCORE_WEBSITE_PATH . '/views/scripts/',
                    PIMCORE_WEBSITE_PATH . '/views/layouts/',
                )
            )
        );

        // additional helpers
        $this->_helper->addPrefix('Modern_Controller_Action_Helper');
        $this->view->addHelperPath('Modern/View/Helper', 'Modern_View_Helper_');

        $this->_messenger = $this->_helper->getHelper('FlashMessenger');
        Modern_View_Helper_FlashMessenger::setPartial('partial/messenger.php');
    }

}
