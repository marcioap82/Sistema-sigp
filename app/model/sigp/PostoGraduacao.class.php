
<?php
class PostoGraduacao extends TRecord
{
    const TABLENAME = 'postograduacao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
  
     
    public function __construct($id = NULL)
      {
        parent::__construct($id);
        parent::addAttribute('nomeposto');
       
        
      }
  }
