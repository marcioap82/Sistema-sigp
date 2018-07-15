<?php

  class ConsultaAudiencia extends TStandardList
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
        parent::setActiveRecord('Audiencia');       // defines the active record
        parent::addFilterField('data_da_audiencia'); // filter field, operator, form field
       
        parent::setDefaultOrder('id', 'asc');  // define the default order
        
        

        
        // creates the form
        $this->form = new BootstrapFormWrapper(new TQuickForm());
        $this->form->setFormTitle('Consulta Audiencia');
        
        //$this->form->class = 'tform';
        
        $data = new TDate('data_da_audiencia');       
        $this->form->addQuickField( 'data:', $data, '70%' );
        
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'fa:search blue');
        //$this->form->addQuickAction('Novo',  new TAction(array('CadastroDeContatos', 'onClear')), 'fa:plus-circle green');
        
        // keep the form filled with the search data
        $this->form->setData( TSession::getValue('Pessoa_filter_data') );
        
        // creates the DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
       // $this->datagrid->style = "width: 100%";
        
        // creates the datagrid columns
       $id =  $this->datagrid->addQuickColumn('Id', 'id', 'center', '5%', new TAction(array($this, 'onReload')), array('order', 'id'));
       
              
        $data = $this->datagrid->addQuickColumn('Data Da Audiência', 'data_da_audiencia', 'center', '25%', new TAction(array($this, 'onReload')), array('order', 'nome'));
        $data->setTransformer(array($this, 'formatDate'));
        $this->datagrid->addQuickColumn('Local Da Audiência:', 'local_da_audiencia', 'center', '40%');
       
        $hora = $this->datagrid->addQuickColumn('Hora da Audiência', 'hora_da_audiencia', 'center', '30%');
        $hora->setTransformer(array($this, 'formatHora'));      
        
      
     
        // creates two datagrid actions
        $this->datagrid->addQuickAction('Vizualizar Pessoas', new TDataGridAction(array('VisualizarPessoa', 'onVisualizar')), 'id', 'fa:fas fa-address-book');
        $this->datagrid->addQuickAction('Editar', new TDataGridAction(array('CadastroDeAudiencia', 'onEditar')), 'id', 'fa:far fa-edit');
        $this->datagrid->addQuickAction('Deletar', new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:trash red');
   
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
    public function formatDate($date, $object)
    {
        $dt = TDate::date2br($date);
        
        return $dt;
    }
    public function formatHora($hora, $object)
    {
        $dt = new DateTime($hora);
        return $dt->format('h:i');
        
       
    }

     
       
    }

