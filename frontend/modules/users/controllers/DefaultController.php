<?php

namespace users\controllers;

use common\components\helpers\MessagesHelper;
use users\models\forms\LoginForm;
use users\models\forms\PasswordResetRequestForm;
use users\models\forms\ResendVerificationEmailForm;
use users\models\forms\ResetPasswordForm;
use users\models\forms\SignupForm;
use users\models\forms\VerifyEmailForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class DefaultController extends Controller
{
    public $defaultAction = 'login';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Logs in a user.
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     * @return mixed
     * @throws Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            MessagesHelper::addSuccessMessage(
                Yii::t('users', 'Thank you for registration. Please check your inbox for verification email.')
            );
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     * @return mixed
     * @throws Exception
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {

                MessagesHelper::addSuccessMessage(
                    Yii::t('users', 'Check your email for further instructions.')
                );

                return $this->goHome();
            }

            MessagesHelper::addErrorMessage(
                Yii::t('users', 'Sorry, we are unable to reset password for the provided email address.')
            );
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException|Exception
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            MessagesHelper::addSuccessMessage(
                Yii::t('users', 'New password saved.')
            );

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            MessagesHelper::addSuccessMessage(
                Yii::t('users', 'Your email has been confirmed!')
            );
            return $this->goHome();
        }

        MessagesHelper::addErrorMessage(
            Yii::t('users', 'Sorry, we are unable to verify your account with provided token.')
        );

        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     * @throws \Exception
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                MessagesHelper::addSuccessMessage(
                    Yii::t('users', 'Check your email for further instructions.')
                );
                return $this->goHome();
            }
            MessagesHelper::addErrorMessage(
                Yii::t('users', 'Sorry, we are unable to resend verification email for the provided email address.')
            );
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
