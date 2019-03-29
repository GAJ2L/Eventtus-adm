<?php

class Pergunta extends TRecord
{
    const TABLENAME  = 'pergunta';
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
        parent::addAttribute('descricao');
        parent::addAttribute('descricao_en');
            
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

    /**
     * Method getRespostaPerguntas
     */
    public function getRespostaPerguntas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pergunta_id', '=', $this->id));
        return RespostaPergunta::getObjects( $criteria );
    }
    /**
     * Method getPerguntaOpcaos
     */
    public function getPerguntaOpcaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pergunta_id', '=', $this->id));
        return PerguntaOpcao::getObjects( $criteria );
    }

    
}

