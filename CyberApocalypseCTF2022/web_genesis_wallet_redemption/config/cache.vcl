vcl 4.1;

backend default {
    .host = "127.0.0.1";
    .port = "1337";
}

sub vcl_hash {
    hash_data(req.url);

    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
    }

    return (lookup);
}

sub vcl_recv {
    # Only allow caching for GET and HEAD requests
    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }
    # get javascript and css from cache
    if (req.url ~ "(\.(js|css|map)$|\.(js|css)\?version|\.(js|css)\?t)") {
        return (hash);
    }
    # get images from cache
    if (req.url ~ "\.(svg|ico|jpg|jpeg|gif|png)$") {
        return (hash);
    }
    # get fonts from cache
    if (req.url ~ "\.(otf|ttf|woff|woff2)$") {
        return (hash);
    }
    # get everything else from backend
    return(pass);
}

sub vcl_backend_response {
    set beresp.ttl = 120s;
}

sub vcl_deliver {
    if (obj.hits > 0) {
        set resp.http.X-Cache = "HIT";
    } else {
        set resp.http.X-Cache = "MISS";
    }

    set resp.http.X-Cache-Hits = obj.hits;
}
