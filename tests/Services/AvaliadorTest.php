<?php

namespace Alura\Leilao\Tests\Services;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{

    /** @var Avaliador  */
    private $leiloeiro;
    public function setUp(): void
    {
        $this->leiloeiro = new Avaliador();

    }

    /**
     * @param Leilao $leilao
     * @return void
     * @dataProvider entregaLeiloes
     */
    /*public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        //$this->assertEquals(2500, $maiorValor);
        self::assertEquals(2500, $maiorValor);


    }*/

    /**
     * @param Leilao $leilao
     * @return void
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesTriploDataProvider(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        //$this->assertEquals(2500, $maiorValor);
        self::assertEquals(2500, $maiorValor);

    }

    /**
     * @param Leilao $leilao
     * @return void
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        //$this->assertEquals(2500, $menorValor);
        self::assertEquals(1700, $menorValor);


    }

    /**
     * @param Leilao $leilao
     * @return void
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();

        //self::assertEquals(3, count($maiores));
        self::assertCount(3, $maiores);
        self::assertEquals(2500, $maiores[0]->getValor());
        self::assertEquals(2000, $maiores[1]->getValor());
        self::assertEquals(1700, $maiores[2]->getValor());

    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Não é possível avaliar leilão vazio');

        $leilao = new Leilao('Fusca Azul 0KM');

        $this->leiloeiro->avalia($leilao);

        /*try {//Arrange - Act - Assert
            $leilao = new Leilao('Fusca Azul 0KM');
            $this->leiloeiro->avalia($leilao);
            //you need to comment out exception on Avaliador
            self:self::fail('Exceção deveria ter sido lançada');
        } catch (\DomainException $exception) {
            self::assertEquals('Não é possível avaliar leilão vazio', $exception->getMessage());
        }*/
        
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat 147 0KM');

        $leilao->recebeLance(new Lance(new Usuario('Maria'), 2000));

        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
        
    }

    // --------------- DATA -----------------
    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');;
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
           "ordem-crescente" => [$leilao]
        ];
        
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');;
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            "ordem-decrescente" => [$leilao]
        ];

    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');;
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            "ordem-aleatoria" => [$leilao]
        ];
        
    }

    public function entregaLeiloes()
    {
        return [
            $this->leilaoEmOrdemCrescente(),
            $this->leilaoEmOrdemDecrescente(),
            $this->leilaoEmOrdemAleatoria(),
        ];
        
    }

}