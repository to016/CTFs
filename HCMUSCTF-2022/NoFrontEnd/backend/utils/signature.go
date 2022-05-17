package utils

import (
	"crypto/hmac"
	"crypto/sha256"
	"encoding/base64"
	"encoding/hex"
)

var key = GenerateRandomBytes(32)

func CreateSignature(data []byte) string {
	sig := hmac.New(sha256.New, key)
	sig.Write([]byte(base64.StdEncoding.EncodeToString(data)))

	return hex.EncodeToString(sig.Sum(nil))
}

func ValidationSignature(data []byte, mac string) bool {
	sig := hmac.New(sha256.New, key)
	sig.Write([]byte(base64.StdEncoding.EncodeToString(data)))

	return hex.EncodeToString(sig.Sum(nil)) == mac
}
