```java
import java.io.IOException;
import java.io.Writer;

public class StringBuilderWriter extends Writer {
    private StringBuilder sb;

    protected StringBuilderWriter(StringBuilder sb) {
        this.sb = sb;
    }

    public void write(int c) throws IOException {
        this.sb.append((char)c);
    }

    public void write(char[] cbuf) throws IOException {
        this.sb.append(cbuf);
    }

    public void write(String str) throws IOException {
        this.sb.append(str);
    }

    public void write(String str, int off, int len) throws IOException {
        this.sb.append(str.toCharArray(), off, len);
    }

    public Writer append(CharSequence csq) throws IOException {
        this.sb.append(csq);
        return this;
    }

    public Writer append(CharSequence csq, int start, int end) throws IOException {
        this.sb.append(csq, start, end);
        return this;
    }

    public Writer append(char c) throws IOException {
        this.sb.append(c);
        return this;
    }

    public void write(char[] cbuf, int off, int len) throws IOException {
        this.sb.append(cbuf, off, len);
    }

    public void flush() throws IOException {
    }

    public void close() throws IOException {
    }
}
```