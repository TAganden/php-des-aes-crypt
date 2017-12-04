<?php
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

$this->title = 'Lab 1';

?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Входные данные</div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'crypt-from',
                        'options' => ['class' => 'form-horizontal col-md-12'],
                    ]) ?>
                    <?= $form->field($model, 'algorithm')->widget(Select2::classname(), [
                        'data' => $algorithm,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите алгоритм'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                    <?= $form->field($model, 'mode')->widget(Select2::classname(), [
                        'data' => $mode,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите режим'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                    <?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>
                    <?= $form->field($model, 'key')->textInput() ?>
                    <div class="row" id="generate-block">
                        <h5>Generate keys:
                            <button class="btn btn-xs btn-primary" id="8bytes">7+1 bytes</button>
                            <button class="btn btn-xs btn-primary" id="16bytes">16 bytes</button>
                            <button class="btn btn-xs btn-primary" id="24bytes">24 bytes</button>
                            <button class="btn btn-xs btn-primary" id="32bytes">32 bytes</button></h5>
                    </div>
                </div>
                <div class="panel-footer">
                    <?= Html::button('Шифровать', ['class' => 'btn btn-success pull-right', 'id' => 'crypt']) ?>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="result-block">
                <div class="panel panel-default">
                    <div class="panel-heading">Результат</div>
                    <div class="panel-body">
                        <div class="result-error">
                            <div class="alert alert-danger"><span id="error"></span></div>
                        </div>
                        <div class="result-success">
                            <div class="data-res">Длинна ключа: <span class="label label-primary" id="key-length"></span></div>
                            <div class="data-res">Ключ: <span class="label label-success" id="key"></span></div>
                            <div class="data-res">Шифрованная строка:</div>
                            <textarea class='form-control' name="hash" id="hash" cols="50" rows="5"></textarea>
                            <div class="data-res">Дешифрованная строка:</div>
                            <textarea class='form-control' name="de-hash" id="de-hash" cols="50" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
