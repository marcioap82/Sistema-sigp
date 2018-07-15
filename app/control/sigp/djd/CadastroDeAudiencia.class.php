<?php
class CadastroDeAudiencia extends TPage
{
    private $form; // form    
    private $notbook;
    private $tabela;
    private $multifilde;
    
    
  
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct($param)
    {
        parent::__construct();
        parent::include_js('app/resources/modifica.js');
        
        $this->form = new BootstrapFormWrapper(new TQuickForm());
        $this->form->style='width:700px;';
        $this->notbook = new BootstrapNotebookWrapper(new TNotebook(700, 100));
        $this->form2 = new TQuickForm('cadastro');
        
        $this->tabela = new TTable();
        $this->tabela->style='width:400px;';
        $this->tabela->add($this->form2);
        $id = new TEntry('id');
        
        $id->setEditable(FALSE);
        $data_audiencia = new TDate('data_da_audiencia');
        $local_da_audiencia = new TEntry('local_da_audiencia');
        $local_da_audiencia->setTip('Cadastre o local de apresentação do policial para a audiência');
        $hora_da_audiencia = new TEntry('hora_da_audiencia');
        $hora_da_audiencia->setTip('Cadastre a Hora de apresentação para a audiência ex: 09:00');
        $valores = array('1° Vara Criminal', '2° Vara Criminal', '3° Vara Criminal', '4° Vara Criminal', '5° Vara Criminal', 'Juizado da infância e juventude');
        $local_da_audiencia->setCompletion($valores);
        $hora_da_audiencia->setMask('99:99');

       
        
         // add the fields inside the form
        $this->form->addQuickField('Id:',    $id,    30);
        $this->form->addQuickField('Data da Audiência:', $data_audiencia, 300);
        $this->form->addQuickField('Local da audiência', $local_da_audiencia, 300);
        $this->form->addQuickField('Hora da Audiência ', $hora_da_audiencia, 50);
        $titulo = new TLabel('Formulario De Cadastro De Audiência');
        $titulo->setFontSize(18);
        $panel = new TPanelGroup($titulo);
        $label = new TLabel('Tela-D001 SIGP versão 1.00');        
       
        $label->setFontSize(10);
        $panel->addFooter($label);                
        $this->notbook->appendPage('Cadastro de Audiencia', $this->form);       
        $panel->add($this->notbook);
        // define the form action 
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSalvar')), 'fa:fas fa-save green fa-2x');
        $this->form->addQuickAction('Limpar', new TAction(array($this, 'onLimpar')), 'fa:check-circle-o green fa-2x');
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));       
       
        $vbox->add($panel);
        
        parent::add($vbox);
           

    }
    public function onSalvar()    
    {
        try{
               
           TTransaction::open('sigp');
           $dados = $this->form->getData('Audiencia');
           
           if(!empty($dados->id)){
               $pessoas = TSession::getValue('pessoa');
               foreach($pessoas as $pessoa)
               {
                   $dados->addPessoa($pessoa);
               }
           
           }
           $dados->store();
           
           new TMessage('info', 'audiencia cadastrada com sucesso');
            $this->form->setData($dados);
            $this->form->addQuickAction('Adicionar Pessoa', new TAction(array('AdicionarPessoa', 'onAdicionar')), 'fa:fas fa-address-card green'); 
           
          
           TTransaction::close();
               
           }catch(Exception $e)
           {
                   new TMessage('error', $e->getMessage());                
           }
    }
    public function onLimpar()
    {
       
    }
    
    
    
    public function onEditar($param)
    {
       TSession::setValue('dados', $param);
       
       try{       
            TTransaction::open('sigp');
            $audiencia = new Audiencia($param['id']);
            $this->form->setData($audiencia);
               //TSession::regenerate();
            TSession::setValue('pessoa', $audiencia->getPessoa());
            
            TTransaction::close();
       
       }catch(Exception $e)
           {
                   new TMessage('error', $e->getMessage());                
           }
    }
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 