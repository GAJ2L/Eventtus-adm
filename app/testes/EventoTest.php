<?php
use PHPUnit\Framework\TestCase;
class EventoTest extends TestCase
{
    public function testNovoEvento()
    {
        $this->expectException( 'Exception' );

        TTransaction::open('eventtus');
        $contato = Pessoa::find(1);

        $evento = new Evento;
        $evento->responsavel_id   = 1;
        $evento->nome             = "Teste";
        $evento->descricao        = "Descricao";
        $evento->dt_inicio        = date('Y-m-d');
        $evento->dt_fim           = date('Y-m-d', strtotime('now + 2day'));
        $evento->contato_nome     = $contato->nome;
        $evento->contato_email    = $contato->email;
        
        $evento->store();

        //TTransaction::close();
    }
}
?>
