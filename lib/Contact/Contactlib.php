<?php

class Contact_Contactlib
{
    /**
     * Send and store the message
     * $meta array of key=>value
     *
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $text
     * @param array $meta
     * @return boolean
     */
    public function sendMessage($sender, $receiver, $subject, $text, $meta = null)
    {
        $validator = new Zend_Validate_EmailAddress();
        $validMail1 = $validator->isValid($sender);
        $validMail2 = $validator->isValid($receiver);

        $filter = new Zend_Filter_HtmlEntities();
        $subject = $filter->filter($subject);

        if ((is_array($meta) || $meta == null) && $validMail1 == true && $validMail2 == true && $subject != "") {

            $table = new Contact_Contact();
            $table->create($sender, $receiver, $subject, $text, $meta);

            $this->_send($sender, $receiver, $subject, $text, $meta);
            return true;
        } else {
            return false;
        }
    }

    private function _send($sender, $receiver, $subject, $text, $meta)
    {
        $info = "";
        if ($meta != null) {
            foreach ($meta as $k => $v) {
                $info .= "<b>" . $k . " : </b>" . $v . "<br/>";
            }
        }
        $mail = Pimcore_Tool::getMail(array(), $subject);
        $mail->setBodyHtml($info . $text);
        $mail->clearFrom();
        $mail->setFrom($sender);
        $mail->addTo($receiver);
        $mail->send();

        return true;
    }

}
