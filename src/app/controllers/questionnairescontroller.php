<?php

class QuestionnairesController extends Controller {

    function view($token) {

        global $lang;

        $this->set('token', $token);

        $this->Questionnaire = new Database();
        $this->Questionnaire->query('SELECT * FROM Questionnaires WHERE token = :token');
        $this->Questionnaire->bind(':token', $token);
        $questionnaire = $this->Questionnaire->single();
        $this->set('questionnaire', $questionnaire);

        $this->Questionnaire = new Database();
        $this->Questionnaire->query('SELECT * FROM Surveys WHERE id = :survey_id');
        $this->Questionnaire->bind(':survey_id', $questionnaire['survey_id']);
        $survey = $this->Questionnaire->single();
        $this->set('survey', $survey);

        $this->set('title', $survey['title_'.$lang]);
        $this->set('subtitle', $survey['subtitle_'.$lang]);

        // If survey is completed, display an error
        if ($questionnaire['completed'] != 0) {
            $this->set('error', __('already-completed'));
        } else {

            // If submitting answers, display message, else display questions.
            if (isset($_POST['submit'])) {
                $this->set('msg', __('answers-submitted'));
            } else {
                $this->Questionnaire = new Database();
                $this->Questionnaire->query('SELECT * FROM Questions WHERE survey_id = :survey_id');
                $this->Questionnaire->bind(':survey_id', $questionnaire['survey_id']);
                $questions = $this->Questionnaire->resultSet();

                if ($this->Questionnaire->rowCount() >= 1) {
                    $this->set('questions', $questions);
                } else {
                    $this->set('error', __('missing-questions'));
                }
            }

        }
    }

}
