# Smart Finance

Projeto base Laravel + Vue.js com Docker e PostgreSQL.

## Como rodar

1. Copie o arquivo `.env.example` para `.env` e ajuste as variáveis de ambiente se necessário.
2. Execute:

```sh
docker-compose up --build
```

- Laravel: http://localhost:8000
- Vue.js: http://localhost:5173

## Estrutura
- Backend: Laravel (PHP)
- Frontend: Vue.js (Vite)
- Banco de dados: PostgreSQL
- Docker para orquestração

## Padrões
- Singleton para serviços
- Separação em services, controllers, interfaces
- Migrations para banco de dados

## Próximos passos
- Implementar tela de boas-vindas em Vue
- Criar controller inicial no Laravel
- Adicionar migrations e interfaces
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
