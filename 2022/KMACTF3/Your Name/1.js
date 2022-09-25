var a = `
<script>alert(1)`

if (a.match(/<(script|svg|iframe)/i)) {
    a = "Don't hack"
}

console.log(a)