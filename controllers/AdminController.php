<?php

class PimContact_AdminController extends Pimcore_Controller_Action_Admin
{
    public function historyAction()
    {
        $action = $this->_getParam('xaction');

        if ($action == null) {
            $action = $this->getRequest()->getPost('xaction');
        }

        if ($action == 'read') {
            $contact = new PimContact();
            $data = $contact->getTable()->fetchAll()->toArray();
            $i = 0;
            foreach ($data as &$row) {
                $row['metadata'] = '';
                $temp = Zend_Json::decode($row['meta']);
                if ($temp != '') {
                    foreach ($temp as $k => $v) {
                        $row['metadata'] .= "<b>$k:</b> $v<br/>";
                    }
                }
                $i++;
            }

            $this->_helper->json(array('data' => $data, 'success' => true));
        }

        $this->removeViewRenderer();
    }

}
