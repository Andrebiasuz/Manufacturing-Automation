var inptdata = {}; // object used to help format the data in to be processed
var dataToPost = {}; // Object to be passed into JSON format and into AJAX
var dataToPost_json = {}; // Object that will go to Server side via AJAX POST

// variables in object type, because of call by reference in the Button Function
let bTN_Supply_input_pump_Outer_Tank = {
    value: 0
};
let bTN_Water_output_pump_Outer_Tank = {
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


// Gets values of the webpage and Delivers JSON String to AJAX POST
function dataProcess() {

    for (const key in inptdata) {
        dataToPost[key] = inptdata[key];
    }
    dataToPost['Floating_Value'] = $('#Floating_Value').val();
    dataToPost['BTN_Supply_input_pump_Outer_Tank'] = bTN_Supply_input_pump_Outer_Tank.value;
    dataToPost['BTN_Water_output_pump_Outer_Tank'] = bTN_Water_output_pump_Outer_Tank.value;
    dataToPost['Customer_High_Level_Inner_tank'] = customer_High_Level_Inner_tank.value;
    dataToPost['BTN_Heater'] = bTN_Heater_HVAC.value;
    dataToPost['Set_Point_Heater'] =  $('#Set_Point_Heater').val();
    dataToPost['External_temperature'] = $('#External_temperature').val();

    dataToPost_json = JSON.stringify(dataToPost);
    writePost();
}

// Takes DataProccess and POSTs into PHP Server side
function writePost() {
    $.ajax({
            contentType: "application/json", // php://input
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

// Changes color of element if condition is met
function changeColor(condition, id, color_clicked, color_default) {
    if (condition === 'True') {
        $(id).css('background-color', color_clicked);
    } else {
        $(id).css('background-color', color_default);
    }
    // Same function in tertiary statement
    //aux == ('1' || 'True' || (1)) ? $(button_id).css('background-color', color_clicked) : $(button_id).css('background-color',color_default) ;
}

// Changes text of element if condition is met
function changeText(condition, id, textIfTrue, textIfFalse) {
    if (condition === 'True') {
        $(id).text(textIfTrue);
    } else if (condition === 'False') {
        $(id).text(textIfFalse);
    }
}

// Assign Boolean value to the Buttons and Calls Processing Function
function buttonChangeState(id, outputVar) {
    $('button').on('click', function() {
        if (this.id == id) {
            outputVar.value == 0 ? outputVar.value = 1 : (outputVar.value == 1 ? outputVar.value = 0 : 1);
            dataProcess();
        }
    });
}

// In a change of value in the form data, gets the value and  calls Processing function
function getChangeAnalogValue(id) {
    $('#' + id).on('change', function() {
        inptdata[this.id] = this.value;
        dataProcess();
    });
}


/* Calls AJAX POST to get all the values from PLC that was sent to the CLOUD in
a JSON string. Parses the STRING and uses Jquery to show in the webApp*/
function getCloud() {
    $.post('./get_CLOUD.php').done(function(dataBack) {
        let retArray = JSON.parse(dataBack);
        $('#Return_Cloud').val(retArray[1][2].toFixed(1));
        $('#Return_Cloud_set').val(retArray[1][1]);
        $('#Tank_level').val(retArray[0][8].toFixed(2));

        $('#heater_temp').val(retArray[1][5].toFixed(2));
        $('#Current_setpoint').val(retArray[1][6]);
        $('#Isolation_temperature').val(retArray[1][4].toFixed(2));

        return_input_pump = retArray[0][9];
        return_output_pump = retArray[0][10];

        if (retArray[1][3] > 1) {
            return_heater = 'True';
          }
          else {
            return_heater = 'False';
          }

        changeColor(return_heater, '#BTN_Heater', 'green', 'red');
        changeText(return_heater, '#heater_status', 'ON', 'OFF');

        status_button_input_pump = retArray[0][11];
        status_button_output_pump = retArray[0][12];
        status_button_heater = retArray[1][7];

        changeColor(return_input_pump, '#bTN_Supply_input_pump_Outer_Tank', 'green', 'red');
        changeColor(return_output_pump, '#bTN_Water_output_pump_Outer_Tank', 'green', 'red');
        changeColor(status_button_heater, '#button_heater', 'green', 'red');
        changeColor(status_button_input_pump, '#button_input_pump', 'green', 'red');
        changeColor(status_button_output_pump, '#button_output_pump ', 'green', 'red');

        changeText(status_button_input_pump, '#input_pump_st_alert', 'ON', 'OFF');
        changeText(status_button_output_pump, '#output_pump_st_alert', 'ON', 'OFF');
        changeText(status_button_heater, '#heater_st_alert', 'ON', 'OFF');
        changeText(return_input_pump, '#input_pump_status', 'ON', 'OFF');
        changeText(return_output_pump, '#output_pump_status', 'ON', 'OFF');
    });
}

// At refresing the page, gets Values saved in Server
$().ready(() => {
    getCloud();
});

// Calls stand-by function to get the values at change of State. Texts are the SAME as
getChangeAnalogValue('Floating_Value');
getChangeAnalogValue('External_temperature');
getChangeAnalogValue('Set_Point_Heater');

buttonChangeState('button_input_pump', bTN_Supply_input_pump_Outer_Tank);
buttonChangeState('button_output_pump', bTN_Water_output_pump_Outer_Tank);
buttonChangeState('button_heater', bTN_Heater_HVAC);


// Runs get.CLOUD every 5000ms
var iVal = setInterval(function() {
    getCloud();
}, 5000);
