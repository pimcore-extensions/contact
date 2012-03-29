<?php

class Contact_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {

    public static function install() {
        Pimcore_API_Plugin_Abstract::getDb()->exec("CREATE TABLE IF NOT EXISTS `plugin_contact` (
		`id` INT NOT NULL AUTO_INCREMENT,
                `sender` TEXT NOT NULL ,
		`receiver` TEXT NULL ,
                `subject` TEXT NULL ,
                `text` LONGTEXT NULL ,
                `meta` TEXT NULL ,
		`date` INT NULL ,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");        

        if (self::isInstalled()) {
            $statusMessage = "Contact Plugin successfully installed.";
        } else {
            $statusMessage = "Contact Plugin could not be installed";
        }
        return $statusMessage;
    }

    public static function needsReloadAfterInstall() {
        return true;
    }

    public static function uninstall() {
        Pimcore_API_Plugin_Abstract::getDb()->exec("DROP TABLE `plugin_contact`");
        

        if (!self::isInstalled()) {
            $statusMessage = "Contact Plugin successfully uninstalled.";
        } else {
            $statusMessage = "Contact Plugin could not be uninstalled";
        }
        return $statusMessage;
    }

    public static function isInstalled() {
        $result = null;
        try {
            $result = Pimcore_API_Plugin_Abstract::getDb()->describeTable("plugin_contact");
        } catch (Zend_Db_Statement_Exception $e) {

        }
        return!empty($result);
    }

    /**
     *
     * @param string $language
     * @return string path to the translation file relative to plugin direcory
     */
    public static function getTranslationFile($language) {
        if ($language == "fr") {
            return "/Contact/texts/fr.csv";
        } else {
            return "/Contact/texts/en.csv";
        }
    }

}