<div class="inicio">
    <h4 class="dashboard__heading2"><i class="fa-solid fa-building-circle-check"></i><?php echo $titulo; ?> </h4>
    
    <div class="bloques">
        <div class="bloques__grid">
            <div class="bloques__bloque">
                <p class="bloques__heading">Ingresos Hoy</p>
                <div class="bloques__contenido">
                    <p class="sign"><span>$</span><?php echo $day->computarizado??'0'; ?></p>
                    <i class="idollar fa-solid fa-dollar-sign"></i>
                </div>
            </div>

            <div class="bloques__bloque">
                <p class="bloques__heading">Servicios Hoy</p>
                <div class="bloques__contenido">
                    <p class="plus"><i class="fa-solid fa-plus"></i><?php echo $day->totalservices??'0';?></p>
                    <i class="icalender fa-regular fa-calendar-check"></i>
                </div>
            </div>

            <div class="bloques__bloque">
                <p class="bloques__heading">Clientes</p>
                <div class="bloques__contenido">
                    <p class="angule"><i class="fa-solid fa-angle-up"></i><?php echo $totalclientes; ?></p>
                    <i class="iusers fa-solid fa-users"></i>
                </div>
            </div>

            <div class="bloques__bloque">
                <p class="bloques__heading">Empleados</p>
                <div class="bloques__contenido">
                    <p class="employee"><i class="fa-solid fa-plus"></i><?php echo $totalempleados; ?></p>
                    <i class="icard fa-solid fa-address-card"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="datos">
        
        <div class="datos__contenedorgraficas">
            <div class="datos__grafica1">
                <h4 class="datos__titulograficas">GRAFICAS</h4>
                <p>PRODUCIDO</p>
                <canvas id="servicios-grafica"></canvas>
            </div>

            <div class="datos__completado">
                <div class="datos__admin">
                    <div class="datos__bg">
                        <p class="bg_texto">Bienvenido !</p>
                        <div class="datos__user">
                            <i class="fa-solid fa-user-large"></i>
                        </div>
                    </div>
                    <div class="datos__admininfo">
                        <div class="datos__perfil">
                            <p class="nombreperfil"><?php echo $user['nombre'] ?></p>
                            <p class="tipoperfil"><?php echo $user['admin']==1?'Empleado':'Admin'; ?></p>
                        </div>
                        <div class="datos__btn">
                            <a class="btnsmall" href="/admin/perfil">Perfil <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="datos__infocompletado">
                    <div class="datos__texto">
                        <p class="textheading">Daily Earning</p>
                        <p class="text">This Day</p>
                        <h3 class="dailyearning"></h3>
                        <a class="btnsmall" href="/admin/facturacion">Ver Mas <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="rueda">
                        <div class="afuera">
                            <div class="adentro">
                                <p class="numero">0%</p>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                            <defs>
                                <linearGradient id="GradientColor1">
                                    <stop offset="0%" stop-color="#e91e63" />
                                    <stop offset="100%" stop-color="#673ab7" />
                                </linearGradient>
                            </defs>
                            <circle cx="80" cy="80" r="70" stroke-linecap="round" />
                        </svg>    
                    </div> <!-- fin rueda -->
                </div> <!-- fin infocompletado -->
                <p class="datos__descripcion">Registro digital en timepo real.</p>
            </div>
        </div>
    </div>

    <div>
        <div class="grafico-circular">
<!-- porcentaje de los servicios mas demandados -->
        </div>
        <div class="barrashorizontal">
<!-- 3 barras progresivas de completacion de dinero, citas etc del dia actual -->
        </div>
        <div class="primeros6clientes">
<!-- fin mostrar los primeros 6 clientes -->
        </div>

    </div>
    
</div>