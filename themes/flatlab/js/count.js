function countUp(count)
{

    var div_by = 100,
            speed = parseInt((count / div_by)),
            $display = $('.count'),
            run_count = 1,
            int_speed = 24;
    //console.log(count, div_by, speed);
    var int = setInterval(function () {
        if (run_count < div_by) {
            $display.text(speed * run_count);
            run_count++;
        } else if (parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}


function loadingFavoritos() {
    $.ajax({
        url: $("#baseUrl").val() + "/metrica/entradasFavorito",
        dataType: 'JSON',
        success: function (data) {
            $("#title1").html(data[0].titulo);
            $("#title2").html(data[1].titulo);
            $("#title3").html(data[2].titulo);
            $("#title4").html(data[3].titulo);

            $("#title1").closest('a').attr('href', data[0].url);
            $("#title2").closest('a').attr('href', data[1].url);
            $("#title3").closest('a').attr('href', data[2].url);
            $("#title4").closest('a').attr('href', data[3].url);

            countUp(data[0].entradas);
            countUp2(data[1].entradas);
            countUp3(data[2].entradas);
            countUp4(data[3].entradas);
        }
    });
}
loadingFavoritos();

function countUp2(count)
{
    var div_by = 100,
            speed = parseInt((count / div_by)),
            $display = $('.count2'),
            run_count = 1,
            int_speed = 24;

    var int = setInterval(function () {
        if (run_count < div_by) {
            $display.text(speed * run_count);
            run_count++;
        } else if (parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}

function countUp3(count)
{

    var div_by = 100,
            speed = parseInt((count / div_by)),
            $display = $('.count3'),
            run_count = 1,
            int_speed = 24;

    var int = setInterval(function () {
        if (run_count < div_by) {
            $display.text(speed * run_count);
            run_count++;
        } else if (parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}

function countUp4(count)
{

    var div_by = 100,
            speed = parseInt((count / div_by)),
            $display = $('.count4'),
            run_count = 1,
            int_speed = 24;

    var int = setInterval(function () {
        if (run_count < div_by) {
            $display.text(speed * run_count);
            run_count++;
        } else if (parseInt($display.text()) < count) {
            var curr_count = parseInt($display.text()) + 1;
            $display.text(curr_count);
        } else {
            clearInterval(int);
        }
    }, int_speed);
}


