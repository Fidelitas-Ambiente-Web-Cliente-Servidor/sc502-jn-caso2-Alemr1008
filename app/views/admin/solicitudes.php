<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitudes pendientes</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    
</head>
<body>
    <nav>
        <div>
            <a href="index.php?page=talleres">Talleres</a>
            <a href="index.php?page=admin">Gestionar Solicitudes</a>
        </div>
        <div>
            <span>Admin: <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Administrador') ?></span>
            <button id="btnLogout" class="btn-logout">Cerrar sesión</button>
        </div>
    </nav>
    
    <main>
        <h2>Solicitudes pendientes de aprobación</h2>
        
        <div class="table-container">
            <table id="tabla-solicitudes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Solicitante</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudes-body">
                    <tr>
                        <td colspan="6" class="loader">Cargando solicitudes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <div id="mensaje"></div>

<script>
$(document).ready(function () {

    cargarSolicitudes();

    //  Cargar solicitudes
    function cargarSolicitudes() {
        $.get('index.php?page=obtenerSolicitudes', function (data) {

            let html = '';

            if (data.length === 0) {
                html = '<tr><td colspan="6">No hay solicitudes pendientes</td></tr>';
            } else {
                data.forEach(s => {
                    html += `
                        <tr>
                            <td>${s.id}</td>
                            <td>${s.nombre}</td>
                            <td>${s.username}</td>
                            <td>${s.usuario_id}</td>
                            <td>${s.fecha_solicitud}</td>
                            <td>
                                <button class="btn-aprobar" data-id="${s.id}">Aprobar</button>
                                <button class="btn-rechazar" data-id="${s.id}">Rechazar</button>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#solicitudes-body').html(html);

        }, 'json');
    }

    //  Aprobar
    $(document).on('click', '.btn-aprobar', function () {

        if (!confirm('¿Aprobar esta solicitud?')) return;

        const id = $(this).data('id');

        $.post('index.php?page=aprobarSolicitud', { id_solicitud: id }, function (res) {

            mostrarMensaje(res.success, res.message || res.error);
            cargarSolicitudes();

        }, 'json');
    });

    //  Rechazar
    $(document).on('click', '.btn-rechazar', function () {

        if (!confirm('¿Rechazar esta solicitud?')) return;

        const id = $(this).data('id');

        $.post('index.php?page=rechazarSolicitud', { id_solicitud: id }, function (res) {

            mostrarMensaje(res.success, res.message || res.error);
            cargarSolicitudes();

        }, 'json');
    });

    //  Mostrar mensajes
    function mostrarMensaje(success, mensaje) {
        $('#mensaje')
            .text(mensaje)
            .css({
                color: success ? 'green' : 'red',
                fontWeight: 'bold'
            })
            .fadeIn()
            .delay(2000)
            .fadeOut();
    }

    //  Logout
    $('#btnLogout').click(function () {
        $.post('index.php?page=logout', function () {
            window.location.href = 'index.php?page=login';
        });
    });

});
</script>
</body>
</html>