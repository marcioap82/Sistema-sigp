<?php
class VisualizarPessoa extends TWindow
{
    private $form;
    private $datagrid; 
    private $idaudiencia;
   
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
   
   public function __construct($id = NULL)
     {
        parent::__construct($id);
        parent::setTitle('VIZUALIZAÇÃO DE DADOS DO PERFIL CADASTRADO'); 
        $this->form = new TForm;
        
        $this->datagrid = new TDataGrid;
       
        $this->datagrid->style="width='600px'";
        
        
         //cria as colunas do data grid
         $id = new TDataGridColumn('id', 'Id', 'center', 20 );
         $nome = new TDataGridColumn('nome', 'Nome', 'center', 400 );
         $posto= new TDataGridColumn('posto', 'Posto/Graduação', 'center', 200 );
         $guerra= new TDataGridColumn('nomeguerra', 'Nome de Guerra', 'center', 200 );
         
         //adiciona as colunas ao datagrid
         $this->datagrid->addColumn($id);
         $this->datagrid->addColumn($nome);
         $this->datagrid->addColumn($posto);
         $this->datagrid->addColumn($guerra);
         
         //adiciona Ação
         $acao1 = new TDataGridAction(array($this, 'onDelete'));
         $acao1->setImage('fa:trash red');
         $acao1->setLabel('Deletar');
         $acao1->setField('id');
         $this->datagrid->addAction($acao1);
           //adiciona Ação
         $button1 = new TButton('action2');
         $button1->setAction(new TAction(array($this, 'onAdicionar')), 'Adicionar Pessoas');
        $button1->setImage('fa:fas fa-plus');        
       
         $tabela = new TTable();
         $linha = $tabela->addRow();
         $linha->addCell($button1);
         $this->form->setFields(array($button1));
         //cria o modelo do datagrid
         $this->datagrid->createModel();
         $this->form->add($tabela);        
         parent::add($this->datagrid);
         parent::add($this->form);
         
         
     }
     
          
    public function onVisualizar($param)
    {
       
        $this->datagrid->clear();
        TSession::setValue('objetos', $param);
        try{
              
        TTransaction::open('sigp');       
        //var_dump();
         $audiencia = TSession::getValue('objetos');

         //var_dump($audiencia);
         $this->id_audiencia = array();
         $this->idaudiencia = new Audiencia($audiencia['key']);
        $this->datagrid->clear();
        foreach($this->idaudiencia->getPessoa() as $pessoa)
        {
             
            $item = new stdClass;
            $item->id = $pessoa->id;
            $item->nome = $pessoa->nome;
            $item->posto = $pessoa->postoGraduacao->nomeposto;
            $item->nomeguerra = $pessoa->nomeguerra; 
                     
            $this->datagrid->addItem($item);
          
           // var_dump($item);
             
        }
      
       
         TTransaction::close();

       } catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }

    }   
     public function onReload($param) 
     {
       
         
         //$this->onVisualizar($param);
         //adiciona os dados ao datagrid
        
     }
     
     public function onDelete($param)
     {  
         //pega a chave do registro
         $key = $param['key'];
         
         //cria ação
         $acao2 = new TAction(array($this, 'delete'));
         
         //define paramentros
         $acao2->setParameter('key', $key);
         
         //cria dialogo

         new TQuestion('Deseja Excluir o Registro?', $acao2);
          
     }   
     
     public function delete($param)
     { 
         try{
             $key = $param['key'];
             TTransaction::open('sigp');
               $audiencia = TSession::getValue('objetos');
               //var_dump($audiencia);
             $pessoa = new PessoaAudiencia;
             $valores = $pessoa->where('id_pessoa', '=', $key)->where('id_audiencia','=', $audiencia['key'])->load();
            
             foreach ($valores as $valor) {
              
               $valor->delete();
               
             }
              
           
            
             new TMessage('info', 'Registro deletado com sucesso!');
               AdiantiCoreApplication::loadPage('VisualizarPessoa', 'onVisualizar', array('key' => $audiencia['key']));
             //$this->onReload(array('key' => $audiencia['key']));
             //$this->onVisualizar(array('key' => $audiencia['key']));
             
             TTransaction::close();
            
             

         }catch(Exception $e)
         {
             new TMessage('error', $e->getMessage());
         }
         
     }
     public function onAdicionar($param)
     {  
       
          $audiencia = TSession::getValue('objetos');
         //define paramentros
           AdiantiCoreApplication::loadPage('AdicionarPessoa', 'onAdicionar', $audiencia);
     } 
    
    public function show()
    {
       
        
         $this->onReload(func_get_arg(0));
        parent::show();
    }
    
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
