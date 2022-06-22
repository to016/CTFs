package com.rh0x01.CheckpointbotApp.controller;

import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

import com.google.gson.Gson;

import com.rh0x01.CheckpointbotApp.model.CheckpointBot;
import com.rh0x01.CheckpointbotApp.repository.CheckpointBotRepository;
import com.rh0x01.CheckpointbotApp.utils.CheckInUtility;

import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.io.ByteArrayResource;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class CheckpointBotController {
    @Autowired
    private CheckpointBotRepository cRepo;

    Gson gson = new Gson();
    Logger log = LogManager.getLogger(IndexController.class);

    @GetMapping("/api/checkpointbot")
    public Map<String, String> handlerCheckIn() {

        Map<String, String> response = new HashMap<String, String>();
        UUID token = UUID.randomUUID();

        try {
            CheckpointBot newBot = new CheckpointBot();
            newBot.setToken(token.toString());
            CheckInUtility checkInUtility = new CheckInUtility(token.toString());
            newBot.setCheckInUtility(checkInUtility);
            cRepo.save(newBot);
        } catch (Exception e) {
            e.printStackTrace();
            response.put("message", "failed");
            return response;
        }

        response.put("token", token.toString());
        return response;
    }

    @GetMapping(value="/api/checkpointbot/check-in", produces="application/json")
    public ResponseEntity<String> handlerCheckIn(@RequestParam("token") String token) {

        Map<String, String> json = new HashMap<String, String>();
        CheckpointBot bot;

        try{
            UUID.fromString(token);
            bot = cRepo.findByToken(token).get(0);
        } catch (IllegalArgumentException exception){
            log.error("Invalid token supplied: " + token);
            json.put("message", "Invalid token supplied");
            return new ResponseEntity<String>(gson.toJson(json),HttpStatus.UNAUTHORIZED);
        }

        try {
            CheckInUtility checkInUtility = bot.getCheckInUtility();
            json.put("message", "Checked in successfully!");
            json.put("check_in", checkInUtility.check_in);
        } catch (Exception e) {
            e.printStackTrace();
            json.put("message", "Something went wrong!");
            return new ResponseEntity<String>(gson.toJson(json),HttpStatus.INTERNAL_SERVER_ERROR);
        }
        return new ResponseEntity<String>(gson.toJson(json),HttpStatus.OK);
    }

    @GetMapping("/api/checkpointbot/sheet")
    public ResponseEntity<?> download(@RequestParam("token") String token) throws Exception {

        Map<String, String> json = new HashMap<String, String>();
        CheckpointBot bot;
        CheckInUtility checkInUtility;

        try{
            UUID.fromString(token);
        } catch (IllegalArgumentException exception){
            log.error("Invalid token supplied: " + token);
            json.put("message", "Invalid token supplied");
            return new ResponseEntity<String>(gson.toJson(json),HttpStatus.UNAUTHORIZED);
        }

        try{
            bot = cRepo.findByToken(token).get(0);
        } catch (Exception e){
            log.error("Invalid token supplied: " + token);
            json.put("message", "Invalid token supplied");
            return new ResponseEntity<String>(gson.toJson(json),HttpStatus.UNAUTHORIZED);
        }

        try {
            checkInUtility = bot.getCheckInUtility();
        } catch (Exception e) {
            e.printStackTrace();
            json.put("message", "Something went wrong!");
            return new ResponseEntity<String>(gson.toJson(json),HttpStatus.INTERNAL_SERVER_ERROR);
        }

        HttpHeaders header = new HttpHeaders();
        header.add(HttpHeaders.CONTENT_DISPOSITION, "attachment; filename=" + checkInUtility.getFileName());
        header.add("Cache-Control", "no-cache, no-store, must-revalidate");
        header.add("Pragma", "no-cache");
        header.add("Expires", "0");

        ByteArrayResource resource = checkInUtility.getFileData();

        return ResponseEntity.ok()
                .headers(header)
                .contentLength(checkInUtility.getFileSize())
                .contentType(MediaType.parseMediaType("application/octet-stream"))
                .body(resource);
    }
}
