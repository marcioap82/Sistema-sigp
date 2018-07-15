<?php
class CadastroDeViatura extends TPage
{
    protected $form; // form
    
  
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct($param)
    {
        parent::__construct();
       
        
        $this->form =  new BootstrapFormWrapper(new TQuickForm, 'form-inline');
        $this->form->style= "width:800px";
        
        $this->form->setFormTitle('cadastro de viatura');
        $id = new TEntry('id');
        $id->setEditable(false);
        $ano = new TEntry('ano');
        $modelo = new TEntry('modelo');
        $marca = new TEntry('marca');
        $Chassi = new TEntry('chassi');
        $cor = new TEntry('cor');
        
        $quilometrosrodados = new TEntry('quilometro');
        $placa = new TEntry('placa');
        $numero = new TEntry('numero');
        $fabricante = new TEntry('fabricante');
        
        $tipo = new TDBCombo('id_tipo', 'sigp', 'tipo', 'id', 'nometipo');
       
        $this->form->addQuickField($l1=new TLabel('ID:'),    $id,    20);
        $this->form->addQuickField($l1=new TLabel('Ano:'),    $ano,    60);
        $this->form->addQuickField($l2=new TLabel('Modelo:'), $modelo, 100);
        $this->form->addQuickField($l3=new TLabel('Marca:'), $marca, 150);
        $this->form->addQuickField($l4=new TLabel('Chassi:'), $Chassi, 150);
        $this->form->addQuickField($l3=new TLabel('Cor:'), $cor, 60); 
        $this->form->addQuickField($l1=new TLabel('Fabricante:'), $fabricante, 100);         
        $this->form->addQuickField($l1=new TLabel('Placa:'), $placa, 62);
        $this->form->addQuickField($l1=new TLabel('Numero'), $numero, 100);
        $this->form->addQuickField($l1=new TLabel('Tipo'), $tipo, 75);
        $this->form->addQuickField($l1=new TLabel('Quilometragem:'), $quilometrosrodados, 200);
        
        $l1->setSize(100);
        $l2->setSize(50);
        $l3->setSize(50);
        $l4->setSize(50);
       
       
        
        $btn = $this->form->addQuickAction('Salvar', new TAction(array($this, 'salvar')), 'fa:fas fa-car');
        $btn->class = 'btn btn-success';
        $btn = $this->form->addQuickAction('Lista de viaturas', new TAction(array('ConsultarViatura', 'onReload')), 'fa:fas fa-car');
        $btn->class = 'btn btn-info';
        
        $panel = new TPanelGroup('Cadastro de viatura');
        $panel->add($this->form);
        
        $this->alertBox = new TElement('div');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->alertBox);
        $vbox->add($panel);

        parent::add($vbox);
        
    }
    public function salvar($param)
    {
        
    try{
          TTransaction::open('sigp');       
          $data = $this->form->getData('Viatura');
          $data->store();
          new TMessage('info', 'dados armazenados com sucesso!');
          $this->form->setData($data);
          TTransaction::close();
        
       
    }catch(Exception $e)
    {
        new TMessage('error', $e->getMessage());
    }
   }
   public function onClear($param)
   {
       
   }
   public function onEdit($param)
    {
        
    try{
           
       if($param['key'])
       {
       TTransaction::open('sigp');  
       $this->form->setData(new Viatura($param['key']));
         TTransaction::close();
      }
      }catch(Exception $e)
      {
           new TMessage('error', $e->getMessage());
      }
    
  }
}
