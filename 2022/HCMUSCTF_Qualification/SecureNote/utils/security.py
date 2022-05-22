def waf_filter(s): 
    forbids = ["'", '"', '*', '\\', '/', '#', ';', '--'] 
    for c in forbids: 
        if c in s: 
            s = s.replace(c, '') 
    return s