<?php
namespace App\Entities;

use CodeIgniter\Entity\Entity;

use App\Models\DailyMovementsModel;

class AccountingPeriod extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    protected function getDailyMovements(){
        
        $DailyMovements = model('DailyMovementsModel');
        $PeriodDailyMovements = $DailyMovements
            ->where('id_period', $this->attributes['id_period'])
            ->findAll();


        foreach ($PeriodDailyMovements as $key => $movement) {

            $details = $movement->details;
            $sumdebe = 0;
            $sumhaber = 0;

            foreach ($details as $detail) {
                if($detail->movement_type == 0) $sumdebe += $detail->value;
                else if($detail->movement_type == 1) $sumhaber += $detail->value;
            }

            // NOTA: 'details' es una palabra reservada de codeigniter,
            // por lo que no se le puede adjuntar la propiedad 'details'
            // a la clase Codeigniter/Entity, descubierto luego de 1h :(
            // adjuntamos la propiedad movement_details (en vez de details)
            // donde iran los detalles de cada movimiento del periodo
            $PeriodDailyMovements[$key]->{'movement_details'} = $details;
            $PeriodDailyMovements[$key]->{'sum_debe'} = number_format($sumdebe,2);
            $PeriodDailyMovements[$key]->{'sum_haber'} = number_format($sumhaber,2);
        }

        return $PeriodDailyMovements;
    }
}


?>