package com.rh0x01.CheckpointbotApp.utils;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.Serializable;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

import org.springframework.core.io.ByteArrayResource;


public class CheckInUtility implements Serializable {

    static final long serialVersionUID = 1L;
    public String token;
    public ByteArrayResource fileData;
    public File sheetFile;
    public String check_in;

    public CheckInUtility(String token) throws Exception
    {
        this.token = token;
        this.sheetFile = new File("/app/files/"+token+".csv");
        this.check_in = null;

        CSVWriter writer = new CSVWriter(new FileWriter(this.sheetFile), '|', '\0','\0',"\n");

        writer.writeRow(new String[] {"ID", "CHECK IN"});
        writer.close();
    }

    public ByteArrayResource readFile(File file) throws IOException {
        Path path = Paths.get(file.getAbsolutePath());
        ByteArrayResource resource = new ByteArrayResource(Files.readAllBytes(path));
        return resource;
    }

    public String getFileName() {
        return this.sheetFile.getName();
    }

    public long getFileSize() {
        return this.sheetFile.length();
    }

    public ByteArrayResource getFileData() {
        return this.fileData;
    }

    private void readObject(ObjectInputStream inputStream) throws Exception
    {
        inputStream.defaultReadObject();

        CSVWriter writer = new CSVWriter(new FileWriter(this.sheetFile, true), '|', '\0','\0',"\n");

        LocalDateTime date = LocalDateTime.now();
        DateTimeFormatter format = DateTimeFormatter.ofPattern("dd-MM-yyyy HH:mm:ss");
        this.check_in = date.format(format);

        writer.writeRow(new String[] {this.token, this.check_in});
        writer.close();

        this.fileData = readFile(this.sheetFile);
    }

}
