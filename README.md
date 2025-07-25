**Легаси-совместимая капча для PHP 5.2+ без внешних зависимостей**
*(написан для легаси сайта на Yii 1, в силу невозможности использовать современные готовые решения из-за старой версии php);

| Файл                  | Краткое назначение                                                                                   |
| --------------------- | ---------------------------------------------------------------------------------------------------- |
| `captcha.php`         | Генерирует PNG-картинку с кодом, сохраняет правильный ответ в `$_SESSION['captcha_code']`            |
| `check-captcha.php`   | Простой энд-поинт: принимает POST-запрос `captcha=abc123`, возвращает JSON `{"success":true/false}`  |
| `some-controller.php` | Метод `actionRequestPartPost()` проверяет капчу, валидирует форму и отправляет e-mail                |
| `some-model.php`      | `RequestPartForm` — модель формы с правилом `captchaValidation`, описывающим поле `captcha`          |
| `captchaRequest.js`   | Клиентский скрипт: AJAX-проверка капчи перед отправкой формы и вывод ошибок                          |

**Как работает:**
1) Пользователь видит картинку из captcha.php, вводит код;
2) JS (без перезагрузки страницы) дергает check-captcha.php;
3) После успешной проверки форма уходит на some-controller.php, где капча валидируется повторно на сервере;
4) При успешной валидации данные письмом уходят менеджеру, сессия очищается, капча больше не принимается;

