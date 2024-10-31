(function( $ ) {
    "use strict";

    $(document).ready(function () {
        for (var key = 0; key <2; key++) {


            var days = {
                monday: $("#monday_area"+key).children('.input_area').length,
                tuesday: $("#tuesday_area"+key).children('.input_area').length,
                wednesday: $("#wednesday_area"+key).children('.input_area').length,
                thursday: $("#thursday_area"+key).children('.input_area').length,
                friday: $("#friday_area"+key).children('.input_area').length,
                saturday: $("#saturday_area"+key).children('.input_area').length,
                sunday: $("#sunday_area"+key).children('.input_area').length
            };
            for (name in days) {
                $("#" + name + "_area"+key).children('.input_area').last().children('.new_plus_btn').show();
                for (var i = 0; i < $("#" + name + "_area"+key).children('.input_area').length; i++) {
                    $($("#" + name + "_area"+key).children('.input_area')[i]).append('<span class="minus_btn">-</span>');
                }
                if ($("#" + name + "_area"+key).children('.input_area').length == 1 &&
                    $($("#" + name + "_area"+key).children('.input_area').children('input')[0]).val() == '99:99' &&
                    $($("#" + name + "_area"+key).children('.input_area').children('input')[1]).val() == '99:99') {
                    $("#" + name + "_area"+key).children('.input_area').hide();
                    $("#" + name + "_area"+key).children('.new_entry').show();
                }
            }
        }
        $(".datepicker").datepicker({
            // showOn: "button",
            closeText: 'Schließen',
            prevText: 'Vorheriger Monat',
            nextText: 'Nächster Monat',
            currentText: 'Idag',
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
                'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
            dayNamesMin: [ 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa' ],
            weekHeader: 'Uge',
            dateFormat: 'dd-mm-yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        });
        var days = {
            monday: $("#monday_area").children('.input_area').length,
            tuesday: $("#tuesday_area").children('.input_area').length,
            wednesday: $("#wednesday_area").children('.input_area').length,
            thursday: $("#thursday_area").children('.input_area').length,
            friday: $("#friday_area").children('.input_area').length,
            saturday: $("#saturday_area").children('.input_area').length,
            sunday: $("#sunday_area").children('.input_area').length
        };
        for (name in days) {
            $("#" + name + "_area").children('.input_area').last().children('.pl_btn').show();
            for (var i = 0; i < $("#" + name + "_area").children('.input_area').length; i++) {
                $($("#" + name + "_area").children('.input_area')[i]).append('<span class="minus_btn">-</span>');
            }
            if ($("#" + name + "_area").children('.input_area').length == 1 &&
                $($("#" + name + "_area").children('.input_area').children('input')[0]).val() == '99:99' &&
                $($("#" + name + "_area").children('.input_area').children('input')[1]).val() == '99:99') {
                $("#" + name + "_area").children('.input_area').hide();
                $("#" + name + "_area").children('.new_entry').show();
            }
        }
        $('body').on('click', '.plus_btn', function () {
            $(this).hide();
            var name = $(this).attr('data-id');
            var number = Number($(this).attr('data-number')) + 1;
            $(this).parent(".input_area").parent('.' + name).append(
                '<div class="input_area appended">' +
                '<input type="text" class="time" name="general_business_hours[' + name + '][' + number + '][from]" value="">' +
                '<input type="text" class="time" name="general_business_hours[' + name + '][' + number + '][to]" value="">' +
                '<span class="plus_btn pl_btn" data-number="' + number + '" data-id="' + name + '">+</span>' +
                '<span class="minus_btn">-</span>'
            )
            $(".time").off();
            $('.time').timeDropper({
                setCurrentTime: false,
                format: 'H:mm'
            });
        });
        // plus button for appended side
        var q=1;
        $('body').on('click', '.new_plus_btn', function () {
            var index = $(this).parent(".input_area").parent("div").attr("id").substr($(this).parent(".input_area").parent("div").attr("id").length-1,1)
            $(this).hide();
            var name = $(this).attr('data-id');
            var number = Number($(this).attr('data-number')) + 1;
            $(this).parent(".input_area").parent('.' + name).append(
                '<div class="input_area appended">' +
                '<input type="text" class="time" name="saisonal_business_hours[' + index + '][' + name + '][' + number + '][from]" value=""> ' +
                '<input type="text" class="time" name="saisonal_business_hours[' + index + '][' + name + '][' + number + '][to]" value=""> ' +
                '<span class="new_plus_btn pl_btn" data-number="' + number + '" data-id="' + name +'">+</span>' +
                '<span class="minus_btn">-</span>'
            )
            $(".time").off();
            $('.time').timeDropper({
                setCurrentTime: false,
                format: 'H:mm'
            });
            q++
        });
        // ********************************************************************************
        $('.time').timeDropper({
            setCurrentTime: false,
            format: 'H:mm'
        });

        $('body').on('click', '.minus_btn', function () {
            var parent = $(this).parent().parent();
            if (parent.children('.input_area').length == 1) {
                $($(this).parent().children('input')[0]).val('99:99');
                $($(this).parent().children('input')[1]).val('99:99');
                $(this).parent().hide();
                parent.children('.new_entry').show();
            } else {
                $(this).parent().remove();
                parent.children('.input_area').last().children('.pl_btn').show();
            }
        });
        $('body').on('click', '.new_entry', function () {
            var parent = $(this).parent();
            parent.children('.input_area').show();

            $(parent.children('.input_area').children('input')[0]).val('');
            $(parent.children('.input_area').children('input')[1]).val('');
            $(".time").off();
            $('.time').timeDropper({
                setCurrentTime: false,
                format: 'H:mm'
            });
            $(this).hide();
        });

        $('#plugin-business-hours-submit').on('click', function (event) {
            var times = $('.input_area');
            var status = true;
            for (var i = 0; i < times.length; i++) {
                var first = Number($($(times[i]).children('input')[0]).val().substr(0, $($(times[i]).children('input')[0]).val().indexOf(':')));
                var second = Number($($(times[i]).children('input')[1]).val().substr(0, $($(times[i]).children('input')[1]).val().indexOf(':')));
                if($($(times[i]).children('input')[1]).val() === '0:00') {
                    second = 24;
                }

                if (first > second ) {
                    status = false;
                    $($(times[i]).children('input').attr('style', 'border:1px solid red'));
                }

                var saisonalFrom = $("input[name='saisonal_business_hours["+i+"][from]']");
                var saisonalTo = $("input[name='saisonal_business_hours["+i+"][to]']");

                if (saisonalFrom.val()) {
                    var dateSaisonalFrom = $.datepicker.parseDate("dd-mm-yy",  saisonalFrom.val());
                    var dateSaisonalTo = $.datepicker.parseDate("dd-mm-yy",  saisonalTo.val());

                    if (dateSaisonalFrom > dateSaisonalTo) {
                        status = false;
                        saisonalFrom.attr('style', 'border:1px solid red');
                        saisonalTo.attr('style', 'border:1px solid red')
                    }
                }

                if (i + 1 === times.length) {
                    if (status) {
                        $('#workHours').submit();
                    } else {
                        event.preventDefault();
                        setTimeout(function () {
                            alert(lang.error_start_end_too_low);
                        }, 200);
                    }
                }
            }
        });


        // append new forms area
        var key = 2;
        $("#append_form_button").click(function(){
            $("#append_forms").append(
                "<div class='new_inputes'>"+
                "<div></div>"+
                "<div class='date_input_area'>"+
                "<span><label> " + lang.from + " </label><input type='text' name='saisonal_business_hours[" + key + "][from]' class='datepicker'></span> "+
                "<span><label> " + lang.to + " </label><input type='text' name='saisonal_business_hours[" + key + "][to]' class='datepicker'></span>"+
                "<span class='close_button'><button class='btn close_button button button-secondary killbutton'>" + lang.remove_entry + " </button></span>"+
                "</div>"+
                "<div class='label monday' id='monday_area" + key + "'>"+
                "<span class='field'>" + lang.monday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][monday][0][from]' value='08:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][monday][0][to]' value='17:00'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0'  data-id='monday'>+</span>"+
                " </div>"+
                "</div>"+
                "<br>"+
                "<br>"+
                "<div class='label tuesday' id='tuesday_area" + key + "'>"+
                "<span class='field'>" + lang.tuesday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][tuesday][0][from]' value='08:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][tuesday][0][to]' value='17:00'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='tuesday'>+</span>    "+
                "</div>"+
                "</div>"+

                "<br/><br/>"+
                "<div class='label wednesday' id='wednesday_area" + key + "'>"+
                "<span class='field'>" + lang.wednesday + ":</span>"+
                "<button  type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][wednesday][0][from]' value='08:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][wednesday][0][to]' value='13:00'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='wednesday'>+</span>  "+
                "</div>"+
                "</div>"+
                "<br/><br/>"+
                "<div class='label thursday' id='thursday_area" + key + "'>"+
                "<span class='field'>" + lang.thursday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][thursday][0][from]' value='08:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][thursday][0][to]' value='17:00'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='thursday'>+</span> "+
                "</div>"+
                "</div>"+
                "<br/><br/>"+
                "<div class='label friday' id='friday_area" + key + "'>"+
                "<span class='field'>" + lang.friday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][friday][0][from]'' value='08:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][friday][0][to]'' value='14:30'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='friday'>+</span> "+
                "</div>"+
                "</div>"+
                "<br/><br/>"+
                "<div class='label saturday' id='saturday_area" + key + "'>"+
                "<span class='field'>" + lang.saturday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][saturday][0][from]' value='09:00'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][saturday][0][to]' value='13:00'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='saturday'>+</span> "+
                "</div>"+
                "</div>"+
                "<br/><br/>"+
                "<div class='label sunday' id='sunday_area" + key + "'>"+
                "<span class='field'>" + lang.sunday + ":</span>"+
                "<button type='button' class='new_entry' style='display:none' style='display:none'>" + lang.add_entry + "</button>"+
                "<div class='input_area'>"+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][sunday][0][from]' value='99:99'> "+
                "<input type='text' class='time' name='saisonal_business_hours[" + key + "][sunday][0][to]' value='99:99'> "+
                "<span class='new_plus_btn pl_btn' style='display:none' data-number='0' data-id='sunday'>+</span> "+
                "</div>"+
                "</div>"+
                "<br/><br/>"+
                "</div>");


            var days = {
                monday: $("#monday_area"+key).children('.input_area').length,
                tuesday: $("#tuesday_area"+key).children('.input_area').length,
                wednesday: $("#wednesday_area"+key).children('.input_area').length,
                thursday: $("#thursday_area"+key).children('.input_area').length,
                friday: $("#friday_area"+key).children('.input_area').length,
                saturday: $("#saturday_area"+key).children('.input_area').length,
                sunday: $("#sunday_area"+key).children('.input_area').length
            };
            for (name in days) {
                $("#" + name + "_area"+key).children('.input_area').last().children('.pl_btn').show();
                for (var i = 0; i < $("#" + name + "_area"+key).children('.input_area').length; i++) {
                    $($("#" + name + "_area"+key).children('.input_area')[i]).append('<span class="minus_btn">-</span>');
                }
                if ($("#" + name + "_area"+key).children('.input_area').length == 1 &&
                    $($("#" + name + "_area"+key).children('.input_area').children('input')[0]).val() == '99:99' &&
                    $($("#" + name + "_area"+key).children('.input_area').children('input')[1]).val() == '99:99') {
                    $("#" + name + "_area"+key).children('.input_area').hide();
                    $("#" + name + "_area"+key).children('.new_entry').show();
                }
            }
            $(".datepicker").datepicker({
                // showOn: "button",
                closeText: 'Luk',
                prevText: '&#x3c;Forrige',
                nextText: 'N&aelig;ste&#x3e;',
                currentText: 'Idag',
                monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
                dayNamesMin: [ 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa' ],
                weekHeader: 'Uge',
                dateFormat: 'dd-mm-yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            });
            $(".time").off();
            $('.time').timeDropper({
                setCurrentTime: false,
                format: 'H:mm'
            });
            key++;

        });

        $("body").on("click", ".killbutton",function(event) {
            event.preventDefault();
            $(this).closest("div").parent(".new_inputes").remove();
        });
		
		
    });
}(jQuery));
