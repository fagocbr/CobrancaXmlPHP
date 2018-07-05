<?php

namespace CobrancaPHP;

/**
 * Class Tipo
 * @package CobrancaPHP
 */
abstract class Tipo
{
    /**
     * @var string
     */
    const TEXTO = 'TEXTO';

    /**
     * @var string
     */
    const NUMERO = 'NUMERO';

    /**
     * @var string
     */
    const NUMERO_FINANCEIRO = 'NUMERO_FINANCEIRO';
}