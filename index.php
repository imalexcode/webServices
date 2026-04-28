<?php
header("Content-Type: application/json; charset=UTF-8");

$metodo = $_SERVER["REQUEST_METHOD"];
$caminho = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$alunos = [
    1 => ["id" => 1, "nome" => "Ana", "curso" => "Cloud Computing"],
    2 => ["id" => 2, "nome" => "Bruno", "curso" => "Redes"],
    3 => ["id" => 3, "nome" => "Carla", "curso" => "Segurança"]
];

if ($caminho == "/" && $metodo == "GET") {
    echo json_encode([
        "mensagem" => "Webservice PHP rodando no Kali Linux",
        "disciplina" => "Cloud Computing"
    ]);
    exit;
}

if ($caminho == "/status" && $metodo == "GET") {
    echo json_encode([
        "status" => "online",
        "servico" => "API PHP",
        "porta" => 8000
    ]);
    exit;
}

if (preg_match("#^/aluno/([0-9]+)$#", $caminho, $matches) && $metodo == "GET") {
    $id = intval($matches[1]);

    if (isset($alunos[$id])) {
        echo json_encode($alunos[$id]);
    } else {
        http_response_code(404);
        echo json_encode(["erro" => "Aluno não encontrado"]);
    }
    exit;
}

if ($caminho == "/media" && $metodo == "POST") {
    $entrada = file_get_contents("php://input");
    $dados = json_decode($entrada, true);

    if (!$dados || !isset($dados["nota1"]) || !isset($dados["nota2"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Envie nota1 e nota2 em JSON"]);
        exit;
    }

    $media = ($dados["nota1"] + $dados["nota2"]) / 2;
    $situacao = $media >= 6 ? "Aprovado" : "Reprovado";

    echo json_encode([
        "nota1" => $dados["nota1"],
        "nota2" => $dados["nota2"],
        "media" => $media,
        "situacao" => $situacao
    ]);
    exit;
}

if ($caminho == "/login" && $metodo == "POST") {
    $entrada = file_get_contents("php://input");
    $dados = json_decode($entrada, true);

    if (!$dados) {
        http_response_code(400);
        echo json_encode(["erro" => "JSON inválido"]);
        exit;
    }

    if ($dados["usuario"] == "admin" && $dados["senha"] == "1234") {
        echo json_encode(["mensagem" => "Login realizado com sucesso"]);
    } else {
        http_response_code(401);
        echo json_encode(["erro" => "Usuário ou senha inválidos"]);
    }
    exit;
}

http_response_code(404);
echo json_encode(["erro" => "Endpoint não encontrado"]);
?>
