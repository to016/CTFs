package utils

import (
	"crypto/rand"
	"strconv"
	"math/big"
	log "github.com/sirupsen/logrus"
)
func IsStringInArray(n string, s []string) bool {
	for _, x := range s {
		if x == n {
			return true
		}
	}
	return false
}

func EnvAtoI(n string, fallback int) int {
	x, err := strconv.Atoi(n)

	if err != nil {
		return fallback
	}
	return x
}

func GenerateRandomBytes(n int) ([]byte) {
	b := make([]byte, n)
	_, err := rand.Read(b)
	if err != nil {
		log.WithFields(log.Fields{
			"location": "GenerateRandomBytes-handler",
			"error":    err,
		}).Errorln("Can't genarate random")
	}
	return b
}

func GenerateRandomString(n int) (string) {
	const letters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-"
	ret := make([]byte, n)
	for i := 0; i < n; i++ {
		num, err := rand.Int(rand.Reader, big.NewInt(int64(len(letters))))
		if err != nil {
			return ""
		}
		ret[i] = letters[num.Int64()]
	}

	return string(ret)
}