<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Guzzle\Exception\GuzzleException;

class APIController extends Controller
{

    public function token(){
        try {
            /* Criação do objeto cliente */
            $guzzle = new Client([
                                     'headers' => [
                                         'gw-dev-app-key' => config('apiCobranca.gw_dev_app_key') ,
                                         'Authorization' => config('apiCobranca.authorization'),
                                         'Content-Type' => 'application/x-www-form-urlencoded',
                                     ],
                                     /* Desativar SSL*/
                                     'verify' => false
                                 ]);
            /* Requisição POST*/
            $response = $guzzle->request('POST', 'https://oauth.sandbox.bb.com.br/oauth/token?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key'),
                                         array(
                                             'form_params' => array(
                                                 'grant_type' => 'client_credentials',
                                                 'client_id' =>  config('apiCobranca.client_id'),
                                                 'client_secret' =>  config('apiCobranca.client_secret') ,
                                                 'scope' => 'cobrancas.boletos-info cobrancas.boletos-requisicao'
                                             )));

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converte o JSON em array associativo PHP */
            $token = json_decode($contents);

            return $token->access_token;

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function registrar(){
        /* Informações do Boleto */
        $body = array(
            'numeroConvenio' => 3128557,
            'numeroCarteira' => 17,
            'numeroVariacaoCarteira' => 35,
            'codigoModalidade' => 1,
            'dataEmissao' => '09.02.2021',
            'dataVencimento' => '12.02.2021',
            'valorOriginal' => 123.50,
            'valorAbatimento' => 0,
            'quantidadeDiasProtesto' => 0,
            'indicadorNumeroDiasLimiteRecebimento' => 'N',
            'numeroDiasLimiteRecebimento' => 0,
            'codigoAceite' => 'A',
            'codigoTipoTitulo' => 4,
            'descricaoTipoTitulo' => 'DS',
            'indicadorPermissaoRecebimentoParcial' => 'N',
            'numeroTituloBeneficiario' => '000101',
            'textoCampoUtilizacaoBeneficiario' => 'TESTE',
            'codigoTipoContaCaucao' => 0,
            'numeroTituloCliente' => '00031285579999990005',
            'textoMensagemBloquetoOcorrencia' => 'TESTE',
            'pagador' => array(
                'tipoRegistro' => 1,
                'numeroRegistro' => 71128590182,
                'nome' => 'Teste',
                'endereco' => 'Endereco',
                'cep' => 70675727,
                'cidade' => 'Sao Oaulo',
                'bairro' => 'Centro',
                'uf' => 'SP',
                'telefone' => '999939669'
            ),
            'email' => 'cliente@email.com'
        );

        /* Converte array em json */
        $body = json_encode($body);

        dd($body);

        try {
            $guzzle = new Client([
                                     'headers' => [
                                         'Authorization' => 'Bearer ' . $this->token(),
                                         'Content-Type' => 'application/json',
                                     ],
                                     'verify' => false
                                 ]);


            /* Requisição */
            $response = $guzzle->request('POST', 'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key'),
                                         [
                                             'body' => $body
                                         ]
            );

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Conveter o JSON em array associativo PHP */
            $boleto = json_decode($contents);

            dd($boleto);

        } catch (ClientException $e) {
            echo $e->getMessage();
        }
    }

    public function listar(){
        try {
            $guzzle = new Client([
                                     'headers' => [
                                         'Authorization' => 'Bearer ' . $this->token(),
                                         'Content-Type' => 'application/json',
                                     ],
                                     'verify' => false
                                 ]);

            /* Requisição */
            $response = $guzzle->request('GET', 'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key') .
                                              '&agenciaBeneficiario=' . '452' .
                                              '&contaBeneficiario=' . '123873' .
                                              '&indicadorSituacao=' . 'B' .
                                              '&indice=' . '300' .
                                              '&codigoEstadoTituloCobranca=' . '7' .
                                              '&dataInicioMovimento=' . '01.01.2021' .
                                              '&dataFimMovimento=' . '09.02.2021'
            );

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converter o JSON em array associativo do PHP */
            $boletos = json_decode($contents);

            dd($boletos);

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }

    }

    public function  consultar(){
        $id = '00031285579999990005';
        try {
            $guzzle = new Client([
                                     'headers' => [
                                         'Authorization' => 'Bearer ' . $this->token(),
                                         'Content-Type' => 'application/json',
                                     ],
                                     'verify' => false
                                 ]);

            /* Requisição */
            $response = $guzzle->request('GET', 'https://api.hm.bb.com.br/cobrancas/v1/boletos/'.
                                              $id .
                                              '?gw-dev-app-key=' . config('apiCobranca.gw_dev_app_key') .
                                              '&numeroConvenio=' .'3128557'
            );

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converter o JSON em array associativo do PHP */
            $boleto = json_decode($contents);

            dd($boleto);

        } catch (ClientException $e) {
            echo $e->getMessage();
        }
    }

    public function baixar(){

        $id = '00031285579999990003';

        try {
            $guzzle = new Client([
                                     'headers' => [
                                         'Authorization' => 'Bearer ' . $this->token(),
                                         'Content-Type' => 'application/json',
                                     ],
                                     'verify' => false
                                 ]);

            /* Requisição */
            $response = $guzzle->request('POST', 'https://api.hm.bb.com.br/cobrancas/v1/boletos/'.
                                               $id . '/baixar?gw-dev-app-key=' . config('apiCobranca.gw_dev_app_key'),
                                         [
                                             'body' => json_encode([
                                                                       'numeroConvenio' => 3128557
                                                                   ])
                                         ]
            );

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converter o JSON em array associativo do PHP */
            $boleto = json_decode($contents);

            dd($boleto);

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function atualizar(){

        $id = '00031285579999990005';

        /* Atributos que serão alterados */
        $dados = array(
            'numeroConvenio' => 3128557,
            'indicadorNovaDataVencimento' => 'S',
            'alteracaoData' => array(
                'novaDataVencimento' => '15.02.2021'
            )
        );

        /* Converte array em json */
        $dados = json_encode($dados);


        try {
            $guzzle = new Client([
                                     'headers' => [
                                         'Authorization' => 'Bearer ' . $this->token(),
                                         'Content-Type' => 'application/json'
                                     ],
                                     'verify' => false,

                                 ]);

            /* Requisição */
            $response = $guzzle->request('PATCH', 'https://api.hm.bb.com.br/cobrancas/v1/boletos/'.$id.'?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key'),
                                         [
                                             'body' => $dados
                                         ]
            );

            /* Recuperar o corpo da resposta da requisição */
            $body = $response->getBody();

            /* Acessar as dados da resposta - JSON */
            $contents = $body->getContents();

            /* Converter o JSON em array associativo do PHP */
            $boleto = json_decode($contents);

            dd($boleto);

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }
}
