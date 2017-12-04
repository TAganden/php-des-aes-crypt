<?php

namespace app\controllers;

use app\models\Lab1Form;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Json;
use yii\web\Controller;

class LabsController extends Controller
{
    public function actionPerform()
    {
        $algorithm = [1 => 'DES', 2 => 'AES'];
        $mode = [1 => 'ECB', 2 => 'CBC', 3 => 'CFC', 4 => 'OFB'];
        $model = new Lab1Form();
        $result = null;

        if(Yii::$app->request->isAjax) {
            return Json::encode($this->doCrypt(Yii::$app->request->post()));
        }
        return $this->render('perform', compact(['algorithm', 'mode', 'model', 'result']));
    }

    private function doCrypt($data)
    {
       switch ($data['algorithm']) {
           case 1:
               return $this->encrypt(MCRYPT_DES, $this->getModeByValue($data), $data);
               break;
           case 2:
               return $this->encrypt(MCRYPT_RIJNDAEL_128, $this->getModeByValue($data), $data);
               break;
       }
       return false;
    }

    public function encrypt($cipher, $mode, $data)
    {
        if($cipher == MCRYPT_DES) $defaultKey = '8631b7e1b7241708';
        if($cipher == MCRYPT_RIJNDAEL_128) $defaultKey = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';

        $key = $data['key'] ? $data['key'] : $defaultKey;

        # --- Шифровка ---
        # ключ должен представлять собой случайную бинарную строку.
        # Для преобразовангия строки в ключ используйте scrypt, bcrypt или PBKDF2
        # Ключ задается в виде строки шестнадцатеричных чисел
        try {
            $key = pack('H*', $key);
        } catch (ErrorException $e) {
            return ['error' => 'Некоректные символы в значении ключа.'];
        }

        # Показываем длину ключа.
        # Длина ключа должна быть 16, 24 или 32 байт для AES-128, 192 и 256 соответственно
        $key_size = strlen($key);

        $plaintext = $data['text'];
        # Создаем случайный инициализирующий вектор используя режим CBC
        $iv_size = mcrypt_get_iv_size($cipher, $mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        # Создаем шифрованный текст совместимыс с AES (размер блока = 128)
        # Подходит только для строк не заканчивающихся на 00h
        # (потому как это символ дополнения по умолчанию)
        try {
            $ciphertext = mcrypt_encrypt($cipher, $key, $plaintext, $mode, $iv);
        } catch (ErrorException $e) {
            return ['error' => 'Некоректный размер ключа.'];
        }

        # Добавляем инициализирующий вектор в начало, чтобы он был доступен для расшифровки
        $ciphertext = $iv . $ciphertext;

        # перекодируем зашифрованный текст в base64
        $hash = base64_encode($ciphertext);

        # --- ДЕШИФРОВКА ---
        $ciphertext_dec = base64_decode($hash);

        # Извлекаем инициализирующий вектор. Длина вектора ($iv_size) должна совпадать
        # с тем, что возвращает функция mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);

        # Извлекаем зашифрованный текст
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        # Отбрасываем завершающие символы 00h
        $deHash = mcrypt_decrypt($cipher, $key, $ciphertext_dec, $mode, $iv_dec);

        return [
            'length' => $key_size === 8 ? '56 bit' : $key_size . ' bytes',
            'hash' => $hash,
            'deHash' => trim($deHash),
            'key' => $data['key'] ? $data['key'] : 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3'
        ];
    }

    private function getModeByValue($data)
    {
        switch ($data['mode']) {
            case 1:
                return MCRYPT_MODE_ECB;
                break;
            case 2:
                return MCRYPT_MODE_CBC;
                break;
            case 3:
                return MCRYPT_MODE_CFB;
                break;
            case 4:
                return MCRYPT_MODE_OFB;
                break;
        }
    }
}
