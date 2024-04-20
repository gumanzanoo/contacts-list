<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de Senha</title>
</head>
<body>
<h1>Recuperação de Senha</h1>
<p>O link abaixo deverá redirecionar para uma página onde o usuário poderá redefinir sua senha.</p>
<a href="{{ 'http://127.0.0.1' }}">Redefinir Senha</a>
<p>O formulário da página deverá enviar uma requisição para:</p>
<pre>@PATCH /api/account-recover</pre>
<p>Com o seguinte payload:</p>
<pre>{"token": "{{ $token }}", "password": "", "password_confirmation": ""}</pre>
<p>Este link de redefinição de senha expirará em 60 minutos.</p>
</body>
</html>
