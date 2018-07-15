<?php
class endereco extends TRecord
{
    const TABLENAME = 'endereco';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('logradouro');
            parent::addAttribute('cep');
            parent::addAttribute('bairro');
            parent::addAttribute('numero');
           
            
        }
        
        
    }
