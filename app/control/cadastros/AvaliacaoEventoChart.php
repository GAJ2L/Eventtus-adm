<?php

class AvaliacaoEventoChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'eventtus';
    private static $activeRecord = 'AvaliacaoEvento';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_AvaliacaoEvento';

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
        $this->form->setFormTitle('Avaliações');

        $inscricao_evento_id = new TDBUniqueSearch('inscricao_evento_id', 'eventtus', 'Evento', 'id', 'id','nome asc'  );

        $inscricao_evento_id->setSize('100%');
        $inscricao_evento_id->setMinLength(0);
        $inscricao_evento_id->setMask('{nome}');

        $row1 = $this->form->addFields([new TLabel('Evento', null, '14px', null),$inscricao_evento_id]);
        $row1->layout = ['col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction('Gerar', new TAction([$this, 'onGenerate']), 'fa:search #ffffff');
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(['Cadastros','Avaliações']));
        $container->add($this->form);

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

        if (isset($data->inscricao_evento_id) AND ( (is_scalar($data->inscricao_evento_id) AND $data->inscricao_evento_id !== '') OR (is_array($data->inscricao_evento_id) AND (!empty($data->inscricao_evento_id)) )) )
        {

            $filters[] = new TFilter('inscricao_id', 'in', "(SELECT id FROM inscricao WHERE evento_id = '{$data->inscricao_evento_id}')");// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);
    }

    /**
     * Load the datagrid with data
     */
    public function onGenerate()
    {
        try
        {
            $this->onSearch();
            // open a transaction with database 'eventtus'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for AvaliacaoEvento
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if ($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {

                $dataTotals = [];
                $groups = [];
                $data = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->estrelas;

                    $groups[$group1] = true;
                    $numericField = $obj->estrelas;

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

                ksort($dataTotals);
                ksort($groups);

                $groups = ['x'=>true]+$groups;

                foreach ($dataTotals as $group1 => $totals) 
                {    

                    array_push($data, [$group1, $totals['sum']/$totals['count']]);
                }

                $chart = new THtmlRenderer('app/resources/c3_pizza_chart.html');
                $chart->enableSection('main', [
                    'data'=> json_encode($data),
                    'height' => 500,
                    'precision' => 0,
                    'decimalSeparator' => ',',
                    'thousandSeparator' => '.',
                    'prefix' => '',
                    'sufix' => ' Estrela',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Média',
                    'showLegend' => 'false',
                    'showPercentage' => 'false',
                    'barDirection' => 'false'
                ]);

                parent::add($chart);
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
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

}

