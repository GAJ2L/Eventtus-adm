<?php

class NotificacaoEventoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'NotificacaoEvento';
    private static $primaryKey = 'id';
    private static $formName = 'form_NotificacaoEvento';

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
        $this->form->setFormTitle('Cadastro de notificação evento');


        $id = new TEntry('id');
        $evento_id = new TDBCombo('evento_id', 'eventtus', 'Evento', 'id', '{id}','id asc'  );
        $titulo = new TEntry('titulo');
        $titulo_en = new TEntry('titulo_en');
        $conteudo = new TText('conteudo');
        $conteudo_en = new TText('conteudo_en');

        $evento_id->addValidation('Evento id', new TRequiredValidator()); 
        $titulo->addValidation('Titulo', new TRequiredValidator()); 
        $conteudo->addValidation('Conteudo', new TRequiredValidator()); 

        $id->setEditable(false);
        $id->setSize(250);
        $titulo->setSize('100%');
        $evento_id->setSize('100%');
        $titulo_en->setSize('100%');
        $conteudo->setSize('100%', 70);
        $conteudo_en->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Evento', '#ff0000', '14px', null, '100%'),$evento_id]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Titulo', '#ff0000', '14px', null, '100%'),$titulo],[new TLabel('Titulo EN', null, '14px', null, '100%'),$titulo_en]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Conteúdo', '#ff0000', '14px', null, '100%'),$conteudo]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel('Conteúdo EN', null, '14px', null, '100%'),$conteudo_en]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:floppy-o #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction('Limpar formulário', new TAction([$this, 'onClear']), 'fa:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(['Geral','Cadastro de notificação evento']));
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

            $object = new NotificacaoEvento(); // create an empty object 

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

                $object = new NotificacaoEvento($key); // instantiates the Active Record 

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

