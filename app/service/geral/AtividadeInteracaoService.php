<?php

class AtividadeInteracaoService
{
    public function __construct($param)
    {
        parent::__construct();
    }
    
    /**
     * Busca as ultimas interalções realizadas na atividade passado no parametro
     * @param  int     $atividade_id Codigo da atividade
     * @return array   retorna um array de objetos do tipo Atividade
     *
     * @author Lucas Tomasi
     * @date   2019-05-28
     */
    public static function getInteracoes(int $atividade_id) : array
    {
        try
        {
            TTransaction::open('eventtus');
            $interacoes = AtividadeInteracao::where('ref_atividade', '=',$atividade_id)->where('fl_aprovado', '=', 't')->load();
            TTransaction::close();
            
            return $interacoes;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
        }
    }
    
    
    public static function getUltimasInteracoesHTML($param)
    {
        try
        {
            TTransaction::open('eventtus');
            $dt = date('Y-m-d H:i:s' , strtotime('now -5 second'));

            $interacoes = AtividadeInteracao::where('dt_registro::timestamp', '>', "NOESC: '$dt'::timestamp" )
                                            ->where('fl_aprovado', '=', 't')->get();
            if($interacoes)
            {
                foreach($interacoes as $interacao)
                {
                    $dt_registro = date('d-m-Y H:i:s', strtotime($interacao->dt_registro));

                    echo "<div class='menssage'>
                            {$interacao->mensagem}
                            <div class='message-info'>
                                <div>{$interacao->pessoa->nome} - {$dt_registro}</div>
                            </div>
                        </div>";
                }
            }

            TTransaction::close();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            TTransaction::rollback();
        }
    }
}
