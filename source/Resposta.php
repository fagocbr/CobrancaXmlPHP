<?php

namespace CobrancaPHP;

/**
 * Class Resposta
 * @package CobrancaPHP
 */
class Resposta
{
    /**
     * @var mixed
     */
    protected $dados;

    /**
     * Resposta constructor.
     * @param mixed $dados
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * @param mixed $dados
     * @return Resposta
     */
    public static function construir($dados)
    {
        return new static($dados);
    }

    /**
     * @param string $atributo
     * @param mixed [$padrao] (null)
     * @return mixed
     */
    public function pegar($atributo, $padrao = null)
    {
        if (is_string($atributo)) {
            $atributo = explode('.', $atributo);
        }

        $contexto = clone $this->dados;

        if (!is_array($contexto) && !is_object($contexto)) {
            return $padrao;
        }

        if (is_array($contexto)) {
            return $this->procurarEmLista($atributo, $contexto, $padrao);
        }

        if (is_object($contexto)) {
            return $this->procurarEmObjeto($atributo, $contexto, $padrao);
        }

        return $contexto;
    }

    /**
     * @param array $caminho
     * @param array $contexto
     * @param $padrao
     * @return mixed
     */
    private function procurarEmLista(array $caminho, array $contexto, $padrao)
    {
        foreach ($caminho as $propriedade) {
            if (!isset($contexto[$propriedade])) {
                return $padrao;
            }
            $contexto = $contexto[$propriedade];
        }
        return $contexto;
    }

    /**
     * @param array $caminho
     * @param mixed $contexto
     * @param $padrao
     * @return mixed
     */
    private function procurarEmObjeto(array $caminho, $contexto, $padrao)
    {
        foreach ($caminho as $propriedade) {
            /** @noinspection PhpVariableVariableInspection */
            if (!isset($contexto->$propriedade)) {
                return $padrao;
            }
            /** @noinspection PhpVariableVariableInspection */
            $contexto = $contexto->$propriedade;
        }
        return $contexto;
    }
}
