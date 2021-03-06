<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
/* @var $this yii\web\View */
/* @var $model webkadabra\yii\modules\cms\models\CmsApp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/cms/pages/view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/blocks', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
$this->context->layout = '//slim';

$this->beginBlock('actions');
echo \yii\helpers\Html::a('View <i class="fa fa-external-link" aria-hidden="true"></i>', $model->getPermalink(), [
    'class' => 'btn btn-default',
    'target' => '_blank'
]);
$this->endBlock();
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="card">
            <?= $this->render('_form', [
                'model' => $model,
                'staticOnly' => $model,
            ]) ?>
        </div>
    </div>
    <div class="col-md-3"><!-- Form Sidebar--></div>
</div>

