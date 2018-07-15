<?php
class PessoaAudiencia extends TRecord
{
    const TABLENAME = 'pessoa_audiencia';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('id_pessoa');
            parent::addAttribute('id_audiencia');
           
       
        }
        
       
       
        
        
    }
