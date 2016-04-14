<?php

class MailfireUnsub extends MailfireDi
{
    const SOURCE_FBL = 2; // Abuse
    const SOURCE_LINK = 4; // Link in email
    const SOURCE_CLIENT = 8; // Any your reason
    const SOURCE_SETTINGS = 9; // Settings on site

    public function addByFbl($user)
    {
        return $this->addUser($user, self::SOURCE_FBL);
    }

    public function addByLink($user)
    {
        return $this->addUser($user, self::SOURCE_LINK);
    }

    public function addByClient($user)
    {
        return $this->addUser($user, self::SOURCE_CLIENT);
    }

    public function addBySettings($user)
    {
        return $this->addUser($user, self::SOURCE_SETTINGS);
    }

    protected function addUser($user, $sourceId)
    {
        $user = $this->user->resolve($user);
        if (!$user || !$user['id']) {
            return false;
        }
        return $this->request->create('unsub/' . $user['id'] . '/source/' . $sourceId);
    }
}
