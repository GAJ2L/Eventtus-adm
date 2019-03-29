<?php

class Atividade extends TRecord
{
    const TABLENAME  = 'atividade';
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
        parent::addAttribute('nome');
        parent::addAttribute('nome_en');
        parent::addAttribute('descricao');
        parent::addAttribute('descricao_en');
        parent::addAttribute('local_nome');
        parent::addAttribute('local_geolocalizacao');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_fim');
            
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

    /**
     * Method getNotificacaoAtividades
     */
    public function getNotificacaoAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $this->id));
        return NotificacaoAtividade::getObjects( $criteria );
    }
    /**
     * Method getResponsaveisAtividades
     */
    public function getResponsaveisAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $this->id));
        return ResponsaveisAtividade::getObjects( $criteria );
    }
    /**
     * Method getInscricaoAtividades
     */
    public function getInscricaoAtividades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $this->id));
        return InscricaoAtividade::getObjects( $criteria );
    }
    /**
     * Method getAnexos
     */
    public function getAnexos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $this->id));
        return Anexo::getObjects( $criteria );
    }
    /**
     * Method getPerguntas
     */
    public function getPerguntas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $this->id));
        return Pergunta::getObjects( $criteria );
    }

    
}

