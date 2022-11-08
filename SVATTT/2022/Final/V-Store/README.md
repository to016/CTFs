# Description
This web application contains at least three vulnerablity categories. 
Each category contains many vulnerable entrypoints from easy to hard one.
The flag is at these locations:
- In table flags (flag)
- In  file /flag
  
# Attack guide
Here is some tips:
- Try to find and exploit the easy bug first and then find the bug that is hard to prevent.
- Write a script to automate the exploit and submit flag to scoreboard. Don't manual, it's too slow.
- Keep persistence in system to easy get flag later.

# Defense guide
Here is some tips:
- Sniff traffic to detect exploit payload
  - Example tool: tcpdump
- Develop a proxy to intercept and drop exploit payload
  - If you don't have one, our teammate provides a good example tool here: https://github.com/Q5Ca/simple-portforwarder
- Exploit yourself system to remove all persistence backdoor.


