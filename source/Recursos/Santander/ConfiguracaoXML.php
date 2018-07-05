<?php

namespace CobrancaPHP\Recursos\Santander;

use CobrancaPHP\ErroValidacaoMarcacao;
use stdClass;

/**
 * Trait ConfiguracaoXML
 * @package CobrancaPHP\Recursos\Santander
 */
trait ConfiguracaoXML
{
    /**
     * @param stdClass $ticket
     * @return array
     */
    public function comporCobranca($ticket)
    {
        $estacao = $this->opcao('estacao');
        $tpAmbiente = $this->opcao('tpAmbiente', 'P');

        $nsu = str_pad($this->parametro('TITULO.NOSSO-NUMERO'), 17, '0', STR_PAD_LEFT);
        $prfNsu = '000';
        if ($tpAmbiente === 'T') {
            $prfNsu = 'TST';
        }
        $dtNsu = $this->opcao('dtNsu', date('dmY'));
        $dto = [
            'ticket' => $ticket,
            'estacao' => $estacao,
            'tpAmbiente' => $tpAmbiente,
            'nsu' => "{$prfNsu}{$nsu}",
            'dtNsu' => $dtNsu,
        ];

        return [
            'dto' => $dto
        ];
    }

    /**
     * @return array
     * @throws ErroValidacaoMarcacao
     */
    private function comporTicket()
    {
        $marcacoes = [
            // CONVENIO
            $this->marcacao('CONVENIO.COD-BANCO', static::CODIGO_BANCO),
            $this->marcacao('CONVENIO.COD-CONVENIO'),
            // PAGADOR
            $this->marcacao('PAGADOR.TP-DOC', '01'),
            $this->marcacao('PAGADOR.NUM-DOC'),
            $this->marcacao('PAGADOR.NOME'),
            $this->marcacao('PAGADOR.ENDER'),
            $this->marcacao('PAGADOR.BAIRRO'),
            $this->marcacao('PAGADOR.CIDADE'),
            $this->marcacao('PAGADOR.UF'),
            $this->marcacao('PAGADOR.CEP'),
            // TITULO
            $this->marcacao('TITULO.NOSSO-NUMERO'),
            $this->marcacao('TITULO.SEU-NUMERO', $this->parametro('TITULO.NOSSO-NUMERO')),
            $this->marcacao('TITULO.DT-VENCTO', date('dmY')),
            $this->marcacao('TITULO.DT-EMISSAO', date('dmY')),
            $this->marcacao('TITULO.ESPECIE', '02'),
            $this->marcacao('TITULO.VL-NOMINAL'),
            $this->marcacao('TITULO.PC-MULTA', 0),
            $this->marcacao('TITULO.QT-DIAS-MULTA', 0),
            $this->marcacao('TITULO.PC-JURO', 0),
            $this->marcacao('TITULO.TP-DESC', 0),
            $this->marcacao('TITULO.VL-DESC', 0),
            $this->marcacao('TITULO.DT-LIMI-DESC', 0),
            $this->marcacao('TITULO.VL-ABATIMENTO', 0),
            $this->marcacao('TITULO.TP-PROTESTO', 0),
            $this->marcacao('TITULO.QT-DIAS-PROTESTO', 0),
            $this->marcacao('TITULO.QT-DIAS-BAIXA', '1'),
            // MENSAGEM
            $this->marcacao('MENSAGEM', 'Sr. Caixa Nao receber apos vencimento nem valor menor que o do documento'),
        ];

        $ticketRequest = [
            'dados' => $marcacoes,
            'expiracao' => $this->opcao('expiracao', 100),
            'sistema' => $this->opcao('sistema', 'YMB')
        ];

        return [
            'TicketRequest' => $ticketRequest
        ];
    }

    /**
     * @param string $propriedade
     * @param mixed [$padrao] ('')
     * @return array
     * @throws ErroValidacaoMarcacao
     */
    public function marcacao($propriedade, $padrao = '')
    {
        $valor = $this->parametro($propriedade, $padrao);
        if (isset($this->marcacoes[$propriedade])) {
            $marcacao = $this->marcacoes[$propriedade];
            /** @var callable $validador */
            $validador = $marcacao['validador'];
            if (!$validador($valor)) {
                throw new ErroValidacaoMarcacao($propriedade, $valor, $marcacao);
            }
            /** @var callable $formatador */
            $formatador = $marcacao['formatador'];
            $valor = $formatador($valor);
        }
        return [
            'key' => $propriedade,
            'value' => $valor
        ];
    }

    /**
     * @param string $propriedade
     * @param mixed [$padrao] (null)
     * @return mixed
     */
    public function parametro($propriedade, $padrao = null)
    {
        if (isset($this->parametros[$propriedade])) {
            return $this->parametros[$propriedade];
        }
        return $padrao;
    }

    /**
     * @param string $propriedade
     * @param mixed [$padrao] (null)
     * @return mixed
     */
    public function opcao($propriedade, $padrao = null)
    {
        if (isset($this->opcoes[$propriedade])) {
            return $this->opcoes[$propriedade];
        }
        return $padrao;
    }
}
