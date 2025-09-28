## 📌 Descrição

O **Pix Withdraw Service** é uma API de conta digital que permite realizar saques via PIX, imediatos ou agendados, garantindo consistência de saldo, auditabilidade de transações e extensibilidade para novos métodos de saque no futuro.

O projeto foi desenvolvido em **PHP Hyperf 3**, com suporte a **MySQL 8** e **Mailhog**.

---

## 🛠️ Tecnologias

- **PHP Hyperf 3** → framework de alta performance baseado em corrotinas (Swoole).
- **MySQL 8** → banco de dados relacional, utilizado com transações e lock para consistência de saldo.
- **Mailhog** → serviço de captura de e-mails para testes de notificação.
- **Docker Compose** → orquestração de serviços para desenvolvimento.

---

## 📂 Estrutura de Banco de Dados

- `account` → informações da conta (`id`, `name`, `balance`).
- `account_withdraw` → informações genéricas do saque (`id`, `account_id`, `amount`, `status`, `error_reason`, etc).
- `account_withdraw_pix` → detalhes específicos de saques via PIX (`type`, `key`).

Esse design facilita a extensão para novos métodos de saque no futuro (TED, boleto, etc). Basta criar tabelas filhas (`account_withdraw_ted`, `account_withdraw_boleto`) sem alterar a tabela principal.

---

## 🔄 Fluxos Principais

### 💵 Saque Imediato

- Deduz o saldo da conta com transação e lock (`SELECT ... FOR UPDATE`), garantindo que o saldo nunca fique negativo.
- Registra a operação em `account_withdraw` e `account_withdraw_pix`.
- Envia notificação por e-mail (capturada pelo Mailhog).

### ⏰ Saque Agendado

- Requisições com campo `schedule` futuro criam um registro agendado.
- Um cronjob do Hyperf verifica periodicamente os saques pendentes.
- O processamento foi implementado de forma assíncrona, permitindo tratar muitos registros simultaneamente sem travar o servidor.

> 📌 **Em ambiente de produção, o ideal seria separar esse processamento em serviços independentes (workers dedicados ou fila de mensagens).**

**Justificativa técnica:**

- Maior resiliência (falha no processamento de agendados não impacta a API).
- Melhor escalabilidade horizontal (pode escalar workers separadamente da API).
- Facilita observabilidade e métricas por serviço.

---

## 📜 Endpoints

### `POST /account/{accountId}/balance/withdraw`

#### Request
```json
{
	"method": "PIX",
	"pix": {
		"type": "email",
		"key": "fulano@email.com"
	},
	"amount": 150.75,
	"schedule": null
}
```

#### Response – Sucesso (201 Created)
```json
{
	"withdraw_id": "uuid-gerado",
	"amount": "150.75",
	"schedule": "27/09/2025 10:10"
}
```

#### Response – Erro de domínio (400 Bad Request)
```json
{
	"error": {
		"code": "INSUFFICIENT_FUNDS",
		"message": "Saldo insuficiente para realizar o saque"
	}
}
```

#### Response – Erro de validação (422 Unprocessable Entity)
```json
{
	"error": {
		"code": "VALIDATION_ERROR",
		"message": "O campo 'amount' deve ser maior que zero"
	}
}
```

#### Response – Erro interno (500 Internal Server Error)
```json
{
	"error": {
		"code": "INTERNAL_ERROR",
		"message": "Ocorreu um erro inesperado, tente novamente mais tarde"
	}
}
```

---

## 🔒 Segurança

- **Saldo nunca negativo** → validado no domínio, garantido via transação com lock e CHECK no banco.
- **Mensagens de erro padronizadas** → sem exposição de stacktrace ou informações sensíveis.
- **UUID v4 para IDs** → imprevisíveis, seguros para exposição na API.
- **Princípio do menor privilégio** → endpoints retornam apenas informações necessárias (ex.: `withdraw_id`, `status`), sem expor saldo total da conta.

---

## 🚀 Como rodar

1. **Clonar repositório:**
	 ```bash
	 git clone https://github.com/seu-user/pix-withdraw-service.git
	 cd pix-withdraw-service
	 ```

2. **Subir ambiente com Docker Compose:**
	 ```bash
	 docker-compose up -d
	 ```

3. **Criar tabelas no MySQL (rodar migrations):**
	```bash
	docker compose exec app php bin/hyperf.php migrate
	```

4. **Popular o banco com dados de exemplo (rodar seeds):**
	```bash
	docker compose exec app php bin/hyperf.php db:seed
	```

	> ⚠️ Uma conta de teste é criada automaticamente pela seed:
	> - **ID:** `f0e570b1-a3bb-499a-bcdf-2df0b66a37d2`
	> - **Saldo:** 1000
	>
	> Use este ID para testar os endpoints de saque.

5. **Acessar serviço:**

	   - API: http://localhost:9501
	   - Mailhog: http://localhost:8025

	   **Exemplo de requisição de saque via curl:**
	   ```bash
	   curl --location 'http://localhost:9501/account/f0e570b1-a3bb-499a-bcdf-2df0b66a37d2/balance/withdraw' \
	   --header 'Content-Type: application/json' \
	   --data-raw '{
		   "method": "PIX",
		   "pix": {
			   "type": "email",
			   "key": "teste@email.com"
		   },
		   "amount": 10,
		   "schedule": null
	   }'
	   ```

---

## ✅ Decisões Técnicas

O projeto adota princípios do Domain-Driven Design (DDD) para garantir clareza, extensibilidade e robustez na modelagem do domínio:

- **Value Objects**:
	- Representam conceitos imutáveis e autocontidos.
	- Encapsulam validações, regras de formatação e precisão, evitando inconsistências e duplicidade de lógica.

- **Entidades**:
	- Modelam os principais elementos do domínio, cada um com identidade própria (ID).
	- Permitem rastrear o ciclo de vida de contas e saques, facilitando auditoria e evolução do sistema.

- **Serviços de Domínio:**
	- Centralizam regras e operações que não pertencem a uma única entidade, como o fluxo de saque e agendamento.
	- Facilitam a orquestração de múltiplos objetos do domínio de forma coesa.

- **Repositórios:**
	- Abstraem o acesso a dados, permitindo trocar a implementação (ex: banco relacional, cache, etc) sem afetar a lógica de negócio.
	- Expõem métodos orientados ao domínio, como buscar conta por ID ou registrar um novo saque.

- **Interfaces de Serviços para Dispatch Assíncrono de Saque:**
	- Permitem desacoplar o domínio do mecanismo de execução assíncrona (ex: fila, cronjob, worker dedicado).
	- Facilitam a substituição ou evolução do mecanismo de processamento sem alterar regras de negócio.

- **Interface para Envio de E-mail:**
	- Garante que o domínio não dependa de detalhes de infraestrutura (ex: SMTP, API externa).
	- Permite simular, trocar ou mockar o envio de e-mails em testes e diferentes ambientes.

- **Transações + Lock:**
	- Evitam race conditions e garantem consistência do saldo, mesmo em cenários de alta concorrência.

- **Cron + Processamento Assíncrono:**
	- Mantém saques agendados performáticos e escaláveis, sem bloquear a API principal.

