<?php
class Vizualizar extends TWindow
{
    private $html;   
   
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param)
    {
       
    try{
        parent::__construct();   
        parent::setTitle('VIZUALIZAÇÃO DE DADOS DO PERFIL CADASTRADO'); 
        
        parent::include_css('app/resources/vizualiza.css');
        $this->html = new THtmlRenderer('app/resources/vizualizar.html');
        TTransaction::open('sigp');
        $pessoa = new Pessoa($param['key']);
        $replaces = array();
        $replaces['id']= $pessoa->id;
        $replaces['nome']= $pessoa->nome;
        $replaces['rg']= $pessoa->rg;
        $replaces['cpf']= $pessoa->cpf;
        $replaces['datanascimento']= $pessoa->datanasc;
        $replaces['logradouro']= $pessoa->endereco->logradouro;
        $replaces['cep']= $pessoa->endereco->cep;
        $replaces['numero']= $pessoa->endereco->numero;
        $replaces['bairro']= $pessoa->endereco->bairro;
        $replaces['sexo']= $pessoa->sexo == 1 ? 'Masculino': "Feminino";
        $replaces['tiposangue']= $pessoa->tiposangue;
        $replaces['nomeguerra']= $pessoa->nomeguerra;
        $replaces['dataentrada']= $pessoa->dataentrada;
        $replaces['postograduacao']= $pessoa->postoGraduacao->nomeposto;
        
   
        $replaces_contatos = array();
        foreach($pessoa->getContatos() as $contato)
        {
            $replaces_contatos[] = array('tipo'=>$contato->tipo,
                                         'valor'=>$contato->nomecontato);
        }
      
        
        
        $this->html->enableSection('main', $replaces);
        $this->html->enableSection('contatos', $replaces_contatos, TRUE);
        TTransaction::close();
        
        parent::add($this->html);
     }catch(Execption $e)
     {
         new TMessage('error', $e->getMessage());
     }
    
    }
    
    public function onVizualizar($param)
    {
      
    }
    
    
 }
