<?php

namespace frontend\modules\blog\repositories;

use frontend\modules\blog\models\Post;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * Repository for handling operations related to posts in the database.
 */
class PostRepository
{
    /**
     * Retrieves all posts from the database.
     *
     * @return Post[] the posts
     */
    public function getAllPosts()
    {
        return Post::find()->all();
    }

    /**
     * Retrieves a post by its ID.
     *
     * @param int $id the post ID
     * @return Post the post model
     * @throws NotFoundHttpException if the post is not found
     */
    public function getPostById($id): Post
    {
        $post = Post::findOne($id);
        if ($post === null) {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
        return $post;
    }

    /**
     * Saves a post to the database.
     *
     * @param Post $post the post model to be saved
     * @return Post the saved post model
     * @throws \RuntimeException if unable to save the post
     */
    public function savePost(Post $post): Post
    {
        if (!$post->save()) {
            throw new \RuntimeException('Unable to save post.');
        }
        return $post;
    }

    /**
     * Updates a post in the database.
     *
     * @param Post $post the post model to be updated
     * @param array $data the attributes to be updated
     * @return Post the updated post model
     * @throws \RuntimeException if unable to update the post
     */
    public function updatePost(Post $post, $data): Post
    {
        $post->attributes = $data;
        if (!$post->save()) {
            throw new \RuntimeException('Unable to update post.');
        }
        return $post;
    }

    /**
     * Deletes a post from the database.
     *
     * @param Post $post the post model to be deleted
     * @return bool whether the deletion is successful
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }
}
