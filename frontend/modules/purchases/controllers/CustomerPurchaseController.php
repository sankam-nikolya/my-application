<?php

namespace purchases\controllers;

use common\components\helpers\MessagesHelper;
use purchases\components\filters\AuthorAccessRule;
use purchases\models\Purchase;
use purchases\models\PurchaseNomenclature;
use purchases\models\searches\CustomerPurchaseSearch;
use purchases\models\searches\PurchaseNomenclatureSearch;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CustomerPurchaseController implements the CRUD actions for Purchase model.
 */
class CustomerPurchaseController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'authorAccess' => [
                    'class' => AccessControl::class,
                    'only' => ['update', 'delete', 'view'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                    'ruleConfig' => [
                        'class' => AuthorAccessRule::class,
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Purchase models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerPurchaseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchase model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $searchModel = new PurchaseNomenclatureSearch();

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search($model->id, $this->request->queryParams),
        ]);
    }

    /**
     * Create or updates Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int|null $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(?int $id = null)
    {
        $isCreate = $id === null;
        $model = $isCreate ? new Purchase() : $this->findModel($id);

        $nomenclatures = $model->nomenclatures;

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->load($this->request->post()) && $model->save()) {
                    $nomenclaturesData = $this->request->post((new PurchaseNomenclature())->formName(), []);
                    $nomenclatureSaved = true;

                    if (count($nomenclaturesData) < 1) {
                        $transaction->rollBack();
                        MessagesHelper::addErrorMessage(
                            Yii::t('purchases', 'Purchase nomenclatures must contains at least 1 item.')
                        );
                        $nomenclatureSaved = false;
                    } else {
                        $existingNomenclatures = PurchaseNomenclature::find()
                            ->byPurchase($model->id)
                            ->indexBy('id')
                            ->all();

                        foreach ($nomenclaturesData as $key => $nomenclatureData) {
                            $currentId = $nomenclatureData['id'];

                            if (isset($existingNomenclatures[$currentId])) {
                                $nomenclatureModel = $existingNomenclatures[$currentId];
                                unset($existingNomenclatures[$currentId]);
                            } else {
                                $nomenclatureModel = new PurchaseNomenclature();
                            }

                            $nomenclatureModel->purchase_id = $model->id;

                            if (
                                $nomenclatureModel->load($nomenclatureData, '')
                                && $nomenclatureModel->validate(array_keys($nomenclatureData))
                            ) {
                                $nomenclatureModel->save(false);
                            } else {
                                $nomenclatureSaved = false;
                            }

                            $nomenclatures[$key] = $nomenclatureModel;
                        }

                        if (!empty($existingNomenclatures)) {
                            PurchaseNomenclature::deleteAll(['id' => array_keys($existingNomenclatures)]);
                        }
                    }

                    if ($nomenclatureSaved) {
                        $transaction->commit();

                        MessagesHelper::addSuccessMessage(
                            $isCreate
                                ? Yii::t('purchases', 'Purchase successfully created.')
                                : Yii::t('purchases', 'Purchase successfully updated.')
                        );

                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                MessagesHelper::addErrorMessage(
                    $isCreate
                        ? Yii::t('purchases', 'Error during purchase creation.')
                        : Yii::t('purchases', 'Error during purchase update.')
                );
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
            'nomenclatures' => $nomenclatures,
        ]);
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): \yii\web\Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Purchase
    {
        $model = Purchase::find()
            ->byId((int)$id)
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('purchases', 'The requested purchase does not exist.'));
    }
}
