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
        return ($this->getAuthorId($request) === $user->id);
    }

    /**
     * Return Author ID
     * @param $request
     * @return null
     */
    private function getAuthorId($request)
    {
        $id = $request->get('id');
        $purchase = Purchase::find()
            ->byId($id)
            ->one();
        return $purchase->created_by ?? null;
    }
}
