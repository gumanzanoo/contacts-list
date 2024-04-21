## Sobre o projeto
Trata-se de uma lista de contatos pronta para ser integrada a uma aplicação cliente. Possuí integração com API do ViaCEP para recuperação de endereços, 
bem como também salva latitude e longitude para utilização para plotagem de localização utilizando API's do Google Maps.

## Tecnologias
- Laravel 11
- SQLite
- PHPUnit

## Como rodar
Antes de tudo, é necessário ter instalado a versão 8.2^ do PHP.

Clone o projeto, em seguida, na pasta onde estiver o projeto rode os comandos:
 - composer install
 - php artisan migrate
 - php artisan serve

Copie o arquivo .env.example e renomeie para .env.
Para utilizar um cliente SMTP, use o serviço de sua preferência e insira as respectivas credenciais no .env.

Em seguida, você pode utilizar o arquivo postman-resources.json para navegar entre as API's e acessar a documentação da coleção.

## Testes
Você pode rodar os testes usando o comando: php artisan test
