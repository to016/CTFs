package com.rh0x01.CheckpointbotApp.repository;

import java.util.List;

import com.rh0x01.CheckpointbotApp.model.CheckpointBot;

import org.springframework.data.repository.CrudRepository;

public interface CheckpointBotRepository extends CrudRepository<CheckpointBot, Integer> {

    List<CheckpointBot> findByToken(String token);
}
