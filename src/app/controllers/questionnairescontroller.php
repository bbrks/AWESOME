<?php

class QuestionnairesController extends Controller {

    function view($token) {

        $this->Questionnaire = new Database();
        $this->Questionnaire->query('SELECT * FROM questionnaires WHERE token = :token');
        $this->Questionnaire->bind(':token', $token);
        $this->Questionnaire->execute();
        $questionnaire = $this->Questionnaire->single();

        if ($this->Questionnaire->rowCount() == 1) {
            $this->set('item', $questionnaire);
        } else {
            $this->set('error', __('missing-questionnaire'));
        }

        $this->Questionnaire->query('SELECT * FROM questions WHERE questionnaire_id = :questionnaire_id');
        $this->Questionnaire->bind(':questionnaire_id', $questionnaire['id']);
        $this->Questionnaire->execute();

        if ($this->Questionnaire->rowCount() >= 1) {
            $this->set('questions', $this->Questionnaire->resultSet());
        } else {
            $this->set('error', __('missing-questions'));
        }

        $this->set('title', $questionnaire['name']);
    }

}
