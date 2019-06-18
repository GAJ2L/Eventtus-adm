<?php

class AnexoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Anexo';
    private static $primaryKey = 'id';
    private static $formName = 'form_Anexo';

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
        $this->form->setFormTitle('Cadastro de anexo');


        $id = new TEntry('id');
        $atividade_id = new TDBCombo('atividade_id', 'eventtus', 'Atividade', 'id', '{nome}','id asc'  );
        $arquivo = new TFile('arquivo');

        $atividade_id->addValidation('Atividade id', new TRequiredValidator()); 
        $arquivo->addValidation('arquivo', new TRequiredValidator()); 

        $arquivo->enableFileHandling();
        $id->setEditable(false);

        $id->setSize(250);
        $arquivo->setSize('100%');
        $atividade_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Atividade', '#ff0000', '14px', null, '100%'),$atividade_id]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Arquivo', '#ff0000', '14px', null, '100%'),$arquivo]);
        $row2->layout = [' col-sm-12'];

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

            $object = new Anexo(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $arquivo_dir = 'arquivos/'; 

            $this->saveFile($object, $data, 'local', $arquivo_dir); 

            $dados_file = json_decode(urldecode($data->arquivo));

            $target_file = str_replace('tmp/', '', $dados_file->fileName);
            $object->nome = $target_file;
            $object->local = "arquivos/{$target_file}";
            $object->tipo = filetype("arquivos/{$target_file}");
            $object->tamanho = filesize("arquivos/{$target_file}");

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

                $object = new Anexo($key); // instantiates the Active Record 

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

