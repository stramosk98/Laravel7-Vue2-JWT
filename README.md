# Laravel 7 + Vue 2 + MySQL - Sistema de Orders

Um projeto simples com autenticação usando Laravel 7, Vue 2 e MySQL, totalmente dockerizado.

## Características

- **Backend**: Laravel 7.30+ com autenticação API
- **Frontend**: Vue 2 com Vue Router
- **Banco de Dados**: MySQL 5.7
- **Containerização**: Docker e Docker Compose
- **Estilo**: Bootstrap 4

### Backend

- Utilizei `api_token` armazenado no banco de dados para autenticação stateless
- Sistema de controle de acesso baseado em roles e permissões
- Middleware customizado `CheckPermission` para verificação de permissões
- Estrutura: User → Role → Permissions (muitos-para-muitos)

- `OrderService` para que o controller foque em receber apenas requisições e retornar respostas
- Não utilizei Repository por não achar que era necessário

- `OrderRequest` centraliza regras de validação com mensagens de erro padronizadas, separando responsabilidades já que valida fora do controller

- Método `scopeFilter()` no model Order para filtrar

### Frontend

- Guards para proteção de rotas
- Adicionado token automaticamente em todas requisições
- tratado erros 401 (não autenticado) e 403 (sem permissão) globalmente com redirecionamento automático
- Persistência do token de autenticação entre sessões

### Infraestrutura

- Separação em 3 serviços: app (Laravel+Apache), db (MySQL), phpmyadmin

## Instalação

### 1. Clone o Projeto

```bash
git clone https://github.com/stramosk98/BoxLog-Test.git
cd BoxLog-Test
```

### 2. Configure o Ambiente

```bash
# Copie o arquivo de configuração
cp .env.example .env

# Edite o .env se necessário (as configurações padrão já funcionam)
```

### 3. Execute com Docker

```bash
# Construir e executar os containers
docker-compose up --build -d

# Verificar se os containers estão rodando
docker-compose ps
```

### 4. Configurar a Aplicação

```bash
# Aguarde o build terminar, depois configure a aplicação
docker exec laravel-simple-app php artisan key:generate

# Executar migrações
docker exec laravel-simple-app php artisan migrate

# Executar seeders (criar usuários de teste)
docker exec laravel-simple-app php artisan db:seed

# Compilar assets frontend
docker exec laravel-simple-app npm run dev
```

> **Nota**: As dependências PHP e JavaScript já são instaladas durante o build do Docker.

## Acesso

- **Aplicação**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **phpMyAdmin**: http://localhost:8081

## Usuários de Teste

O sistema vem com dois usuários pré-configurados:

- **Admin**: admin@exemplo.com / 123456
- **Usuário**: usuario@exemplo.com / 123456

## Comandos Úteis

```bash
# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f

# Acessar container
docker exec -it laravel-simple-app bash

# Recompilar assets
docker exec laravel-simple-app npm run dev

# Limpar cache
docker exec laravel-simple-app php artisan cache:clear
```

## Como Funciona a Autenticação

1. **Login**: Usuário envia credenciais para `/api/login`
2. **Token**: Sistema gera um token único para o usuário
3. **Armazenamento**: Token é salvo no localStorage do navegador
4. **Proteção**: Rotas protegidas verificam o token via middleware `auth:api`
5. **Logout**: Token é removido do banco e do localStorage

## Frontend (Vue 2)

- **LoginComponent**: Tela de login com validação
- **DashboardComponent**: Painel do usuário autenticado
- **Vue Router**: Navegação entre páginas
- **Axios**: Requisições HTTP para a API
- **Bootstrap 4**: Interface responsiva

## Desenvolvimento

### Assistir Mudanças nos Assets

```bash
docker exec laravel-simple-app npm run watch
```

### Executar Testes

```bash
docker exec laravel-simple-app php artisan test
```

## Solução de Problemas

### Erro 500 na API
```bash
docker exec laravel-simple-app tail -f storage/logs/laravel.log
```

### Assets não carregando
```bash
docker exec laravel-simple-app npm run dev
```

### Problemas com permissões
```bash
docker exec laravel-simple-app chown -R www-data:www-data storage bootstrap/cache
docker exec laravel-simple-app chmod -R 775 storage bootstrap/cache
```

