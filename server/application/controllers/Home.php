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

        if ($response) {
            $hash = 'AUIPhe908HAEfoh-80h240HA)F_EH_08';

            $this->email
                    ->from('É Só Marcar')
                    ->to('charles.a.goettert@gmail.com')
                    ->subject('Confirmação de e-mail')
                    ->message('<p>Para confirmar seu cadastro clique <a href="' . $this->config->item('base_url') . 'home/ativar/newsletter/' . $hash . '">aqui</a>.</p>')
                    ->send();
        }

        $array = $this->gerarRetorno($response, $response ? "Obrigado! Seu e-mail foi armazenado em nossa lista." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    public function ativar() {
        print_r($this->uri->segment(3));
        print_r($this->uri->segment(4));

        if ($this->uri->segment(3) == 'newsletter' && $this->uri->segment(4)) {
            print_r('Newsletter ativado!');
        }

        if ($this->uri->segment(3) == 'cadastro' && $this->uri->segment(4)) {
            print_r('Cadastro ativado!');
        }
    }

    public function email() {
        $subject = 'Ativação de Conta';
        $message = '<p>Para ativar sua conta clique <a href="hahahah">aqui</a>.</p>';

        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                <title>' . html_escape($subject) . '</title>
                <style type="text/css">
                    body {
                        font-family: Arial, Verdana, Helvetica, sans-serif;
                        font-size: 16px;
                    }
                </style>
            </head>
            <body>
            ' . $message . '
            </body>
            </html>';

        print_r($this->config->item('base_url'));

        // Also, for getting full html you may use the following internal method:
        $body = $this->email->full_html($subject, $message);

        $result = $this->email
                ->from('pogo01acc@gmail.com')
                ->to('charles.a.goettert@gmail.com')
                ->subject($subject)
                ->message($body)
                ->send();

        var_dump($result);
        echo '<br />';
        echo $this->email->print_debugger();
    }

}
