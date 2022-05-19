package com.rh0x01.CheckpointbotApp.utils;

import java.io.IOException;
import java.io.Writer;

public class CSVWriter {
    public char SEPERATOR = ',';
    public char QUOTE_CHARACTER = '"';
    public char ESCAPE_CHARACTER = '"';
    public String LINE_END = "\n";

    protected final Writer writer;

    public CSVWriter(Writer writer, char seperator, char quoteChar, char escapeChar, String lineEnd) {
        this(writer);
        this.SEPERATOR = seperator;
        this.QUOTE_CHARACTER = quoteChar;
        this.ESCAPE_CHARACTER = escapeChar;
        this.LINE_END = lineEnd;
    }

    public CSVWriter(Writer writer) {
        this.writer = writer;
    }

    public void writeRow(String[] items)  throws IOException {
        StringBuilder lineData = new StringBuilder();

        if (items == null) {
            return;
        }

        for (int i = 0; i < items.length; i++) {
            if (i != 0) {
                lineData.append(SEPERATOR);
            }

            String nextItem = items[i];

            if (nextItem == null) {
                continue;
            }

            boolean specialChars = containsSpecialChars(nextItem);

            if (specialChars) {
                filterLine(nextItem, lineData);
            } else {
                lineData.append(nextItem);
            }
        }

        lineData.append(LINE_END);
        writer.write(lineData.toString());

    }

    public void flush() throws IOException {
        writer.flush();
    }

    public void close() throws IOException {
        flush();
        writer.close();
     }

    protected boolean containsSpecialChars(String line) {
        return line.indexOf(QUOTE_CHARACTER) != -1
                || line.indexOf(ESCAPE_CHARACTER) != -1
                || line.indexOf(SEPERATOR) != -1
                || line.contains(LINE_END)
                || line.contains("\r");
    }

    protected void filterLine(String nextItem, Appendable lineData) throws IOException {
        for (int j = 0; j < nextItem.length(); j++) {
           char nextChar = nextItem.charAt(j);
           filterCharacter(lineData, nextChar);
        }
    }

    protected void filterCharacter(Appendable lineData, char nextChar) throws IOException {
        if (QUOTE_CHARACTER == nextChar) {
            lineData.append(ESCAPE_CHARACTER);
        }
        lineData.append(nextChar);
    }

}
