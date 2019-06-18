<?php

class AtividadeInteracaoView extends TPage
{
    private $html;
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'AtividadeInteracao';
    private static $primaryKey = 'id';
    private static $formName = 'form_AtividadeInteracao';
    /**
     * Class constructor
     * Creates the page
     */
    function __construct( $param )
    {
        parent::__construct();

       
         // creates the form
         $this->form = new BootstrapFormBuilder(self::$formName);
         // define the form title
         $this->form->setFormTitle('Visualizar Perguntas');
        $atividade_id = new TDBCombo('atividade_id', 'eventtus', 'Atividade', 'id', '{nome}','id asc'  );
        $atividade_id->addValidation('Atividade', new TRequiredValidator()); 
        $atividade_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Atividade', '#ff0000', '14px', null, '100%'),$atividade_id]);
        $row1->layout = [' col-sm-6'];


        $btn_onload = $this->form->addAction('Visualizar perguntas', new TAction([$this, 'onLoad'],['static' => 1]), 'fa:search #ffffff');
        $btn_onload->addStyleClass('btn-primary');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);
    }

    public static function onLoad($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $param['atividade_id']));

        $criteria->setProperty('order', 'dt_registro asc');

        TTransaction::open('eventtus');
        $atividade   = Atividade::find($param['atividade_id']);
        $interacaoes = AtividadeInteracao::getObjects($criteria);

        $param = [];
        $param['nome'] = $atividade->nome;
        $param['id']   = $atividade->id;
        $mensagens = [];
        foreach ($interacaoes as $interacao)
        {
            if($interacao->fl_aprovado)
            {
                $mensagens[] = [
                    'dt_registro' => date('d/m/y H:i:s', strtotime($interacao->dt_registro)),
                    'mensagem'    => $interacao->mensagem,
                    'nome'        => $interacao->pessoa->nome,
                ];   
            }
        }
        
        TTransaction::close();

        $html = new THtmlRenderer('app/resources/geral/atividade_interacao.html');
        $html->enableSection('main');
        $html->enableSection('atividade', $param);
        $html->enableSection('mensagem', $mensagens, TRUE);
        
        $window = TWindow::create($atividade->nome, 0.8, 0.8);
        $window->add($html);
        $window->show();
    }
    
    public static function onAprovar($param)
    {
        try 
        {
            TTransaction::open('eventtus');
            
            $atividade_interacao = new AtividadeInteracao($param['id']);
            
            $atividade_interacao->fl_aprovado = TRUE;
            $atividade_interacao->store();
            
            new TMessage('info', 'Mensagem aprovado com sucesso!', new TAction(['AtividadeInteracaoSimpleList', 'onShow']));
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
