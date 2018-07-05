<?php

namespace CobrancaPHP;

/**
 * Class Transacao
 * @package CobrancaPHP
 */
abstract class Transacao implements TransacaoContrato
{
    /**
     * @var string
     */
    protected $certificado = '';

    /**
     * @var string
     */
    protected $senha = '';

    /**
     * @var array
     */
    protected $opcoes = [];

    /**
     * Transacao constructor.
     * @param string $certificado
     * @param string $senha
     * @param array [$opcoes] ([])
     */
    public function __construct($certificado, $senha, array $opcoes = [])
    {
        $this->certificado = $certificado;
        $this->senha = $senha;
        $this->opcoes = $opcoes;
    }

    /**
     * @param string $certificado
     * @param string $senha
     * @param array [$opcoes] ([])
     * @return Transacao
     */
    public static function criar($certificado, $senha, $opcoes = [])
    {
        return new static($certificado, $senha, $opcoes);
    }
}
