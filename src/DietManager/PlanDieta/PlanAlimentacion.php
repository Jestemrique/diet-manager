<?php

namespace Epr\Dietmanager\PlanAlimentacion;

use Epr\Database;

class PlanAlimentacion{

    private $plan_id = null;
    private $plan_name = null;



    public function __construct(
        private \Epr\DietManager\Database\Database $eprdb
    ){
    }//End construct();

    public function creaNuevoPlanAlimentacion(){
        echo "Crear nuevo plan alimentación";
    }//End creaNuevoPlanAlimentacion();


}//End PlanAlimentacion.