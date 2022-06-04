<?php

/**
 * This file is part of the FUPAPP.
 *
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */


namespace App\Entities;

use CodeIgniter\Entity\Entity;

use App\Models\DailyMovementsDetailsModel;

/**
 * Daily movement Entity
 *
 * @package    FUPAPP
 * @subpackage Entity
 * @category   Entity
 * @author     FUPAPP Dev Team
 */
class DailyMovement extends Entity
{

    protected function getDetails(){
        
        $Details = model('DailyMovementsDetailsModel');
        $MovementDetails = $Details
            ->where('id_movement', $this->attributes['id_movement'])
            ->findAll();

        return $MovementDetails;
    }
}
