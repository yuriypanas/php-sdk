<?php

namespace Mailfire;

class MailfireUser extends MailfireDi
{
    const PLATFORM_UNKNOWN = 0;
    const PLATFORM_DESKTOP = 1;
    const PLATFORM_MOBILE = 2;
    const PLATFORM_ANDROID = 3;
    const PLATFORM_IOS = 4;

    public function getByEmail($email, $projectId)
    {
        return $this->request->receive('user/email/' . $email . '/project/' . $projectId);
    }

    public function getById($userId)
    {
        return $this->request->receive('user/id/' . $userId);
    }

    public function resolve($user)
    {
        if (is_array($user)) {
            return $user;
        }
        if (is_int($user)) {
            return $this->getById($user);
        }
    }

    public function getPlatformUnknown()
    {
        return self::PLATFORM_UNKNOWN;
    }

    public function getPlatformDesktop()
    {
        return self::PLATFORM_DESKTOP;
    }

    public function getPlatformMobile()
    {
        return self::PLATFORM_MOBILE;
    }

    public function getPlatformAndroid()
    {
        return self::PLATFORM_ANDROID;
    }

    public function getPlatformIos()
    {
        return self::PLATFORM_IOS;
    }
}