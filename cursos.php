<?php session_start(); ?>

<?php
if(!isset($_SESSION['op'])){
    include('includes/header_usuario.php');
} else{
    include('includes/header_inicio.php');
}
?>

<head><title>Cursos</title></head>

<link rel="stylesheet" href="estilos/index.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<?php
require_once 'includes/mysql/mysqlGetCursos.php';
require_once 'includes/mysql/mysqlGetCursosUsuario.php';
require_once 'includes/alert.php';
$data = consultarCursos();
$cursosUsuarios = getCursosUsuario();
$cursosArray = array();
foreach ($cursosUsuarios as $elemento){
    if(isset($elemento['idCurso'])){
        $cursosArray[] = $elemento["idCurso"];
    }
}
if(!isset($_SESSION['username'])){
    $_SESSION['not_signed_title'] = "Error";
    $_SESSION['not_signed'] = "Debe iniciar sesión.";
    header('Location: index.php');
} elseif (isset($_SESSION['unexpected_error'])){
    $unexpected_error_title = $_SESSION['unexpected_error_title'];
    $unexpected_error = $_SESSION['unexpected_error'];
    unset($_SESSION['unexpected_error_title']);
    unset($_SESSION['unexpected_error']);
    $alert = new alerta($unexpected_error_title, $unexpected_error);
    $alert->informar_error();
} elseif (isset($_SESSION['deleted_success'])){
    $deleted_success_title = $_SESSION['deleted_success_title'];
    $deleted_success = $_SESSION['deleted_success'];
    unset($_SESSION['deleted_success_title']);
    unset($_SESSION['deleted_success']);
    $alert = new alerta($deleted_success_title, $deleted_success);
    $alert->informar_error();
}
?>
<body class="d-flex flex-column min-vh-100">
<div class="container mt-5">
    <h1 class="text-center text-white">Catálogo de Cursos</h1>

    
    
    <div class="row">
        <?php
        // Imprime los datos en la tabla
        foreach ($data as $row) {
            echo "<div class=\"col-md-4\"><div class=\"card mb-3\">";
            echo "<img src=\"{$row['imagenCurso']}\" class=\"card-img-top img-fluid\" alt=\"Curso Python\">";
            echo "<div class=\"card-body\">";
            echo "<h5 class=\"card-title\">{$row['nombreCurso']}</h5>";
            echo "<p class=\"card-text\">{$row['descripcionCurso']}</p>";
            echo "<p class=\"card-text\">Duracion: {$row['duracionCurso']} horas</p>";
            echo "<p class=\"id-curso-{$row['id']} card-text\" style=\"display: none;\">{$row['id']}</p>";
            if(in_array($row['id'], $cursosArray)){
                echo "<button href=\"#\" class=\"inscribirse btn btn-primary disabled\" id=\"inscribirse{$row['id']}\">Inscribirse</button>";
            } else{
                echo "<button href=\"#\" class=\"inscribirse btn btn-primary\" id=\"inscribirse{$row['id']}\" data-id=\"{$row['id']}\">Inscribirse</button>";
            }
            echo "</div></div></div>";
        }
    ?>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('.inscribirse').on('click', function (e) {
            e.preventDefault();
            var dataIdValue = $(this).data('id');
            console.log(dataIdValue);
            var cursoId = $('.id-curso-'+dataIdValue).text();
            $.ajax({
                url: 'includes/mysql/mysqlInscripcion.php',
                type: 'POST',
                data: { idCurso: cursoId },
                success: function (response) {
                    console.log(response);
                    $('#inscribirse'+cursoId).prop('disabled', true);
                    Swal.fire({
                        title: "Exito",
                        text: response,
                        icon: "success"
                    });
                },
                error: function (error) {
                    Swal.fire({
                        title: "Error en la solicitud AJAX",
                        text: error,
                        icon: "error"
                    });
                }
            });
        });
    });
</script>

<?php include('includes/footer.php'); ?>