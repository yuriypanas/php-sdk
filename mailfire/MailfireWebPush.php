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
     * @param array|int $user
     * @param string $title
     * @param string $url
     * @param string $iconUrl
     * @param int $typeId
     * @param array $meta
     * @return bool
     */
    public function sendByUser($user, $title, $text, $url, $iconUrl, $typeId, $meta = [], $imageUrl = null)
    {
        $pushUser = $this->getPushUserByUser($user);
        if (!$pushUser || !$pushUser['id']) {
            $this->errorHandler->handle(new Exception('PushUser was not found'));
            return false;
        }

        $webpushMessage = [
            'push_user_id' => $pushUser['id'],
            'title' => $title,
            'url' => $url,
            'icon' => $iconUrl,
            'type_id' => $typeId,
            'meta' => $meta,
            'text' => $text,
            'image_url' => $imageUrl,
        ];

        $result = $this->request->create('webpush/send', $webpushMessage);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param $projectId
     * @param $hash
     * @param string $title
     * @param string $url
     * @param string $iconUrl
     * @param int $typeId
     * @param array $meta
     * @param array $webpushMessage
     */
    public function sendByProjectIdAndHash($projectId, $hash,  $title, $text, $url, $iconUrl, $typeId, $meta = [], $imageUrl = null)
    {
        $pushUser = $this->getPushUserByProjectIdAndHash($projectId, $hash);
        if (!$pushUser || !$pushUser['id']) {
            $this->errorHandler->handle(new Exception('PushUser was not found'));
            return false;
        }

        $webpushMessage = [
            'push_user_id' => $pushUser['id'],
            'title' => $title,
            'url' => $url,
            'icon' => $iconUrl,
            'type_id' => $typeId,
            'meta' => $meta,
            'text' => $text,
            'image_url' => $imageUrl,
        ];

        $result = $this->request->create('webpush/send', $webpushMessage);
        return !empty($result['result']) ? $result['result'] : false;
    }

    /**
     * @param int $projectId
     * @param $title
     * @param $text
     * @param $url
     * @param $iconUrl
     * @param $typeId
     * @param array $meta
     * @param string $imageUrl
     * @return bool
     */
    public function sendByProject($projectId, $title, $text, $url, $iconUrl, $typeId, $meta = [], $imageUrl = null)
    {

        $webpushMessage = [
            'project_id' => $projectId,
            'title' => $title,
            'url' => $url,
            'icon' => $iconUrl,
            'type_id' => $typeId,
            'meta' => $meta,
            'text' => $text,
            'image_url' => $imageUrl,
        ];

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

    /**
     * @param array $user
     * @param $url
     * @param $publicKey
     * @param $authToken
     * @return bool|int
     */
    public function createPushUser(array $user, $url, $publicKey, $authToken)
    {
        if (!$user) {
            $this->errorHandler->handle(new Exception('User was not found.'));
            return false;
        }
        if (!isset($user['id']) || !$user['id']) {
            $this->errorHandler->handle(new Exception('User id must be set.'));
            return false;
        }
        if (!isset($user['project_id']) || !$user['project_id']) {
            $this->errorHandler->handle(new Exception('Project id must be set.'));
            return false;
        }

        $userId = $user['id'];
        $projectId = $user['project_id'];

        $sendData = array(
            'user_id' => $userId,
            'meta' => array(
                'url' => $url,
                'public_key' => $publicKey,
                'auth_token' => $authToken,
            ),
        );

        $result = $this->request->create('webpush/project/' . $projectId, $sendData);
        return !empty($result['push_user_id']) ? $result['push_user_id'] : false;
    }
}