package com.gayabelajar.gayabelajar.entity;

import java.time.LocalDateTime;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.AllArgsConstructor;
import lombok.Builder;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Entity
@Builder
@Table(name = "tb_quizresult")
public class QuizResult {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "quizresult_id")
    private Integer id;
    @Column(name = "dominantStyle", nullable = false)
    private String dominantStyle;
    @Column(name = "totalsore_quiz", nullable = false)
    private int totalScore;
    @Column(name = "tanggal")
    private LocalDateTime createdAt;
}
