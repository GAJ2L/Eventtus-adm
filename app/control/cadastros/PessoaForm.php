<?php

class PessoaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pessoa';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle('Pessoa');


        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $email = new TEntry('email');
        $email2 = new TEntry('email2');
        $foto = new TFile('foto');
        $idioma = new TRadioGroup('idioma');

        $nome->addValidation('Nome', new TRequiredValidator()); 
        $email->addValidation('E-mail', new TRequiredValidator()); 

        $id->setEditable(false);
        $foto->enableFileHandling();
        $idioma->addItems(['en'=>'Inglês','pt'=>'Português']);
        $idioma->setLayout('horizontal');
        $idioma->setUseButton();

        $id->setSize(250);
        $idioma->setSize(80);
        $nome->setSize('100%');
        $foto->setSize('100%');
        $email->setSize('100%');
        $email2->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Nome', '#ff0000', '14px', null, '100%'),$nome]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('E-mail', '#ff0000', '14px', null, '100%'),$email],[new TLabel('E-mail 2', null, '14px', null, '100%'),$email2]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Foto', null, '14px', null, '100%'),$foto],[new TLabel('Idioma', null, '14px', null, '100%'),$idioma]);
        $row3->layout = ['col-sm-6',' col-sm-6'];

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

            $object = new Pessoa(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $foto_dir = 'app/documentos/pessoas'; 

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'foto', $foto_dir);
            $messageAction = new TAction(['PessoaList', 'onShow']);   

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

                $object = new Pessoa($key); // instantiates the Active Record 

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

