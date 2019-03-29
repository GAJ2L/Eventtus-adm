<?php

class Pessoa extends TRecord
{
    const TABLENAME  = 'pessoa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('email');
        parent::addAttribute('email2');
        parent::addAttribute('email3');
        parent::addAttribute('foto');
        parent::addAttribute('token');
        parent::addAttribute('idioma');
            
    }

    /**
     * Method getEventos
     */
    public function getEventos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('responsavel_id', '=', $this->id));
        return Evento::getObjects( $criteria );
    }
    /**
     * Method getInscricaos
     */
    public function getInscricaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Inscricao::getObjects( $criteria );
    }

    
}

