var inptdata = {};
var dataToPost = {};
var return_cloud = 0;
var dataToPost_json = {};
let bTN_Supply_Valve_Outer_Tank = {
    value: 0
};
let bTN_Water_Pump_Outer_Tank = {
    value: 0
};
let customer_High_Level_Inner_tank = {
    value: 0
};
let bTN_Heater_HVAC = {
    value: 0
};
let start_bt = {
    value: 0
};

function dataProcess() {
    for (const key in inptdata) {
        dataToPost[key] = inptdata[key];
    }
    dataToPost['Floating_Value'] = $('#Floating_Value').val();
    //dataToPost['Tank_level'] = $('#Tank_level').val() // reserved if we want to send a setpoint to the Tank (advanced)
    dataToPost['BTN_Supply_Valve_Outer_Tank'] = bTN_Supply_Valve_Outer_Tank.value;
    dataToPost['BTN_Water_Pump_Outer_Tank'] = bTN_Water_Pump_Outer_Tank.value;
    dataToPost['Customer_High_Level_Inner_tank'] = customer_High_Level_Inner_tank.value;
    dataToPost['BTN_Heater'] = bTN_Heater_HVAC.value;
    dataToPost['Set_Point_Heater'] =  $('#Set_Point_Heater').val();
    dataToPost['External_temperature'] = $('#External_temperature').val();

    dataToPost_json = JSON.stringify(dataToPost);
    writePost();
}

function dataProcess_BA() {
    for (const key in inptdata) {
        dataToPost[key] = inptdata[key];
    }

    dataToPost['BTN_Heater'] = bTN_Heater_HVAC.value;
    dataToPost['Set_Point_Heater'] =  $('#Set_Point_Heater').val();
    dataToPost['External_temperature'] = $('#External_temperature').val();
    dataToPost_json = JSON.stringify(dataToPost);
    writePost_BA();
}



function writePost() {
    $.ajax({
            contentType: "application/json", // php://input
            //contentType: "application/x-www-form-urlencoded; charset=UTF-8", // $_POST
            dataType: "JSON",
            method: "POST",
            url: './post_CLOUD.php',
            data: dataToPost_json
        })
        .done(function(data) {
            console.log("test: ", data);
        })
        .fail(function(data) {
            console.log("error: ", data);
        });
}

function writePost_BA() {
    $.ajax({
            contentType: "application/json", // php://input
            //contentType: "application/x-www-form-urlencoded; charset=UTF-8", // $_POST
            dataType: "JSON",
            method: "POST",
            url: './post_CLOUD.php',
            data: dataToPost_json
        })
        .done(function(data) {
            console.log("test: ", data);
            $("#result").text(data.name);
        })
        .fail(function(data) {
            console.log("error: ", data);
        });

}

function changeColor(condition, id, color_clicked, color_default) {
    if (condition === 'True') {
        $(id).css('background-color', color_clicked);
    } else {
        $(id).css('background-color', color_default);
    }
    //aux == ('1' || 'True' || (1)) ? $(button_id).css('background-color', color_clicked) : $(button_id).css('background-color',color_default) ;
}

function changeText(condition, id, textIfTrue, textIfFalse) {
    if (condition === 'True') {
        $(id).text(textIfTrue);
    } else if (condition === 'False') {
        $(id).text(textIfFalse);
    }
}

function buttonChangeState(id, outputVar) {
    $('button').on('click', function() {
        if (this.id == id) {
            outputVar.value == 0 ? outputVar.value = 1 : (outputVar.value == 1 ? outputVar.value = 0 : 1);
            dataProcess();
        }
    });
}

function buttonChangeState_BA(id, outputVar) {
    $('button').on('click', function() {
        if (this.id == id) {
            outputVar.value == 0 ? outputVar.value = 1 : (outputVar.value == 1 ? outputVar.value = 0 : 1);
            dataProcess_BA();
        }
    });
}

function getChangeAnalogValue_BA(id) {
    $('#' + id).on('change', function() {
        inptdata[this.id] = this.value;
        dataProcess_BA();
    });
}

function getChangeAnalogValue(id) {
    $('#' + id).on('change', function() {
        inptdata[this.id] = this.value;
        dataProcess();
    });
}



function getCloud() {
    $.post('./get_CLOUD.php').done(function(dataBack) {
        //console.log("Databack is: ", dataBack)
        let retArray = JSON.parse(dataBack);

        $('#Return_Cloud').val(retArray[1][2].toFixed(1)); // one decimal point for setpoint
        $('#Return_Cloud_set').val(retArray[1][1]);
        $('#Tank_level').val(retArray[0][10].toFixed(2));

        $('#heater_temp').val(retArray[1][5].toFixed(2));
        $('#Current_setpoint').val(retArray[1][6]);
        $('#Isolation_temperature').val(retArray[1][4].toFixed(2));

        return_valve = retArray[0][8];
        return_pump = retArray[0][9];

        if (retArray[1][3] > 1) {
            return_heater = 'True';
          }
          else {
            return_heater = 'False';
          }

        changeColor(return_heater, '#BTN_Heater', 'green', 'red');
        changeText(return_heater, '#heater_status', 'ON', 'OFF');

        status_button_valve = retArray[0][11];
        status_button_pump = retArray[0][12];
        status_button_heater = retArray[1][7];

        changeColor(return_valve, '#bTN_Supply_Valve_Outer_Tank', 'green', 'red');
        changeColor(return_pump, '#bTN_Water_Pump_Outer_Tank', 'green', 'red');
        changeColor(status_button_heater, '#button_heater', 'green', 'red');
        changeColor(status_button_valve, '#button_valve', 'green', 'red');
        changeColor(status_button_pump, '#button_pump ', 'green', 'red');

        changeText(status_button_valve, '#valve_st_alert', 'ON', 'OFF');
        changeText(status_button_pump, '#pump_st_alert', 'ON', 'OFF');
        changeText(status_button_heater, '#heater_st_alert', 'ON', 'OFF');
        changeText(return_valve, '#valve_status', 'ON', 'OFF');
        changeText(return_pump, '#pump_status', 'ON', 'OFF');
    });
}

$().ready(() => {
    getCloud();
});

getChangeAnalogValue('Floating_Value');
getChangeAnalogValue('External_temperature');
getChangeAnalogValue('Set_Point_Heater');

buttonChangeState('button_valve', bTN_Supply_Valve_Outer_Tank);
buttonChangeState('button_pump', bTN_Water_Pump_Outer_Tank);
buttonChangeState('button_heater', bTN_Heater_HVAC);

var iVal = setInterval(function() {
    getCloud();
}, 5000);
