<?php

class AtividadeInteracaoView extends TPage
{
    private $html;
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();

        $this->html = new THtmlRenderer('app/resources/geral/atividade_interacao.html');

        parent::add($this->html);
    }

    public function onLoad($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_id', '=', $param['atividade_id']));

        $criteria->setProperty('order', 'dt_registro asc');

        TTransaction::open('eventtus');
        $atividade   = Atividade::find($param['atividade_id']);
        $interacaoes = AtividadeInteracao::getObjects($criteria);

        $param = [];
        $param['nome'] = $atividade->nome;
        $param['id']   = $atividade->id;
        $mensagens = [];
        foreach ($interacaoes as $interacao)
        {
            if($interacao->fl_aprovado)
            {
                $mensagens[] = [
                    'dt_registro' => date('d/m/y H:i:s', strtotime($interacao->dt_registro)),
                    'mensagem'    => $interacao->mensagem,
                    'nome'        => $interacao->pessoa->nome,
                ];   
            }
        }
        
        TTransaction::close();

        $this->html->enableSection('main');
        $this->html->enableSection('atividade', $param);
        $this->html->enableSection('mensagem', $mensagens, TRUE);
    }
    
    public static function onAprovar($param)
    {
        try 
        {
            TTransaction::open('eventtus');
            
            $atividade_interacao = new AtividadeInteracao($param['id']);
            
            $atividade_interacao->fl_aprovado = TRUE;
            $atividade_interacao->store();
            
            new TMessage('info', 'Mensagem aprovado com sucesso!');
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
