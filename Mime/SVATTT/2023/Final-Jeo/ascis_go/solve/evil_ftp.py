import socket
import os

# Define the server address and port
HOST = '0.0.0.0'
PORT = 2121
DATA_PORT = 61343  # Port for data transfer

# Create a data socket for data transfer
data_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
data_socket.bind((HOST, DATA_PORT))
data_socket.listen(1)

# Function to handle USER command
def handle_user(client_socket):
    client_socket.send(b'331 Please specify the password.\r\n')
    return True

# Function to handle PASS command
def handle_pass(client_socket):
    client_socket.send(b'230 Login successful.\r\n')
    return True

# Function to handle TYPE command
def handle_type(client_socket):
    client_socket.send(b'200 Type set to: Binary.\r\n')
    return True

# Function to handle SIZE command
def handle_size(client_socket, counter):
    if(counter == 1):
        client_socket.send(b'213 460\r\n')  # Assuming the file size is 18342 bytes
        return True
    else:
        client_socket.send(b'550 /payload.bin is not retrievable.\r\n')
    return True


# Function to handle EPSV command
def handle_epsv(client_socket, counter):
    if(counter == 1):
        client_socket.send(b'229 Entering extended passive mode (|||61343|).\r\n')
    else:
        client_socket.send(b'227 Entering Extended Passive Mode (172,18,0,2,0,6379)\r\n')
    return True

# Function to handle RETR command and send the file
def handle_retr(client_socket):
    client_socket.send(b'150 File status okay. About to open data connection.\r\n')


    
    # Accept the data connection from the client
    data_client_socket, data_client_addr = data_socket.accept()


    with open('payload.bin', 'rb') as file:
        data = file.read(1024)
        while data:
            data_client_socket.send(data)
            data = file.read(1024)

    data_client_socket.close()
    data_socket.close()

    client_socket.send(b'226 Transfer complete.\r\n')

    return True

# Function to handle QUIT command
def handle_quit(client_socket):
    client_socket.send(b'221 Goodbye.\r\n')
    return False  # Return False to close the connection


def handle_pasv(client_socket):
    client_socket.send(b'227 Entering Extended Passive Mode (172,18,0,2,0,6379)\r\n')
    return True
def handle_stor(client_socket):
    client_socket.send(b'150 File status okay. About to open data connection.\r\n')
    return True


# Define the FTP command handler dictionary
command_handlers = {
    'USER': handle_user,
    'PASS': handle_pass,
    'TYPE': handle_type,
    'SIZE': handle_size,
    'EPSV': handle_epsv,
    'RETR': handle_retr,
    'QUIT': handle_quit,
    'STOR': handle_stor,
    'PASV': handle_pasv
}


# Create the FTP server socket
server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server_socket.bind((HOST, PORT))
server_socket.listen(1)

print(f"FTP server listening on {HOST}:{PORT}")
counter_size = 1
counter_espv = 1
if __name__ == "__main__":
    while True:
    
        client_socket, client_addr = server_socket.accept()
        print(f"Connected to {client_addr}")

        client_socket.send(b'220 FTP Server\r\n')  # Send server greeting

        is_authenticated = False  # Flag to track user authentication status

        while True:
            client_data = client_socket.recv(1024).decode().strip()
            if not client_data:
                break  
            print(f"Client sent: {client_data}")
                
            command_parts = client_data.split(' ', 1)
            command = command_parts[0]
            if command in command_handlers:
                if not is_authenticated and command not in ('USER', 'PASS'):
                    client_socket.send(b'530 Not logged in.\r\n')
                else:
                    
                    if command != 'EPSV' and command != 'SIZE':
                        handler_function = command_handlers[command]
                        is_authenticated = handler_function(client_socket)
                    elif command == 'EPSV':
                        print(counter_espv)
                        handler_function = command_handlers[command]
                        is_authenticated = handler_function(client_socket, counter_espv)
                        counter_espv += 1 
                    elif command == 'SIZE':
                        print(counter_size)
                        handler_function = command_handlers[command]
                        is_authenticated = handler_function(client_socket, counter_size)
                        counter_size += 1 
                        
            else:
                client_socket.send(b'502 Command not implemented.\r\n')

        client_socket.close()
        print(f"Connection from {client_addr} closed.")