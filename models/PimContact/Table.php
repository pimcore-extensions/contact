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
 * @category    Pimcore
 * @package     Plugin_PimContact
 * @author      Rafał Gałka <rafal.galka@modernweb.pl>
 * @copyright   Copyright (c) 2007-2013 ModernWeb (http://www.modernweb.pl)
 */
class PimContact_Table extends Zend_Db_Table_Abstract
{
    protected $_name = PimContact_Plugin::DB_TABLE;
    protected $_primary = 'id';

}
