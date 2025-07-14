//содержимое вьюшки с выводом формы 404 страницы    
//в конце вьюшки
<script>
document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('request-part-form');
    let captchaInput = document.getElementById('captcha-input');
    let captchaField = form.querySelector('input[name="RequestPartForm[captcha]"]');
    let submitButton = form.querySelector('button[type="submit"]');

    submitButton.addEventListener('click', function(event) {
        event.preventDefault(); // Предотвращаем отправку формы

        // Устанавливаем значение капчи в скрытое поле формы
        captchaField.value = captchaInput.value;

        // Очистка предыдущих ошибок
        let errorElements = form.querySelectorAll('.errorMessage');
        errorElements.forEach(function(element) {
            element.innerHTML = '';
            element.style.display = 'none';
        });

        // Отправляем AJAX-запрос для проверки капчи
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/check-captcha.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Капча верна, отправляем форму через AJAX
                    let formData = new FormData(form);

                    let xhrForm = new XMLHttpRequest();
                    xhrForm.open('POST', form.action, true);
                    xhrForm.onreadystatechange = function() {
                        if (xhrForm.readyState === XMLHttpRequest.DONE && xhrForm.status === 200) {
                            let response = JSON.parse(xhrForm.responseText);
                            if (response.success) {
                                // Успешная отправка формы
                                alert('Запрос отправлен, в ближайшее время свяжемся с вами!');
                                window.location.href = response.redirectUrl; // Перенаправление на главную страницу
                            } else {
                                // Ошибки при отправке формы
                                alert(response.message);
                                // Вывод ошибок
                                if (response.errors) {
                                    for (let field in response.errors) {
                                        if (response.errors.hasOwnProperty(field)) {
                                            let errorElement = document.getElementById('RequestPartForm_' + field + '_em_');
                                            if (errorElement) {
                                                errorElement.innerHTML = response.errors[field].join('<br>');
                                                errorElement.style.display = 'block';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    };
                    xhrForm.send(formData);
                } else {
                    // Капча неверна, выводим сообщение об ошибке
                    alert(response.message);
                }
            }
        };
        xhr.send('captcha=' + encodeURIComponent(captchaInput.value));
    });
});
</script>