<?php

namespace CobrancaPHP\Recursos\Santander;

use CobrancaPHP\Tipo;
use function is_numeric;

/**
 * Trait ConfiguracaoMarcacao
 * @package CobrancaPHP\Recursos\Santander
 */
trait ConfiguracaoMarcacao
{
    /**
     * @param array $marcacoes
     */
    protected function configurarMarcacoes(array $marcacoes = [])
    {
        $this->marcacoes = $marcacoes;

        $opcional = function () {
            return true;
        };

        $this->adicionarMarcacao('CONVENIO.COD-BANCO', Tipo::NUMERO, 4);
        $this->adicionarMarcacao('CONVENIO.COD-CONVENIO', Tipo::NUMERO, 9);
        // PAGADOR
        $this->adicionarMarcacao('PAGADOR.TP-DOC', Tipo::NUMERO, 2);
        $this->adicionarMarcacao('PAGADOR.NUM-DOC', Tipo::NUMERO, 15);
        $this->adicionarMarcacao('PAGADOR.NOME', Tipo::TEXTO, 40);
        $this->adicionarMarcacao('PAGADOR.ENDER', Tipo::TEXTO, 40);
        $this->adicionarMarcacao('PAGADOR.BAIRRO', Tipo::TEXTO, 30);
        $this->adicionarMarcacao('PAGADOR.CIDADE', Tipo::TEXTO, 20);
        $this->adicionarMarcacao('PAGADOR.UF', Tipo::TEXTO, 2);
        $this->adicionarMarcacao('PAGADOR.CEP', Tipo::NUMERO, 8);
        // TITULO
        $this->adicionarMarcacao('TITULO.NOSSO-NUMERO', Tipo::NUMERO, 13);
        $this->adicionarMarcacao('TITULO.SEU-NUMERO', Tipo::TEXTO, 15, $opcional);
        $this->adicionarMarcacao('TITULO.DT-VENCTO', Tipo::NUMERO, 8);
        $this->adicionarMarcacao('TITULO.DT-EMISSAO', Tipo::NUMERO, 8);
        $this->adicionarMarcacao('TITULO.ESPECIE', Tipo::NUMERO, 2);
        $this->adicionarMarcacao('TITULO.VL-NOMINAL', Tipo::NUMERO_FINANCEIRO, 13);
        $this->adicionarMarcacao('TITULO.PC-MULTA', Tipo::NUMERO_FINANCEIRO, 3, $opcional);
        $this->adicionarMarcacao('TITULO.QT-DIAS-MULTA', Tipo::NUMERO, 2, $opcional);
        $this->adicionarMarcacao('TITULO.PC-JURO', Tipo::NUMERO_FINANCEIRO, 3, $opcional);
        $this->adicionarMarcacao('TITULO.TP-DESC', Tipo::NUMERO, 1);
        $this->adicionarMarcacao('TITULO.VL-DESC', Tipo::NUMERO_FINANCEIRO, 13);
        $this->adicionarMarcacao('TITULO.DT-LIMI-DESC', Tipo::NUMERO, 8);
        $this->adicionarMarcacao('TITULO.VL-ABATIMENTO', Tipo::NUMERO_FINANCEIRO, 13, $opcional);
        $this->adicionarMarcacao('TITULO.TP-PROTESTO', Tipo::NUMERO, 1);
        $this->adicionarMarcacao('TITULO.QT-DIAS-PROTESTO', Tipo::NUMERO, 2);
        $this->adicionarMarcacao('TITULO.QT-DIAS-BAIXA', Tipo::NUMERO, 1);
        // MENSAGEM
        $this->adicionarMarcacao('MENSAGEM', Tipo::TEXTO, 100);
    }

    /**
     * @param string $name
     * @param string $tipo
     * @param int $tamanho
     * @param callable [$validador] (null)
     * @param callable [$formatador] (null)
     */
    protected function adicionarMarcacao($name, $tipo, $tamanho, $validador = null, $formatador = null)
    {
        if (!is_callable($validador)) {
            $validador = function ($valor) use ($tipo, $tamanho) {
                return $this->validarValor($valor, $tipo, $tamanho);
            };
        }

        if (!is_callable($formatador)) {
            $formatador = function ($valor) use ($tipo, $tamanho) {
                return $this->formatarValor($valor, $tipo, $tamanho);
            };
        }

        if (!isset($this->marcacoes[$name])) {
            $this->marcacoes[$name] = [];
        }

        $this->marcacoes[$name]['tipo'] = $tipo;
        $this->marcacoes[$name]['tamanho'] = $tamanho;

        if (!isset($this->marcacoes[$name]['formatador'])) {
            $this->marcacoes[$name]['formatador'] = $formatador;
        }

        if (!isset($this->marcacoes[$name]['validador'])) {
            $this->marcacoes[$name]['validador'] = $validador;
        }
    }

    /**
     * @param mixed $valor
     * @param string $tipo
     * @param int $tamanho
     * @return bool
     * @SuppressWarnings(Unused)
     */
    protected function validarValor($valor, $tipo, $tamanho)
    {
        $valor = strval($valor);
        if (strlen($valor) > $tamanho) {
            return false;
        }
        if (strlen($valor) === 0) {
            return false;
        }
        return true;
    }

    /**
     * @param mixed $valor
     * @param string $tipo
     * @param int $tamanho
     * @return string
     */
    protected function formatarValor($valor, $tipo, $tamanho)
    {
        if ($tipo === Tipo::TEXTO) {
            return $valor;
        }
        if (!is_numeric($valor)) {
            return $valor;
        }
        if ($tipo === Tipo::NUMERO_FINANCEIRO) {
            $valor = number_format((float)$valor, 2, '.', '');
        }
        $valor = preg_replace('/\D/', '', $valor);
        return str_pad($valor, $tamanho, '0', STR_PAD_LEFT);
    }
}
