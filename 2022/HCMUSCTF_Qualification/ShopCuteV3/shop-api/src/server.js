const express = require("express");
const app = express();
const port = process.env.APP_PORT || 8080;
const host = "0.0.0.0";

app.use(express.json());
app.use(express.static("public"))

const items_mapping = {"Doraemon": "/img/item1.jpg", "Pikachu": "/img/item2.jpg", "Qoobee": "/img/item3.jpg", "Bò chăm chỉ": "/img/item4.jpg", "Heo nằm ngủ": "/img/item5.jpg", "Cú dễ thương": "/img/item6.jpg", "Mèo dễ thương": "/img/item7.jpg", "Kỳ lân nằm ngủ": "/img/item8.jpg"}
const items = ["Doraemon", "Pikachu", "Qoobee", "Bò chăm chỉ", "Heo nằm ngủ", "Cú dễ thương", "Mèo dễ thương", "Kỳ lân nằm ngủ"]
let user_data = [];    

app.get("/items.json", (req, res) => {
    return res.json({"status": 200, "msg": items_mapping});
})

function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
}

app.route("/users/:id/items.json")  
    .get((req, res) => {
        if (!(req.params.id in user_data) || user_data[req.params.id].length == 0) {
            user_data[req.params.id] = []
            shuffleArray(items)
            user_data[req.params.id].push(items[0])
            user_data[req.params.id].push(items[1])
            user_data[req.params.id].push(items[2])
            user_data[req.params.id].push(items[3])
        }
        return res.json({"status": 200, "msg": user_data[req.params.id]})
    })

app.listen(port, host, () => {
    console.log(`Example app listening at http://localhost:${port}`)
  })
