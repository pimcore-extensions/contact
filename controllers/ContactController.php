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
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
class Pimcontact_ContactController extends PimContact_Controller_Action
{
    const CONFIG_RECIPIENT_OPTION = 'contact_recipient';
    const CONFIG_SUBJECT_OPTION = 'contact_subject';

    public function formAction()
    {
        $this->enableLayout();

        $action = $this->document->getFullPath();

        $form = new PimContact_Form();
        $form->setAction($action);

        $data = $this->_request->getPost('contact');
        if ($data && $form->isValid($data)) {

            $options = Pimcore_Config::getWebsiteConfig()->toArray();
            if (!isset($options[self::CONFIG_RECIPIENT_OPTION])) {
                throw new Exception(
                    'Undefined recipient address. Define email as ' .
                    self::CONFIG_RECIPIENT_OPTION . ' in website settings'
                );
            }

            $values = $form->getValues(true);

            $sender = $values['email'];
            $recipient = $options[self::CONFIG_RECIPIENT_OPTION];
            $subject = isset($options[self::CONFIG_SUBJECT_OPTION])
                ? isset($options[self::CONFIG_SUBJECT_OPTION])
                : sprintf($this->_translate->_('Contact message from %s'), $this->_request->getHttpHost());
            $message = nl2br($values['message']);
            unset($values['email'], $values['message'], $values['cpt']);

            $contact = new PimContact();

            try {
                $contact->sendMessage($sender, $recipient, $subject, $message, $values);
                $ret = true;
            } catch (Exception $e) {
                Logger::crit($e);
                $ret = false;
            }

            if ($ret) {
                $this->_messenger->addMessage(
                    $this->_translate->_('Thank you. We will reply as soon as possible.')
                );
            } else {
                $this->_messenger->addMessage(
                    $this->_translate->_('Upsss... Something went wrong. Try again later.'),
                    Modern_Controller_Action_Helper_FlashMessenger::MESSAGE_ERROR);
            }

            return $this->_redirect($action);
        }

        $this->view->form = $form;
    }

}
