<?php

class MailfireWebPush extends MailfireDi
{
    /**
     * @param int|array $user
     * @return bool
     */
    public function unsubscribeByUser($user)
    {
        $pushUser = $this->getPushUserByUser($user);
        if (!$pushUser || !$pushUser['id']) {
            return false;
        }

        $result = $this->request->create('webpush/unsubscribe/' . $pushUser['id']);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param int $projectId
     * @param string $hash
     * @return bool
     */
    public function unsubscribeByProjectIdAndHash($projectId, $hash)
    {
        $pushUser = $this->getPushUserByProjectIdAndHash($projectId, $hash);
        if (!$pushUser || !$pushUser['id']) {
            return false;
        }

        $result = $this->request->create('webpush/unsubscribe/' . $pushUser['id']);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param array|int $user
     * @return bool
     */
    public function subscribeByUser($user)
    {
        $pushUser = $this->getPushUserByUser($user);
        if (!$pushUser || !$pushUser['id']) {
            return false;
        }

        $result = $this->request->delete('webpush/subscribe/' . $pushUser['id']);
        return !empty($result['result']) ? $result['result'] : false;
    }


    public function subscribeByHash($projectId, $hash)
    {
        $pushUser = $this->getPushUserByProjectIdAndHash($projectId, $hash);
        if (!$pushUser || !$pushUser['id']) {
            return false;
        }

        $result = $this->request->delete('webpush/subscribe/' . $pushUser['id']);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * array['title']   string (required) Webpush title
     *      ['url']     string (required) Webpush link url
     *      ['icon']    string (required) Webpush icon image url
     *      ['type_id'] int (required) Webpush type ID
     *      ['meta']    array (optional) Webpush meta
     *
     * @param array|int $user
     * @param array $webpushMessage (See above)
     * @return bool
     */
    public function sendByUser($user, array $webpushMessage)
    {
        $pushUser = $this->getPushUserByUser($user);
        if (!$pushUser || !$pushUser['id']) {
            $this->errorHandler->handle(new Exception('PushUser was not found'));
            return false;
        }

        $webpushMessage['push_user_id'] = $pushUser['id'];

        $result = $this->request->create('webpush/send', $webpushMessage);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * array['title']   string (required) Webpush title
     *      ['url']     string (required) Webpush link url
     *      ['icon']    string (required) Webpush icon image url
     *      ['type_id'] int (required) Webpush type ID
     *      ['meta']    array (optional) Webpush meta
     *
     * @param $projectId
     * @param $hash
     * @param array $webpushMessage
     */
    public function sendByProjectIdAndHash($projectId, $hash, array $webpushMessage)
    {
        $pushUser = $this->getPushUserByProjectIdAndHash($projectId, $hash);
        if (!$pushUser || !$pushUser['id']) {
            $this->errorHandler->handle(new Exception('PushUser was not found'));
            return false;
        }

        $webpushMessage['push_user_id'] = $pushUser['id'];

        $result = $this->request->create('webpush/send', $webpushMessage);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param array|int $user
     * @return bool
     */
    protected function getPushUserByUser($user)
    {
        if (is_int($user)) {
            $user = ['id' => $user];
        }

        if (!$user || !isset($user['id']) || !$user['id']) {
            return false;
        }

        $result = $this->request->receive('webpush/user/get/' . $user['id']);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param int $projectId
     * @param string $hash
     * @return bool
     */
    protected function getPushUserByProjectIdAndHash($projectId, $hash)
    {
        $result = $this->request->receive('webpush/project/get/' . $projectId . '/hash/' . $hash);
        return !empty($result['result']) ? $result['result'] : false;
    }
}