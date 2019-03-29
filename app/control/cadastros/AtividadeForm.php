<?php

class AtividadeForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Atividade';
    private static $primaryKey = 'id';
    private static $formName = 'form_Atividade';

    use Adianti\Base\AdiantiMasterDetailTrait;

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
        $this->form->setFormTitle('Atividade');


        $id = new TEntry('id');
        $evento_id = new TDBCombo('evento_id', 'eventtus', 'Evento', 'id', '{nome}','id asc'  );
        $nome = new TEntry('nome');
        $local_nome = new TEntry('local_nome');
        $nome_en = new TEntry('nome_en');
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_fim = new TDateTime('dt_fim');
        $descricao = new TEntry('descricao');
        $descricao_en = new TEntry('descricao_en');
        $local_geolocalizacao = new TEntry('local_geolocalizacao');
        $responsaveis_atividade_atividade_usuario_id = new TDBCombo('responsaveis_atividade_atividade_usuario_id', 'eventtus', 'Pessoa', 'id', '{nome}','id asc'  );
        $responsaveis_atividade_atividade_id = new THidden('responsaveis_atividade_atividade_id');

        $evento_id->addValidation('Evento id', new TRequiredValidator()); 
        $nome->addValidation('Nome', new TRequiredValidator()); 
        $local_nome->addValidation('Local', new TRequiredValidator()); 
        $dt_inicio->addValidation('Inicío', new TRequiredValidator()); 

        $id->setEditable(false);

        $evento_id->enableSearch();
        $responsaveis_atividade_atividade_usuario_id->enableSearch();

        $dt_fim->setMask('dd/mm/yyyy hh:ii');
        $dt_inicio->setMask('dd/mm/yyyy hh:ii');

        $dt_fim->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(160);
        $nome->setSize('99%');
        $dt_fim->setSize('100%');
        $nome_en->setSize('100%');
        $descricao->setSize('99%');
        $evento_id->setSize('100%');
        $dt_inicio->setSize('100%');
        $local_nome->setSize('100%');
        $descricao_en->setSize('100%');
        $local_geolocalizacao->setSize('100%');
        $responsaveis_atividade_atividade_usuario_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Evento', '#ff0000', '14px', null, '100%'),$evento_id]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Nome', '#ff0000', '14px', null, '100%'),$nome],[new TLabel('Local', '#ff0000', '14px', null, '100%'),$local_nome]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Nome em inglês', null, '14px', null, '100%'),$nome_en],[new TLabel('Inicío', '#ff0000', '14px', null, '100%'),$dt_inicio],[new TLabel('Fim', null, '14px', null, '100%'),$dt_fim]);
        $row3->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row4 = $this->form->addFields([new TLabel('Descrição', null, '14px', null, '100%'),$descricao],[new TLabel('Descrição em inglês', null, '14px', null, '100%'),$descricao_en]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel('Geolocalização', null, '14px', null, '100%'),$local_geolocalizacao]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addContent([new TFormSeparator('Responsáveis', '#333333', '18', '#eeeeee')]);
        $row7 = $this->form->addFields([new TLabel('Pessoa', '#ff0000', '14px', null)],[$responsaveis_atividade_atividade_usuario_id]);
        $row8 = $this->form->addFields([$responsaveis_atividade_atividade_id]);         
        $add_responsaveis_atividade_atividade = new TButton('add_responsaveis_atividade_atividade');

        $action_responsaveis_atividade_atividade = new TAction(array($this, 'onAddResponsaveisAtividadeAtividade'));

        $add_responsaveis_atividade_atividade->setAction($action_responsaveis_atividade_atividade, 'Adicionar');
        $add_responsaveis_atividade_atividade->setImage('fa:plus #000000');

        $this->form->addFields([$add_responsaveis_atividade_atividade]);

        $this->responsaveis_atividade_atividade_list = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->responsaveis_atividade_atividade_list->style = 'width:100%';
        $this->responsaveis_atividade_atividade_list->class .= ' table-bordered';
        $this->responsaveis_atividade_atividade_list->disableDefaultClick();
        $this->responsaveis_atividade_atividade_list->addQuickColumn('', 'edit', 'left', 50);
        $this->responsaveis_atividade_atividade_list->addQuickColumn('', 'delete', 'left', 50);

        $column_responsaveis_atividade_atividade_usuario_id = $this->responsaveis_atividade_atividade_list->addQuickColumn('Pessoa', 'responsaveis_atividade_atividade_usuario_id', 'left');

        $this->responsaveis_atividade_atividade_list->createModel();
        $this->form->addContent([$this->responsaveis_atividade_atividade_list]);

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

            $object = new Atividade(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['AtividadeList', 'onShow']);   

            $responsaveis_atividade_atividade_items = $this->storeItems('ResponsaveisAtividade', 'atividade_id', $object, 'responsaveis_atividade_atividade', function($masterObject, $detailObject){ 

                //code here

            }); 

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

                $object = new Atividade($key); // instantiates the Active Record 

                $responsaveis_atividade_atividade_items = $this->loadItems('ResponsaveisAtividade', 'atividade_id', $object, 'responsaveis_atividade_atividade', function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                    $this->onReload();

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

        TSession::setValue('responsaveis_atividade_atividade_items', null);

        $this->onReload();
    }

    public function onAddResponsaveisAtividadeAtividade( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->responsaveis_atividade_atividade_usuario_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', 'Pessoa'));
            }             

            $responsaveis_atividade_atividade_items = TSession::getValue('responsaveis_atividade_atividade_items');
            $key = isset($data->responsaveis_atividade_atividade_id) && $data->responsaveis_atividade_atividade_id ? $data->responsaveis_atividade_atividade_id : uniqid();
            $fields = []; 

            $fields['responsaveis_atividade_atividade_usuario_id'] = $data->responsaveis_atividade_atividade_usuario_id;
            $responsaveis_atividade_atividade_items[ $key ] = $fields;

            TSession::setValue('responsaveis_atividade_atividade_items', $responsaveis_atividade_atividade_items);

            $data->responsaveis_atividade_atividade_id = '';
            $data->responsaveis_atividade_atividade_usuario_id = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditResponsaveisAtividadeAtividade( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('responsaveis_atividade_atividade_items');

        // get the session item
        $item = $items[$param['responsaveis_atividade_atividade_id_row_id']];

        $data->responsaveis_atividade_atividade_usuario_id = $item['responsaveis_atividade_atividade_usuario_id'];

        $data->responsaveis_atividade_atividade_id = $param['responsaveis_atividade_atividade_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );
    }

    public function onDeleteResponsaveisAtividadeAtividade( $param )
    {
        $data = $this->form->getData();

        $data->responsaveis_atividade_atividade_usuario_id = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('responsaveis_atividade_atividade_items');

        // delete the item from session
        unset($items[$param['responsaveis_atividade_atividade_id_row_id']]);
        TSession::setValue('responsaveis_atividade_atividade_items', $items);

        // reload sale items
        $this->onReload( $param );
    }

    public function onReloadResponsaveisAtividadeAtividade( $param )
    {
        $items = TSession::getValue('responsaveis_atividade_atividade_items'); 

        $this->responsaveis_atividade_atividade_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteResponsaveisAtividadeAtividade')); 
                $action_del->setParameter('responsaveis_atividade_atividade_id_row_id', $key);   

                $action_edi = new TAction(array($this, 'onEditResponsaveisAtividadeAtividade'));  
                $action_edi->setParameter('responsaveis_atividade_atividade_id_row_id', $key);  

                $button_del = new TButton('delete_responsaveis_atividade_atividade'.$cont);
                $button_del->class = 'btn btn-default btn-sm';
                $button_del->setAction($action_del, '');
                $button_del->setImage('fa:trash-o'); 
                $button_del->setFormName($this->form->getName());

                $button_edi = new TButton('edit_responsaveis_atividade_atividade'.$cont);
                $button_edi->class = 'btn btn-default btn-sm';
                $button_edi->setAction($action_edi, '');
                $button_edi->setImage('bs:edit');
                $button_edi->setFormName($this->form->getName());

                $rowItem->edit = $button_edi;
                $rowItem->delete = $button_del;

                $rowItem->responsaveis_atividade_atividade_usuario_id = '';
                if(isset($item['responsaveis_atividade_atividade_usuario_id']) && $item['responsaveis_atividade_atividade_usuario_id'])
                {
                    TTransaction::open('eventtus');
                    $pessoa = Pessoa::find($item['responsaveis_atividade_atividade_usuario_id']);
                    if($pessoa)
                    {
                        $rowItem->responsaveis_atividade_atividade_usuario_id = $pessoa->render('{nome}');
                    }
                    TTransaction::close();
                }

                $row = $this->responsaveis_atividade_atividade_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {
        TSession::setValue('responsaveis_atividade_atividade_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadResponsaveisAtividadeAtividade($params);
    }

    public function show() 
    { 
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}

