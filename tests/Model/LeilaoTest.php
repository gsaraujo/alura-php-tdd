<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana,1000));
        $leilao->recebeLance(new Lance($ana,1500));

        //not evaluated when exception is thrown
        //self::assertCount(1, $leilao->getLances());
        //self::assertEquals(1000,$leilao->getLances()[0]->getValor());

    }

    /**
     * @param int $qtdLances
     * @param Leilao $leilao
     * @param int $ultimoLance
     * @return void
     * @dataProvider geraMultiplosLancesMesmoUsuario
     */
    public function testLeilaoNaoPermiteMaisQueCincoLancesMesmoUsuario(
        int $qtdLances,
        Leilao $leilao,
        int $ultimoLance
    ) {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        ////not evaluated when exception is thrown
        //self::assertCount($qtdLances, $leilao->getLances());
        //self::assertEquals($ultimoLance, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());

    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');
        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao,1000));
        $leilao->recebeLance(new Lance($maria,1500));

        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($maria,2500));

        $leilao->recebeLance(new Lance($joao,3000));
        $leilao->recebeLance(new Lance($maria,3500));

        $leilao->recebeLance(new Lance($joao,4000));
        $leilao->recebeLance(new Lance($maria,4500));

        $leilao->recebeLance(new Lance($joao,5000));
        $leilao->recebeLance(new Lance($maria,5500));

        $leilao->recebeLance(new Lance($joao,6000));

        //not evaluated when exception is thrown
        //self::assertCount(10, $leilao->getLances());
        //self::assertEquals(5500,$leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
        
    }

    /**
     * @param int $qtdLances
     * @param Leilao $leilao
     * @param array $valoresLances
     * @return void
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valoresLances
    ) {

        self::assertCount($qtdLances,$leilao->getLances());
        foreach ($valoresLances as $i => $valorEsperado) {
            self::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());

        }

    }

    public function geraLances()
    {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));

        $leilaoCom1Lance = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lance->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2,$leilaoCom2Lances,[1000,2000]],
            '1-lance' => [1,$leilaoCom1Lance,[5000]],
        ];

    }

    public function geraMultiplosLancesMesmoUsuario()
    {
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');

        $leilao = new Leilao('Fiat 147 0KM');

        for ($i=1; $i < 7; $i++) {
            $leilao->recebeLance(new Lance($maria, 1000*$i));
            $leilao->recebeLance(new Lance($joao, 2000*$i));
        }

        return [
            '6-lances' => [10,$leilao,10000],
        ];

    }

}