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
 * Core plugin model class.
 *
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
class PimContact
{
    /**
     * @var PimContact_Table
     */
    protected $_table;

    public function __construct()
    {
        $db = Pimcore_Resource_Mysql::get();
        if (Pimcore_Version::$revision > 1350) {
            Zend_Db_Table::setDefaultAdapter($db->getResource());
        } else {
            Zend_Db_Table::setDefaultAdapter($db);
        }

        $this->_table = new PimContact_Table();
    }

    /**
     * @return PimContact_Table
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Send and store the message
     * $meta array of key=>value
     *
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $text
     * @param array $meta
     */
    public function sendMessage($sender, $receiver, $subject, $text, array $meta = null)
    {
        $row = array(
            'sender' => $sender,
            'receiver' => $receiver,
            'subject' => $subject,
            'text' => $text,
        );
        if (!empty($meta)) {
            $row['meta'] = Zend_Json::encode($meta);
        }
        $this->_table->insert($row);

        $this->_send($sender, $receiver, $subject, $text, $meta);
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $text
     * @param array $meta
     */
    private function _send($sender, $receiver, $subject, $text, array $meta = null)
    {
        $info = "";
        if (!empty($meta)) {
            foreach ($meta as $k => $v) {
                $info .= "<b>" . $k . " : </b>" . $v . "<br/>";
            }
        }
        $mail = Pimcore_Tool::getMail(array(), $subject);
        $mail->setBodyHtml($info . $text);
        $mail->setReplyTo($sender);
        $mail->addTo($receiver);
        $mail->send();
    }

}
