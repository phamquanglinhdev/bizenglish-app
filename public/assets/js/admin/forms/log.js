$("input[name='hour_salary']").change(function () {
    calSalary()
})
$("input[name='hour_salary']").keyup(function () {
    calSalary()
})

$("input[name='duration']").change(function () {
    calSalary()
})
$("input[name='duration']").keyup(function () {
    calSalary()
})

function calSalary() {
    let duration = 0;
    duration = $("input[name='duration']").val()
    duration = parseInt(duration)
    let salary = 0;
    salary = $("input[name='hour_salary']").val();

    salary = parseInt(salary)
    let log_salary = duration * salary / 60;
    $("input[name='log_salary']").val(log_salary);
}


$(document).ready(function () {
    let name = $("#status_name");
    EffectWhenChangeStatus(name.val())
    name.change(function (e) {
            console.log(name.val())
            EffectWhenChangeStatus(name.val())
        }
    );

    function EffectWhenChangeStatus(value) {
        messages = $("#status_message");
        suffix = $("#status_time .input-group-append .input-group-text");
        inputs = $("#status_time");
        switch (value * 1) {
            case 0:
                inputs.hide()
                messages.hide()
                break;
            case 1:
            case 2:
                inputs.show()
                messages.hide()
                suffix.text("Phút/Minutes")
                break;
            case 3:
            case 4:
                inputs.show()
                messages.hide()
                suffix.text("Giờ/Hour")
                break;
            default:
                inputs.hide()
                messages.show()
        }
    }
});


