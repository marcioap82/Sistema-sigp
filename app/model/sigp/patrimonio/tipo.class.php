<?php
class tipo extends TRecord
{
    const TABLENAME = 'tipo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('nometipo');
          
       
        }
        
       
        
        
    }


