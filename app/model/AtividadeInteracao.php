<?php

class AtividadeInteracao extends TRecord
{
    const TABLENAME  = 'atividade_interacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $pessoa;
    private $atividade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dt_registro');
        parent::addAttribute('mensagem');
        parent::addAttribute('fl_aprovado');
        parent::addAttribute('atividade_id');
        parent::addAttribute('pessoa_id');
            
    }

    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }

    /**
     * Method get_pessoa
     * Sample of usage: $var->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
    
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    /**
     * Method set_atividade
     * Sample of usage: $var->atividade = $object;
     * @param $object Instance of Atividade
     */
    public function set_atividade(Atividade $object)
    {
        $this->atividade = $object;
        $this->atividade_id = $object->id;
    }

    /**
     * Method get_atividade
     * Sample of usage: $var->atividade->attribute;
     * @returns Atividade instance
     */
    public function get_atividade()
    {
    
        // loads the associated object
        if (empty($this->atividade))
            $this->atividade = new Atividade($this->atividade_id);
    
        // returns the associated object
        return $this->atividade;
    }

    public function store()
    {
        if(is_null($this->fl_aprovado)) {
            #fix-me to phpunit error
            $this->fl_aprovado = TRUE;
        }

        parent::store();
    }    
}

