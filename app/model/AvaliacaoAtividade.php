<?php

class AvaliacaoAtividade extends TRecord
{
    const TABLENAME  = 'avaliacao_atividade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $inscricao_atividade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('inscricao_atividade_id');
        parent::addAttribute('estrelas');
        parent::addAttribute('comentario');
        parent::addAttribute('dt_registro');
            
    }

    /**
     * Method set_inscricao_atividade
     * Sample of usage: $var->inscricao_atividade = $object;
     * @param $object Instance of InscricaoAtividade
     */
    public function set_inscricao_atividade(InscricaoAtividade $object)
    {
        $this->inscricao_atividade = $object;
        $this->inscricao_atividade_id = $object->id;
    }

    /**
     * Method get_inscricao_atividade
     * Sample of usage: $var->inscricao_atividade->attribute;
     * @returns InscricaoAtividade instance
     */
    public function get_inscricao_atividade()
    {
    
        // loads the associated object
        if (empty($this->inscricao_atividade))
            $this->inscricao_atividade = new InscricaoAtividade($this->inscricao_atividade_id);
    
        // returns the associated object
        return $this->inscricao_atividade;
    }

    
}

