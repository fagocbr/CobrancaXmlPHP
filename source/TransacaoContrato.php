<?php

namespace CobrancaPHP;

/**
 * Class TransacaoContrato
 * @package CobrancaPHP
 */
interface TransacaoContrato
{
    /**
     * @param array $parametros
     * @return Resposta
     */
    public function registrar(array $parametros);
}
