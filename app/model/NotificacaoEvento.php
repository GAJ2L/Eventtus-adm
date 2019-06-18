<?php

class NotificacaoEvento extends TRecord
{
    const TABLENAME  = 'notificacao_evento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $evento;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('evento_id');
        parent::addAttribute('titulo');
        parent::addAttribute('titulo_en');
        parent::addAttribute('conteudo');
        parent::addAttribute('conteudo_en');
        parent::addAttribute('logo');
        parent::addAttribute('dt_notificacao');
    
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

    public function store()
    {
        if(!$this->id)
        {
            $this->dt_notificacao = date("Y-m-d H:i:s");
        }
    
        parent::store();
    }

}

