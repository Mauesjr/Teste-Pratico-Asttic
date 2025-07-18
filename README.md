# Sistema PPC - Plano Pedagógico de Curso

Este projeto é um **protótipo de sistema web** desenvolvido como parte do processo seletivo para estágio na **Assessoria Técnica de Tecnologia da Informação e Comunicação (ASTTIC/PROEG)** da Universidade Federal do Pará.

## Objetivo

Criar um sistema web que permita:

###  **Submissão de propostas de cursos** por unidades acadêmicas, contendo:
  - Nome do curso
  - Carga horária total
  - Quantidade de semestres
  - Justificativa da criação do curso
  - Impacto social
  - Grade curricular completa, com disciplinas distribuídas por semestre

###  **Avaliação da proposta** por um servidor (avaliador), com possibilidade de:
  - Inserir comentários e recomendações
  - Retornar a proposta à unidade acadêmica
  - Encaminhar à Câmara de Ensino

###  **Decisão final da Câmara de Ensino**: aprovação ou reprovação da proposta

## Tecnologias utilizadas

- **Laravel** (PHP) – Backend e API
- **Vue.js** – Frontend
- **MySQL** – Banco de dados
- **Git/GitHub** – Controle de versão


## 🗃️ Estrutura do Banco de Dados

O sistema utiliza um banco de dados relacional (MySQL). A seguir, a descrição de cada entidade:

### 🔹 usuarios

Armazena os dados de todas as pessoas que usam o sistema.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador único |
| nome | VARCHAR(255) | Nome do usuário |
| email | VARCHAR(255) | Email único |
| senha | VARCHAR(255) | Senha criptografada |
| tipo | ENUM | Papel: `submissor`, `avaliador`, `camara` |
| criado_em | DATETIME | Data de criação |

Relacionamentos:
- 1:N com propostas_curso → como autor, avaliador ou decisor final

### 🔹 propostas_curso

Representa uma proposta de criação de curso.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador |
| nome | VARCHAR(255) | Nome do curso |
| carga_horaria_total | INT | Carga horária total |
| quantidade_semestres | INT | Quantidade de semestres |
| justificativa | TEXT | Justificativa do curso |
| impacto_social | TEXT | Impacto social |
| autor_id | INT (FK) | Usuário que criou a proposta |
| avaliador_id | INT (FK) | Usuário que avaliou |
| decisor_final_id | INT (FK) | Usuário que decidiu |

Relacionamentos:
- N:1 com usuarios
- 1:N com disciplinas
- 1:N com historico_status_proposta

### 🔹 disciplinas

Disciplinas da grade curricular da proposta.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador |
| curso_id | INT (FK) | Proposta à qual pertence |
| nome | VARCHAR(255) | Nome da disciplina |
| carga_horaria | INT | Carga horária |
| semestre | INT | Semestre ofertado |

### 🔹 historico_status_proposta

Histórico de status de uma proposta.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador |
| proposta_id | INT (FK) | Proposta relacionada |
| status | ENUM | `submetida`, `em_avaliacao`, etc. |
| data_status | DATETIME | Data do status |
| observacao | TEXT | Observação ou justificativa |

---

### 🔗 Relacionamentos Resumidos

- `usuarios` → `propostas_curso` (via `autor_id`, `avaliador_id`, `decisor_final_id`)
- `propostas_curso` → `disciplinas`
- `propostas_curso` → `historico_status_proposta`


## 💻 Como rodar o projeto em outro ambiente

Para rodar o projeto em outro computador com Laravel e MySQL:

1. **Clonar o repositório**
   ```bash
   git clone https://github.com/Mauesjr/Teste-Pratico-Asttic.git
   cd sistema_ppc
   ```

2. **Instalar as dependências do Laravel**
   ```bash
   composer install
   ```

