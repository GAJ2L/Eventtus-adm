<?php

class InscricaoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Inscricao';
    private static $primaryKey = 'id';
    private static $formName = 'form_Inscricao';

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
        $this->form->setFormTitle('Cadastro de inscricao');


        $id = new TEntry('id');
        $pessoa_id = new TDBCombo('pessoa_id', 'eventtus', 'Pessoa', 'id', '{id}','id asc'  );
        $evento_id = new TDBCombo('evento_id', 'eventtus', 'Evento', 'id', '{id}','id asc'  );
        $codigo = new TEntry('codigo');
        $dt_ativacao = new TDateTime('dt_ativacao');
        $dt_cancelamento = new TDateTime('dt_cancelamento');

        $pessoa_id->addValidation('Pessoa id', new TRequiredValidator()); 
        $evento_id->addValidation('Evento id', new TRequiredValidator()); 
        $codigo->addValidation('Codigo', new TRequiredValidator()); 
        $dt_ativacao->addValidation('Dt ativacao', new TRequiredValidator()); 

        $id->setEditable(false);

        $dt_ativacao->setMask('dd/mm/yyyy hh:ii');
        $dt_cancelamento->setMask('dd/mm/yyyy hh:ii');

        $dt_ativacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_cancelamento->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $codigo->setSize('70%');
        $pessoa_id->setSize('70%');
        $evento_id->setSize('70%');
        $dt_ativacao->setSize(150);
        $dt_cancelamento->setSize(150);

        $row1 = $this->form->addFields([new TLabel('Id:', null, '14px', null, '100%'),$id],[new TLabel('Pessoa id:', '#ff0000', '14px', null, '100%'),$pessoa_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel('Evento id:', '#ff0000', '14px', null, '100%'),$evento_id],[new TLabel('Codigo:', '#ff0000', '14px', null, '100%'),$codigo]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Dt ativacao:', '#ff0000', '14px', null, '100%'),$dt_ativacao],[new TLabel('Dt cancelamento:', null, '14px', null, '100%'),$dt_cancelamento]);
        $row3->layout = ['col-sm-6','col-sm-6'];

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

            $object = new Inscricao(); // create an empty object 

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

                $object = new Inscricao($key); // instantiates the Active Record 

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

