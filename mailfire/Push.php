<?php

class MailfirePush extends MailfireDi
{
    private $resources = [
        Mailfire::CATEGORY_SYSTEM => 'push/system',
        Mailfire::CATEGORY_TRIGGER => 'push/trigger',
    ];

    public function send($typeId, $categoryId, $projectId, $user, $data, $meta = [])
    {
        $data['user'] = $user;
        $params = [
            'type_id' => $typeId,
            'category' => $categoryId,
            'client_id' => $this->clientId,
            'project_id' => $projectId,
            'data' => $data,
            'meta' => $meta,
        ];

        $resource = $this->resources[$categoryId];

        return $this->request->create($resource, $params);
    }
}