# Requisitos
- PHP 8.0
- Laravel requisitos
  - BCMath PHP Extension
  - Ctype PHP Extension
  - Fileinfo PHP extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extensio

# Instalação
- Clonar repositório
- Navegar até a pasta do projeto
- Executar
  - composer install
  - cp .env.example .env
  - php artisan key:generate
- Configurar
  - editar arquivo .env com dados do banco de dados
- Executar
  - php artisan migrate

# Execução
- Comando: php artisan serve
- Navegador: http://127.0.0.1:8000

# Testes
- Comando: ./vendor/bin/phpunit

# API
*GET: `/api/transactions`*
> Retorna as trasações agrupadas por loja e o saldo.
> 
*Response:*
```json
{
    "success":true,
    "stores":[
        {
            "store":{
                "id":1,
                "name":"BAR DO JO\u00c3O",
                "owner":"JO\u00c3O MACEDO",
                "created_at":"2022-02-09T05:45:21.000000Z",
                "updated_at":"2022-02-09T05:45:21.000000Z"
            },
            "transactions":{
                "0":{
                    "id":1,
                    "store_id":1,
                    "type":3,
                    "transaction_at":"2019-03-01T18:34:53.000000Z",
                    "card":"4753****3153",
                    "document":"09620676017",
                    "value":-142,
                    "created_at":"2022-02-09T05:45:21.000000Z",
                    "updated_at":"2022-02-09T05:45:21.000000Z",
                    "type_name":"Financiamento",
                    "store":{
                        "id":1,
                        "name":"BAR DO JO\u00c3O",
                        "owner":"JO\u00c3O MACEDO",
                        "created_at":"2022-02-09T05:45:21.000000Z",
                        "updated_at":"2022-02-09T05:45:21.000000Z"
                    }
                },
                ...
            },
            "sum":-102
        }
        ...
    ]
}
```

*POST: `/api/transactions/import`*
> Importa um arquivo CNAB com as transações financeira.

*Request:*
```json
{
    "file": file binary
}
```

*Response:*
```json
{
    "success": true,
    "message": "Arquivo CNAB importado com sucesso"
}
```
