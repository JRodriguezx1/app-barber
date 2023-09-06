<?php 

require_once __DIR__ . '/../includes/app.php'; //apunta al directorio raiz y luego a app.php, el archivo app contiene: las variables de entorno para el deploy,
                    //la clase ActiveRecord, el autoload de composer = localizador de clases, archivo de funciones debuguear y sanitizar html
                    //archivo de conexion de bd mysql con variables de entorno y me establece la conexion mediante: ActiveRecord::setDB($db);

//me importa clases del controlador

use Controllers\logincontrolador; //clase para logueo, registro de usuario, recuperacion, deslogueo etc..
use Controllers\dashboardcontrolador;
use Controllers\paginacontrolador;
use Controllers\servicioscontrolador;
use Controllers\factcontrolador;
use Controllers\citascontrolador;
use Controllers\clientescontrolador;
use Controllers\fidelizacioncontrolador;
use Controllers\adminconfigcontrolador;
use Controllers\configcontrolador;
///////controlador del cliente ///////////
use Controllers\controladorcliente;
// me importa la clase router
use MVC\Router;  



$router = new Router();



// Login
$router->get('/login', [logincontrolador::class, 'login']);
$router->post('/login', [logincontrolador::class, 'login']);
$router->post('/logout', [logincontrolador::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [logincontrolador::class, 'registro']);
$router->post('/registro', [logincontrolador::class, 'registro']);

// Formulario de olvide mi password
$router->get('/olvide', [logincontrolador::class, 'olvide']);
$router->post('/olvide', [logincontrolador::class, 'olvide']);

// Colocar el nuevo password
$router->get('/recuperarpass', [logincontrolador::class, 'recuperarpass']);
$router->post('/recuperarpass', [logincontrolador::class, 'recuperarpass']);

// Confirmación de Cuenta
$router->get('/mensaje', [logincontrolador::class, 'mensaje']);
$router->get('/confirmar-cuenta', [logincontrolador::class, 'confirmar_cuenta']);

//area publica
$router->get('/', [paginacontrolador::class, 'index']);
$router->get('/promos', [paginacontrolador::class, 'promos']);

/////area de administracion/////
$router->get('/admin/dashboard', [dashboardcontrolador::class, 'index']);
$router->get('/admin/perfil', [dashboardcontrolador::class, 'perfil']);
$router->post('/admin/perfil', [dashboardcontrolador::class, 'perfil']);
$router->get('/admin/perfil/cambiarpassword', [dashboardcontrolador::class, 'cambiarpassword']);
$router->post('/admin/perfil/cambiarpassword', [dashboardcontrolador::class, 'cambiarpassword']);

$router->get('/admin/servicios', [servicioscontrolador::class, 'index']);
$router->post('/admin/servicios/crear', [servicioscontrolador::class, 'index']);
$router->post('/admin/servicios/editar', [servicioscontrolador::class, 'editar']);

$router->get('/admin/facturacion', [factcontrolador::class, 'index']);
$router->post('/admin/facturacion', [factcontrolador::class, 'index']);
$router->get('/admin/facturacion/buscarxfecha', [factcontrolador::class, 'buscarxfecha']);
$router->get('/admin/facturacion/gestionar', [factcontrolador::class, 'gestionar']);
$router->post('/admin/facturacion/gestionar', [factcontrolador::class, 'gestionar']);

$router->get('/admin/citas', [citascontrolador::class, 'index']);
$router->post('/admin/citas', [citascontrolador::class, 'index']); //llamado desde citas.js cuando se reprograma cita de algun cliente
$router->post('/admin/citas/consultaxprofesxfecha', [citascontrolador::class, 'consultaxprofesxfecha']); //llamado desde citas.js
$router->get('/admin/citas/filtroxfecha', [citascontrolador::class, 'filtroxfecha']); 
$router->post('/admin/citas/crear', [citascontrolador::class, 'crear']);  //se llama desde citas.js cuando admin crea la cita por el cliente
$router->post('/admin/citas/finalizar', [citascontrolador::class, 'finalizar']);  //se llama desde finalizcita.js cuando admin termina o finaliza la cita
$router->post('/admin/citas/consultaxestadoxname', [citascontrolador::class, 'consultaxestadoxname']);

$router->get('/admin/clientes', [clientescontrolador::class, 'index']);
$router->post('/admin/clientes', [clientescontrolador::class, 'index']); //filtro de busqueda
$router->post('/admin/clientes/crear', [clientescontrolador::class, 'crear']);
$router->post('/admin/clientes/actualizar', [clientescontrolador::class, 'actualizar']);
$router->get('/admin/clientes/detalle', [clientescontrolador::class, 'detalle']);
$router->get('/admin/clientes/hab_desh', [clientescontrolador::class, 'hab_desh']); //habilitar deshabilitar cliente

$router->get('/admin/fidelizacion', [fidelizacioncontrolador::class, 'index']);  //tambein es llamado desde fidelizacion.js cuando se elimina oferta
$router->get('/admin/fidelizacion/creardctoxproduct', [fidelizacioncontrolador::class, 'creardctoxproduct']);
$router->post('/admin/fidelizacion/creardctoxproduct', [fidelizacioncontrolador::class, 'creardctoxproduct']);
$router->post('/admin/fidelizacion/editar_dctoxproduct', [fidelizacioncontrolador::class, 'editar_dctoxproduct']);

$router->get('/admin/adminconfig', [adminconfigcontrolador::class, 'index']);
$router->post('/admin/adminconfig/actualizar', [adminconfigcontrolador::class, 'actualizar']); //metodo para actualizar negocio
$router->post('/admin/adminconfig/crear_empleado', [adminconfigcontrolador::class, 'crear_empleado']); //metodo para crear cliente
$router->post('/admin/adminconfig/update_employee', [adminconfigcontrolador::class, 'update_employee']); //metodo para actualizar empleado
$router->get('/admin/adminconfig/eliminaremployee', [adminconfigcontrolador::class, 'eliminaremployee']);
$router->post('/admin/adminconfig/actualizarmalla', [adminconfigcontrolador::class, 'actualizarmalla']); //metodo para actualizar malla a empleado
$router->post('/admin/adminconfig/fechadesc', [adminconfigcontrolador::class, 'fechadesc']); //metodo para ingresar fecha especial de descanso

$router->post('/admin/adminconfig/configuracion/crearuserprincipal', [configcontrolador::class, 'crearuserprincipal']); //crear usuario principal

///apis///
$router->get('/admin/api/getservices', [servicioscontrolador::class, 'getservices']); //fetch en facturacion.js
$router->post('/admin/api/eliminarservicio', [servicioscontrolador::class, 'eliminar']); //fetch en servicios.js
$router->get('/admin/api/getAllemployee', [adminconfigcontrolador::class, 'getAllemployee']); //fetch en empleados.js
$router->get('/admin/api/getmalla', [adminconfigcontrolador::class, 'getmalla']); //fetch en malla.js y en dash_cliente_assign.js
$router->get('/admin/api/getfechadesc', [adminconfigcontrolador::class, 'getfechadesc']);  //fetch en fechadesc.js y en dash_cliente_assign.js
$router->get('/admin/api/deletefechadesc', [adminconfigcontrolador::class, 'deletefechadesc']);  //metodo para eliminar fecha llamado desde fechadesc.js
$router->get('/admin/api/getemployee_services', [citascontrolador::class, 'getemployee_services']); //metodo llamado desde dash_cliente.js
$router->post('/admin/api/enviarcita', [controladorcliente::class, 'enviarcita']);  // api llamada desde dash_cliente_assign.js
$router->get('/admin/api/getcitas', [controladorcliente::class, 'getcitas']);  //llamado desde dash_liente_assign.js
$router->get('/admin/api/cancelarcita', [controladorcliente::class, 'cancelarcita']); //elimina cita desde el dashboard del cliente en dash_cliente.js
$router->get('/admin/api/allclientes', [clientescontrolador::class, 'allclientes']); // me trae todos los clientes o usuarios desde citas.js
$router->get('/admin/api/alldays', [dashboardcontrolador::class, 'alldays']); // me trae todos los dias desde graficas.js
$router->get('/admin/api/totalcitas', [dashboardcontrolador::class, 'totalcitas']);
$router->get('/admin/api/detallepagoxcita', [factcontrolador::class, 'detallepagoxcita']); //api se ejecuta en el modulo de citas en admin
$router->get('/admin/api/anularpagoxcita', [factcontrolador::class, 'anularpagoxcita']); //api se ejecuta en el modulo de citas en admin
$router->get('/admin/api/getmediospago', [configcontrolador::class, 'getmediospago']); //api llamada desde configuracion.js
$router->post('/admin/api/setmediospago', [configcontrolador::class, 'setmediospago']); //api llamada desde configuracion.js para setear los medios de pago
$router->post('/admin/api/habilitarempleado', [configcontrolador::class, 'habilitarempleado']); //api llamada desde configuracion.js para habilitar empleado a ingresar a sistema dashboard
$router->post('/admin/api/setearpass', [configcontrolador::class, 'setearpass']); //api para setear password
$router->post('/admin/api/coloresapp', [configcontrolador::class, 'coloresapp']); //api establecer colores app
$router->post('/admin/api/tiemposervicio', [configcontrolador::class, 'tiemposervicio']); //api establecer tiempo de servicio
$router->get('/admin/api/gettimeservice', [configcontrolador::class, 'gettimeservice']); //api para traer el tiempo de duracion general del servicio

/////area de clientes/////
$router->get('/Cliente/app', [controladorcliente::class, 'index']);
$router->post('/Cliente/app', [controladorcliente::class, 'index']);  //cuando se actualize el formulario del cliente desde el dashboard del cliente


$router->comprobarRutas();

