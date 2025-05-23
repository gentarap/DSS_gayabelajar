package com.gayabelajar.gayabelajar.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import com.gayabelajar.gayabelajar.entity.QuizResult;

@Repository
public interface QuizResultRepository extends JpaRepository<QuizResult, Integer> {
}
