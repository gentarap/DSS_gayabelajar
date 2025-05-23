package com.gayabelajar.gayabelajar.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.gayabelajar.gayabelajar.model.request.AnswerRequest;
import com.gayabelajar.gayabelajar.model.response.ResultResponse;
import com.gayabelajar.gayabelajar.service.QuizService;

@RestController
@RequestMapping("/api/quiz")
@CrossOrigin(origins = "*")
public class QuizController {
    @Autowired
    private QuizService quizService;
    @PostMapping("/submit")
    public ResultResponse submitQuiz(@RequestBody AnswerRequest request) {
        return quizService.calculateStyle(request);
    }
}
