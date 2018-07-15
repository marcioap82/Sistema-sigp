<?php
class tipocontato extends TRecord
{
    const TABLENAME = 'tipocontato';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('tipo');
            
       
        }
        
       
        
        
    }
