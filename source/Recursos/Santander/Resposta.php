<?php

namespace CobrancaPHP\Recursos\Santander;

use CobrancaPHP\Resposta as RespostaAbstrata;

/**
 * Class Resposta
 * @package CobrancaPHP\Recursos\Santander
 */
class Resposta extends RespostaAbstrata
{
    /**
     * @return bool
     */
    public function estaOk()
    {
        return $this->pegar('return.status') === '00';
    }
}
