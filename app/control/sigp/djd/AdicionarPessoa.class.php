<?php
class AdicionarPessoa extends TPage
{
    private $form; // form 
    private $multifilde;
    private $id_audiencia;
    
 
   
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        parent::include_js('app/resources/modifica.js');
        $this->form = new TForm('cadastro');
        $tabela = new TTable();
        $this->form->add($tabela);     
        $titulo = new TLabel('Adicionar Pessoa - Audiencia ');
        $id_audiencia = new TEntry('id_audiencia');
        $titulo->setFontSize(14);
        $panel = new TPanelGroup($titulo);        
        $this->multifilde = new TMultiField('multi');
        $this->multifilde->setHeight(200);
        $pesso_id = new TDBSeekButton('id_pessoa', 'sigp','cadastro','Pessoa', 'nome', 'multi_id_pessoa', 'multi_nomepessoa');
        
        $nome = new TEntry('multi_nomepessoa');
        $nome->setEditable(FALSE);
        $nome->setSize(450);
        $this->multifilde->addField('id_pessoa', 'Id:', $pesso_id, 20);
        $this->multifilde->addField('nomepessoa', 'Nome:', $nome, 500);       
       
        $tabela->addRowSet($this->multifilde);
        $button1 = new TButton('action1');
        $button1->setAction(new TAction(array($this, 'onSalvar')), 'Salvar');
        $button1->setImage('fa:far fa-save');
        $tabela->addRowSet($button1);
        $this->form->setFields(array($pesso_id, $nome, $this->multifilde, $button1, $id_audiencia));
        $panel->add($this->form);
        
        parent::add($panel);
        
    }
    public function onAdicionar($param)
    {
     TSession::setValue('dados', $param);
       
       
    }
    public function onSalvar()
    {
       //le as coniguraçoes do email
       $ini = parse_ini_file('app/config/email.ini');
        $pessoas = $this->form->getData();
        $this->id_audiencia = TSession::getValue('dados');
        
        try{  
           TTransaction::open('sigp');
        $audiencia = new Audiencia($this->id_audiencia['id']);
     
       foreach($pessoas->multi as $pes)
       {
           
           $pessoa = new Pessoa($pes->id_pessoa);
           $audiencia->addPessoa($pessoa);
         
          $contatos = $pessoa->getContatos();
          if(isset($contatos))
          {
               //enviar email
               foreach($contatos as $cont)
               {
                   
                  if($cont->id_tipocontato==2)
                    {  
                       $mail = $cont->nomecontato;                 
                       $email = new TMail;                      
                    
                       $email->setFrom($ini['from'], $ini['name']);
                       $email->setSubject('Polícia Militar - 10 BPM - Batalhão de Força Tática - DJD - Informação de Audiencia');
                       $email->setHtmlBody($pessoa->nome.'<br> Infomamos que sua audiencia esta marcada para o dia: '.$audiencia->data_da_audiencia.' Hora: '.$audiencia->hora_da_audiencia.' Local :'.$audiencia->local_da_audiencia);
                       $email->addAddress(trim($mail), $pessoa->name);
                       $email->SetUseSmtp();      
                       $email->SetSmtpHost($ini['host'], $ini['port']);
                       $email->SetSmtpUser($ini['user'], $ini['pass']);
                       $email->setReplyTo($ini['repl']);
                       $email->send();
                     }
                   }
               }
           }
        
      $audiencia->store();
      new TMessage('info', 'Pessoa adicionada a audiência Numero:'.$this->id_audiencia['id'].' com sucesso');
       TTransaction::close();
       }catch(Exception $e)
       {
           new TMessage('error', $e->getMessage());
       }
    }
    
 }