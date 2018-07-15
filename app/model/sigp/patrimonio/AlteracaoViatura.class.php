<?php
class AlteracaoViatura extends TRecord
{
    const TABLENAME = 'alteracaoviatura';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
       private $status;
       private $local;
       private $viatura;
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('dataevento');
            parent::addAttribute('local_id');
            parent::addAttribute('status_id');
            parent::addAttribute('descricao');
            parent::addAttribute('id_viatura');
         
          }
          
     public function get_viatura()
     {
       if(empty($this->viatura))
       {
           $this->viatura = new Viatura($this->id_viatura);
       } 
       return $this->viatura;
     }
      
      
      public function get_status()
      {
          if(empty($this->status))
          {
              $this->status = new Status($this->status_id);
          }
          return $this->status;
      }

}