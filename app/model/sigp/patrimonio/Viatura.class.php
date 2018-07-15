<?php
class Viatura extends TRecord
{
    const TABLENAME = 'viatura';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     private $tipo;
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('ano');
            parent::addAttribute('modelo');
            parent::addAttribute('marca');
            parent::addAttribute('chassi');
            parent::addAttribute('cor');
            parent::addAttribute('placa');
            parent::addAttribute('numero');
            parent::addAttribute('quilometro');
            parent::addAttribute('id_tipo');   
            parent::addAttribute('fabricante');         
       
        }
        
         public function set_tipo(tipo $object)
        {      
            $this->tipo = $object;
            $this->id_tipo= $object->id;
            
        }
        
        public function get_tipo()
        {
            // loads the associated object
            if (empty($this->tipo))
            {
                $this->tipo= new tipo($this->id_tipo);
            }
            // returns the associated object
            return $this->tipo;
        }
        
        
       
        
        
    }

