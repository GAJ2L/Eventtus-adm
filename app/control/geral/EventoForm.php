<?php

class EventoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Evento';
    private static $primaryKey = 'id';
    private static $formName = 'form_Evento';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle('Cadastro de evento');


        $id = new TEntry('id');
        $responsavel_id = new TEntry('responsavel_id');
        $nome = new TEntry('nome');
        $nome_en = new TEntry('nome_en');
        $descricao = new TEntry('descricao');
        $desricao_en = new TEntry('desricao_en');
        $banner = new TEntry('banner');
        $logo = new TEntry('logo');
        $cor = new TEntry('cor');
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_fim = new TDateTime('dt_fim');
        $contato_nome = new TEntry('contato_nome');
        $contato_email = new TEntry('contato_email');
        $contato_telefone = new TEntry('contato_telefone');

        $responsavel_id->addValidation('Responsavel id', new TRequiredValidator()); 
        $nome->addValidation('Nome', new TRequiredValidator()); 
        $descricao->addValidation('Descricao', new TRequiredValidator()); 
        $dt_inicio->addValidation('Dt inicio', new TRequiredValidator()); 
        $contato_nome->addValidation('Contato nome', new TRequiredValidator()); 
        $contato_email->addValidation('Contato email', new TRequiredValidator()); 
        $contato_telefone->addValidation('Contato telefone', new TRequiredValidator()); 

        $id->setEditable(false);

        $dt_fim->setMask('dd/mm/yyyy hh:ii');
        $dt_inicio->setMask('dd/mm/yyyy hh:ii');

        $dt_fim->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $cor->setSize('70%');
        $nome->setSize('70%');
        $logo->setSize('70%');
        $dt_fim->setSize(150);
        $banner->setSize('70%');
        $nome_en->setSize('70%');
        $dt_inicio->setSize(150);
        $descricao->setSize('70%');
        $desricao_en->setSize('70%');
        $contato_nome->setSize('70%');
        $contato_email->setSize('70%');
        $responsavel_id->setSize('70%');
        $contato_telefone->setSize('70%');

        $row1 = $this->form->addFields([new TLabel('Id:', null, '14px', null, '100%'),$id],[new TLabel('Responsavel id:', '#ff0000', '14px', null, '100%'),$responsavel_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel('Nome:', '#ff0000', '14px', null, '100%'),$nome],[new TLabel('Nome en:', null, '14px', null, '100%'),$nome_en]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Descricao:', '#ff0000', '14px', null, '100%'),$descricao],[new TLabel('Desricao en:', null, '14px', null, '100%'),$desricao_en]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel('Banner:', null, '14px', null, '100%'),$banner],[new TLabel('Logo:', null, '14px', null, '100%'),$logo]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel('Cor:', null, '14px', null, '100%'),$cor],[new TLabel('Dt inicio:', '#ff0000', '14px', null, '100%'),$dt_inicio]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel('Dt fim:', null, '14px', null, '100%'),$dt_fim],[new TLabel('Contato nome:', '#ff0000', '14px', null, '100%'),$contato_nome]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel('Contato email:', '#ff0000', '14px', null, '100%'),$contato_email],[new TLabel('Contato telefone:', '#ff0000', '14px', null, '100%'),$contato_telefone]);
        $row7->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:floppy-o #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction('Limpar formulário', new TAction([$this, 'onClear']), 'fa:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Evento(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Evento($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

