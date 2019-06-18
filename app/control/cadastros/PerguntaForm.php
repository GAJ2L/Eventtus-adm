<?php

class PerguntaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Pergunta';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pergunta';

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
        $this->form->setFormTitle('Cadastro de pergunta');


        $id = new TEntry('id');
        $atividade_id = new TDBCombo('atividade_id', 'eventtus', 'Atividade', 'id', '{id}','id asc'  );
        $descricao = new TEntry('descricao');
        $descricao_en = new TEntry('descricao_en');
        $pergunta_opcao_pergunta_opcao_id = new TDBCombo('pergunta_opcao_pergunta_opcao_id', 'eventtus', 'Opcao', 'id', '{id}','id asc'  );
        $pergunta_opcao_pergunta_id = new THidden('pergunta_opcao_pergunta_id');

        $atividade_id->addValidation('Atividade id', new TRequiredValidator()); 
        $descricao->addValidation('Descrição', new TRequiredValidator()); 

        $id->setEditable(false);
        $id->setSize(250);
        $descricao->setSize('100%');
        $atividade_id->setSize('100%');
        $descricao_en->setSize('100%');
        $pergunta_opcao_pergunta_opcao_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel('Código', null, '14px', null, '100%'),$id],[new TLabel('Atividade', '#ff0000', '14px', null, '100%'),$atividade_id]);
        $row1->layout = [' col-sm-3',' col-sm-9'];

        $row2 = $this->form->addFields([new TLabel('Descrição', '#ff0000', '14px', null, '100%'),$descricao],[new TLabel('Descrição EN', null, '14px', null, '100%'),$descricao_en]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addContent([new TFormSeparator('Detail', '#333', '18', '#eee')]);
        $row4 = $this->form->addFields([new TLabel('Opção', '#ff0000', '14px', null, '100%')],[$pergunta_opcao_pergunta_opcao_id]);
        $row5 = $this->form->addFields([$pergunta_opcao_pergunta_id]);         
        $add_pergunta_opcao_pergunta = new TButton('add_pergunta_opcao_pergunta');

        $action_pergunta_opcao_pergunta = new TAction(array($this, 'onAddPerguntaOpcaoPergunta'));

        $add_pergunta_opcao_pergunta->setAction($action_pergunta_opcao_pergunta, 'Adicionar');
        $add_pergunta_opcao_pergunta->setImage('fa:plus #000000');

        $this->form->addFields([$add_pergunta_opcao_pergunta]);

        $this->pergunta_opcao_pergunta_list = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->pergunta_opcao_pergunta_list->style = 'width:100%';
        $this->pergunta_opcao_pergunta_list->class .= ' table-bordered';
        $this->pergunta_opcao_pergunta_list->disableDefaultClick();
        $this->pergunta_opcao_pergunta_list->addQuickColumn('', 'edit', 'left', 50);
        $this->pergunta_opcao_pergunta_list->addQuickColumn('', 'delete', 'left', 50);

        $column_pergunta_opcao_pergunta_opcao_id = $this->pergunta_opcao_pergunta_list->addQuickColumn('Opção', 'pergunta_opcao_pergunta_opcao_id', 'left');

        $this->pergunta_opcao_pergunta_list->createModel();
        $this->form->addContent([$this->pergunta_opcao_pergunta_list]);

        // create the form actions
        $btn_onsave = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:floppy-o #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction('Limpar formulário', new TAction([$this, 'onClear']), 'fa:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(['Cadastros','Cadastro de pergunta']));
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

            $object = new Pergunta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $pergunta_opcao_pergunta_items = $this->storeItems('PerguntaOpcao', 'pergunta_id', $object, 'pergunta_opcao_pergunta', function($masterObject, $detailObject){ 

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

                $object = new Pergunta($key); // instantiates the Active Record 

                $pergunta_opcao_pergunta_items = $this->loadItems('PerguntaOpcao', 'pergunta_id', $object, 'pergunta_opcao_pergunta', function($masterObject, $detailObject, $objectItems){ 

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

        TSession::setValue('pergunta_opcao_pergunta_items', null);

        $this->onReload();
    }

    public function onAddPerguntaOpcaoPergunta( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->pergunta_opcao_pergunta_opcao_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', 'Opcao id'));
            }             

            $pergunta_opcao_pergunta_items = TSession::getValue('pergunta_opcao_pergunta_items');
            $key = isset($data->pergunta_opcao_pergunta_id) && $data->pergunta_opcao_pergunta_id ? $data->pergunta_opcao_pergunta_id : uniqid();
            $fields = []; 

            $fields['pergunta_opcao_pergunta_opcao_id'] = $data->pergunta_opcao_pergunta_opcao_id;
            $pergunta_opcao_pergunta_items[ $key ] = $fields;

            TSession::setValue('pergunta_opcao_pergunta_items', $pergunta_opcao_pergunta_items);

            $data->pergunta_opcao_pergunta_id = '';
            $data->pergunta_opcao_pergunta_opcao_id = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditPerguntaOpcaoPergunta( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('pergunta_opcao_pergunta_items');

        // get the session item
        $item = $items[$param['pergunta_opcao_pergunta_id_row_id']];

        $data->pergunta_opcao_pergunta_opcao_id = $item['pergunta_opcao_pergunta_opcao_id'];

        $data->pergunta_opcao_pergunta_id = $param['pergunta_opcao_pergunta_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );
    }

    public function onDeletePerguntaOpcaoPergunta( $param )
    {
        $data = $this->form->getData();

        $data->pergunta_opcao_pergunta_opcao_id = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('pergunta_opcao_pergunta_items');

        // delete the item from session
        unset($items[$param['pergunta_opcao_pergunta_id_row_id']]);
        TSession::setValue('pergunta_opcao_pergunta_items', $items);

        // reload sale items
        $this->onReload( $param );
    }

    public function onReloadPerguntaOpcaoPergunta( $param )
    {
        $items = TSession::getValue('pergunta_opcao_pergunta_items'); 

        $this->pergunta_opcao_pergunta_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeletePerguntaOpcaoPergunta')); 
                $action_del->setParameter('pergunta_opcao_pergunta_id_row_id', $key);   

                $action_edi = new TAction(array($this, 'onEditPerguntaOpcaoPergunta'));  
                $action_edi->setParameter('pergunta_opcao_pergunta_id_row_id', $key);  

                $button_del = new TButton('delete_pergunta_opcao_pergunta'.$cont);
                $button_del->class = 'btn btn-default btn-sm';
                $button_del->setAction($action_del, '');
                $button_del->setImage('fa:trash-o'); 
                $button_del->setFormName($this->form->getName());

                $button_edi = new TButton('edit_pergunta_opcao_pergunta'.$cont);
                $button_edi->class = 'btn btn-default btn-sm';
                $button_edi->setAction($action_edi, '');
                $button_edi->setImage('bs:edit');
                $button_edi->setFormName($this->form->getName());

                $rowItem->edit = $button_edi;
                $rowItem->delete = $button_del;

                $rowItem->pergunta_opcao_pergunta_opcao_id = '';
                if(isset($item['pergunta_opcao_pergunta_opcao_id']) && $item['pergunta_opcao_pergunta_opcao_id'])
                {
                    TTransaction::open('eventtus');
                    $opcao = Opcao::find($item['pergunta_opcao_pergunta_opcao_id']);
                    if($opcao)
                    {
                        $rowItem->pergunta_opcao_pergunta_opcao_id = $opcao->render('{id}');
                    }
                    TTransaction::close();
                }

                $row = $this->pergunta_opcao_pergunta_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {
        TSession::setValue('pergunta_opcao_pergunta_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadPerguntaOpcaoPergunta($params);
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

