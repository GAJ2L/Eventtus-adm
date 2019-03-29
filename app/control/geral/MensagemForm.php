<?php

class MensagemForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Mensagem';
    private static $primaryKey = 'id';
    private static $formName = 'form_Mensagem';

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
        $this->form->setFormTitle('Cadastro de mensagem');


        $id = new TEntry('id');
        $inscricao_atividade_id = new TDBCombo('inscricao_atividade_id', 'eventtus', 'InscricaoAtividade', 'id', '{id}','id asc'  );
        $dt_registro = new TDateTime('dt_registro');
        $conteudo = new TEntry('conteudo');

        $inscricao_atividade_id->addValidation('Inscricao atividade id', new TRequiredValidator()); 
        $dt_registro->addValidation('Dt registro', new TRequiredValidator()); 
        $conteudo->addValidation('Conteudo', new TRequiredValidator()); 

        $dt_registro->setMask('dd/mm/yyyy hh:ii');
        $id->setEditable(false);
        $dt_registro->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $conteudo->setSize('70%');
        $dt_registro->setSize(150);
        $inscricao_atividade_id->setSize('70%');

        $row1 = $this->form->addFields([new TLabel('Id:', null, '14px', null, '100%'),$id],[new TLabel('Inscricao atividade id:', '#ff0000', '14px', null, '100%'),$inscricao_atividade_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel('Dt registro:', '#ff0000', '14px', null, '100%'),$dt_registro],[new TLabel('Conteudo:', '#ff0000', '14px', null, '100%'),$conteudo]);
        $row2->layout = ['col-sm-6','col-sm-6'];

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

            $object = new Mensagem(); // create an empty object 

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

                $object = new Mensagem($key); // instantiates the Active Record 

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

