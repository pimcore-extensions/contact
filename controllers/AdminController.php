<?php

class Contact_AdminController extends Pimcore_Controller_Action_Admin {

   

    public function historyAction() {

        $action = $this->_getParam('xaction');
        $datas = $this->_getParam('data');
        
        if ($action == null) {
            $action = $this->getRequest()->getPost('xaction');
            $datas = $this->getRequest()->getPost('data');
         }

       if ($action == "read") {
            $table = new Contact_Contact();
            $table->init();
            $datas = $table->read();
            $i=0;
            foreach ($datas as $data){
               $datas[$i]["metadata"] = "";
               $temp = Zend_Json::decode($data["meta"]);
               if($temp != "") {
                   
                   foreach($temp as $k=>$v){
                       $datas[$i]["metadata"] .= "<b>" . $k . " : </b>" . $v . "<br/>";
                   }
               }               
                $i++;
            }


            
            $this->_helper->json(array("data" => $datas, "success" => true));
        }

        $this->removeViewRenderer();
    }
}