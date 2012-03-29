<?php


class Contact_Contact {

    private $table;
    
    public function init(){
Zend_Db_Table::setDefaultAdapter(Pimcore_Resource_Mysql::get());

//        $db =Zend_Db::factory('PDO_MYSQL', array(
//    'host'     => 'localhost',
//    'username' => 'root',
//    'password' => '',
//    'dbname'   => 'pimcore'
//));
//        Newsletter_DbTable_Shortcuts::setDefaultAdapter($db);
        $this->table = new Contact_DbTable_Contact();
        
    }
    /**
     *
     * @param <type> $sender
     * @param <type> $receiver
     * @param <type> $subject
     * @param <type> $text
     * @param <type> $meta
     * @return <type>
     */
    public function create($sender,$receiver,$subject,$text,$meta){
        $this->init();
        $metadata = Zend_Json_Encoder::encode($meta);

        $this->table->insert(array("sender"=>$sender,"receiver"=>$receiver,"subject"=>$subject,"text"=>$text,"meta"=>$metadata,"date"=>time()));
        
        return true;
    }   
   

    public function read(){
        
        $rows = $this->table->fetchAll();
       
        return $rows->toArray();
    }


}