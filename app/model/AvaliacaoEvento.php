<?php

class AvaliacaoEvento extends TRecord
{
    const TABLENAME  = 'avaliacao_evento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $inscricao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('inscricao_id');
        parent::addAttribute('estrelas');
        parent::addAttribute('comentario');
        parent::addAttribute('dt_registro');
            
    }

    /**
     * Method set_inscricao
     * Sample of usage: $var->inscricao = $object;
     * @param $object Instance of Inscricao
     */
    public function set_inscricao(Inscricao $object)
    {
        $this->inscricao = $object;
        $this->inscricao_id = $object->id;
    }

    /**
     * Method get_inscricao
     * Sample of usage: $var->inscricao->attribute;
     * @returns Inscricao instance
     */
    public function get_inscricao()
    {
    
        // loads the associated object
        if (empty($this->inscricao))
            $this->inscricao = new Inscricao($this->inscricao_id);
    
        // returns the associated object
        return $this->inscricao;
    }

    
}

