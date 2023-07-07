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

/////area de administracion/////
$router->get('/admin/dashboard', [dashboardcontrolador::class, 'index']);

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

$router->get('/admin/clientes', [clientescontrolador::class, 'index']);
$router->post('/admin/clientes', [clientescontrolador::class, 'index']); //filtro de busqueda
$router->post('/admin/clientes/crear', [clientescontrolador::class, 'crear']);
$router->post('/admin/clientes/actualizar', [clientescontrolador::class, 'actualizar']);
$router->get('/admin/clientes/detalle', [clientescontrolador::class, 'detalle']);
$router->get('/admin/clientes/eliminar', [clientescontrolador::class, 'eliminar']);

$router->get('/admin/fidelizacion', [fidelizacioncontrolador::class, 'index']);
$router->post('/admin/fidelizacion/crear', [fidelizacioncontrolador::class, 'crear']); //llamada de metodo para crear dcto global
$router->get('/admin/fidelizacion/dctoindividual', [fidelizacioncontrolador::class, 'dctoindividual']);
$router->post('/admin/fidelizacion/editar_dctoindividual', [fidelizacioncontrolador::class, 'editar_dctoindividual']);
$router->get('/admin/fidelizacion/eliminar_dctoindividual', [fidelizacioncontrolador::class, 'eliminar_dctoindividual']);

$router->get('/admin/configuracion', [configcontrolador::class, 'index']);
$router->post('/admin/configuracion/actualizar', [configcontrolador::class, 'actualizar']); //metodo para actualizar negocio
$router->post('/admin/configuracion/crear_empleado', [configcontrolador::class, 'crear_empleado']); //metodo para crear cliente
$router->post('/admin/configuracion/update_employee', [configcontrolador::class, 'update_employee']); //metodo para actualizar empleado
$router->get('/admin/configuracion/eliminaremployee', [configcontrolador::class, 'eliminaremployee']);
$router->post('/admin/configuracion/actualizarmalla', [configcontrolador::class, 'actualizarmalla']); //metodo para actualizar malla a empleado
$router->post('/admin/configuracion/fechadesc', [configcontrolador::class, 'fechadesc']); //metodo para ingresar fecha especial de descanso
///apis///
$router->get('/admin/api/getservices', [servicioscontrolador::class, 'getservices']); //fetch en facturacion.js
$router->post('/admin/api/eliminarservicio', [servicioscontrolador::class, 'eliminar']); //fetch en servicios.js
$router->get('/admin/api/getAllemployee', [configcontrolador::class, 'getAllemployee']); //fetch en empleados.js
$router->get('/admin/api/getmalla', [configcontrolador::class, 'getmalla']); //fetch en malla.js y en dash_cliente_assign.js
$router->get('/admin/api/getfechadesc', [configcontrolador::class, 'getfechadesc']);  //fetch en fechadesc.js y en dash_cliente_assign.js
$router->get('/admin/api/deletefechadesc', [configcontrolador::class, 'deletefechadesc']);  //metodo para eliminar fecha llamado desde fechadesc.js
$router->get('/admin/api/getemployee_services', [citascontrolador::class, 'getemployee_services']); //metodo llamado desde dash_cliente.js
$router->post('/admin/api/enviarcita', [controladorcliente::class, 'enviarcita']);  // api llamada desde dash_cliente_assign.js
$router->get('/admin/api/getcitas', [controladorcliente::class, 'getcitas']);  //llamado desde dash_liente_assign.js
$router->get('/admin/api/cancelarcita', [controladorcliente::class, 'cancelarcita']); //elimina cita desde el dashboard del cliente en dash_cliente.js
$router->get('/admin/api/allclientes', [clientescontrolador::class, 'allclientes']); // me trae todos los clientes o usuarios desde citas.js
$router->get('/admin/api/alldays', [dashboardcontrolador::class, 'alldays']); // me trae todos los dias desde graficas.js
$router->get('/admin/api/totalcitas', [dashboardcontrolador::class, 'totalcitas']);

/////area de clientes/////
$router->get('/Cliente/app', [controladorcliente::class, 'index']);
$router->post('/Cliente/app', [controladorcliente::class, 'index']);  //cuando se actualize el formulario del cliente desde el dashboard del cliente


$router->comprobarRutas();

