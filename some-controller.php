//НекийКласс, внутри которого был добавлен метод actionRequestPartPost:
class SomeClass {
    public function actionRequestPartPost()
        {
            if (Yii::app()->request->isPostRequest && isset($_POST['RequestPartForm'])) {
                $requestData = Yii::app()->request->getPost('RequestPartForm');
                $captchaInput = Yii::app()->request->getPost('RequestPartForm')['captcha'];

                // Проверка капчи
                if (!isset(Yii::app()->session['captcha_code']) || $captchaInput !== Yii::app()->session['captcha_code']) {
                    echo json_encode(['success' => false, 'message' => 'Неверная капча.']);
                    Yii::app()->end();
                }

                // Обработка данных формы
                $requestPart = new RequestPartForm();
                $requestPart->attributes = $requestData;

                if ($requestPart->validate()) {
                    $requestPart->sendRequest();
                    unset(Yii::app()->session['captcha_code']);

                    // Успешная отправка формы
                    echo json_encode(['success' => true, 'redirectUrl' => Yii::app()->homeUrl]);
                    Yii::app()->end();
                } else {
                    // Получаем ошибки валидации
                    $errors = $requestPart->getErrors();
                    echo json_encode(['success' => false, 'message' => 'Пожалуйста, исправьте ошибки в форме.', 'errors' => $errors]);
                    Yii::app()->end();
                }
            }

            echo json_encode(['success' => false, 'message' => 'Неверный запрос.']);
            Yii::app()->end();
        }
}