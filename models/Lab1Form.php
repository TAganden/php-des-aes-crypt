<?php

namespace app\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Lab1Form extends Model
{
    public $algorithm;
    public $key;
    public $text;
    public $mode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            //[['algorithm', 'mode'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'algorithm' => 'Алгоритм',
            'key' => 'Ключ шифрования',
            'text' => 'Сообщение',
            'mode' => 'Режим шифрования'
        ];
    }
}
