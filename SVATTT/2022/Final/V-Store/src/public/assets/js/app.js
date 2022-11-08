function checkStock(id=0){
    var url = String(window.location);
    url = url.substring(0, url.lastIndexOf('/')) + '/' + '/api/stock.php?id=' + id;
    var data = {
        "method": "check_stock",
        "apiUrl": url,
    }
    $.ajax({
        url: "index.php?page=shop",
        data: data,
        type: 'POST',
        dataType: "json",
        success : function(data){
            $('#' + id).html("<b>    " + data.content + "</b>");
        },
        error : function (xhr, ajaxOptions, thrownError){  
            console.log(xhr.status);          
            console.log(thrownError);
        } 
    }); 
}


function getKey(passphrase, salt){
    var key = CryptoJS.PBKDF2(passphrase, salt, {
        hasher: CryptoJS.algo.SHA256,
        keySize: 64 / 8,
        iterations: 100
    });
    return key;
}

function encrypt(data){

    var key = getKey(TEAM_SECRET, SALT);

    var encrypted = CryptoJS.AES.encrypt(data, key, {
        iv: CryptoJS.enc.Utf8.parse(IV)
    });

    return encrypted.ciphertext.toString(CryptoJS.enc.Base64);
}

function login(){
    username = document.getElementById("username").value;
    password = document.getElementById("password").value;
    // console.log('username ' + username + ", password: " + password);

    username_encrypted = encrypt(username);
    password_encrypted = encrypt(password);

    data = {"username": username_encrypted, "password": password_encrypted}
    $.ajax({
        url: "index.php?page=login",
        data: data,
        type: 'POST',
        dataType: "json",
        success : function(data){
            if(data.success){
                console.log("Login success");
                window.location = "/index.php?page=shop";
            } else {
                console.log("Login failed");
                alert(data.error);
            }
        },
        error : function (xhr, ajaxOptions, thrownError){  
            console.log(xhr.status);          
            console.log(thrownError);
        } 
    }); 

    return false;

}


function addToCart(id=0){
    // var url = String(window.location);
    var data = {
        "item_id": id,
        "method": "add_to_cart"
    }
    $.ajax({
        url: "index.php?page=shop",
        data: data,
        type: 'POST',
        dataType: "json",
        success: function(data) {
            if (data.redirect) {
                console.log(data.redirect)
                // data.redirect contains the string URL to redirect to
                window.location.href = data.content;
            }else{
                alert(data.content);
            }
        }
    });
}

function updateProfile(e){
    e.preventDefault();
    var data = JSON.stringify($("#myForm").serializeArray());
    $.ajax({
        url: "index.php?page=user",
        data: data,
        type: 'POST',
        contentType : "application/json",
        dataType: "text",
        success: function(data) {
            location.reload();  
        },
        error: function(data) {
            location.reload();  
        },
    });
}   

function preview(e){
    e.preventDefault();
    data = {
        'name': $('#name').val(),
        'preview': "1"
    }
    $.ajax({
        url: "index.php?page=user",
        data: data,
        type: 'POST',
        dataType: "text",
        success: function(data) {
            console.log(data);
            $('#preview').text(data);
        }
    });
}