<?php

class MailfireGoal extends MailfireDi
{

    const GOAL_RESOURCE = 'goals/create';

    private $validGoals = [];
    private $invalidGoals = [];

    /**
     * @param array $data
     * @return array
     */
    public function createGoal(array $data)
    {

        $resultArray = ['goals_created' => 0];
        
        foreach ($data as $dataItem) {
            $validData = $this->validateData($dataItem);
            if ($validData === false) {
                continue;
            }
            $this->validGoals[] = $validData;
        }

        if (count($this->validGoals) == 0) {
            return array_merge($resultArray, $this->invalidGoals);
        }

        $sendStatus = $this->request->sendToApi2(self::GOAL_RESOURCE,"POST", $data);

        if ($sendStatus) {
            $resultArray['goals_created'] = count($this->validGoals);
            return array_merge($resultArray,$this->invalidGoals);
        }
        
        return array_merge($resultArray, ['errors' => 'Sending data error!']);
        
    }

    private function validateData(array $data)
    {

        $invalidItem = ['error_messages'=>[]];

        $filterOptions = [
            'options' => [
                'default' => false,
                'min_range' => 1
            ]];

        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (empty($data['email'])) {
            $invalidItem['error_messages'][] = "Parameter email is invalid";
        }

        $data['type'] = filter_var(trim(strtolower($data['type'])), FILTER_SANITIZE_STRING);
        if (empty($data['type'])) {
            $invalidItem['error_messages'][] = "Parameter type is invalid";
        }

        $data['project_id'] = filter_var($data['project_id'], FILTER_VALIDATE_INT, $filterOptions);
        if (empty($data['project_id'])) {
            $invalidItem['error_messages'][] = "Parameter project_id is invalid";
        }

        $data['mail_id'] = filter_var($data['mail_id'], FILTER_VALIDATE_INT, $filterOptions);
        if (empty($data['mail_id'])) {
            $invalidItem['error_messages'][] = "Parameter mail_id is invalid";
        }

        if(count($invalidItem['error_messages']) > 0) {
            $invalidItem['goal_data'] = implode(';',$data);
            $this->invalidGoals[] = $invalidItem;
            return false;
        }
        
        return $data;

    }

}
