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
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 * @license     http://www.modernweb.pl/license/new-bsd     New BSD License
 */

/**
 * Basic contact form.
 *
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
class PimContact_Form extends Zend_Form
{
    public function init()
    {
        $this->setIsArray(true);
        $this->setElementsBelongTo('contact');

        $this->addElement('text', 'name', array(
            'label' => 'Name',
            'dimension' => 4,
            'required' => true,
            'filters' => array(
                new Zend_Filter_StringTrim(),
                new Zend_Filter_StripTags(),
            ),
            'validators' => array(
                new Zend_Validate_StringLength(array('min' => 4, 'encoding' => 'utf-8')),
            ),
        ));

        $this->addElement('text', 'email', array(
            'label' => 'E-mail',
            'dimension' => 4,
            'required' => true,
            'filters' => array(
                new Zend_Filter_StringTrim(),
                new Zend_Filter_StripTags(),
            ),
            'validators' => array(
                new Zend_Validate_EmailAddress(),
            ),
        ));

        $this->addElement('textarea', 'message', array(
            'label' => 'Message',
            'dimension' => 4, 'rows' => 10, 'cols' => 20,
            'required' => true,
            'filters' => array(
                new Zend_Filter_StringTrim(),
                new Zend_Filter_StripTags(),
            ),
            'validators' => array(
                new Zend_Validate_StringLength(array('min' => 20, 'encoding' => 'utf-8')),
            ),
        ));

        $this->addElement('captcha', 'cpt', array(
            'label' => 'Type the characters you see in the picture',
            'dimension' => 4,
            'captcha' => 'Image',
            'captchaOptions' => array(
                'wordLen' => 5,
                'timeout' => 300,
                'width' => 120,
                'dotNoiseLevel' => 2,
                'font' => PIMCORE_PLUGINS_PATH . '/PimContact/static/fonts/bebas.ttf',
                'fontSize' => 24,
                'imgDir' => PIMCORE_TEMPORARY_DIRECTORY . '/',
                'imgUrl' => str_replace(PIMCORE_DOCUMENT_ROOT, '', PIMCORE_TEMPORARY_DIRECTORY) . "/",
            )
        ));

        $this->addElement('button', 'submit', array(
            'label' => 'Send message',
            'type' => 'submit',
        ));
    }

}
