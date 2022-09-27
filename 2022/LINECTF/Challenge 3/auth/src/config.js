exports.creds = {
    "admin": {
        "upw": process.env.ADMINPW || "this_is_fake_passwd",
        "is_admin": true
    }
};

exports.flag = process.env.FLAG || "LINE{this_is_fake_flag}"
