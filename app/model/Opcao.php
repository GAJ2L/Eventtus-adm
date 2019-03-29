<?php

class Opcao extends TRecord
{
    const TABLENAME  = 'opcao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('descricao_en');
        parent::addAttribute('valor');
            
    }

    /**
     * Method getRespostaPerguntas
     */
    public function getRespostaPerguntas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('opcao_id', '=', $this->id));
        return RespostaPergunta::getObjects( $criteria );
    }
    /**
     * Method getPerguntaOpcaos
     */
    public function getPerguntaOpcaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('opcao_id', '=', $this->id));
        return PerguntaOpcao::getObjects( $criteria );
    }

    
}

