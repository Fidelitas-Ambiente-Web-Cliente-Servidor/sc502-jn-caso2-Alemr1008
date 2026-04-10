<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="container mt-5">

    <h2>Login</h2>

    <form id="formLogin" method="POST" action="javascript:void(0);">
        <input class="form-control mb-2" name="username" id="username" placeholder="Usuario">

        <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Contraseña">

        <button type="submit" class="btn btn-primary">Ingresar</button>
        <a href="index.php?page=registro" class="btn btn-secondary">Registrarse</a>
    </form>

    <!--  IMPORTANTE: JS AL FINAL -->
    <script src="/sc502-jn-caso2-Alemr1008/public/js/auth.js"></script>

</body>

</html>