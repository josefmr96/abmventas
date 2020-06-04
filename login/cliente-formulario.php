<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/provincia.php";
include_once "entidades/localidad.php";
include_once "entidades/domicilio.php";

$cliente = new Cliente();
$cliente->cargarFormulario($_REQUEST);

if ($_GET) {
  if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $id = $_GET["id"];
    $cliente->obtenerPorId($id);
  }

  if (isset($_GET["do"]) && $_GET["do"] == "buscarLocalidad") {
    $idProvincia = $_GET["id"];
    $localidad = new Localidad();
    $aLocalidad = $localidad->obtenerPorProvincia($idProvincia);
    echo json_encode($aLocalidad);
    exit;
  }
  if(isset($_GET["do"]) && $_GET["do"] == "cargarGrilla"){
    $idCliente = $_GET['idCliente'];
    $request = $_REQUEST;
  
    $entidad = new Domicilio();
    $aDomicilio = $entidad->obtenerFiltrado($idCliente);
  
    $data = array();
  
    $inicio = $request['start'];
    $registros_por_pagina = $request['length'];
  
    if (count($aDomicilio) > 0)
        $cont=0;
        for ($i=$inicio; $i < count($aDomicilio) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = $aDomicilio[$i]->tipo;
            $row[] = $aDomicilio[$i]->provincia;
            $row[] = $aDomicilio[$i]->localidad;
            $row[] = $aDomicilio[$i]->domicilio;
            $cont++;
            $data[] = $row;
        }
  
    $json_data = array(
        "draw" => intval($request['draw']),
        "recordsTotal" => count($aDomicilio), //cantidad total de registros sin paginar
        "recordsFiltered" => count($aDomicilio),//cantidad total de registros en la paginacion
        "data" => $data
    );
    echo json_encode($json_data);
    exit;
  }
}
$provincia = new Provincia();
$aProvincias = $provincia->obtenerTodos();

