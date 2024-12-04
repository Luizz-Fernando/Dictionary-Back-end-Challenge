

# Fullstack Challenge 🏅 - Dictionary API

Uma API desenvolvida em Laravel 10 para gerenciamento de palavras do dicionário. A aplicação oferece suporte a autenticação de usuários, favoritos e histórico de palavras utilizando a Free Dictionary API.

---

## Tecnologias Utilizadas

- **Linguagem**: PHP
- **Framework**: Laravel 10
- **Bibliotecas**:
  - Sanctum
  - JWT-Auth
- **API Externa**: Free Dictionary API
- **Banco de Dados**: MySQL, PostgreSQL ou equivalente

---

## Instalação e Uso

### Pré-requisitos

Certifique-se de que sua máquina atenda aos seguintes requisitos:
- PHP 8.1 ou superior
- Composer
- Banco de dados (MySQL, PostgreSQL ou equivalente)
- Git

### Passos para Instalação

1. **Clone o Repositório**  
   Clone o repositório do projeto e acesse o diretório:
   ```bash
   git clone <url-do-repositorio>
   cd <nome-do-repositorio>
   
2. **Instale as dependências**
    ```bash
    composer install
    
3. **Instale as dependências**
    ```bash
    php artisan migrate
    
## Autenticação de Usuário

A aplicação utiliza **JWT-Auth** para gerenciar tokens de autenticação. Siga os passos abaixo para autenticar-se e utilizar os endpoints protegidos.

---

## Registro de Usuário

- **Endpoint**: `[POST] /api/auth/singup`
- **Descrição**: Registra um novo usuário.

### Parâmetros:
- `name` (string, obrigatório)
- `email` (string, obrigatório, formato email)
- `password` (string, obrigatório)

---

## Login de Usuário

- **Endpoint**: `[POST] /api/login`
- **Descrição**: Autentica um usuário existente.

### Parâmetros:
- `email` (string, obrigatório, formato email)
- `password` (string, obrigatório)

---

## Uso da API

Após autenticar-se, utilize o token recebido para acessar os endpoints protegidos. Adicione o token no cabeçalho `Authorization` como `Bearer {token}`.

### Exemplo de Cabeçalho:
```http
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOi...