3. **Copiar e configurar o arquivo `.env`**
   ```bash
   cp .env.example .env
   ```
   Em seguida, edite as variáveis do banco no `.env`:
   ```ini
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Gerar a chave da aplicação**
   ```bash
   php artisan key:generate
   ```

5. **Executar as migrations**
   ```bash
   php artisan migrate
   ```

6. **Popular o banco com dados de exemplo**
   ```bash
   php artisan db:seed
   ```
7. **Inicie o Servidor Laravel**
  ```bash
  php artisan serve
  ```
---
Para Iniciar o Vue.js :
1. **Mudar para a pasta de aplicação**
   ```bash
   cd sistema_ppc_frontend
   npm install
   ```
2. **Rodar o servidor Vue**
  ```bash
   npm run dev
   ```
---

Com os dois servidores rodando, basta acessar o servidor do Vue para visualizar o sistema.

## 📘 Models Laravel

Cada tabela possui um model Eloquent responsável pelas regras e relacionamentos:

- **Usuario** → representa um usuário do sistema.
- **PropostaCurso** → representa uma proposta de curso, com ligação a usuários e disciplinas.
- **Disciplina** → representa uma disciplina vinculada à proposta.
- **HistoricoStatusProposta** → representa cada atualização de status de uma proposta.

---
## 📌 Funcionalidades Implementadas

Este sistema gerencia o processo de criação e avaliação de propostas de cursos de graduação. As principais funcionalidades são:

### 📝 Submissão de Propostas
Usuários com o papel de `submissor` podem:
- Criar propostas de cursos contendo:
  - Nome do curso
  - Carga horária total
  - Número de semestres
  - Justificativa
  - Impacto social
  - Disciplinas organizadas por semestre

### 🔍 Avaliação Técnica
Usuários com o papel de `avaliador` podem:
- Acessar propostas submetidas
- Adicionar comentários técnicos (`comentario_avaliador`)
- Definir uma ação:
  - `retornar` (necessita alterações)
  - `encaminhar` (seguir para decisão)

### ✅ Decisão Final
Usuários com o papel de `decisor` podem:
- Acessar propostas encaminhadas para decisão
- Adicionar comentários finais (`comentario_decisor`)
- Definir uma decisão:
  - `aprovar` (proposta aprovada)
  - `reprovar` (proposta rejeitada)

### 🔐 Controle de Acesso por Papéis
- Cada tipo de usuário (`submissor`, `avaliador`, `decisor`) tem acesso apenas às funcionalidades específicas do seu papel.
- Middleware personalizado garante essa separação de permissões.

### 💾 Armazenamento e Persistência
- Informações como status da proposta, autor, avaliador, decisor e comentários são devidamente persistidas no banco de dados.

# 🔐 Credenciais de Acesso

Use as credenciais abaixo para acessar o sistema com diferentes papéis de usuário.  
**Todos os usuários utilizam a mesma senha: `senha123`.**

| Tipo de Usuário | E-mail                     | Senha     |
|-----------------|----------------------------|-----------|
| Submissor       | submissor1@exemplo.com     | senha123  |
| Avaliador       | avaliador1@exemplo.com     | senha123  |
| Decisor         | decisor1@exemplo.com       | senha123  |

---

# 🌐 Rotas por Tipo de Usuário

A seguir, estão listadas as rotas disponíveis na API para cada tipo de usuário, considerando o controle de acesso via **token (Bearer Token)**.

---

## 🔵 Submissor

| Verbo | Rota                                | Descrição                          |
|-------|-------------------------------------|------------------------------------|
| POST  | `/api/login`                        | Login no sistema                   |
| POST  | `/api/propostas`                    | Submeter uma nova proposta         |
| GET   | `/api/minhas-propostas`             | Listar propostas do usuário        |
| GET   | `/api/propostas/{id}/corrigir`      | Ver formulário de correção         |
| PUT   | `/api/propostas/{id}/corrigir`      | Submeter correção de proposta      |

---

## 🟠 Avaliador

| Verbo | Rota                                | Descrição                                 |
|-------|-------------------------------------|-------------------------------------------|
| GET   | `/api/propostas-para-avaliar`       | Listar propostas disponíveis para avaliação |
| PUT   | `/api/propostas/{id}/avaliar`       | Avaliar proposta (retornar ou encaminhar) |

---

## 🔴 Decisor

| Verbo | Rota                                | Descrição                               |
|-------|-------------------------------------|-----------------------------------------|
| GET   | `/api/propostas-para-decisao`       | Listar todas as propostas para decisão  |
| PUT   | `/api/propostas/{id}/decidir`       | Aprovar ou Reprovar proposta            |


## 📌 Observação

Este repositório faz parte da minha participação no processo seletivo de **julho de 2025** para estágio na **ASTTIC/PROEG**.



---

👨‍💻 Desenvolvido por: Marco Antônio Maués
