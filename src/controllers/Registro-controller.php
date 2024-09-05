<?php 

include './src/models/Registro.model.php';

    class Registro {

        private $registro;
       
        public function registro($parametros){
            $this->registro = new RegistroModel();
            $registro = $this->registro->registro($parametros);
            echo $registro ;
            
        }

    }