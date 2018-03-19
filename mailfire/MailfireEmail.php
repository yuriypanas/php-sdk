<?php

class MailfireEmail extends MailfireDi
{
    const CHECK_EMAIL_RESOURCE = 'email/check';

    public function check($email, $sanitize = true)
    {
        return $this->request->create(self::CHECK_EMAIL_RESOURCE, array(
            'email' => $email,
            'sanitize' => $sanitize));
    }

    public function validate($projectId, $email, $typeId)
    {
        return $this->request->sendToApi2('emails/validate', 'POST', [
            'project' => $projectId, 'email' => $email, 'type' => $typeId
        ]);
    }
}
