<?php

namespace CobrancaPHP;

use Exception;

/**
 * Class ErroValidacaoMarcacao
 * @package CobrancaPHP
 */
class ErroValidacaoMarcacao extends Exception
{
    /**
     * ErroValidacaoMarcacao constructor.
     * @param string $nome
     * @param mixed $valor
     * @param array $marcacao
     */
    public function __construct($nome = '', $valor = null, $marcacao = [])
    {
        parent::__construct($this->mensagem($nome, $valor, $marcacao), 0, null);
    }

    /**
     * @param string $nome
     * @param mixed $valor
     * @param array $marcacao
     * @return string
     */
    private function mensagem($nome, $valor, $marcacao)
    {
        $var = var_export($marcacao, true);
        return "Problema ao validar o rótulo: '{$nome}'. O valor informado foi: '{$valor}'.\n[{$var}]";
    }
}
