<?php
/**
 * WelcomeView
 *
 * @version    1.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class WelcomeView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        parent::include_css('vizualiza.css');
        try{
       TTransaction::open('permission');
       TTransaction::open('sigp');
       
       TSession::regenerate();
       $valor= TSession::getValue('userunitid');
       if($valor==1)
       {
        //$html1 = new THtmlRenderer('app/resources/system_welcome_en.html');
        $html2 = new THtmlRenderer('app/resources/inicioforcatatica.html');
        
        $pessoas = new Pessoa();
        $valores = $pessoas->all();
         
        $replaces_valor = array();
        $dataatual= date('y-m-d');
        $somaAtual = explode('-',$dataatual);
        $somada  = (int)$somaAtual[1];        
       
       
       
        foreach($valores as $valor)
           {          
              $pessoa = new Pessoa($valor->id);       
                 if($pessoa->SomaData() == $somada)
                {
                     $replaces_valor[] = array('nome'=>$valor->nomeguerra,
                                               'postograduacao'=>$valor->postoGraduacao->nomeposto,
                                               'datanascimento'=>TDateTime::convertToMask($valor->datanasc, 'yyyy-mm-dd', 'dd/mm/yyyy'));              
                }
             }
        }else
       {
           $html2 = new THtmlRenderer('app/resources/system_welcome_pt.html');
       }
       TTransaction::close();
        TTransaction::close();
         }catch(Exception $e)
         {
             new TMessage('error', $e->getMessage);
         }
        // replace the main section variables
       // $html1->enableSection('main', array());
           $html2->enableSection('main', array());
           $html2->enableSection('aniversariante', $replaces_valor, TRUE);
         
        
        //$panel1 = new TPanelGroup('Welcome!');
        //$panel1->add($html1);
        $texto = new TLabel('Bem-vindo!');
        $texto->style='text-sty';
        $texto->setFontColor('red');
        $texto->setFontSize(14);
        $panel2 = new TPanelGroup($texto);
        $panel2->style='background-color: #e9ebee';
       
        $panel2->add($html2);
        
        // add the template to the page
        parent::add( TVBox::pack($panel2) );
        
    }
}
