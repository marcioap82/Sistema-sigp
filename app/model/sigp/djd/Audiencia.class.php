<?php
class Audiencia extends TRecord
{
    const TABLENAME = 'audiencia';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     private $pessoa;
     
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('data_da_audiencia');
            parent::addAttribute('local_da_audiencia');
            parent::addAttribute('hora_da_audiencia');
       
        }
        
     
       
        public function getPessoa()
    {
        return $this->pessoa;
    }
      public function addPessoa(Pessoa $objeto)
       {
          $this->pessoa[] = $objeto; 
       }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->pessoa = array();
    }
      public function store()
        {
        
          parent::store();
    
        parent::saveAggregate('PessoaAudiencia', 'id_audiencia', 'id_pessoa', $this->id, $this->pessoa);                         
        } 
       
       public function load($id)
    {
        $this->pessoa = parent::loadAggregate('Pessoa', 'PessoaAudiencia', 'id_audiencia', 'id_pessoa', $id);
    
        // load the object itself
        return parent::load($id);
    }
        
     
 }
