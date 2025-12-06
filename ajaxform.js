$(document).ready(function() {
	// в скрытое поле заносим текущий урл чтобы знать откуда заполнена форма
	$('[name=url]').each(function(){
		eval($(this).val(window.location.href));
	});

     $(".ajaxform").on("submit", function(ev) {
    	ev.preventDefault();
    	
    	if( ! validatePhoneAjaxForm($(this).find('input[name="ajaxx_phone"]').val())) { return false; }
		let dataSubmit = $(this).serialize()
		const thisForm = $(this);
		thisForm.hide(1000);
	    thisForm.next(".messageAjaxForm").show(2000);
	    
		$.ajax({
		    url: '/ajaxform.php',
		    method: 'post',
		    dataType: 'html',
		    data: dataSubmit,
		    success: function(dataSubmit){
			  //  console.log(dataSubmit);
			    ym(99999999999999999999999999999999999,'reachGoal','formcompleted');
			    console.log('Достижение цели: Успешная отправка формы.');
			    

			}
        });
    })
    
	
	// цель в метрику по открытию модального окна (обычно оно с формой)
	$("[data-bs-toggle=modal]").on("click", function() {
		ym(103306672,'reachGoal','openmodal');
		console.log('Достижение цели: Открыл модальное окно');
	});
	
	// проверка телефона
	
	 function validatePhoneAjaxForm(phoneInput) {
		if(phoneInput.length < 7) { alert('Слишком короткий номер телефона. Перепроверьте. '); return false;}
		let digitsPhone = phoneInput.replace(/\D/g, "");
		if(digitsPhone.length < 7) { alert('Слишком короткий номер телефона. Проверьте цифры.  '); return false;}
		if(digitsPhone.length > 14) { alert('Слишком длинный номер телефона. Проверьте цифры.  '); return false;}
		else  { return true;}
		
	}
	
})