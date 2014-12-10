<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banco_ws
 *
 * This is an Banco_ws of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Amanda Cristina
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Banco_ws extends REST_Controller
{
    // Variavel que faz intereçõ com banco de dados
    private $_c;

	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
       // $this->methods['user_get']['limit']     = 9999999; //9999999 requests per hour per user/key
        //$this->methods['user_post']['limit']    = 9999999; //9999999 requests per hour per user/key
        //$this->methods['user_delete']['limit']  = 9999999; //9999999 requests per hour per user/key
    }
    function index(){
        $thi->parser->parse('banco');
    }
    function saldo_get()
    {
        $this->_c = new Conta();

        if(!$this->get('conta'))
        {
        	$this->response(array('error' => "Conta não informada"), 400);
            return false;
        }

        $_data['num_conta'] = $this->get('conta');
        // $user = $this->some_model->getSomething( $this->get('id') );
        // 
        
    	$conta = $this->_c->getById($_data['num_conta']);
		
    	$conta = $conta->stored;
    	$id_conta = $conta->id_cont;
        //echo "<pre>"; print_r($conta); echo "</pre>";die;
        if(!empty($id_conta))
        {

            $this->response($conta, 200); // 200 being the HTTP response code
        }

        else
        {
            //echo "<pre>"; print_r($_data); echo "</pre>";die;
           $conta = $this->_c->salvar($_data);
           $this->response($conta->stored, 200); // 200 being the HTTP response code
        }
    }
    

function sacar_get()
    {
         $this->_c = new Conta();

        if(!$this->get('conta') || !$this->get('valor') )
        {
            $this->response(array('error' => "Dados inválidos"), 400);
            return false;
        }

        $_data['num_conta'] = $this->get('conta');
        $_data['valor']     = $this->get('valor');
        
        $conta          = $this->_c->getById($_data['num_conta']);
        
        $conta          = $conta->stored;
        $id_conta       = $conta->id_cont;
        $valor_conta    = $conta->saldo;

        if(empty($id_conta)){
             $this->response(array('error' => "Conta não existe, utilize a opção saldo para criar uma conta. "), 400);
            return false;
        }

        if($valor_conta < $_data['valor']){
             $this->response(array('error' => "Saldo insuficiente para saque"), 400);
             return false;
        }
        $conta = $this->_c->sacar($_data['num_conta'], $_data['valor']);
        $this->response($conta->stored, 200); // 200 being the HTTP response code
    }



function depositar_get()
    {
         $this->_c = new Conta();

        if(!$this->get('valor') )
        {
            $this->response(array('error' => "Dados inválidos"), 400);
            return false;
        }

        $_data['num_conta'] = $this->get('conta');
        $_data['valor']     = $this->get('valor');
        
        $conta          = $this->_c->getById($_data['num_conta']);
        
        $conta          = $conta->stored;
        $id_conta       = $conta->id_cont;
        
        if(empty($id_conta)){
             $this->response(array('error' => "Conta não existe, utilize a opção saldo para criar uma conta. "), 400);
            return false;
        }
        $conta = $this->_c->depositar($_data['num_conta'], $_data['valor']);
        $this->response($conta->stored, 200); // 200 being the HTTP response code
    }

function transferir_get()
    {
         $this->_c = new Conta();

        if(!$this->get('conta') || !$this->get('valor') || !$this->get('origem'))
        {
            $this->response(array('error' => "Dados inválidos"), 400);
            return false;
        }

        $_data['num_conta'] = $this->get('conta');
        $_data['origem']    = $this->get('origem');
        $_data['valor']     = $this->get('valor');
        
        $conta              = $this->_c->getById($_data['num_conta']);
        $conta_origem       = $this->_c->getById($_data['origem']);
        
        $conta              = $conta->stored;
        $id_conta           = $conta->id_cont;
        $id_conta_origem    = $conta_origem->id_cont;
        $valor_conta        = $conta->saldo;
        if(empty($id_conta)){
             $this->response(array('error' => "Conta não existe, utilize a opção saldo para criar uma conta. "), 400);
            return false;
        }
        else if(empty($id_conta_origem)){
             $this->response(array('error' => "Conta Destino não existe, utilize a opção saldo para criar uma conta. "), 400);
            return false;
        }
        if($valor_conta < $_data['valor']){
             $this->response(array('error' => "Saldo insuficiente para transferencia"), 400);
             return false;
        }
        else{
            $conta = $this->_c->transferir($_data);
            $this->response($conta->stored, 200); // 200 being the HTTP response code
        }
    }





















    function user_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
		);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}