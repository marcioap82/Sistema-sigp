<?php
class ConsultaAteracao extends TPage
{
    private $datagrid;
    private $parametro;   
    public function __construct($param)
    {
        parent::__construct($param);
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid());
        
       $this->parametro =  ($param['key'])?  $param['key']:0;
       $this->datagrid->setHeight(300);
 
        $this->datagrid->makeScrollable();
        //$this->datagrid->setActionWidth(400);
        
        $id = new TDataGridColumn('id', 'ID', 'left', 50);
        $viatura = new TDataGridColumn('id_viatura', 'viatura', 'left', 200);
        $dataevento = new TDataGridColumn('dataevento', 'Data do Problema', 'left', 200);
        $status = new TDataGridColumn('status_id', 'Status da Viatura', 'left', 200);
        
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($viatura);
        $this->datagrid->addColumn($dataevento);
        $this->datagrid->addColumn($status);
        
        
        $action = new TDataGridAction(array('VizualizarAlteracao', 'onVizualizar'));
        $action->setImage('fa:fas fa-cogs');
        $action->setLabel('Vizualizar Descrição');
        
        $action->setField('id');
        $this->datagrid->addAction($action);
        
        
        $this->datagrid->createModel();
        $vbox = new TVBox;
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->datagrid);

       
         parent::add($vbox);
        
    }
    public function show()
    {
        
    try{
           TTransaction::open('sigp');
       
        $alteracao = AlteracaoViatura::where('id_viatura', '=', $this->parametro)->orderBy('id')->load();
        $this->datagrid->clear();
     foreach($alteracao as $alt)
     {

         $alteracaoitem = new stdClass();
         $alteracaoitem->id = $alt->id;
         $alteracaoitem->id_viatura = $alt->viatura->numero;
         $alteracaoitem->dataevento = TDate::convertToMask($alt->dataevento,'yyyy-mm-dd', 'dd/mm/yyyy');
         $alteracaoitem->status_id = $alt->status->nomestatus;
         $this->datagrid->addItem($alteracaoitem);
     }
        TTransaction::close();
        
        }catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
        parent::show();
    }
    
   
    
    public function onGerenciar()
    {
                                     
    }
  }