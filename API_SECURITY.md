# Segurança da API - License Server

## 🔐 Autenticação por API Key

A API agora requer autenticação via API Key para maior segurança.

## 📋 Como Funciona

### 1. Gerar uma API Key

No servidor de licenças, execute:

```bash
php artisan api-key:generate "Nome da API Key"
```

Ou com data de expiração:

```bash
php artisan api-key:generate "API Key Produção" --expires="2025-12-31"
```

**⚠️ IMPORTANTE:** Copie a API key imediatamente! Ela só é exibida uma vez.

### 2. Configurar no ERP

No arquivo `.env` do projeto ERP, adicione:

```env
LICENSE_API_KEY=ls_sua_api_key_aqui
```

### 3. Usar na Requisição

A API key pode ser enviada de duas formas:

#### Opção 1: Header X-API-Key (Recomendado)
```http
POST /api/license/validate
X-API-Key: ls_sua_api_key_aqui
Content-Type: application/json

{
  "token": "...",
  "domain": "...",
  "device_id": "..."
}
```

#### Opção 2: Header Authorization
```http
POST /api/license/validate
Authorization: Bearer ls_sua_api_key_aqui
Content-Type: application/json

{
  "token": "...",
  "domain": "...",
  "device_id": "..."
}
```

## 🛡️ Proteções Implementadas

### 1. Autenticação Obrigatória
- Todas as requisições à API requerem uma API key válida
- API keys podem ser desativadas ou expiradas

### 2. Rate Limiting
- **60 requisições por minuto** por IP
- Protege contra ataques de força bruta

### 3. Validação de API Key
- API keys são armazenadas como hash (SHA-256)
- Verificação de expiração automática
- Rastreamento de último uso

## 📊 Gerenciamento de API Keys

### Listar API Keys
```bash
php artisan tinker
```

```php
\App\Models\ApiKey::all();
```

### Desativar uma API Key
```php
$apiKey = \App\Models\ApiKey::find(1);
$apiKey->update(['is_active' => false]);
```

### Verificar uso
```php
$apiKey = \App\Models\ApiKey::find(1);
echo $apiKey->last_used_at;
```

## 🔄 Migração

### Para ativar a autenticação:

1. **Rodar migration:**
```bash
php artisan migrate
```

2. **Gerar API key:**
```bash
php artisan api-key:generate "ERP Production"
```

3. **Configurar no ERP:**
```env
LICENSE_API_KEY=ls_gerada_aqui
```

4. **Testar:**
```bash
php artisan license:check
```

## ⚠️ Compatibilidade

### Modo de Compatibilidade (Opcional)

Se você quiser permitir acesso sem API key temporariamente (não recomendado para produção), você pode modificar o middleware `AuthenticateApiKey` para tornar a API key opcional.

**NÃO RECOMENDADO PARA PRODUÇÃO!**

## 🧪 Testando

### Teste Manual com cURL

```bash
curl -X POST http://localhost:8000/api/license/validate \
  -H "X-API-Key: ls_sua_api_key" \
  -H "Content-Type: application/json" \
  -d '{
    "token": "seu_token_licenca",
    "domain": "localhost",
    "device_id": "test_device"
  }'
```

### Teste sem API Key (deve falhar)

```bash
curl -X POST http://localhost:8000/api/license/validate \
  -H "Content-Type: application/json" \
  -d '{
    "token": "test",
    "domain": "localhost",
    "device_id": "test"
  }'
```

Resposta esperada:
```json
{
  "error": "API key não fornecida",
  "message": "Forneça uma API key válida no header X-API-Key ou Authorization"
}
```

## 📝 Estrutura da Tabela

```sql
api_keys
- id
- name (nome descritivo)
- key (token completo - apenas na criação)
- hash (hash SHA-256 para validação)
- is_active (ativo/inativo)
- last_used_at (último uso)
- expires_at (data de expiração)
- created_at
- updated_at
```

## 🔒 Boas Práticas

1. **Gere API keys separadas** para cada ambiente (dev, staging, production)
2. **Use expiração** para API keys temporárias
3. **Desative API keys** não utilizadas
4. **Monitore o uso** através de `last_used_at`
5. **Nunca compartilhe** API keys em repositórios públicos
6. **Rotacione API keys** periodicamente em produção

## 🚨 Troubleshooting

### Erro: "API key não fornecida"
- Verifique se `LICENSE_API_KEY` está configurado no `.env`
- Verifique se o header está sendo enviado corretamente

### Erro: "API key inválida"
- Verifique se a API key está correta
- Verifique se a API key está ativa: `is_active = true`
- Verifique se não expirou: `expires_at`

### Rate Limit Excedido
- Aguarde 1 minuto antes de tentar novamente
- Considere aumentar o limite se necessário (modificar middleware)

