<?php

class Evento extends TRecord
{
    const TABLENAME  = 'evento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $responsavel;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('responsavel_id');
        parent::addAttribute('nome');
        parent::addAttribute('nome_en');
        parent::addAttribute('descricao');
        parent::addAttribute('desricao_en');
        parent::addAttribute('banner');
        parent::addAttribute('logo');
        parent::addAttribute('cor');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_fim');
        parent::addAttribute('contato_nome');
        parent::addAttribute('contato_email');
        parent::addAttribute('contato_telefone');
            
    }

    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_responsavel(Pessoa $object)
    {
        $this->responsavel = $object;
        $this->responsavel_id = $object->id;
    }

    /**
     * Method get_responsavel
     * Sample of usage: $var->responsavel->attribute;
     * @returns Pessoa instance
     */
    public function get_responsavel()
    {
    
        // loads the associated object
        if (empty($this->responsavel))
            $this->responsavel = new Pessoa($this->responsavel_id);
    
        // returns the associated object
        return $this->responsavel;
    }

    /**
     * Method getAtividades
     */
    public function getAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('evento_id', '=', $this->id));
        return Atividade::getObjects( $criteria );
    }
    /**
     * Method getNotificacaoEventos
     */
    public function getNotificacaoEventos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('evento_id', '=', $this->id));
        return NotificacaoEvento::getObjects( $criteria );
    }
    /**
     * Method getInscricaos
     */
    public function getInscricaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('evento_id', '=', $this->id));
        return Inscricao::getObjects( $criteria );
    }

    public function store()
    {
        if(!$this->contato_telefone) {
            #fix-me to phpunit error
            // $this->contato_telefone = '999999999999';
        }

        parent::store();
    }   
}

