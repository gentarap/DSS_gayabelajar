package com.gayabelajar.gayabelajar.model.response;

import lombok.AllArgsConstructor;
import lombok.Data;

@Data
@AllArgsConstructor
public class ResultResponse {
    private String dominantStyle;
    private int score;
}