<?php

class EstatisiticaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'eventtus';
    private static $activeRecord = 'Evento';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Evento';

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
        $this->form->setFormTitle('Estatísticas');


        $id = new TDBUniqueSearch('id', 'eventtus', 'Evento', 'id', 'nome','id asc'  );

        $id->setSize('100%');
        $id->setMinLength(0);
        $id->setMask('{id} - {nome}');

        $row1 = $this->form->addFields([new TLabel('Código:', null, '14px', null, '100%'),$id]);
        $row1->layout = ['col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_estrela_transformed = new TDataGridColumn('id', 'Estrelas', 'left');
        $column_avaliacao_transformed = new TDataGridColumn('id', 'Avaliações', 'left');
        $column_inscritostransformed = new TDataGridColumn('id', 'Inscritos', 'left');

        $column_estrela_transformed->setTransformer(function($value, $object, $row)
        {
            $avalicaoes = AvaliacaoEvento::where("inscricao_id", " IN ", "NOESC: (SELECT id FROM inscricao WHERE  evento_id = $value)")->get();

            if(empty($avalicaoes))
            {
                return 0;
            }

            $soma = 0;

            foreach($avalicaoes as $avalicao) 
            {
                $soma += ($avalicao->estrelas??0);
            }

            return ($soma/count($avalicaoes));

        });

        $column_avaliacao_transformed->setTransformer(function($value, $object, $row)
        {
            return AvaliacaoEvento::where("inscricao_id", " IN ", "NOESC: (SELECT id FROM inscricao WHERE  evento_id = $value)")->count();

        });

        $column_inscritostransformed->setTransformer(function($value, $object, $row)
        {
            return Inscricao::where("evento_id", "=", $value)->count();

        });        

        
        $this->datagrid->addColumn($column_estrela_transformed);
        $this->datagrid->addColumn($column_avaliacao_transformed);
        $this->datagrid->addColumn($column_inscritostransformed);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        // $panel = new TPanelGroup;
        // $panel->add($this->datagrid);

        // $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(['Cadastros','Estatísticas']));
        $container->add($this->form);
        // $container->add($panel);

        parent::add($container);

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

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;
        $param['aqui'] = 1;

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
            if(empty($param['aqui']))
            {
                return;
            }
            // open a transaction with database 'eventtus'
            TTransaction::open(self::$database);

            // creates a repository for Evento
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
                    $avalicaoes = AvaliacaoEvento::where("inscricao_id", " IN ", "NOESC: (SELECT id FROM inscricao WHERE  evento_id = {$object->id})")->get();

                    $media = 0;
                    if(!empty($avalicaoes))
                    {
                        $soma = 0;

                        foreach($avalicaoes as $avalicao) 
                        {
                            $soma += ($avalicao->estrelas??0);
                        }

                        $media = ($soma/count($avalicaoes));
                        
                    }

                    // add the object inside the datagrid
                    $div = new TElement('div');
                    $div->class = 'col-sm-4';
                    $div->add('<div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">star</i>
                        </div>
                        <div class="content">
                            <div class="text">MÉDIA DE AVALIAÇÕES</div>
                            <div class="number count-to" data-from="0" data-to="'.$media.'" data-speed="15" data-fresh-interval="20">'.$media.'</div>
                        </div>
                    </div>');
                    
                    parent::add($div);
                    $count = AvaliacaoEvento::where("inscricao_id", " IN ", "NOESC: (SELECT id FROM inscricao WHERE  evento_id = {$object->id})")->count();
                    $div = new TElement('div');
                    $div->class = 'col-sm-4';
                    $div->add('<div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">forum</i>
                        </div>
                        <div class="content">
                            <div class="text">QUANTIDADE DE VOTOS</div>
                            <div class="number count-to" data-from="0" data-to="'.$count.'" data-speed="1000" data-fresh-interval="20">'.$count.'</div>
                        </div>
                    </div>');

                    parent::add($div);
                    $insc = Inscricao::where("evento_id", "=", $object->id)->count();
                    $div = new TElement('div');
                    $div->class = 'col-sm-4';
                    $div->add('<div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">INSCRITOS</div>
                            <div class="number count-to" data-from="0" data-to="'.$insc.'" data-speed="1000" data-fresh-interval="20">'.$insc.'</div>
                        </div>
                    </div>');

                    parent::add($div);
                    return;

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


