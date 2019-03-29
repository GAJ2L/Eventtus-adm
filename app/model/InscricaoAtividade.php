<?php

class InscricaoAtividade extends TRecord
{
    const TABLENAME  = 'inscricao_atividade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $inscricao;
    private $atividade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('atividade_id');
        parent::addAttribute('inscricao_id');
            
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
     * Method getMensagems
     */
    public function getMensagems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('inscricao_atividade_id', '=', $this->id));
        return Mensagem::getObjects( $criteria );
    }
    /**
     * Method getAvaliacaoAtividades
     */
    public function getAvaliacaoAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('inscricao_atividade_id', '=', $this->id));
        return AvaliacaoAtividade::getObjects( $criteria );
    }
    /**
     * Method getRespostaPerguntas
     */
    public function getRespostaPerguntas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_inscricao_id', '=', $this->id));
        return RespostaPergunta::getObjects( $criteria );
    }

    
}

