<?php 
/**
 * This file is part of the FUPAPP.
 * 
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 * 
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * GlobalSettings Config
 * 
 * It contains information on internal system configurations, such as the number
 * of records per page among others.
 * 
 * @package		FUPAPP
 * @subpackage	Config
 * @category	Config
 * @author		FUPAPP Dev Team
 */
class GlobalSettings extends BaseConfig{

    /**
     * defines the number of records per paging.
     *
     * @var int
     */
    public $regPerPage = 10;

}

?>