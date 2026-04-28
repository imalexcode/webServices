from flask import Flask, jsonify, request

app = Flask(__name__)

alunos = {
    1: {"id": 1, "nome": "Ana", "curso": "Cloud Computing"},
    2: {"id": 2, "nome": "Bruno", "curso": "Segurança da Informação"},
    3: {"id": 3, "nome": "Carla", "curso": "Redes de Computadores"}
}

@app.route("/", methods=["GET"])
def home():
    return jsonify({
        "mensagem": "Webservice em execução no Kali Linux",
        "disciplina": "Cloud Computing"
    })

@app.route("/status", methods=["GET"])
def status():
    return jsonify({
        "status": "online",
        "porta": 5000,
        "servico": "API Flask"
    })

@app.route("/aluno/<int:id>", methods=["GET"])
def buscar_aluno(id):
    if id in alunos:
        return jsonify(alunos[id])
    return jsonify({"erro": "Aluno não encontrado"}), 404

@app.route("/media", methods=["POST"])
def calcular_media():
    dados = request.get_json()

    if not dados:
        return jsonify({"erro": "Nenhum JSON enviado"}), 400

    if "nota1" not in dados or "nota2" not in dados:
        return jsonify({"erro": "Envie nota1 e nota2"}), 400

    nota1 = dados["nota1"]
    nota2 = dados["nota2"]
    media = (nota1 + nota2) / 2

    situacao = "Aprovado" if media >= 6 else "Reprovado"

    return jsonify({
        "nota1": nota1,
        "nota2": nota2,
        "media": media,
        "situacao": situacao
    })

@app.route("/login", methods=["POST"])
def login():
    dados = request.get_json()

    if not dados:
        return jsonify({"erro": "Nenhum JSON enviado"}), 400

    usuario = dados.get("usuario")
    senha = dados.get("senha")

    if usuario == "admin" and senha == "1234":
        return jsonify({"mensagem": "Login realizado com sucesso"})
    
    return jsonify({"erro": "Usuário ou senha inválidos"}), 401

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
