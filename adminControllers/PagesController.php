<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\adminControllers;

use webkadabra\yii\modules\cms\models\CmsApp;
use webkadabra\yii\modules\cms\models\CmsRoute;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PagesController
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\backendControllers
 */
class PagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $rules = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->allowedRoles,
                    ],
                ],
            ],
        ];
        if (class_exists('AccessRule')) {
            $rules['access']['ruleConfig'] = [
                'class' => AccessRule::className(),
            ];
        }
        return $rules;
    }

    /**
     * Finds the CmsRoute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CmsRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all CmsRoute models.
     * @return mixed
     */
    public function actionIndex($appId=null, $filter = null)
    {
        /** @var CmsApp[] $apps */
        $apps = CmsApp::find()->all();
        $tabs = [];
        $i = 1;
        foreach ($apps as $app) {
            if (!$appId) {
                $appId = $app->id;
            }
            $tabs[] = [
                'label' => $app->name,
                'filter' => [
                    'where' => [
                        'container_app_id' => $app->id
                    ],
                ],
                'url' => \yii\helpers\Url::toRoute(['index', 'appId' => $app->id]),
                'active' => $appId == $app->id
            ];
            $i++;
        }
        $query = CmsRoute::find();
        $searchModel = new CmsRoute();

        $query->andWhere(['container_app_id' => $appId]);
//
//        if (($filter && !$filterConfig = SavedObjectFilter::findObjectFilter($searchModel, $filter)) || !$filter) {
//            $filterConfig = SavedObjectFilter::getDefaultFilter($searchModel);
//        }
//        if (isset($filterConfig)) {
//            if (isset($filterConfig->filter_config['where']))
//                $query->andWhere($filterConfig->filter_config['where']);
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'tabs' => $tabs,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CmsRoute model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Changes Saved'));
            return $this->refresh();
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CmsRoute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent_id=null, $appId=null)
    {
        if ($parent_id && intval($parent_id)) {
            $parent = CmsRoute::find()->where(['id' => $parent_id])->limit(1)->one();
            if (!$parent) {
                throw new NotFoundHttpException();
            }
        } else {
            $parent = null;
        }
        $model = new CmsRoute();
        if (Yii::$app->request->isPost) {
            $className = explode('\\', ($model->className()));
            $className = array_pop($className);
            $bodyParams = Yii::$app->request->bodyParams;
            if (isset($_POST[$className]['category_ids'])) {
                if (empty($bodyParams[$className]['category_ids'])) {
                    $bodyParams[$className]['category_ids'] = [];
                } else {
                    $bodyParams[$className]['category_ids'] = explode(',', $_POST[$className]['category_ids']);
                }
                Yii::$app->request->setBodyParams($bodyParams);
            }
        }
        $apps = CmsApp::find()->all();
        if ($appId) {
            $model->container_app_id = $appId;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent' => $parent,
                'apps' => $apps,
            ]);
        }
    }

    /**
     * Updates an existing CmsRoute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $className = explode('\\', ($model->className()));
            $className = array_pop($className);
            $bodyParams = Yii::$app->request->bodyParams;
            if (isset($_POST[$className]['category_ids'])) {
                if (empty($bodyParams[$className]['category_ids'])) {
                    $bodyParams[$className]['category_ids'] = [];
                } else {
                    $bodyParams[$className]['category_ids'] = explode(',', $_POST[$className]['category_ids']);
                }
                Yii::$app->request->setBodyParams($bodyParams);
            }
        }
//        $models = $model->getItemOptionsDataProvider()->models;
//        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
//            $count = 0;
//            foreach ($models as $index => $modelIN) {
//                if ($modelIN->save()) {
//                    $count++;
//                }
//            }
//        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}