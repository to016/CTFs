from base64 import b64decode
from base64 import b64encode
from Crypto.Cipher import AES

class AESCipher:
	def __init__(self, key):
		self.key = key

	def encrypt(self, plaintext):
		self.cipher = AES.new(self.key, AES.MODE_GCM)
		c, tag = self.cipher.encrypt_and_digest(plaintext)
		nonce = self.cipher.nonce
		return b64encode(nonce + tag + c)

	def decrypt(self, ciphertext):
		ciphertext = b64decode(ciphertext)
		nonce, tag, c = ciphertext[:16], ciphertext[16:32], ciphertext[32:]
		self.cipher = AES.new(self.key, AES.MODE_GCM, nonce)
		return self.cipher.decrypt_and_verify(c, tag)