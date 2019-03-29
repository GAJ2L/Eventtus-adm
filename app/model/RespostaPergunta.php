<?php

class RespostaPergunta extends TRecord
{
    const TABLENAME  = 'resposta_pergunta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $pergunta;
    private $opcao;
    private $atividade_inscricao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('atividade_inscricao_id');
        parent::addAttribute('opcao_id');
        parent::addAttribute('pergunta_id');
        parent::addAttribute('dt_registro');
            
    }

    /**
     * Method set_pergunta
     * Sample of usage: $var->pergunta = $object;
     * @param $object Instance of Pergunta
     */
    public function set_pergunta(Pergunta $object)
    {
        $this->pergunta = $object;
        $this->pergunta_id = $object->id;
    }

    /**
     * Method get_pergunta
     * Sample of usage: $var->pergunta->attribute;
     * @returns Pergunta instance
     */
    public function get_pergunta()
    {
    
        // loads the associated object
        if (empty($this->pergunta))
            $this->pergunta = new Pergunta($this->pergunta_id);
    
        // returns the associated object
        return $this->pergunta;
    }
    /**
     * Method set_opcao
     * Sample of usage: $var->opcao = $object;
     * @param $object Instance of Opcao
     */
    public function set_opcao(Opcao $object)
    {
        $this->opcao = $object;
        $this->opcao_id = $object->id;
    }

    /**
     * Method get_opcao
     * Sample of usage: $var->opcao->attribute;
     * @returns Opcao instance
     */
    public function get_opcao()
    {
    
        // loads the associated object
        if (empty($this->opcao))
            $this->opcao = new Opcao($this->opcao_id);
    
        // returns the associated object
        return $this->opcao;
    }
    /**
     * Method set_inscricao_atividade
     * Sample of usage: $var->inscricao_atividade = $object;
     * @param $object Instance of InscricaoAtividade
     */
    public function set_atividade_inscricao(InscricaoAtividade $object)
    {
        $this->atividade_inscricao = $object;
        $this->atividade_inscricao_id = $object->id;
    }

    /**
     * Method get_atividade_inscricao
     * Sample of usage: $var->atividade_inscricao->attribute;
     * @returns InscricaoAtividade instance
     */
    public function get_atividade_inscricao()
    {
    
        // loads the associated object
        if (empty($this->atividade_inscricao))
            $this->atividade_inscricao = new InscricaoAtividade($this->atividade_inscricao_id);
    
        // returns the associated object
        return $this->atividade_inscricao;
    }

    
}

