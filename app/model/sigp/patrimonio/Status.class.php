<?php
class Status extends TRecord
{
    const TABLENAME = 'status';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
      
     
        public function __construct($id = NULL)
        {
            parent::__construct($id);           
            parent::addAttribute('nomestatus');
            
           
         
          }
 }

?>