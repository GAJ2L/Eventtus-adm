<?php

class AtividadeInteracaoSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'eventtus';
    private static $activeRecord = 'AtividadeInteracao';
    private static $primaryKey = 'id';
    private static $formName = 'formList_AtividadeInteracao';

    public function __construct()
    {
        parent::__construct();

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', 'Código', 'center' , '70px');
        $column_dt_registro = new TDataGridColumn('dt_registro', 'Data', 'left');
        $column_mensagem = new TDataGridColumn('mensagem', 'Mensagem', 'left');
        $column_fl_aprovado = new TDataGridColumn('fl_aprovado', 'Aprovado', 'left');
        $column_atividade_nome = new TDataGridColumn('atividade->nome', 'Atividade', 'left');
        $column_pessoa_nome = new TDataGridColumn('pessoa->nome', 'Pessoa ', 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_dt_registro);
        $this->datagrid->addColumn($column_mensagem);
        $this->datagrid->addColumn($column_fl_aprovado);
        $this->datagrid->addColumn($column_atividade_nome);
        $this->datagrid->addColumn($column_pessoa_nome);

        $action_onAprovar = new TDataGridAction(array('AtividadeInteracaoView', 'onAprovar'));
        $action_onAprovar->setUseButton(false);
        $action_onAprovar->setButtonClass('btn btn-default btn-sm');
        $action_onAprovar->setLabel('Aprovar');
        $action_onAprovar->setImage('fa:gavel #065c0a');
        $action_onAprovar->setField(self::$primaryKey);
        $action_onAprovar->setDisplayCondition('AtividadeInteracaoSimpleList::teste');
        $action_onAprovar->setParameter('id', '{id}');
        $this->datagrid->addAction($action_onAprovar);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
       // $container->add(TBreadCrumb::create(['Cadastros','Atividade']));
        $container->add($panel);

        parent::add($container);

    }

    public static function teste($object)
    {
        try 
        {
            if($object->fl_aprovado)
            {
                return false;
            }

            return true;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'eventtus'
            TTransaction::open(self::$database);

            // creates a repository for AtividadeInteracao
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;
            // creates a criteria
            $criteria = new TCriteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

