
<?php
class Contatos extends TRecord
{
    const TABLENAME = 'contatos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
     private $tipocontato;
        public function __construct($id = NULL)
        {
            parent::__construct($id);
            parent::addAttribute('nomecontato');
            parent::addAttribute('id_tipocontato');
            parent::addAttribute('id_pessoa');
       
        }
        
       public function get_tipocontato()
       {
           if(empty($this->tipocontato))
           {
               $this->tipocontato = new tipocontato(id_tipocontato); 
           }
           return $this->tipocontato;
       }
       
       
        
        
    }

