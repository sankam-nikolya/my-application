<?php

namespace purchases\components\filters;

use purchases\models\Purchase;
use yii\filters\AccessRule;

/**
 * Class AuthorAccessRule
 */
class AuthorAccessRule extends AccessRule
{
    public $allow = true;
    public $roles = ['@'];

    /**
     * @inheritDoc
     */
    public function allows($action, $user, $request)
    {
        $parentRes = parent::allows($action, $user, $request);
        if ($parentRes !== true) {
            return $parentRes;
        }

        return $this->isValidAuthor($request, $user);
    }

    /**
     * Return Author ID
     * @param $request
     * @param $user
     * @return bool
     */
    private function isValidAuthor($request, $user): bool
    {
        $id = $request->get('id');

        if ($id === null) {
            return true;
        }

        $purchase = Purchase::find()
            ->byId($id)
            ->one();

        return $user->id === ($purchase->created_by ?? null);
    }
}
