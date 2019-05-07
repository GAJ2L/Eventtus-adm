<?php

class EventoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Evento';
    private static $primaryKey = 'id';
    private static $formName = 'form_Evento';

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
        $this->form->setFormTitle('Evento');


        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_fim = new TDateTime('dt_fim');
        $cor = new TColor('cor');
        $responsavel_id = new TDBCombo('responsavel_id', 'eventtus', 'Pessoa', 'id', '{nome}','id asc'  );
        $nome_en = new TEntry('nome_en');
        $descricao = new TEntry('descricao');
        $desricao_en = new TEntry('desricao_en');
        $banner = new TFile('banner');
        $logo = new TFile('logo');
        $contato_nome = new TEntry('contato_nome');
        $contato_email = new TEntry('contato_email');
        $contato_telefone = new TEntry('contato_telefone');

        $nome->addValidation('Nome', new TRequiredValidator()); 
        $dt_inicio->addValidation('Início', new TRequiredValidator()); 
        $responsavel_id->addValidation('Responsável', new TRequiredValidator()); 
        $descricao->addValidation('Descrição', new TRequiredValidator()); 
        $contato_nome->addValidation('Contato', new TRequiredValidator()); 
        $contato_email->addValidation('E-mail', new TRequiredValidator()); 
        $contato_telefone->addValidation('Telefone', new TRequiredValidator()); 

        $id->setEditable(false);
        // $responsavel_id->enableSearch();

        $dt_fim->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');

        $logo->enableFileHandling();
        $banner->enableFileHandling();

        $dt_fim->setMask('dd/mm/yyyy hh:ii');
        $dt_inicio->setMask('dd/mm/yyyy hh:ii');
        $contato_telefone->setMask('(99)99999-9999');

        $cor->setSize(100);
        $id->setSize('100%');
        $nome->setSize('100%');
        $logo->setSize('100%');
        $dt_fim->setSize('100%');
        $banner->setSize('100%');
        $nome_en->setSize('100%');
        $dt_inicio->setSize('100%');
        $descricao->setSize('100%');
        $desricao_en->setSize('100%');
        $contato_nome->setSize('100%');
        $contato_email->setSize('100%');
        $responsavel_id->setSize('100%');
        $contato_telefone->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Nome', '#ff0000', '14px', null, '100%'),$nome]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Início', '#ff0000', '14px', null, '100%'),$dt_inicio],[new TLabel('Fim', null, '14px', null, '100%'),$dt_fim],[new TLabel('Cor', null, '14px', null, '100%'),$cor]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel('Responsável', '#ff0000', '14px', null, '100%'),$responsavel_id],[new TLabel('Nome inglês', null, '14px', null, '100%'),$nome_en]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel('Descrição', '#ff0000', '14px', null, '100%'),$descricao],[new TLabel('Descrição em inglês', null, '14px', null, '100%'),$desricao_en]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel('Banner', null, '14px', null, '100%'),$banner],[new TLabel('Logo', null, '14px', null, '100%'),$logo]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel('Contato', '#ff0000', '14px', null, '100%'),$contato_nome],[new TLabel('E-mail', '#ff0000', '14px', null, '100%'),$contato_email],[new TLabel('Telefone', '#ff0000', '14px', null, '100%'),$contato_telefone]);
        $row6->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

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

            $banner_dir = 'app/documents/banner';
            $logo_dir = 'app/documents/logo'; 

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'banner', $banner_dir);
            $this->saveFile($object, $data, 'logo', $logo_dir);
            $messageAction = new TAction(['EventoList', 'onShow']);   

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

