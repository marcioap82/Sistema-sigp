<?php
class VizualizarAlteracao extends TWindow
{
    private $form;   
   
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param)
    {
        parent::__construct();
        try{
               TTransaction::open('sigp');
               $alteracao = new AlteracaoViatura($param['key']);
               $this->form = new TFrame();
               $this->form->class="Tframe";
               $this->form->setLegend("Descrição Do Problema");
               $this->form->add($alteracao->descricao);
               
               TTransaction::close();
               parent::add($this->form);
           }catch(Exception $e)
           {
               new TMessage('error', $e->getMessage());
           }
    }
    
     public function onVizualizar($param)
    {
        
    }
  }
