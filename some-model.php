class RequestPartForm extends CFormModel
{
    public
        $name,
        $email,
        $phone,
        $parts,
        $car,
        $captcha;

    public function rules()
    {
        return array(
            array('parts,name,car', 'required', 'message'=>'Поле не может быть пустым.'),
            array('email,name,car,parts,phone', 'safe'),
            array('email', 'email'),
            array('captcha', 'captchaValidation', 'message' => 'Неверная капча.'),
        );
    }
    //new captcha validation
    public function captchaValidation($attribute, $params)
    {
        $captchaInput = $this->$attribute;
        if (!isset(Yii::app()->session['captcha_code']) || $captchaInput !== Yii::app()->session['captcha_code']) {
            $this->addError($attribute, 'Неверная капча.');
        }
    }

    public function attributeLabels()
    {
        return array(
            'parts' => 'Введите один или несколько интересующих номеров запчастей, или их названия',
            'car'   => 'Укажите информацию о Вашем автомобиле, чем подробнее, тем лучше',
            'email' => 'Укажите e-mail для ответа',
            'phone' => 'Если хотите получить ответ по телефону, укажите номер Вашего телефона',
            'name'  => 'Как Вас зовут?',
            'captcha' => 'Введите код с картинки'
        );
    }

    public function getReasons()
    {
        return is_array(Yii::app()->params['reasons']) ? Yii::app()->params['reasons'] : array();

        $list = array();

        if (is_array(Yii::app()->params['reasons']))
        {
            $tmp = Yii::app()->params['reasons'];

            foreach($tmp as $item)
            {
                $list[$item] = $item;
            }
        }

        return  $list;
    }

    /
     * @return array
     */
    public function getModels()
    {
        return array(
            1 => 'Defender 2007-',
            2 => 'Defender 1987-2006',
            3 => 'Discovery Series I 1989-1998',
            4 => 'Discovery Series II 1998-2004',
            5 => 'LR3/Discovery 3 (GCAT) 2005-2009',
            6 => 'LR4/Discovery 4 (GCAT) 2010-',
            7 => 'Freelander 2/LR2 (GCAT) 2006-',
            8 => 'Freelander 1996-2006',
            9 => 'Range Rover Classic MY1986-MY1991',
            10 => 'Range Rover Classic MY1992-MY1994',
            11 => 'Range Rover MY1995-RunOut',
            12 => 'Range Rover (GCAT) 2002-2009',
            13 => 'Range Rover (GCAT) 2010-2012',
            14 => 'Range Rover Sport (GCAT) 2005-2009',
            15 => 'Range Rover Sport (GCAT) 2010-2013',
            16 => 'Range Rover Evoque (GCAT) 2012-',
            17 => 'Range Rover (GCAT) 2013-',
            18 => 'Range Rover Sport (GCAT) 2014-',
            0 => 'Другая модель'
        );
    }

    /
     * @param $id
     * @return null
     */
    public function getCarModel($id)
    {
        $cars = $this->getModels();

        return isset($cars[$id]) ? $cars[$id] : null;
    }

    public function sendRequest()
    {
        $data = array(
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'parts'   => $this->parts,
            'car'     => $this->car,// $this->getCarModel($this->car),
            'captcha' => $this->captcha,
            'from'    => Yii::app()->getBaseUrl(true) . Yii::app()->request->requestUri
        );

        $mail = new YiiMailer();
        $mail->setView( 'request_part' );
        $mail->setData($data);
        $mail->setLayout('mail');
        $mail->setFrom('example@domain.name', 'Робот LR.RU');
        $mail->setTo(Yii::app()->params['requestPart']);
        $mail->setSubject("Запрос на отсутствующую запчасть c LR.RU");

        return $mail->send();
    }
}