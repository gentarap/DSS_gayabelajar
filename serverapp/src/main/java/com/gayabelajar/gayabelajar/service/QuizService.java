package com.gayabelajar.gayabelajar.service;

import java.util.HashMap;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.gayabelajar.gayabelajar.entity.QuizResult;
import com.gayabelajar.gayabelajar.model.request.AnswerRequest;
import com.gayabelajar.gayabelajar.model.response.ResultResponse;
import com.gayabelajar.gayabelajar.repository.QuizResultRepository;

@Service
public class QuizService {

    @Autowired
    private QuizResultRepository quizResultRepository;

    public ResultResponse calculateStyle(AnswerRequest request) {
        Map<String, Integer> scoreMap = new HashMap<>();
        scoreMap.put("visual", 0);
        scoreMap.put("auditori", 0);
        scoreMap.put("kinestetik", 0);

        for (String answer : request.getAnswers()) {
            scoreMap.computeIfPresent(answer.toLowerCase(), (k, v) -> v + 1);
        }

        String dominant = scoreMap.entrySet().stream()
            .max(Map.Entry.comparingByValue())
            .get().getKey();

        int finalScore = scoreMap.get(dominant);

        // Simpan ke database
        QuizResult result = QuizResult.builder()
            .dominantStyle(dominant)
            .totalScore(finalScore)
            .build();

        quizResultRepository.save(result);

        return new ResultResponse(dominant, finalScore);
    }
}
