<?php
use PHPUnit\Framework\TestCase;
class AtividadeInteracaoTest extends TestCase
{
    public function testNovaInteracao()
    {
        TTransaction::open('eventtus');
        $interacao = new AtividadeInteracao;
        $interacao->mensagem     = 'Teste';
        $interacao->atividade_id = 1;
        $interacao->pessoa_id    = 1;
        $interacao->dt_registro  = date('Y-m-d H:i:s');
        $interacao->store();
        
        $interacao = AtividadeInteracao::find($interacao->id);

        $this->assertFalse( $interacao->fl_aprovado );
        //TTransaction::close();
    }
}
?>
