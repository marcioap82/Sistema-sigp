<?php
class LocalAlteracaoVeiculo extends TPage
{
    private $form; // form
    private $tipo;
    
    
  
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct($param)
    {
        parent::__construct($param);
   
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastro de Local de Alteração no Veiculo');
        $this->form->style = 'width: 930px';
        
         $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
}

