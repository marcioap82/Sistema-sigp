<?php
class Pessoa extends TRecord
{
    const TABLENAME = 'Pessoa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
 
    /**
     * Constructor method
     */
   private $endereco;
   private $contatos;
   private $posto;
   

     
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('rg');
        parent::addAttribute('cpf');       
        parent::addAttribute('datanasc');
        parent::addAttribute('id_endereco');
        parent::addAttribute('id_posto');
        parent::addAttribute('sexo');
        parent::addAttribute('dataentrada');
        parent::addAttribute('tiposangue');
        parent::addAttribute('nomeguerra');
        
      }
      
      public function SomaData()
      {
         $partes1 = explode("-", $this->datanasc);
          return (int)$partes1[1];
      }
      
      
      public function set_endereco(endereco $object)
        {      
            $this->endereco = $object;
            $this->id_endereco = $object->id;
            
        }
        
        public function get_endereco()
        {
            // loads the associated object
            if (empty($this->endereco))
            {
                $this->endereco = new endereco($this->id_endereco);
            }
            // returns the associated object
            return $this->endereco;
        }
       
        
        public function get_postoGraduacao()
        {
            // loads the associated object
            if (empty($this->posto))
            {
                $this->posto = new PostoGraduacao($this->id_posto);
        
            // returns the associated object
            
           }
           return $this->posto;
        }
        
         public function AddContatos(Contatos $contato)
        {
            $this->contatos[] = $contato;
        }
        
         public function getContatos()
       {
          return $this->contatos;
       }
       
       public function clearParts()
    {
        $this->contatos = array();
    }
    
    public function load($id)
    {
        $this->contatos = parent::loadComposite('Contatos', 'id_pessoa', $id);
    
        // load the object itself
        return parent::load($id);
    }
    public function store()
    {
        // store the object itself
        parent::store();
    
        parent::saveComposite('Contatos', 'id_pessoa', $this->id, $this->contatos);
    }
        
       
        
        
    }
