query = "select b from (create temptable as select (select READFILE('/etc/passwd') as b)"

ban = ['.', 'lo', ';', 'read', 'im']

for x in ban:
    if x in query:
        print("Filtered..")