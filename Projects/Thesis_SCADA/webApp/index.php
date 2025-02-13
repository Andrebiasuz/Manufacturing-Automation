<?php
session_start();
//U need to know the password hash first, to verify it later, ideally u wouldnt echo it out, but it's needed so u don't have to register.
//print_r (password_hash('Hello123'));
if(isset($_SESSION['sid'])){
  $uname = $_SESSION['uname'];
echo <<<EOD

<html lang="en" dir="ltr">
    <!-- Header-->
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width , initial-scale=1" />
        <title>Thesis app</title>
        <link rel="stylesheet" href="css/bootstrap.css" />
        <link rel="stylesheet" href="css/main.css" />
    </head>
    <body>
        <!-- NavBar collapsable when screen size is tablet or less -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"> <img src="images/wago_logo.jpeg" width="96" height="32" class="d-inline-block align-top" alt="" />CLOUD CLONE APP </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">
                        Home
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="https://www.wago.com">Wago Hungary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://cloud.wago.com/apps/dashboard/dashboard/grafana-app?" target="_blank">CLOUD Application</a>
                    </li>
                    <li class="nav-item">
                    <form class="" action="./logout.php" method="post">
                      <button type="submit" class="btn btn-danger p-0" name="button">LOGOUT</button>
                      </form>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Contents of the page -->
        <div class="full_page">
            <div class="container align-content-center">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 col-xs-2 border border-4 rounded-3 border border-success mx-auto gx-10" id="widget left">
                        <div>
                            <p id="title_left">PRODUCT'S MANAGERS ROOM</p>
                        </div>
                        <div class="container_wl">
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="arriba">Temp:(°C)</span>
                                        </div>
                                        <input id="Return_Cloud" type="text" class="form-control" placeholder="Fetching" readonly />
                                    </div>
                                </div>
                                <div class="col-4 col-md-4">
                                    <a href="https://cloud.wago.com/apps/dashboard/dashboard/grafana-app/d/FHA81r57k/wago-temperature-readouts-and-water-tank-contro?" class="btn btn-primary btn-md active" role="button" aria-pressed="true" target="_blank">CLOUD Dashboard</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="arriba">Setpoint(°C)</span>
                                        </div>
                                        <input id="Return_Cloud_set" type="text" class="form-control" placeholder="Fetching" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 border border-4 rounded-3 border border-success mx-auto" id="widget_right">
                        <div class="align-self-center">
                            <p id="title_left">Outer tank - Obudai</p>
                        </div>
                        <div class="container_wl">
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Tank Level:(%)</span>
                                        </div>
                                        <input id="Tank_level" type="text" class="form-control" placeholder="Fetching..." readonly />
                                    </div>
                                </div>
                                <div class="col-4 col-md-4">
                                    <a href="https://cloud.wago.com/apps/dashboard/dashboard/grafana-app/d/FHA81r57k/wago-temperature-readouts-and-water-tank-contro?" class="btn btn-primary btn-md active" role="button" aria-pressed="true" target="_blank">CLOUD Dashboard</a>
                                </div>
                            </div>
                            <div class="row p-8 gy-3">
                                <div class="col-1 col-md-1"></div>
                                <div class="col-3 col-md-3"><span>INPUT</span></div>
                                <div class="col-4 col-md-4"><span>OUTPUT</span></div>
                            </div>
                            <div class="row px-4 gy-1">
                                <div class="col-4 col-md-4 justify-content-center">
                                    <button type="button" class="btn btn-danger opacity-100" id="bTN_Supply_input_pump_Outer_Tank" disabled>PUMP<span class="badge bg-secondary" id="input_pump_status">FETCHING</span></button>
                                </div>
                                <div class="col-4 col-md-4">
                                    <button type="button" class="btn btn-danger opacity-100" id="bTN_Water_output_pump_Outer_Tank" disabled>PUMP<span class="badge bg-secondary" id="output_pump_status">FETCHING</span></button>
                                </div>
                            </div>
                            <div class="row px-4 gy-1">
                                <div class="col-4 col-md-4 text-center">
                                    <button type="button" class="btn btn-danger" id="button_input_pump">Enable <span class="badge bg-secondary" id="input_pump_st_alert">FETCHING</span></button>
                                </div>
                                <div class="col-4 col-md-4 text-center">
                                    <button type="button" class="btn btn-danger" id="button_output_pump">Enable <span class="badge bg-secondary" id="output_pump_st_alert">FETCHING</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 col-xs-2 border border-4 rounded-3 border border-success mx-auto gx-10" id="Building_Automation_Model">
                        <div>
                            <p id="title_left">GAS HEATING - CONTROL MODEL</p>
                        </div>
                        <div class="container_wl">
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="arriba">Temp:(°C)</span>
                                        </div>
                                        <input id="heater_temp" type="text" class="form-control" placeholder="Fetching" readonly />
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 justify-content-center">
                                    <button type="button" class="btn btn-danger opacity-100" id="BTN_Heater" disabled>HEATER<span class="badge bg-secondary" id="heater_status">FETCHING</span></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Setpoint(°C)</span>
                                        </div>
                                        <input id="Set_Point_Heater" type="text" class="form-control" placeholder="Please input value" required />
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 text-center">
                                    <button type="button" class="btn btn-danger" id="button_heater">Enable <span class="badge bg-secondary" id="heater_st_alert">FETCHING</span></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Ext Temp(°C)</span>
                                        </div>
                                        <input id="External_temperature" type="number" class="form-control" placeholder="Please input value" required />
                                    </div>
                                </div>
                                <div class="col-4 col-md-4">
                                    <a href="https://cloud.wago.com/apps/dashboard/dashboard/grafana-app/d/oA9Q295nz/heating_model?" class="btn btn-primary btn-md active" role="button" aria-pressed="true" target="_blank">CLOUD Dashboard</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Curr Setpoint(°C)</span>
                                        </div>
                                        <input id="Current_setpoint" type="number" class="form-control" placeholder="Fetching" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-md-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Wall Isolation Temp:</span>
                                        </div>
                                        <input id="Isolation_temperature" type="number" class="form-control" placeholder="Fetching" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 col-xs-2 border border-4 rounded-3 border border-success mx-auto gx-10" id="empty">
                </div>
            </div>
        </div>
        <script src="js/jquery-3.6.0.min.js" charset="utf-8"></script>
        <script src="js/bootstrap.min.js" charset="utf-8"></script>
        <script src="js/main.js" charset="utf-8"></script>
    </body>
</html>

EOD;
}
else{
  header('location:./login.php');
}

 ?>





<!DOCTYPE html>
