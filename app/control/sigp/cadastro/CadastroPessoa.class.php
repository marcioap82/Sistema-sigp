<?php
class CadastroPessoa extends TPage
{
    protected $form; // form
    protected $notebook;
    
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
         $this->form = new TForm;
        
        $this->notebook = new BootstrapNotebookWrapper(new TNotebook(700,200));
        
       
        $this->form->add($this->notebook);
        $page1 = new TTable;
        $page1->style='border-color:#333; padding:1px 2px 3px 2px;';
        $page2 = new TTable;
        $page3 = new TTable;
        $page4 = new TTable; 
                   
        //$page3->border='1';
        $imagem = new TImage('/images/adianti.png');
       
         // adds two pages in the notebook
        $this->notebook->appendPage('Dados Pessoais', $page1);
        $this->notebook->appendPage('Endereço', $page2);
         $this->notebook->appendPage('Dados Profissionais', $page3);
         $this->notebook->appendPage('Contatos', $page4);
        //dados pessoais
        $id = new TEntry('id');
        $id->setEditable(false);
        
        $nome = new TEntry('nome');
        
        $nome->setTip('informe seu nome completo');
        $nome->setSize(400);
        $rg = new TEntry('rg');
        $rg->setTip('informe seu RG civil');
        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99');
        $datanasc = new TDate('datanasc');
        $contatos = new TMultiField('contatos');
        
       
        //dados de endereço
        $logradouro = new TEntry('logradouro');
        $logradouro->setSize(400);
        $cep = new TEntry('cep');
        $tiposangue = new TEntry('tiposangue');
        $tiposangue->setTip('Insira seu tipo sanguinio com fator RH. EX: O+, AB-');
        $nomeguerra = new TEntry('nomeguerra');
        $cep->setMask('99999-999');
        $bairro = new TEntry('bairro');
        $numero = new TEntry('numero');
        $array = array(  1=>'Masculino', 2=>'Feminino');
        $sexo = new TCombo('sexo');
        $postograduacao =   new TDBCombo('id_posto', 'sigp', 'PostoGraduacao', 'id', 'nomeposto');
        $postograduacao->setSize(200);
         $dataentrada = new TDate('dataentrada');
       
        $sexo->addItems([ '1'=>'Masculino', '2'=>'Feminino' ]);        
        $bairro->setSize(300);
      
        //$datanasc->setMask('dd/mm/yyyy');
         $datanasc->setValue('07/03/1982');
        $cpf->setTip('informe seu cpf sem pontos,apenas o numero');
         $page1->addRowSet(new TLabel('ID'), $id );
        $page1->addRowSet(new TLabel('Nome'), $nome );
        $page1->addRowSet(new TLabel('Rg'), $rg );
        $page1->addRowSet(new TLabel('CPF'), $cpf);
        $page1->addRowSet(new TLabel('Data de Nascimento'),$datanasc);
        $page1->addRowSet(new TLabel('Sexo:'), $sexo);
       
        //adiciona na pagian os dados de endereço
        $page2->addRowSet(new TLabel('Logradouro:'), $logradouro);
        $page2->addRowSet(new TLabel('Cep:'), $cep);
        $page2->addRowSet(new TLabel('Bairro'), $bairro);
        $page2->addRowSet(new TLabel('Numero'), $numero);
        $page3->addRowSet(new TLabel('Posto/Graduação:'), $postograduacao);
        $page3->addRowSet(new TLabel('Data de Entrada Na Policia:'), $dataentrada);
        $page3->addRowSet(new TLabel('Tipo Sanguinio'), $tiposangue);
        $page3->addRowSet(new TLabel('Nome de Guerra'), $nomeguerra);
        $vbox2 = new TVBox;       
        $contatos->setHeight(200);
        $contatos->setClass('Contatos');
        $contatos->addField('nomecontato','Contato:',new TEntry('nomecontato'), 300);
        $contatos->addField('id_tipocontato','Tipo:',new TDBCombo('id_tipocontato','sigp', 'tipocontato','id', 'tipo'), 300);
        $vbox2->add($contatos);
        $linha2 = $page4->addRow();
        $linha2->addCell($vbox2);
       
        
        //validação dos campos 
        $nome->addValidation('nome', new TRequiredValidator);
        $rg->addValidation('rg', new TRequiredValidator);
        $cpf->addValidation('cpf', new TRequiredValidator);
        $datanasc->addValidation('datanasc', new TRequiredValidator);
        $logradouro->addValidation('logradouro', new TRequiredValidator);
        $cep->addValidation('cep', new TRequiredValidator);
        $bairro->addValidation('bairro', new TRequiredValidator);
        $numero->addValidation('numero', new TRequiredValidator);
        $sexo->addValidation('sexo', new TRequiredValidator);
        $sexo->addValidation('postograduacao', new TRequiredValidator);
        $sexo->addValidation('dataentrada', new TRequiredValidator);
                  
        $button1 = new TButton('action1');
        $button1->setAction(new TAction(array($this, 'onSalvar')), 'Salvar');
        $button1->setImage('fa:far fa-save');
        
        $button2 = new TButton('action2');
        $button2->setAction(new TAction(array('ConsultaEfetivo', 'onReload')), 'Lista de Efetivo');
        $button2->setImage('fa:fal fa-search');
        
       
         
          $panel = new TPanelGroup('Cadastro de Efetivo');         
          $panel->add($this->form);
          $panel->add($button1);
          $panel->add($button2);
          
          
          $vbox = new TVBox;
        
        
          
         $this->form->setFields(array($nome, $id, $rg, $cpf, $tiposangue, $nomeguerra, $button1,$button2, $datanasc,$logradouro, $cep, $bairro, $numero,$sexo, $postograduacao,$dataentrada, $contatos ));
          $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
          $vbox->add($panel);
          
           parent::add($vbox);
        
        
     }
     
      public function onSalvar($param)
    {
       try{
       TTransaction::open('sigp');
      
         $this->form->validate();
         
         
         $data = $this->form->getData('Pessoa');
      
         $repositorio = new TRepository('Pessoa');
       
        //$objeto = $repositorio->Where('cpf', '=', $param['cpf'])->load();
        
          
            $endereco = new endereco;
            $endereco->logradouro = $data->logradouro;
            $endereco->cep = $data->cep;
            $endereco->numero = $data->numero;
            $endereco->bairro = $data->bairro;
            $endereco->id = $data->id;
            $endereco->store();
            
            $data->set_endereco($endereco); 
                         
            if($data->contatos)
            {
                foreach($data->contatos as $contato)
                {
                    $data->AddContatos($contato);
                }
            }         
            $data->store(); 
            $this->form->setData($data);     
        
        new TMessage('info','Dados Armazenado com Sucesso');
         TTransaction::close();
        }
       
        catch(Exception $e)
        {
            new TMessage('erro', $e->getMessage());
        }
    }
    function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                // get the parameter $key
                $key = $param['id'];
                
                TTransaction::open('sigp');   // open a transaction with database 'samples'
                $object = new Pessoa($key);
                $endereco = $object->endereco;     // instantiates object City
                $this->form->setData($endereco);              
                $object->contatos = $object->getContatos();
                $this->form->setData($object);
             
                  
                TTransaction::close();           // close the transaction
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
