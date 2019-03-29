<?php

class AtividadeList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'eventtus';
    private static $activeRecord = 'Atividade';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Atividade';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle('Listagem de atividades');


        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $nome_en = new TEntry('nome_en');
        $descricao = new TEntry('descricao');
        $descricao_en = new TEntry('descricao_en');
        $local_nome = new TEntry('local_nome');
        $local_geolocalizacao = new TEntry('local_geolocalizacao');
        $dt_inicio = new TDateTime('dt_inicio');
        $dt_fim = new TDateTime('dt_fim');

        $dt_fim->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd hh:ii');

        $dt_fim->setMask('dd/mm/yyyy hh:ii');
        $dt_inicio->setMask('dd/mm/yyyy hh:ii');

        $id->setSize(100);
        $nome->setSize('70%');
        $dt_fim->setSize(150);
        $nome_en->setSize('70%');
        $dt_inicio->setSize(150);
        $descricao->setSize('70%');
        $local_nome->setSize('70%');
        $descricao_en->setSize('70%');
        $local_geolocalizacao->setSize('70%');

        $row1 = $this->form->addFields([new TLabel('Id:', null, '14px', null, '100%'),$id],[new TLabel('Nome:', null, '14px', null, '100%'),$nome]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel('Nome en:', null, '14px', null, '100%'),$nome_en],[new TLabel('Descricao:', null, '14px', null, '100%'),$descricao]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel('Descricao en:', null, '14px', null, '100%'),$descricao_en],[new TLabel('Local nome:', null, '14px', null, '100%'),$local_nome]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel('Local geolocalizacao:', null, '14px', null, '100%'),$local_geolocalizacao],[new TLabel('Dt inicio:', null, '14px', null, '100%'),$dt_inicio]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel('Dt fim:', null, '14px', null, '100%'),$dt_fim],[]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction('Exportar como CSV', new TAction([$this, 'onExportCsv']), 'fa:file-text-o #000000');

        $btn_onshow = $this->form->addAction('Cadastrar', new TAction(['AtividadeForm', 'onShow']), 'fa:plus #69aa46');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', 'Id', 'center' , '70px');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_nome_en = new TDataGridColumn('nome_en', 'Nome en', 'left');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_descricao_en = new TDataGridColumn('descricao_en', 'Descricao en', 'left');
        $column_local_nome = new TDataGridColumn('local_nome', 'Local nome', 'left');
        $column_local_geolocalizacao = new TDataGridColumn('local_geolocalizacao', 'Local geolocalizacao', 'left');
        $column_dt_inicio = new TDataGridColumn('dt_inicio', 'Dt inicio', 'left');
        $column_dt_fim = new TDataGridColumn('dt_fim', 'Dt fim', 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_nome_en);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_descricao_en);
        $this->datagrid->addColumn($column_local_nome);
        $this->datagrid->addColumn($column_local_geolocalizacao);
        $this->datagrid->addColumn($column_dt_inicio);
        $this->datagrid->addColumn($column_dt_fim);

        $action_onEdit = new TDataGridAction(array('AtividadeForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel('Editar');
        $action_onEdit->setImage('fa:pencil-square-o #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('AtividadeList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel('Excluir');
        $action_onDelete->setImage('fa:trash-o #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

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
        $container->add(TBreadCrumb::create(['Geral','Atividades']));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = new TCriteria; // creates a criteria

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Atividade($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if (isset($data->nome_en) AND ( (is_scalar($data->nome_en) AND $data->nome_en !== '') OR (is_array($data->nome_en) AND (!empty($data->nome_en)) )) )
        {

            $filters[] = new TFilter('nome_en', 'like', "%{$data->nome_en}%");// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->descricao_en) AND ( (is_scalar($data->descricao_en) AND $data->descricao_en !== '') OR (is_array($data->descricao_en) AND (!empty($data->descricao_en)) )) )
        {

            $filters[] = new TFilter('descricao_en', 'like', "%{$data->descricao_en}%");// create the filter 
        }

        if (isset($data->local_nome) AND ( (is_scalar($data->local_nome) AND $data->local_nome !== '') OR (is_array($data->local_nome) AND (!empty($data->local_nome)) )) )
        {

            $filters[] = new TFilter('local_nome', 'like', "%{$data->local_nome}%");// create the filter 
        }

        if (isset($data->local_geolocalizacao) AND ( (is_scalar($data->local_geolocalizacao) AND $data->local_geolocalizacao !== '') OR (is_array($data->local_geolocalizacao) AND (!empty($data->local_geolocalizacao)) )) )
        {

            $filters[] = new TFilter('local_geolocalizacao', 'like', "%{$data->local_geolocalizacao}%");// create the filter 
        }

        if (isset($data->dt_inicio) AND ( (is_scalar($data->dt_inicio) AND $data->dt_inicio !== '') OR (is_array($data->dt_inicio) AND (!empty($data->dt_inicio)) )) )
        {

            $filters[] = new TFilter('dt_inicio', '=', $data->dt_inicio);// create the filter 
        }

        if (isset($data->dt_fim) AND ( (is_scalar($data->dt_fim) AND $data->dt_fim !== '') OR (is_array($data->dt_fim) AND (!empty($data->dt_fim)) )) )
        {

            $filters[] = new TFilter('dt_fim', '=', $data->dt_fim);// create the filter 
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
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

            // creates a repository for Atividade
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

