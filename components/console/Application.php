<?php
/**
 * @author xialeistudio
 * @date 2019-05-19
 */

namespace app\components\console;


use Throwable;
use Yii;

class Application extends \yii\console\Application
{
    public function run()
    {
        try {
            $this->state = self::STATE_BEFORE_REQUEST;
            $this->trigger(self::EVENT_BEFORE_REQUEST);

            $this->state = self::STATE_HANDLING_REQUEST;
            $response = $this->handleRequest($this->getRequest());

            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);

            $this->state = self::STATE_SENDING_RESPONSE;
            $response->send();

            $this->state = self::STATE_END;

            return $response->exitStatus;
        } catch (Throwable $e) {
            Yii::$app->errorHandler->handleException($e);
            return 1;
        }
    }
}