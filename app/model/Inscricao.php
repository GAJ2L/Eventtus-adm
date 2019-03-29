<?php

class Inscricao extends TRecord
{
    const TABLENAME  = 'inscricao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $evento;
    private $pessoa;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('evento_id');
        parent::addAttribute('codigo');
        parent::addAttribute('dt_ativacao');
        parent::addAttribute('dt_cancelamento');
            
    }

    /**
     * Method set_evento
     * Sample of usage: $var->evento = $object;
     * @param $object Instance of Evento
     */
    public function set_evento(Evento $object)
    {
        $this->evento = $object;
        $this->evento_id = $object->id;
    }

    /**
     * Method get_evento
     * Sample of usage: $var->evento->attribute;
     * @returns Evento instance
     */
    public function get_evento()
    {
    
        // loads the associated object
        if (empty($this->evento))
            $this->evento = new Evento($this->evento_id);
    
        // returns the associated object
        return $this->evento;
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
     * Method getAvaliacaoEventos
     */
    public function getAvaliacaoEventos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('inscricao_id', '=', $this->id));
        return AvaliacaoEvento::getObjects( $criteria );
    }
    /**
     * Method getInscricaoAtividades
     */
    public function getInscricaoAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('inscricao_id', '=', $this->id));
        return InscricaoAtividade::getObjects( $criteria );
    }

    
}

