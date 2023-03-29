$(document).ready(function () {
	$('#op_dob').datepicker( {
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: '1900:'+(new Date).getFullYear(),
		onClose: function(dateText, inst) { 
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
		}
	});

	$('#lic_validity').datepicker( {
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "-20:+70",
		onClose: function(dateText, inst) { 
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
		}
	});
});

$("body").on("focus", ".date-picker", function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        clearBtn: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "-20:+70",
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
        }
    });
});