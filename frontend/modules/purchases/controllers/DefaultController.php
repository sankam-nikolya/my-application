<?php

namespace purchases\controllers;

use purchases\models\Purchase;
use purchases\models\searches\PurchaseNomenclatureSearch;
use purchases\models\searches\PurchaseSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Purchase model.
 */
class DefaultController extends Controller
{
    /**
     * Lists all Purchase models.
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PurchaseSearch();
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
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Purchase
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Purchase
    {
        $model = Purchase::find()
            ->byId((int)$id)
            ->active()
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('purchases', 'The requested purchase does not exist.'));
    }
}
