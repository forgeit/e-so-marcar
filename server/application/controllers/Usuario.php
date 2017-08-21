<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Controller {

    public function buscar() {
        $array = array('data' =>
            array('dto' =>
                $this->UsuarioModel->buscarPorIdNativo($this->jwtController->id)));

        print_r(json_encode($array));
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->UsuarioModel->buscarTodosAtivo()))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $this->validaDados($usuario);

        $usuario->data_cadastro = date('Y-m-d');
        $usuario->flag_email_confirmado = 0;
        unset($usuario->senha_denovo);

        $id = $this->UsuarioModel->inserirRetornaId($usuario);

        if ($id) {
            $hash = hash('md5', $usuario->email . 'cadastro-esomarcar');

            $this->email
                    ->from($this->config->item('smtp_user'))
                    ->to($usuario->email)
                    ->subject('Validação de Conta')
                    ->message('<p>Para ativar sua conta clique <a href="' . $this->config->item('base_url') . '/server/home/ativar/cadastro/' . $id . '/hash/' . $hash . '">aqui</a>.</p>')
                    ->send();
        }

        $array = $this->gerarRetorno(TRUE, "Sucesso ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($usuario) {
        if (empty($usuario->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        $emailUnico = $this->UsuarioModel->buscarPorColuna('email', $usuario->email);
        if ($emailUnico != null) {
            $this->gerarErro("E-mail já cadastrado.");
        }

        if (empty($usuario->senha)) {
            $this->gerarErro("Senha é obrigatório.");
        }

        if (empty($usuario->senha_denovo)) {
            $this->gerarErro("Repetir Senha é obrigatórios.");
        }

        if ($usuario->senha != $usuario->senha_denovo) {
            $this->gerarErro("As senhas são diferentes.");
        }
    }

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        if (empty($usuario->nome)) {
            $this->gerarErro("Nome é obrigatório.");
        }

        $usuarioBanco = $this->UsuarioModel->buscarPorId($this->jwtController->id);

        $usuarioBanco['nome'] = $usuario->nome;
        $usuarioBanco['telefone'] = $usuario->telefone;
        $usuarioBanco['biogradia'] = $usuario->biogradia;
        $usuarioBanco['endereco'] = $usuario->endereco;
        if (isset($usuario->cidade)) {
            $usuarioBanco['id_cidade'] = $usuario->cidade->id;
        } else {
            $usuarioBanco['id_cidade'] = NULL;
        }
        if (empty($usuario->data_nascimento)) {
            $usuarioBanco['data_nascimento'] = NULL;
        } else {
            $usuarioBanco['data_nascimento'] = $this->toDate($usuario->data_nascimento);
        }
        if (isset($usuario->imagem) && $usuario->imagem != $usuarioBanco['imagem']) {
            $usuarioBanco['imagem'] = $this->uploadArquivo($usuario->imagem, 'imagem');
        }

        $this->UsuarioModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Dados atualizados com sucesso.");
        print_r(json_encode($array));
    }

    public function alterarSenha() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $senhas = json_decode($data);

        if (empty($senhas->atual)) {
            $this->gerarErro("Senha atual é obrigatório.");
        }

        if (empty($senhas->nova)) {
            $this->gerarErro("Nova senha é obrigatório.");
        }

        if (empty($senhas->nova2)) {
            $this->gerarErro("Repetir nova senha é obrigatório.");
        }

        if ($senhas->nova2 != $senhas->nova) {
            $this->gerarErro("Novas senhas são diferentes.");
        }

        $usuarioBanco = $this->UsuarioModel->buscarPorId($this->jwtController->id);

        if ($usuarioBanco['senha'] != md5($senhas->atual)) {
            $this->gerarErro("Senha atual incorreta.");
        }

        $usuarioBanco['senha'] = md5($senhas->nova);

        $this->UsuarioModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Senha atualizada com sucesso.");
        print_r(json_encode($array));
    }

    public function desativarConta() {
        $senha = $this->security->xss_clean($this->input->raw_input_stream);

        if (empty($senha)) {
            $this->gerarErro("Senha é obrigatório.");
        }

        $usuarioBanco = $this->UsuarioModel->buscarPorId($this->jwtController->id);

        if ($usuarioBanco['senha'] != md5($senha)) {
            $this->gerarErro("Senha incorreta.");
        }

        $reservas = $this->ReservaModel->buscarEmAberto($this->jwtController->id);
        if ($reservas == null) {
            $this->gerarErro("Não é possível desativar a conta enquanto houverem reservas em aberto.");
        }

        $usuarioBanco['flag_ativo'] = 0;

        $this->UsuarioModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Conta desativada com sucesso.");
        print_r(json_encode($array));
    }

    public function buscarReservas() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->ReservaModel->buscarEmAberto($this->jwtController->id)))));
    }
    
    public function buscarReservasMensal() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->ReservaModel->buscarEmAbertoMensal($this->jwtController->id)))));
    }

    public function cancelarReserva() {
        $reserva = $this->ReservaModel->buscarParaCancelamento($this->uri->segment(3));

        if ($reserva['id_usuario'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode cancelar esta reserva.");
        }

        if ($reserva['data_hora_reserva'] < date('Y-m-d H:i:s')) {
            $this->gerarErro("Não pode ser cancelada. Reserva já utilizada.");
        }

        if ($reserva['data_possivel_cancelamento'] < date('Y-m-d H:i:s')) {
            $this->gerarErro("Não pode ser cancelada. Antecedência de " . $reserva['horas_antecedencia_cancelamento'] . " para cancelamentos.");
        }

        $this->ReservaModel->excluir($reserva['id']);
        $array = $this->gerarRetorno(TRUE, "Reserva cancelada com sucesso.");
        print_r(json_encode($array));
    }
    
    public function cancelarReservaMensal() {
        $horario = $this->HorarioModel->buscarPorId($this->uri->segment(3));

        if ($horario['id_usuario'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode cancelar esta reserva.");
        }
        
        $horario['id_usuario'] = NULL;

        $this->HorarioModel->atualizar($horario['id'], $horario);
        $array = $this->gerarRetorno(TRUE, "Reserva mensal cancelada com sucesso.");
        print_r(json_encode($array));
    }

}
