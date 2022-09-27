const express = require("express");
const morgan = require("morgan");

const PORT = parseInt(process.env.PORT) || 10000;
const HOST = process.env.HOST || "0.0.0.0";
const config = require("./config");
const creds = config.creds;

const logs = [];

const app = express();
app.use(morgan());

const isObject = (obj) => obj && obj.constructor && obj.constructor === Object;

const merge = (dst, src)=>{
    for(key in src) {
        if(isObject(src[key]) && isObject(dst[key])) {
            merge(dst[key], src[key]);
        } else {
            dst[key] = src[key];
        }
    }
};


app.use((req, res, next)=>{
    if(logs.length > 30) {
        logs = []
    }
    next();
});

app.get("/", (req, res)=>{
    res.json({result: true});
});

app.get("/auth", (req, res)=>{
    if(req.query.uid != null && req.query.upw != null) {
        if(creds[req.query.uid] != null && creds[req.query.uid].upw == req.query.upw) {
            if(creds[req.query.uid].is_admin == true){
                return res.json({result: true, msg: config.flag });
            } else {
                return res.json({result: true, msg: "welcome :)"});
            }
        }
        return res.json({result: false});
    }
    return res.json({result: false});
});

app.get("/logs", (req, res)=>{
    try {
        let new_logs = {
            "ip": null,
            "filename": null
        };
        merge(new_logs, JSON.parse(req.query.logs));
        logs.push(new_logs);
        return res.json({result: true});
    } catch (e) 
    {   
        console.log(e)
        return res.json({result: false});
    }
});

app.listen(PORT, HOST, ()=>{
    console.log(`Server running on port ${PORT}.`);
});