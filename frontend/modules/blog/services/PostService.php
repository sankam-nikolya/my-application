<?php

namespace frontend\modules\blog\services;

use frontend\modules\blog\models\Post;
use frontend\modules\blog\repositories\PostRepository;
use yii\db\StaleObjectException;

/**
 * Service layer for handling business logic related to posts.
 */
class PostService
{
    private $postRepository;

    /**
     * Constructor.
     *
     * @param PostRepository $postRepository the post repository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Retrieves all posts.
     *
     * @return Post[] the posts
     */
    public function getAllPosts(): array
    {
        return $this->postRepository->getAllPosts();
    }

    /**
     * Retrieves a post by its ID.
     *
     * @param int $id the post ID
     * @return Post the post model
     * @throws \yii\web\NotFoundHttpException if the post is not found
     */
    public function getPostById(int $id): Post
    {
        return $this->postRepository->getPostById($id);
    }

    /**
     * Creates a new post instance.
     *
     * @return Post the new post model
     */
    public function createNewPost(): Post
    {
        return new Post();
    }

    /**
     * Saves a post.
     *
     * @param array $data the data of the post to be saved
     * @return Post|null the saved post model, or null on failure
     */
    public function savePost($data): ?Post
    {
        $post = new Post();
        $post->attributes = $data;
        if ($this->postRepository->savePost($post)) {
            return $post;
        }
        return null;
    }

    /**
     * Updates a post.
     *
     * @param Post $post the post model to be updated
     * @param array $data the data to update
     * @return Post|null the updated post model, or null on failure
     */
    public function updatePost(Post $post, array $data): ?Post
    {
        if ($this->postRepository->updatePost($post, $data)) {
            return $post;
        }
        return null;
    }

    /**
     * Deletes a post.
     *
     * @param int $id the post ID
     * @return bool whether the deletion is successful
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function deletePost(int $id): bool
    {
        $post = $this->getPostById($id);
        return $this->postRepository->deletePost($post);
    }

    /**
     * Returns a query for retrieving all posts.
     *
     * @return \yii\db\ActiveQueryInterface the query for retrieving posts
     */
    public function getAllPostsQuery(): \yii\db\ActiveQueryInterface
    {
        return Post::find();
    }
}
