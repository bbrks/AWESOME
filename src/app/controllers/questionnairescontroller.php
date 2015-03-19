<?php

class QuestionnairesController extends Controller {

    function view($token) {

        $this->Questionnaire = new Database();
        $this->Questionnaire->query('SELECT * FROM questionnaires WHERE token = :token');
        $this->Questionnaire->bind(':token', $token);
        $questionnaire = $this->Questionnaire->single();
        $this->set('questionnaire', $questionnaire);

        if ($questionnaire['completed'] != 0) {
            $this->set('error', __('already-completed'));
        } else {

            $this->Questionnaire = new Database();
            $this->Questionnaire->query('SELECT * FROM surveys WHERE id = :survey_id');
            $this->Questionnaire->bind(':survey_id', $questionnaire['survey_id']);
            $survey = $this->Questionnaire->single();
            $this->set('survey', $survey);

            $this->Questionnaire = new Database();
            $this->Questionnaire->query('SELECT * FROM questions WHERE survey_id = :survey_id');
            $this->Questionnaire->bind(':survey_id', $questionnaire['survey_id']);
            $questions = $this->Questionnaire->resultSet();

            if ($this->Questionnaire->rowCount() >= 1) {
                $this->set('questions', $questions);
            } else {
                $this->set('error', __('missing-questions'));
            }

            $this->set('title', $survey['title']);
            $this->set('subtitle', $survey['subtitle']);

        }

        $this->set('token', $token);
    }

}
