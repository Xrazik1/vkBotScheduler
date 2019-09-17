// Создание массива с изменёнными значениями



$(function (changed_values) {

			changed_values = [];


			$( "input[type='text']" ).change(function () { // Запись в массив при изменении значений
					var input_name = $(this).attr("name");
					var changed_values_split = input_name.split('_');
					var input_value = $(this).val();

					changed_values.push({changed_values_split, input_value});
					return changed_values;
				});

			/*
			$('#1').click(function () {
				console.log(changed_values);
			})
			*/
			
			$(' .form_edit ').submit(function (e) { // Направление массива с новыми значениями в test.php
				e.preventDefault();
				$.ajax(
		        {
		            type:'POST',
		            url:'save_data.php',
		            data: {changed_values: changed_values},
		            success:function(data)
		            {
		            	alert('Данные успешно сохранены');
		            },
		            error:function(data)
		            {
		            	alert('Вы не изменили расписание');


		            },
		        });

		        
			});
			

});



