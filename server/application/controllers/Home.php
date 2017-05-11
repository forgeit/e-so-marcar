<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function count() {
        $quadras = $this->QuadraModel->countPorColuna();
        $jogos = $this->ReservaModel->countPorColuna();
        $jogadores = $this->UsuarioModel->countPorColuna();


        $array = array('data' =>
            array('dto' => array($quadras, $jogos, $jogadores)));

        print_r(json_encode($array));
    }

    public function newsletter() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $newsletter = json_decode($data);

        if (empty($newsletter->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        $response = array('exec' => $this->NewsletterModel->inserir($newsletter));

        $array = $this->gerarRetorno($response, $response ? "Obrigado! Seu e-mail foi armazenado em nossa lista." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

}
