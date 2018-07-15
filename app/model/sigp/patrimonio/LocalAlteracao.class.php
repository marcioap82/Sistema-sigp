<?php
class LocalAlteracao extends TRecord
{
    const TABLENAME = 'lacalalteracao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
      
       private $tipo;
        public function __construct($id = NULL)
        {
            parent::__construct($id);           
            parent::addAttribute('nomealteracao');
            parent::addAttribute('id_tipo');
           
         
          }
          
          public function set_tipo(tipo $tipo)
          {
        
               $this->tipo = $tipo;
            
          }
          
          public function get_tipo()
          {
              if($this->tipo)
              {
                 $this->tipo = new tipo($this->id_tipo);
              }
              return $this->tipo;
          }
      }



