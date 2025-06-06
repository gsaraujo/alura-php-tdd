<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
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

}