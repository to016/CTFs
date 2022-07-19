import mojo from "@mojojs/core";
import Path from "@mojojs/path";


const toLeet = {
    A: 4,
    E: 3,
    G: 6,
    I: 1,
    S: 5,
    T: 7,
    O: 0,
};

const fromLeet = Object.fromEntries(
    Object.entries(toLeet).map(([k, v]) => [v, k])
);

const layout = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1337</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <main>
        <%== ctx.content.main %>
    </main>
    <canvas width="500" height="200" id="canv" />
    <script src="static/matrix.js"></script>
</body>
</html>`;

const indexTemplate = `
<h1>C0NV3R7 70/FR0M L337</h1>
<form id="leetform" action="/">
    <input type="text" id="text" name="text" placeholder="Your text here">
    <div class="switch-field">
        <input type="radio" id="dir-to" name="dir" value="to" checked="checked">
        <label for="dir-to">TO</label>
        <input type="radio" id="dir-from" name="dir" value="from">
        <label for="dir-from">FROM</label>
    </div>
    <input type="submit" value="SUBMIT">
</form>
<div id="links">
  <a href="/source">/source</a>
  <a href="/docker">/docker</a>
</div>
`;

const app = mojo();

const leetify = (text, dir) => {
    const charBlocked = ["'", "`", '"'];
    const charMap = dir === "from" ? fromLeet : toLeet;

    const processed = Array.from(text)
        .map((c) => {
            if (c.toUpperCase() in charMap) {
                return charMap[c.toUpperCase()];
            }

            if (charBlocked.includes(c)) {
                return "";
            }

            return c;
        })
        .join("");

    return `<h1>${processed}</h1><a href="/">â†BACK</a>`;
};

app.get("/", async(ctx) => {
    const params = await ctx.params();
    if (params.has("text")) {
        return ctx.render({
            inline: leetify(params.get("text"), params.get("dir")),
            inlineLayout: layout,
        });
    }
    ctx.render({ inline: indexTemplate, inlineLayout: layout });
});

app.get("/source", async(ctx) => {
    const readable = new Path("index.js").createReadStream();
    ctx.res.set("Content-Type", "text/plain");
    await ctx.res.send(readable);
});

app.get("/docker", async(ctx) => {
    const readable = new Path("Dockerfile").createReadStream();
    ctx.res.set("Content-Type", "text/plain");
    await ctx.res.send(readable);
});

app.start();