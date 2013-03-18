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
 * Pimcore API plugin class.
 *
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
class PimContact_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface
{
    const DB_TABLE = 'plugin_pimcontact';

    public static function install()
    {
        $queries = array(
            sprintf('CREATE TABLE IF NOT EXISTS `%s` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `sender` TEXT NOT NULL,
                `receiver` TEXT NULL,
                `subject` TEXT NULL,
                `text` LONGTEXT NULL,
                `meta` TEXT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;', self::DB_TABLE)
        );

        if (self::_executeQueries($queries)) {
            return 'PimContact Plugin successfully installed.';
        } else {
            return 'PimContact Plugin could not be installed. See debug log for more details.';
        }
    }

    public static function needsReloadAfterInstall()
    {
        return true;
    }

    public static function uninstall()
    {
        $queries = array(sprintf('DROP TABLE `%s`', self::DB_TABLE));

        if (self::_executeQueries($queries)) {
            return "PimContact Plugin successfully uninstalled.";
        } else {
            return "PimContact Plugin could not be uninstalled";
        }
    }

    public static function isInstalled()
    {
        try {
            Pimcore_API_Plugin_Abstract::getDb()->describeTable(self::DB_TABLE);
            $result = true;
        } catch (Zend_Db_Statement_Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH . "/PimContact/static/texts";
    }

    /**
     * @param string $language
     * @return string path to the translation file relative to plugin direcory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory() . "/" . $language . ".csv")) {
            return "/PimContact/static/texts/" . $language . ".csv";
        } else {
            return "/PimContact/static/texts/en.csv";
        }
    }

    /**
     * Executes queries in single transaction.
     *
     * @param array $queries
     * @return boolean
     */
    protected static function _executeQueries(array $queries)
    {
        $db = Pimcore_API_Plugin_Abstract::getDb()->getResource();
        try {
            $db->beginTransaction();
            foreach ($queries as $query) {
                $db->query($query);
            }
            $db->commit();
            return true;
        } catch (Zend_Db_Exception $e) {
            $db->rollBack();
            logger::crit($e->getMessage());
            return false;
        }
    }

}
