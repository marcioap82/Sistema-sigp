<?php
/**
 * CompleteDataGridView Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ConsultaEfetivo extends TPage
{
    private $form;     // registration form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        new TSession;
        
        // creates the form
        $this->form = new TForm;
       // $this->form->setFormTitle('Consulta Efetivo');
        //$this->form->class = 'tform';
        
        $nome= new TEntry('nomebusca');
        $nome->setSize(400);
        $page = new TTable();
        
         $button1 = new TButton('action1');
         $button1->setAction(new TAction(array($this, 'onSearch')), 'Buscar');
         $button1->setImage('fa:search blue');
         $this->form->add($page);
         $button2 = new TButton('action2');
         $button2->setAction(new TAction(array($this, 'onLimpar')), 'Limpar');
         $button2->setImage('fa:fas fa-paint-brush');
         $this->form->add($page);
        
       $page->addRowSet( new TLabel('Nome:'), $nome);
       $linha = $page->addRow();
       $linha->addCell($button1);
       $linha->addCell($button2);
       
        //$this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'fa:search blue');
       // $this->form->addQuickAction('New',  new TAction(array('CompleteFormView', 'onClear')), 'fa:plus-circle green');

        // keep the form filled with the search data
         $nome->setValue(TSession::getValue( 'City_name'));
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
       // $this->datagrid->style = 'width: 100%';
        
        // creates the datagrid columns
         $id    = new TDataGridColumn('id', 'Id', 'right', '10%');
         $name  = new TDataGridColumn('nome', 'Nome', 'left', '40%');
         $nameguerra  = new TDataGridColumn('nomeguerra', 'Nome de Guerra', 'left', '30%');
         $posto = new TDataGridColumn('postoGraduacao->nomeposto', 'Posto/Graduação', 'left', '20%');      
         $tiposangue = new TDataGridColumn('tiposangue', 'Tipo Sanguinio', 'left', '40%');

        // creates the datagrid actions
        $order1 = new TAction(array($this, 'onReload'));
        $order2 = new TAction(array($this, 'onReload'));

        // define the ordering parameters
        $order1->setParameter('order', 'id');
        $order2->setParameter('order', 'nome');

        // assign the ordering actions
        $id->setAction($order1);
        $name->setAction($order2);
        

        // add the columns to the DataGrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($nameguerra);
        $this->datagrid->addColumn($posto);
        $this->datagrid->addColumn($tiposangue);
        //$this->datagrid->addColumn($state);

        // creates two datagrid actions
        $action1 = new TDataGridAction(array('CadastroPessoa', 'onEdit'));
        $action1->setImage('fa:edit blue');
        $action1->setLabel('Editar');
       // $action1->setLabel('Editar');
       // $action1->setButtonClass('btn btn-info');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setImage('fa:trash red');
         $action2->setLabel('Deletar');
        //$action2->setLabel('Deletar');
       // $action2->setButtonClass('btn btn-warning');
        $action2->setField('id');
        $action3 = new TDataGridAction(array('Vizualizar', 'onVizualizar'));
        $action3->setImage('fa:desktop fa-fw');
        $action3->setLabel('Vizualizar');
        //$action3->setLabel('vizualizar');
       // $action3->setButtonClass('btn btn-success btn-lg');
        $action3->setField('id');
        
        // add the actions to the datagrid
         $this->datagrid->addAction($action1);
         $this->datagrid->addAction($action2);
         $this->datagrid->addAction($action3);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        $this->form->setFields(array($nome, $button1, $button2));
        
        // creates the page structure using a table
        $vbox = new TVBox;
        
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form); // add a row to the form
        $vbox->add($this->datagrid); // add a row to the datagrid
        $vbox->add($this->pageNavigation); // add a row for page navigation
        
        // add the table inside the page
        parent::add($vbox);
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
     
        // get the search form data
        $data = $this->form->getData();
         
            
        // check if the user has filled the form
        if (isset($data->nomebusca))
        {
       
            // creates a filter using what the user has typed
            $filter = new TFilter('nome', 'like', "%{$data->nomebusca}%");
          
            // stores the filter in the session
            TSession::setValue('City_filter', $filter);
            TSession::setValue('City_nome',   $data->nomebusca);
            
            // fill the form with data again
            $this->form->setData($data);
        }
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('sigp');
            
            // creates a repository for City
            $repository = new TRepository('Pessoa');
            $limit = 8;
            
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('City_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('City_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion('Tem Certeza que deseja deletar ?', $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            // get the parameter $key
            $key = $param['id'];
            
            TTransaction::open('sigp'); // open a transaction with database 'samples'
            $object = new Pessoa($key);      // instantiates object City
            $object->delete();             // deletes the object from the database
            TTransaction::close();         // close the transaction
            
            // reload the listing
            $this->onReload( $param );
            
            // shows the success message
            new TMessage('info', "Arquivo deletado");
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    public function onEdit()
    {
        
    }
    public function onLimpar()
    {
         $this->form->clear();
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded)
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
