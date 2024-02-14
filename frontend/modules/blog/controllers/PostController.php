<?php

namespace frontend\modules\blog\controllers;

use frontend\modules\blog\services\PostService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii\web\Response;

/**
 * Controller for managing blog posts.
 */
class PostController extends Controller
{
    private $postService;

    /**
     * Constructor.
     *
     * @param string $id the ID of this controller
     * @param mixed $module the module that this controller belongs to
     * @param PostService $postService the post service
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($id, $module, PostService $postService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $postService;
    }

    /**
     * Lists all posts.
     *
     * @return string the rendering result
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->postService->getAllPostsQuery(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single post.
     *
     * @param int $id the post ID
     * @return string the rendering result
     * @throws NotFoundHttpException if the post is not found
     */
    public function actionView($id)
    {
        $post = $this->postService->getPostById($id);
        return $this->render('view', ['model' => $post]);
    }

    /**
     * Creates a new post.
     *
     * @return Response|string the rendering result
     */
    public function actionCreate()
    {
        $model = $this->postService->createNewPost();

        if (
            $model->load(Yii::$app->request->post())
            && ($savedModel = $this->postService->savePost($model->attributes))
        ) {
            return $this->redirect(['view', 'id' => $savedModel->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing post.
     *
     * @param int $id the post ID
     * @return Response|string the rendering result
     * @throws NotFoundHttpException if the post is not found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->postService->getPostById($id);

        if (
            $model->load(Yii::$app->request->post())
            && $this->postService->updatePost($model, $model->attributes)
        ) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing post.
     *
     * @param int $id the post ID
     * @return Response the response object
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->postService->deletePost($id);
        return $this->redirect(['index']);
    }
}
