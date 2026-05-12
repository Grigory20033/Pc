<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <Title>Gestor PC</Title>
    <link  href="css/bootstrap.css" rel="stylesheet"/>
    <style> 
     .contenido{ 
    background-image: url('img/baner.png');
background-size:cover;
background-repeat :no-repeat;
background-position:center;
display:flex;
align-items:center;
justify-content:center;
min-height:100vh; }

.btn{
    padding: 10px 20px;
    border-radius: 5px;
    border: #D4C31C;
    background-color: #429904
    color( black)
   
    
}



</style>
    </head>
    <body> 
       <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" >Gestor de Inventario y Mantenimiento</a>
        </div>
       </nav>
     <div class="container-fluid">
        <div class="row">
            <div class="col-2 bg-dark text-white p-3">
                <a href="Autenticar.php" class="btn btn-outline-light w-100 mb-2">Autenticar</a>
                <a href="nuevousuario.php" class="btn btn-outline-light w-100 mb-2">Crear nuevo Usuario</a>
                <a href="agregar.php" class="btn btn-outline-light w-100 mb-2">Agregar</a>
                
                <a href="Eliminar.php" class="btn btn-outline-light w-100 mb-2">Eliminar</a>
                <a href="informe.php" class="btn btn-outline-light w-100 mb-2">Informe</a>
                
            </div>
            <div class="col contenido">
                <h3 style="color:#001f3f;">Bienvenido</h3>
            </div>
        </div>
     </div>
     <script src="js/bootstrap.bundle.min.js."></script>
     
     <article class="block">
        <i class="icon-social fa fa-twitter" ></i>
        <h2>Twitter</h2>
        <p>Lorem ipsum 
            
        dolor sit, amet consectetur adipisicing elit. Eaque accusantium nemo dolore placeat nam dicta saepe maiores totam? Tempore quod voluptate numquam esse veniam laudantium labore suscipit! Dolore, provident ab!</p>
     <a href="#" class="btn">Ver</a>
    </article>


    </body>
    
</html>