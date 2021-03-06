<?php

namespace CobrancaPHP\Test;

use CobrancaPHP\Recursos\Santander;
use CobrancaPHP\Recursos\Santander\Resposta;

/**
 * Class SantanderTest
 * @package CobrancaPHP\Test
 */
class SantanderTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function test()
    {
        $criado = Santander::criar('', '', []);
        $this->assertInstanceOf(Santander::class, $criado, 'Instância de `Santander`');

        $env = require dirname(__DIR__) . '/env.php';

        $certificado = $env['certificado'];
        $senha = $env['senha'];
        $opcoes = $env['opcoes'];
        $parametros = $env['parametros'];

        $registrado = Santander::criar($certificado, $senha, $opcoes)->registrar($parametros);
        $this->assertInstanceOf(
            Resposta::class,
            $registrado,
            "Comunicação com `Santander`: {$registrado->pegar('return.descricaoErro')}"
        );
        $this->assertEquals(
            $registrado->pegar('return.situacao'),
            '00',
            "Comunicação com `Santander`: {$registrado->pegar('return.descricaoErro')}"
        );

        $opcionais = [
            // 'TITULO.NOSSO-NUMERO' => null,
            'TITULO.DT-VENCTO' => date('dmY'),
            'TITULO.DT-EMISSAO' => date('dmY'),
            'TITULO.ESPECIE' => '02',
            // 'TITULO.VL-NOMINAL' => null,
            'TITULO.PC-MULTA' => 0,
            'TITULO.QT-DIAS-MULTA' => 0,
            'TITULO.PC-JURO' => 0,
            'TITULO.TP-DESC' => 0,
            'TITULO.VL-DESC' => 0,
            'TITULO.DT-LIMI-DESC' => 0,
            'TITULO.VL-ABATIMENTO' => 0,
            'TITULO.TP-PROTESTO' => 0,
            'TITULO.QT-DIAS-PROTESTO' => 0,
            'TITULO.QT-DIAS-BAIXA' => '1',
            // MENSAGEM
            'MENSAGEM' => 'Sr. Caixa Nao receber apos vencimento nem valor menor que o do documento',
        ];

        $completo = Santander
            ::criar($certificado, $senha, $opcoes)
            ->registrar($parametros + $opcionais);
        $this->assertEquals(
            $completo->pegar('return.situacao'),
            '00',
            "Comunicação com `Santander`: {$completo->pegar('return.descricaoErro')}"
        );
    }
}
