    <?php

    require_once('../libs/php/sql_sigp.php');
    require_once('../libs/php/generales.php');
    require_once('../libs/php/funciones_regiones.php');

    // isset($_POST['funcion']) && 
    if ($_POST['funcion'] == "listar_permisos") {
        //echo "ListarUsuarios";

        $cns = "select id, nombre, url, nombre_estados, nombre_modulos from dbo.vista_usuarios_permisos order by nombre, nombre_modulos ASC";

        $a = get_datos($cns);
        $contador = 0;
        $objeto = "[";
        while (odbc_fetch_row($a)) {
            $objeto .= "{";
            $objeto .= "'id':'" . odbc_result($a, "id") . "',";
            $objeto .= "'nombre':'" . str_replace("'", "", encoder(odbc_result($a, "nombre"))) . "',";
            $objeto .= "'url':'" . encoder(odbc_result($a, "url")) . "',";
            $objeto .= "'estado':'" . encoder(odbc_result($a, "nombre_estados")) . "',";
            $objeto .= "'modulo':'" . encoder(odbc_result($a, "nombre_modulos")) . "'";
            $objeto .= "},";
            $contador = 1;
        }
        if ($contador >= 1) {
            $objeto = substr($objeto, 0, -1);
        }
        $objeto .= "]";

        echo $objeto;
    }
    if ($_POST['funcion'] == "listar_permisos_por_modulo") {
        //echo "ListarUsuarios";

        $cns = "SELECT id_modulos 
                                ,modulos_nombre 
                                ,modulos_estado 
                                ,id_permisos 
                                ,permisos_nombre 
                                ,permisos_url 
                                ,usuario 
                                ,seleccionar 
                            FROM sigp.dbo.vista_permisos_usuariospermisos 
                            WHERE id_modulos = $_POST[idModulo] AND (usuario = '$_POST[idUsuario]' or usuario is null) ORDER BY permisos_nombre ASC";

        $a = get_datos($cns);
        $contador = 0;
        $objeto = "[";
        while (odbc_fetch_row($a)) {
            $objeto .= "{";
            $objeto .= "'id':'" . odbc_result($a, "id_permisos") . "',";
            $objeto .= "'nombre':'" . str_replace("'", "", encoder(odbc_result($a, "permisos_nombre"))) . "',";
            $objeto .= "'seleccionar':'" . str_replace("'", "", encoder(odbc_result($a, "seleccionar"))) . "'";
            $objeto .= "},";
            $contador = 1;
        }
        if ($contador >= 1) {
            $objeto = substr($objeto, 0, -1);
        }
        $objeto .= "]";

        echo $objeto;
    }

    if ($_POST['funcion'] == "nuevo_permiso") {
        $cns = "EXEC [dbo].[crea_permisos_usuarios] @nombre = N'" . decoder($_POST['nombre']) . "', @url=N'" . decoder($_POST['url']) . "', @modulo=" . decoder($_POST['modulo']) . ", @estado = " . $_POST['estado'];

        $a = get_datos($cns);

        /* $cns = "SELECT @@IDENTITY AS ID";

          $b = get_datos($cns); */

        while (odbc_fetch_row($a)) {
            $respuesta = odbc_result($a, "respuesta");
        }

        echo $respuesta;
        //echo $cns;
    }

    if ($_POST['funcion'] == "updt_permiso") {
        //echo "ListarUsuarios";

        $cns = "EXEC [dbo].[actualiza_permisos_usuarios] @nombre = N'" . decoder($_POST['nombre']) . "', @url=N'" . decoder($_POST['url']) . "', @modulo=" . decoder($_POST['modulo']) . ", @estado = " . $_POST['estado'] . ", @id = " . $_POST['id'];

        $a = get_datos($cns);
        while (odbc_fetch_row($a)) {
            $respuesta = odbc_result($a, 'respuesta');
        }
        echo $respuesta;
    }

    if ($_POST['funcion'] == "listar_estados") {
        //echo "ListarUsuarios";

        $cns = "select id, nombre from vista_usuarios_estados order by nombre ASC";

        $a = get_datos($cns);

        $objeto = "[";
        while (odbc_fetch_row($a)) {
            $objeto .= "{";
            $objeto .= "'id':'" . odbc_result($a, "id") . "',";
            $objeto .= "'nombre':'" . encoder(odbc_result($a, "nombre")) . "'";
            $objeto .= "},";
        }
        $objeto = substr($objeto, 0, -1);
        $objeto .= "]";

        echo $objeto;
    }

    if ($_POST['funcion'] == "listar_modulos") {
        //echo "ListarUsuarios";

        $cns = "select id, nombre from dbo.vista_usuarios_modulos order by nombre ASC";

        $a = get_datos($cns);

        $objeto = "[";
        while (odbc_fetch_row($a)) {
            $objeto .= "{";
            $objeto .= "'id':'" . odbc_result($a, "id") . "',";
            $objeto .= "'nombre':'" . encoder(odbc_result($a, "nombre")) . "'";
            $objeto .= "},";
        }
        $objeto = substr($objeto, 0, -1);
        $objeto .= "]";

        echo $objeto;
    }
    if ($_POST['funcion'] == "borrar_permisos_por_modulo") {
        //echo "ListarUsuarios";

        $cns = "DELETE sigp_usuarios.dbo.usuarios_permisos
                                FROM sigp_usuarios.dbo.usuarios_permisos p INNER JOIN sigp.dbo.vista_permisos_usuariospermisos up ON up.id_permisos = p.id_permisos 
                            WHERE up.id_modulos = $_POST[idModulo] AND p.usuario = '$_POST[idUsuario]'";

        $a = get_datos($cns);
    }
    if ($_POST['funcion'] == "nuevo_permisos_por_modulo") {
        //echo "ListarUsuarios";

        $cns = "EXEC dbo.asignar_permisos_usuario @usuario = N'$_POST[idUsuario]' , @idpermiso = '$_POST[idPermiso]' ";

        $a = get_datos($cns);
        
        while (odbc_fetch_row($a)) {
            $respuesta = odbc_result($a, "respuesta");
        }

        echo $respuesta;
    }
    ?>