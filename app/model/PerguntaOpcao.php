<?php

class PerguntaOpcao extends TRecord
{
    const TABLENAME  = 'pergunta_opcao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $opcao;
    private $pergunta;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pergunta_id');
        parent::addAttribute('opcao_id');
            
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

    
}

