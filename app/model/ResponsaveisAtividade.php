<?php

class ResponsaveisAtividade extends TRecord
{
    const TABLENAME  = 'responsaveis_atividade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $atividade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('atividade_id');
        parent::addAttribute('usuario_id');
            
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

    
}

