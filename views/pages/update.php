<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

/* @var $this yii\web\View */
/* @var $model \common\modules\cms\models\CmsRoute */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['/cms/pages']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/cms/pages/view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['/cms/blocks', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
$this->context->layout = '//slim';
?>
<div class="row">
    <div class="col-md-7 col-md-offset-2">
        <div class="card">
            <?= $this->render('_form', [
                'model' => $model,
                'apps' => $apps,
            ]) ?>
        </div>
    </div>
    <div class="col-md-3">
        <?php if ($model->versions) {
            echo count($model->versions) .' versions:';
            $dataProvider = new \yii\data\ArrayDataProvider();
            $dataProvider->models = $model->versions;
            $dataProvider->pagination = false;
            echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'list-group'],
                'itemView' => function ($model, $key, $index, $widget) {
                    /** @var \common\modules\cms\models\CmsDocumentVersion $model */
                    return \yii\helpers\Html::a($model->name, \yii\helpers\Url::toRoute(['/cms/document-version/view',
                        'id' => $model->id]), ['class' => 'list-group-item']);
                }
            ]);
        } ?>
        <?=\yii\helpers\Html::a('Create New Version', ['/cms/document-version/create','page' => $model->id], [
    'class' => 'btn btn-default'
]);?>
        <?=\yii\helpers\Html::a('View', $model->getPermalink(), [
    'class' => 'btn btn-default',
            'target' => '_blank'
]);?>
    </div>
</div>

