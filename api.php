<?php
//CONFIGURAÇÕES INCIAIS
//Define a resposta da Api, neste caso em JSON
header("Content-Type: application/json; charset=UTF-8");
//Permite o acesso de qualquer origem, ou seja, qualquer site pode acessar a Api
header("Access-Control-Allow-Origin: *");
//Permite os métodos GET, POST, PUT e DELETE, que são os métodos que a Api vai aceitar
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require "config.php";

$rota = $_GET['rota'] ?? "";
$metodo = $_SERVER['REQUEST_METHOD']; 

//necessário para utilizarmos o PUT
$corpo = json_decode(file_get_contents("php://input"), true)?? [];

function teste() {
   echo json_encode(["mensagem" => "Back-end da transportadora respondendo!"]);
}

//CRUD - Cliente
function createCliente($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO CLIENTE (nome, endereco, telefone, email)
        VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$dados['nome'], $dados['endereco'], $dados['telefone'], $dados['email']]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Cliente criado com sucesso", "id" => $con->lastInsertId()]);
}

//READ
function readCliente($con){
    $stmt = $con->query("SELECT * FROM CLIENTE");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
//UPDATE
function updateCliente($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE CLIENTE SET nome = ?, endereco = ?, telefone = ?, email = ? WHERE idCliente = ?"
    );
    $stmt->execute([$dados['nome'], $dados['endereco'], $dados['telefone'], $dados['email'], $id]);
    echo json_encode(["mensagem" => "Cliente atualizado com sucesso"]);
}
//DELETE
function deleteCliente($con, $id){
    $stmt = $con->prepare("DELETE FROM CLIENTE WHERE idCliente = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Cliente removido com sucesso"]);
}

function createFuncionario($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO FUNCIONARIO (nome, cargo, salario)
        VALUES (?, ?, ?)"
    );
    $stmt->execute([$dados['nome'], $dados['cargo'], $dados['salario']]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Funcionário criado com sucesso", "id" => $con->lastInsertId()]);
}

function readFuncionario($con){
    $stmt = $con->query("SELECT * FROM FUNCIONARIO");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function updateFuncionario($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE FUNCIONARIO SET nome = ?, cargo = ?, salario = ? WHERE idFuncionario = ?"
    );
    $stmt->execute([$dados['nome'], $dados['cargo'], $dados['salario'], $id]);
    echo json_encode(["mensagem" => "Funcionário atualizado com sucesso"]);
}

function deleteFuncionario($con, $id){
    $stmt = $con->prepare("DELETE FROM FUNCIONARIO WHERE idFuncionario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Funcionário removido com sucesso"]);
}

function createVeiculo($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO VEICULO (modelo, placa, capacidade)
        VALUES (?, ?, ?)"
    );
    $stmt->execute([$dados['modelo'], $dados['placa'], $dados['capacidade']]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Veículo criado com sucesso", "id" => $con->lastInsertId()]);
}

function readVeiculo($con){
    $stmt = $con->query("SELECT * FROM VEICULO");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}


function updateVeiculo($con, $placa, $dados) {
    $stmt = $con->prepare(
        "UPDATE veiculo
        SET modelo = ?, capacidade = ?
        WHERE placa = ?"
    );

    $stmt->execute([
        $dados['modelo'],
        $dados['capacidade'],
        $placa
    ]);

    echo json_encode([
        "mensagem" => "Veículo atualizado com sucesso"
    ]);
}


function deleteVeiculo($con, $placa){
    $stmt = $con->prepare("DELETE FROM VEICULO WHERE placa = ?");
    $stmt->execute([$placa]);
    echo json_encode(["mensagem" => "Veículo removido com sucesso"]);
}


// ROTAS
if ($rota == "cliente"){
    switch ($metodo) {
        case "GET":
            readCliente($con);
            break;
        case "POST":
            createCliente($con, $corpo);
            break;
        case "PUT":
            updateCliente($con, $_GET['id'], $corpo);
            break;
        case "DELETE":
             deleteCliente($con, $_GET['id']);
            break;
        default:
          teste();
    }
   }else if ($rota === "funcionario") {
    switch ($metodo) {
        case "GET":
            readFuncionario($con);
            break;
        case "POST":
            createFuncionario($con, $corpo);
            break;
        case "PUT":
            updateFuncionario($con, $_GET['id'], $corpo);
            break;
        case "DELETE":
             deleteFuncionario($con, $_GET['id']);
            break;
        default:
          teste();
    }
   }else if ($rota === "veiculo") {
    switch ($metodo) {
        case "GET":
            readVeiculo($con);
            break;
        case "POST":
            createVeiculo($con, $corpo);
            break;
        case "PUT":
            updateVeiculo($con, $_GET['placa'], $corpo);
            break;
        case "DELETE":
             deleteVeiculo($con, $_GET['placa']);
            break;
        default:
          teste();
    }
}