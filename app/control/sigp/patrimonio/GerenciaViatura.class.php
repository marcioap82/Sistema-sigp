<?php
class GerenciaViatura extends TPage
{
    private $form; // form
    private $tipo;
    private $viatura;
    private $filtro;
    private $criterio;
    private $local;
    
    
  
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
   
        $this->form = new TForm();
       
                   
        $tabela2 = new TTable();  
        $tabela2->width="900"; 
        $row = $tabela2->addRow();
        $cell = $row->addCell('Informar Alteraçoes no Veiculo');
       
        $cell->style="box-shadow: 10px -9px 4px #fff;";  
        $id = new TEntry('id_viatura');        
        $data = new TDate('dataevento');
      
     
          $local= new TDBCombo('local_id', 'sigp', 'LocalAlteracao', 'id', 'nomealteracao','id');
          $local->setTip('Informe a localização da alteração: Ex: motor, lataria');
          $local->setTip('Informe a localização da alteração: Ex: motor, lataria');
              
        $status = new TDBCombo('status_id','sigp','Status', 'id', 'nomestatus', 'id');
        
        $descricao = new THtmlEditor('descricao');
        $descricao->setSize(700, 250);
       
        $button1 = new TButton('action1');
        $button1->setAction(new TAction(array($this, 'onSalvar')), 'Salvar');
        $button1->class = 'btn btn-info';
        $button1->setImage('fa:far fa-save #000');
        $button2 = new TButton('action2');
        $button2->setAction(new TAction(array('ConsultarViatura', 'onReload')), 'Lista de Viaturas');
        $button2->class = 'btn btn-success';
        $button2->setImage('fa:fas fa-car #000');
        
        
        $id->setEditable(false);
        $cell->colspan="6";
        $cell->align="center";
        $cell->style=" font-size:20px; border: 1px solid #ccc; color:#fff; background:#428bca;";
        $row = $tabela2->addRow();
        $cell = $row->addCell('id:');        
        $cell = $row->addCell($id);  
        $row = $tabela2->addRow();
        $cell = $row->addCell('Data do Evento:');        
        $cell = $row->addCell($data);      
        $row = $tabela2->addRow();      
        $cell = $row->addCell('Localização:');
        $cell = $row->addCell($local);
        $row = $tabela2->addRow();      
        $cell = $row->addCell('Status da Viatura:');
        $cell = $row->addCell($status);
        $row = $tabela2->addRow();
        $cell = $row->addCell('Descrição do Problema:');
        $cell = $row->addCell($descricao);
        $row = $tabela2->addRow();
        $tabela3 = new TTable();
        $cell = $row->addCell($tabela3);
      
        $row = $tabela3->addRow();              
        $cell = $row->addCell($button1);
        $cell = $row->addCell($button2);
        
        
        $this->form->add($tabela2);
               
        $vbox = new TVBox;
        $vbox->add($this->form);
        $this->form->setFields(array($button1, $button2, $id, $data, $local, $status, $descricao));
     
        parent::add($vbox);
        
    }
    public function onSalvar()
    {
        try{
               
           TTransaction::open('sigp');
            $dados = $this->form->getData('AlteracaoViatura');
            
            $dados->store();
            new TMessage('info', 'Dados armazenados com sucesso');
               $dados = $this->form->setData($dados);
            
             TTransaction::close();
           //     TApplication::loadPage('ConsultarViatura', 'onReload');
           
           
    }catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
      public function onGerenciar($param)
      {
             $alteracao = new stdClass;
             $alteracao->id_viatura = $param['id'];
              
           $this->form->setData($alteracao);
            
      } 
      
       public function onClear()
    {
        $this->form->clear();
    }
    
      public function onEdit($param)
      {
          $id = $param['key'];
          $objeto = new stdClass;
          $objeto->id_viatura = $id;
          $this->form->setData($objeto);
          
          
          
      }
         
    
 }

