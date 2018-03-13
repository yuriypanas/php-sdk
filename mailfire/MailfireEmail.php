<?php

class MailfireEmail extends MailfireDi
{
    const CHECK_EMAIL_RESOURCE = 'email/check';

    public function check($email)
    {
        return $this->request->create(self::CHECK_EMAIL_RESOURCE, array('email' => $email));
    }

    public function validate($projectId, $email, $typeId)
    {
        return $this->request->sendToApi2('emails/validate', 'POST', [
            'project' => $projectId, 'email' => $email, 'type' => $typeId
        ]);
    }
}