if ($_POST) {
  

  if (isset($_POST["btnGuardar"])) {
    if (isset($_GET["id"]) && $_GET["id"] > 0) {

      
      //Actualizo un cliente existente
      $cliente->actualizar();
    } else {
      //Es nuevo
      $cliente->insertar();
       
      for($i=0; $i < count($_POST["txtTipo"]); $i++){
          $domicilio = new Domicilio();
          $domicilio->fk_tipo = $_POST["txtTipo"][$i];
          $domicilio->fk_idcliente = $cliente->idcliente;
          $domicilio->fk_idlocalidad = $_POST["txtLocalidad"][$i];
          $domicilio->domicilio = $_POST["txtDomicilio"][$i];
          $domicilio->insertar();
      }
    }
  } else if (isset($_POST["btnBorrar"])) {
    $cliente->eliminar();
  }
}
if (isset($_GET["id"]) && $_GET["id"] > 0) {
  $cliente->obtenerPorId();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Blank</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <form action="" method="POST">
    <!-- Page Wrapper -->
    <div id="wrapper">

      <?php include_once("menu.php"); ?>
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
              <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

              <!-- Nav Item - Search Dropdown (Visible Only XS) -->
              <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                  <div class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                      <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                          <i class="fas fa-search fa-sm"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </li>

              <!-- Nav Item - Alerts -->
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-bell fa-fw"></i>
                  <!-- Counter - Alerts -->
                  <span class="badge badge-danger badge-counter">3+</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                  <h6 class="dropdown-header">
                    Alerts Center
                  </h6>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 12, 2019</div>
                      <span class="font-weight-bold">A new monthly report is ready to download!</span>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 7, 2019</div>
                      $290.29 has been deposited into your account!
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-warning">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 2, 2019</div>
                      Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                  </a>
                  <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                </div>
              </li>

              <!-- Nav Item - Messages -->
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-envelope fa-fw"></i>
                  <!-- Counter - Messages -->
                  <span class="badge badge-danger badge-counter">7</span>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                  <h6 class="dropdown-header">
                    Message Center
                  </h6>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                      <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                      <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                      <div class="status-indicator"></div>
                    </div>
                    <div>
                      <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                      <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                      <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                      <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                      <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                      <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                      <div class="small text-gray-500">Chicken the Dog · 2w</div>
                    </div>
                  </a>
                  <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                </div>
              </li>

              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small">José Moreno</span>
                  <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                  </a>
                </div>
              </li>

            </ul>

          </nav>
          <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Cliente</h1>
            <div class="row">
              <div class="col-12 mb-3">
                <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
              </div>
            </div>
            <div class="row">
              <div class="col-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $cliente->nombre ?>">
              </div>
              <div class="col-6 form-group">
                <label for="txtCuit">CUIT:</label>
                <input type="number" required class="form-control" name="txtCuit" id="txtCuit" value="<?php echo $cliente->cuit ?>" maxlength="11">
              </div>
              <div class="col-6 form-group">
                <label for="txtFechaNac">Fecha de nacimiento:</label>
                <input type="date" class="form-control" name="txtFechaNac" id="txtFechaNac" value="<?php echo $cliente->fecha_nac ?>">
              </div>
              <div class="col-6 form-group">
                <label for="txtTelefono">Teléfono:</label>
                <input type="number" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $cliente->telefono ?>">
              </div>
              <div class="col-6 form-group">
                <label for="txtCorreo">Correo:</label>
                <input type="" class="form-control" name="txtCorreo" id="txtCorreo" required value="<?php echo $cliente->correo ?>">
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card mb-3">
                  <div class="card-header">
                    <i class="fa fa-table"></i> Domicilios
                    <div class="pull-right">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDomicilio" data-whatever="@mdo">Agregar</button>
                    </div>
                  </div>
                  <div class="panel-body">
                    <table id="grilla" class="display" style="width:98%">
                      <thead>
                        <tr>
                          <th>Tipo</th>
                          <th>Provincia</th>
                          <th>Localidad</th>
                          <th>Dirección</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

              </div>
              <!-- /.container-fluid -->

            </div>
            <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-12 form-group">
                        <label for="lstTipo">Tipo:</label>
                        <select name="lstTipo" id="lstTipo" class="form-control">
                          <option value="" disabled selected>Seleccionar</option>
                          <option value="1">Personal</option>
                          <option value="2">Laboral</option>
                          <option value="3">Comercial</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 form-group">
                        <label for="lstProvincia">Provincia:</label>
                        <select name="lstProvincia" id="lstProvincia" onchange="fBuscarLocalidad();" class="form-control">
                          <option value="" disabled selected>Seleccionar</option>
                          <?php foreach ($aProvincias as $prov) : ?>
                            <option value="<?php echo $prov->idprovincia; ?>"><?php echo $prov->nombre; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 form-group">
                        <label for="lstLocalidad">Localidad:</label>
                        <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                          <option value="" disabled selected>Seleccionar</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 form-group">
                        <label for="txtDireccion">Dirección:</label>
                        <input type="text" name="" id="txtDireccion" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio()">Agregar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
              <div class="container my-auto">
                <div class="copyright text-center my-auto">
                  <span>Copyright &copy; Your Website 2019</span>
                </div>
              </div>
            </footer>
            <!-- End of Footer -->

          </div>
          <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
          <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>


  </form>
  <script>
   $(document).ready( function () {
            var idCliente = '<?php echo isset($cliente) && $cliente->idcliente > 0?  $cliente->idcliente : 0 ?>';
            var dataTable = $('#grilla').DataTable({
                "processing": true,
                "serverSide": true,
                "bFilter": true,
                "bInfo": true,
                "bSearchable": true,
                "pageLength": 25,
                "order": [[ 0, "asc" ]],
                "ajax": "cliente-formulario.php?do=cargarGrilla&idCliente=" + idCliente
            });
        } );

    function fBuscarLocalidad() {
      idProvincia = $("#lstProvincia option:selected").val();
      $.ajax({
        type: "GET",
        url: "cliente-formulario.php?do=buscarLocalidad",
        data: {
          id: idProvincia
        },
        async: true,
        dataType: "json",
        success: function(respuesta) {
          $("#lstLocalidad option").remove();
          $("<option>", {
            value: 0,
            text: "Seleccionar",
            disabled: true,
            selected: true
          }).appendTo("#lstLocalidad");

          for (i = 0; i < respuesta.length; i++) {
            $("<option>", {
              value: respuesta[i]["idlocalidad"],
              text: respuesta[i]["nombre"]
            }).appendTo("#lstLocalidad");
          }
          $("#lstLocalidad").prop("selectedIndex", "0");
        }
      });
    }
    function fAgregarDomicilio(){
            var grilla = $('#grilla').DataTable();
            grilla.row.add([
                $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='"+ $("#lstTipo option:selected").val() +"'>",
                $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='"+ $("#lstProvincia option:selected").val() +"'>",
                $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='"+ $("#lstLocalidad option:selected").val() +"'>",
                $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='"+$("#txtDireccion").val()+"'>",
                ""
            ]).draw();
            $('#modalDomicilio').modal('toggle');
            limpiarFormulario();
        }

        function limpiarFormulario(){
            $("#lstTipo").val(0);
            $("#lstProvincia").val(0);
            $("#lstLocalidad").val(0);
            $("#txtDireccion").val("");
        }

       

  </script>
</body>

</html>