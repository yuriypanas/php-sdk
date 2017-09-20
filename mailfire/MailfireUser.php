<?php

class MailfireUser extends MailfireDi
{
    const PLATFORM_UNKNOWN = 0;
    const PLATFORM_DESKTOP = 1;
    const PLATFORM_MOBILE = 2;
    const PLATFORM_ANDROID = 3;
    const PLATFORM_IOS = 4;

    public function getByEmail($email, $projectId)
    {
        $result = $this->request->receive('user/project/' . $projectId . '/email/' . $email);
        return $result ? $result['user'] : false;
    }

    public function getById($userId)
    {
        $result = $this->request->receive('user/id/' . $userId);
        return $result ? $result['user'] : false;
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

    /**
     * Set custom user fields by user email and project ID
     *
     * @param string $email
     * @param int $projectId
     * @param array $data ['fieldName' => 'fieldValue']
     * @return bool
     */
    public function setUserFieldsByEmailAndProjectId($email, $projectId, array $data)
    {
        $resource = strtr('userfields/project/:projectId/email/:email', [
            ':projectId' => $projectId,
            ':email' => $email,
        ]);
        return $this->request->update($resource, $data);
    }

    /**
     * Set custom user fields by user
     * 
     * @param array $user
     * @param array $data ['fieldName' => 'fieldValue']
     * @return bool
     */
    public function setUserFieldsByUser(array $user, array $data)
    {
        $user = $this->resolve($user);
        if (!$user || !$user['id']) {
            return false;
        }
        
        $resource = strtr('userfields/user/:userId', [
            ':userId' => $user['id'],
        ]);
        return $this->request->update($resource, $data);
    }

    /**
     * Get custom user fields by user email and project ID
     * 
     * @param string $email
     * @param int $projectId
     * @return bool
     */
    public function getUserFieldsByEmailAndProjectId($email, $projectId)
    {
        $resource = strtr('userfields/project/:projectId/email/:email', [
            ':projectId' => $projectId,
            ':email' => $email,
        ]);
        return $this->request->receive($resource);
    }

    /**
     * Get custom user fields by user
     * 
     * @param array $user
     * @return bool
     */
    public function getUserFieldsByUser(array $user)
    {
        $user = $this->resolve($user);
        if (!$user || !$user['id']) {
            return false;
        }
        
        $resource = strtr('userfields/user/:userId', [
            ':userId' => $user['id'],
        ]);
        return $this->request->receive($resource);
    }
}
