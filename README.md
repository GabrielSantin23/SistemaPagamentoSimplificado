Sistema de Pagamento Simplificado

Este projeto é uma aplicação web desenvolvida com Laravel, que simula um sistema simplificado de pagamentos.

Tecnologias Utilizadas

- PHP
- Laravel
- Composer
- MySQL
- Tailwind CSS

Pré-requisitos

Antes de iniciar, certifique-se de ter instalado em sua máquina:

- XAMPP
- COMPOSER
- NODE.JS
- MYSQL

Instalação

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/GabrielSantin23/SistemaPagamentoSimplificado.git
   cd SistemaPagamentoSimplificado
   ```

-- Abrir a pasta do projeto clonado --

2. **Instale as dependências do PHP:**

   ```bash
   composer install
   ```

3. **Instale as dependências do Node.js:**

   ```bash
   npm install
   ```

4. **Copie o arquivo de ambiente e configure as variáveis:**

   ```bash
   copy .env.example .env
   ```

   Abra o arquivo `.env` e configure as seguintes variáveis conforme seu ambiente:

   ```env
   APP_NAME=SistemaPagamentoSimplificado
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pagamento_simplificado
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

5. **Gere a chave da aplicação:**

   ```bash
   php artisan key:generate
   ```

6. **Execute as migrações do banco de dados:**

   ```bash
   php artisan migrate
   ```

7. **Execute as seeds do banco de dados:**

   ```bash
   php artisan migrate --seed
   ```

8. **Compile os assets front-end:**

   ```bash
   npm run dev
   ```

9. **Inicie o servidor de desenvolvimento:**

   ```bash
   php artisan serve
   ```

   A aplicação estará disponível em `http://localhost:8000`.

usuário admin: admin@example.com
senha admin: password
