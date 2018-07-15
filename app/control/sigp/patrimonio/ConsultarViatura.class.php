<?php
/**
 * StandardDataGridView Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ConsultarViatura extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('sigp');        // defines the database
        parent::setActiveRecord('Viatura');       // defines the active record
        parent::addFilterField('numero'); // filter field, operator, form field
        parent::addFilterField('id_tipo','=','id_tipo');
        parent::setDefaultOrder('id', 'asc');  // define the default order
        
        

        
        // creates the form
        $this->form = new BootstrapFormWrapper(new TQuickForm());
        $this->form->setFormTitle('Consulta  Viatura');
        //$this->form->class = 'tform';
        
        $numero = new TEntry('numero');
        $tipo = new TDBCombo('id_tipo', 'sigp', 'tipo', 'id', 'nometipo');
        $this->form->addQuickField( 'Numero:', $numero, '70%' );
        $this->form->addQuickField( 'Tipo:', $tipo, '70%' );
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction('Novo',  new TAction(array('CadastroDeViatura', 'onClear')), 'fa:plus-circle green');
        
        // keep the form filled with the search data
        $this->form->setData( TSession::getValue('Viatura_filter_data') );
        
        // creates the DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
       // $this->datagrid->style = "width: 100%";
        
        // creates the datagrid columns
        $this->datagrid->addQuickColumn('Id', 'id', 'right', '10%', new TAction(array($this, 'onReload')), array('order', 'id'));
              
        $this->datagrid->addQuickColumn('Numero', 'numero', 'left', '10%', new TAction(array($this, 'onReload')), array('order', 'numero'));
        $this->datagrid->addQuickColumn('Chassi', 'chassi', 'center', '30%');
        $this->datagrid->addQuickColumn('Frabricante', 'fabricante', 'center', '15%');
        $this->datagrid->addQuickColumn('Placa', 'placa', 'center', '15%');
        $this->datagrid->addQuickColumn('Modelo', 'modelo', 'center', '15%');
        $this->datagrid->addQuickColumn('Cor', 'cor', 'center', '15%');
        $this->datagrid->addQuickColumn('Tipo', 'tipo->nometipo', 'center', '15%');
        
      
     
        // creates two datagrid actions
        $this->datagrid->addQuickAction('Editar', new TDataGridAction(array('CadastroDeViatura', 'onEdit')), 'id', 'fa:edit blue');
        $this->datagrid->addQuickAction('Deletar', new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:trash red');
        $this->datagrid->addQuickAction('Relatar Problema', new TDataGridAction(array('GerenciaViatura', 'onGerenciar')), 'id', 'fa:fas fa-car #000');
       $action =  $this->datagrid->addQuickAction('Historico de Problemas em viatura', new TDataGridAction(array('ConsultaAteracao', 'onGerenciar')), 'id', 'fa:fas fa-book #4caf50');
       
        
       
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->pageNavigation);
        
        // add the table inside the page
        parent::add($vbox);
    }
  public function Listar()
  {
      
  }
    
}
