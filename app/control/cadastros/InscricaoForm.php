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
        $this->form->setFormTitle('Inscrição no Evento');


        $id = new TEntry('id');
        $pessoa_id = new TDBCombo('pessoa_id', 'eventtus', 'Pessoa', 'id', '{nome}','id asc'  );
        $evento_id = new TDBCombo('evento_id', 'eventtus', 'Evento', 'id', '{nome}','id asc'  );
        $codigo = new TEntry('codigo');

        $pessoa_id->addValidation('Pessoa', new TRequiredValidator()); 
        $evento_id->addValidation('Evento', new TRequiredValidator()); 
        $codigo->addValidation('Código', new TRequiredValidator()); 

        $pessoa_id->enableSearch();
        $id->setEditable(false);

        $id->setSize(160);
        $codigo->setSize('100%');
        $pessoa_id->setSize('100%');
        $evento_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Pessoa', '#ff0000', '14px', null, '100%'),$pessoa_id]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Evento', '#ff0000', '14px', null, '100%'),$evento_id],[new TLabel('Código sistema externo', '#ff0000', '14px', null, '100%'),$codigo]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:floppy-o #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction('Limpar formulário', new TAction([$this, 'onClear']), 'fa:eraser #dd5a43');
        $this->form->addAction(_t('Back'),new TAction(array('InscricaoList','onReload')),'fa:arrow-circle-o-left blue');

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
            if(!$object->id)
            {
                $object->dt_ativacao = date('Y-m-d H:m:i');   
            }

            $object->store(); // save the object 

            $messageAction = new TAction(['InscricaoList', 'onShow']);   

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

