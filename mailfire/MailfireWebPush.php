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

        return $this->request->create('webpush/unsubscribe/' . $pushUser['id']);
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

        return $this->request->create('webpush/unsubscribe/' . $pushUser['id']);
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

        return $this->request->delete('webpush/subscribe/' . $pushUser['id']);
    }


    public function subscribeByHash($projectId, $hash)
    {
        $pushUser = $this->getPushUserByProjectIdAndHash($projectId, $hash);
        if (!$pushUser || !$pushUser['id']) {
            return false;
        }

        return $this->request->delete('webpush/subscribe/' . $pushUser['id']);
    }

    /**
     * @param array|int $user
     * @return bool
     */
    protected function getPushUserByUser($user)
    {
        if (is_int($user)) {
            $user['id'] = $user;
        }

        if (!$user || !isset($user['id']) || !$user['id']) {
            return false;
        }

        return $this->request->receive('webpush/user/get/' . $user['id']);
    }

    /**
     * @param int $projectId
     * @param string $hash
     * @return bool
     */
    protected function getPushUserByProjectIdAndHash($projectId, $hash)
    {
        return $this->request->receive('webpush/user/get/' . $projectId . '/hash/' . $hash);
    }
}