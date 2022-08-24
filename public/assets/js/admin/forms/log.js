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

