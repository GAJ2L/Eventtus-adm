<?php

class InscricaoUploadForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'eventtus';
    private static $activeRecord = 'Inscricao';
    private static $primaryKey = 'id';
    private static $formName = 'form_Inscricao';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFieldSizes('100%');
        // define the form title
        $this->form->setFormTitle('Cadastro de inscrição em evento por upload de documento');

        $filename = new TFile('filename');
        $filename->setService('SystemDocumentUploaderService');

        $this->form->addFields( [new TLabel(_t('File')), $filename] );
        $filename->setSize('80%');

        $evento_id = new TDBCombo('evento_id', 'eventtus', 'Evento', 'id', '{nome}','id asc'  );
        $evento_id->addValidation('Evento', new TRequiredValidator()); 
        $evento_id->setChangeAction(new TAction([$this, 'onChangeEvento']));
        $evento_id->setSize('100%');

        $atividade_id = new TSelect('atividade_id');
        // $atividade_id->addValidation('Evento', new TRequiredValidator()); 
        $atividade_id->setSize('100%');

        $row2 = $this->form->addFields([new TLabel('Evento', '#ff0000', '14px', null, '100%'),$evento_id]);
        $row2 = $this->form->addFields([new TLabel('Atividades', '', '14px', null, '100%'),$atividade_id]);
       // $row2->layout = ['col-sm-6','col-sm-6'];


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

    public static function onChangeEvento($param)
    {
        TTransaction::open('eventtus');
        $evento = new Evento($param['key']);
        $atividades = $evento->getAtividades();
        $a = [];
        if($atividades)
        {   
            foreach ($atividades as $atividade)
            {
                $a[$atividade->id] = $atividade->nome;
            }
        }

        TSelect::reload(self::$formName, 'atividade_id', $a);

        TTransaction::close();
    }

    public function onSave($param = null) 
    {
        try
        {
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            $messageAction = null;

            //SALVANDO ARQUIVO

            $data = $this->form->getData(); // get form data as array
            $arquivoRecebido = $data->filename;
            $evento_id = $data->evento_id;

        
            $source_file   = 'tmp/'. $arquivoRecebido;
            $target_path   = 'files/documents/';
            $target_file   =  $target_path . '/'. $arquivoRecebido;
            
            if (file_exists($source_file))
            {
                if (!file_exists($target_path))
                {
                    if (!mkdir($target_path, 0777, true))
                    {
                        throw new Exception(_t('Permission denied'). ': '. $target_path);
                    }
                }
                else
                {
                    foreach (glob("$target_path/*") as $file)
                    {
                        unlink($file);
                    }
                }
                
                // if the user uploaded a source file
                if (file_exists($target_path))
                {
                    // move to the target directory
                    rename($source_file, $target_file);
                }
            }

            $arquivo = $target_file;
            //$handle = fopen('/home/dai/Downloads/modelo import inscritos.csv', 'r');
            $handle = fopen($arquivo, 'r');

            while(!feof($handle) && ($dados = fgetcsv($handle, 10000, ",")) !== false) {
                
                // Verifica se o Dados Não é o cabeçalho ou não esta em branco
                if($dados[0] != 'codigo inscrito' && !empty($dados)) 
                {
                    TTransaction::open(self::$database); // open a transaction

                    $dataPessoa = array();
                    $dataPessoa['nome'] = $dados[1];
                    $dataPessoa['email'] = $dados[2];
                    $dataPessoa['idioma'] = $dados[3];
    
                    // load the objectPessoa with data
                    $objectPessoa = new Pessoa(); // create an empty object 
                    $objectPessoa->fromArray($dataPessoa);                    
                    $objectPessoa->store(); // save the objectPessoa 

                    $dataInscricao = array();
                    $dataInscricao['pessoa_id'] = $objectPessoa->id;
                    //$dataInscricao['evento_id'] = $dados[5];
                    $dataInscricao['evento_id'] = $evento_id;
                    $dataInscricao['codigo'] = $dados[0];
                    $dataInscricao['dt_ativacao'] = $dados[4];
                    
                    // load the objectInscricao with data
                    $objectInscricao = new Inscricao();  // create an empty object 
                    $objectInscricao->fromArray($dataInscricao);
                    $objectInscricao->store(); // save the objectInscricao 
                    
                    TTransaction::close(); // close the transaction
                
                }
            }
            // Fecha arquivo aberto
            fclose($handle);

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Records saved'), $messageAction);

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
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

