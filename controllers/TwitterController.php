<?php
/**
 * Created by PhpStorm.
 * User: Shcha
 * Date: 04.08.2019
 * Time: 18:51
 */

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use app\models\Request;
use app\models\Twitter;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\authclient\OAuthToken;
use yii\authclient\clients\Twitter as TwitterClient;

class TwitterController extends Controller
{
    protected $sectetKey = 'accesstrue';

    public function actionAdd($id, $user, $secret)
    {
        if(empty($id) || empty($user) || empty($secret)){ return Yii::$app->response->statusText = json_encode(["error" => "missing parameter"]); }

        if($secret === $this->sectetKey)
        {
            $request = Request::find()->where(['id_request' => $id])->one();
            if($request === null)
            {
                $twitter = new Twitter();
                $twitter->user = $user;
                $twitter->secret = $secret;
                $twitter->save();

                $newRequest = new Request();
                $newRequest->id_request = $id;
                $newRequest->id_user = $twitter->id;
                $newRequest->request_type = 'add_twitter_usr';
                $newRequest->save();

                return Yii::$app->response->statusCode = 200;
            }
            return Yii::$app->response->statusText = json_encode(["error" => "request id not unique"]);
        }
        return Yii::$app->response->statusText = json_encode(["error" => "wrong secret"]);
    }

    public function actionFeed($id, $secret)
    {
        if(empty($id) || empty($secret)){ return Yii::$app->response->statusText = json_encode(["error" => "missing parameter"]); }

        if($secret === $this->sectetKey)
        {
            $request = Request::find()->where(['id_request' => $id])->one();
            if($request === null) {
                $tUsers = Twitter::find()->all();

                $token = new OAuthToken([
                    'token' => Yii::$app->params['twitterAccessToken'],
                    'tokenSecret' => Yii::$app->params['twitterAccessTokenSecret']
                ]);

                // Запускаем Twitter используя полученный $token
                // recently created token
                $twitter = new TwitterClient([
                    'accessToken' => $token,
                    'consumerKey' => Yii::$app->params['twitterApiKey'],
                    'consumerSecret' => Yii::$app->params['twitterApiSecret']
                ]);
                foreach ($tUsers as $tUser) {
                    echo $twitter->api('search/tweets.json?q=@' . $tUser['user'] . '', 'GET');
                }

                $newRequest = new Request();
                $newRequest->id_request = $id;
                $newRequest->id_user = 0;
                $newRequest->request_type = 'get_twitter_posts';
                $newRequest->save();

                return Yii::$app->response->statusCode = 200;
            }
            return Yii::$app->response->statusText = json_encode(["error" => "request id not unique"]);
        }

        return Yii::$app->response->statusText = json_encode(["error" => "wrong secret"]);
    }

    public function actionRemove($id, $user, $secret)
    {
        if(empty($id) || empty($user) || empty($secret)){ return Yii::$app->response->statusText = json_encode(["error" => "missing parameter"]); }

        if($secret === $this->sectetKey)
        {
            $request = Request::find()->where(['id_request' => $id])->one();
            if ($request === null)
            {
                $tUser = Twitter::find()->where(['user' => $user])->one();
                $tUser->delete();

                $newRequest = new Request();
                $newRequest->id_request = $id;
                $newRequest->id_user = $tUser->id;
                $newRequest->request_type = 'remove_twitter_user';
                $newRequest->save();

                return Yii::$app->response->statusCode = 200;
            }
            return Yii::$app->response->statusText = json_encode(["error" => "request id not unique"]);
        }
        return Yii::$app->response->statusText = json_encode(["error" => "wrong secret"]);
    }

}